<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $table = 'reports';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'daftar_pelaksana' => 'array',
            'tanggal_surat_tugas' => 'date',
            'tgl_mulai_dinas' => 'date',
            'tgl_selesai_dinas' => 'date',
            'tanggal_berangkat' => 'date',
            'tanggal_kembali' => 'date',
            'tanggal_sppd' => 'date',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_cost' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function ppk(): BelongsTo
    {
        return $this->belongsTo(MasterPpk::class, 'ppk_id');
    }

    public function masterPpk(): BelongsTo
    {
        return $this->belongsTo(MasterPpk::class, 'master_ppk_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ReportSchedule::class, 'report_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class, 'report_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(ReportExpense::class, 'report_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(ReportRevision::class, 'report_id');
    }

    public function businessProcesses(): BelongsToMany
    {
        return $this->belongsToMany(BusinessProcess::class, 'report_business_processes', 'report_id', 'business_process_id');
    }

    public function getTotalKkpAttribute(): float
    {
        return (float) $this->expenses()
            ->where(function ($q) {
                $q->where('payment_method', 'kkp')
                  ->orWhere('metode_pembayaran', 'KKP');
            })
            ->sum(\Illuminate\Support\Facades\Schema::hasColumn('report_expenses', 'amount') ? 'amount' : 'nominal');
    }

    public function getTotalCashAttribute(): float
    {
        return (float) $this->expenses()
            ->where(function ($q) {
                $q->where('payment_method', 'cash')
                  ->orWhere('metode_pembayaran', 'Cash');
            })
            ->sum(\Illuminate\Support\Facades\Schema::hasColumn('report_expenses', 'amount') ? 'amount' : 'nominal');
    }

    public function recalculateTotalCost(): void
    {
        $col = \Illuminate\Support\Facades\Schema::hasColumn('report_expenses', 'amount') ? 'amount' : 'nominal';
        $total = $this->expenses()->sum($col);
        $this->update(['total_cost' => $total, 'total_biaya' => $total]);
    }

    /**
     * Resolve a valid NIP string for a pelaksana, falling back to linked User/Pegawai records.
     */
    public static function resolveNip(?string $nip, string $name, ?User $user = null, ?Pegawai $pegawai = null): string
    {
        $trimmed = trim((string) $nip);
        if ($trimmed !== '' && $trimmed !== '-') {
            return $trimmed;
        }

        if ($user) {
            $userNip = $user->pegawai?->nip;
            if (!empty($userNip) && trim($userNip) !== '-') {
                return trim($userNip);
            }
        }

        if ($pegawai && !empty($pegawai->nip) && trim($pegawai->nip) !== '-') {
            return trim($pegawai->nip);
        }

        $cleanName = trim($name);
        if ($cleanName !== '') {
            $matchedUser = User::with('pegawai')->where('name', $cleanName)->first();
            $matchedNip = $matchedUser?->pegawai?->nip;
            if (!empty($matchedNip) && trim($matchedNip) !== '-') {
                return trim($matchedNip);
            }

            $hasNama = \Illuminate\Support\Facades\Schema::hasColumn('pegawai', 'nama');
            $hasNamaLengkap = \Illuminate\Support\Facades\Schema::hasColumn('pegawai', 'nama_lengkap');
            if ($hasNama || $hasNamaLengkap) {
                $pegawaiQuery = Pegawai::query();
                if ($hasNama) {
                    $pegawaiQuery->where('nama', $cleanName);
                }
                if ($hasNamaLengkap) {
                    $hasNama ? $pegawaiQuery->orWhere('nama_lengkap', $cleanName) : $pegawaiQuery->where('nama_lengkap', $cleanName);
                }
                $pegawaiNip = $pegawaiQuery->value('nip');
                if (!empty($pegawaiNip) && trim((string) $pegawaiNip) !== '-') {
                    return trim((string) $pegawaiNip);
                }
            }
        }

        return '-';
    }

    /**
     * Return daftar_pelaksana entries with missing/placeholder NIPs resolved from User/Pegawai data.
     */
    public function resolvedPelaksanaList(): array
    {
        $list = is_array($this->daftar_pelaksana) ? $this->daftar_pelaksana : [];

        if (empty($list)) {
            $fallbackName = $this->user?->name ?? ($this->pegawai?->nama ?? '-');
            $fallbackNip = self::resolveNip(null, $fallbackName, $this->user, $this->pegawai);
            return [['name' => $fallbackName, 'nip' => $fallbackNip]];
        }

        $missingNames = collect($list)
            ->filter(fn($p) => in_array(trim((string) ($p['nip'] ?? '')), ['', '-'], true))
            ->pluck('name')
            ->filter()
            ->unique()
            ->values();

        $usersByName = $missingNames->isNotEmpty()
            ? User::with('pegawai')->whereIn('name', $missingNames)->get()->keyBy('name')
            : collect();

        return array_map(function ($p) use ($usersByName) {
            $name = (string) ($p['name'] ?? '-');
            $userHint = $usersByName->get($name);
            if (!$userHint && $this->user && $this->user->name === $name) {
                $userHint = $this->user;
            }
            $pegawaiHint = ($this->pegawai && $this->pegawai->nama === $name) ? $this->pegawai : null;

            return [
                'name' => $name,
                'nip' => self::resolveNip($p['nip'] ?? null, $name, $userHint, $pegawaiHint),
            ];
        }, $list);
    }
}
