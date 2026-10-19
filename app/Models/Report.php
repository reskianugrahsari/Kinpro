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
}
