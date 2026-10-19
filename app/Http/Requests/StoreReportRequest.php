<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $parseIndonesianDate = function ($value) {
            if (!$value || !is_string($value)) return $value;

            $value = trim($value);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return $value;
            }

            $months = [
                'januari' => '01', 'jan' => '01',
                'februari' => '02', 'feb' => '02',
                'maret' => '03', 'mar' => '03',
                'april' => '04', 'apr' => '04',
                'mei' => '05',
                'juni' => '06', 'jun' => '06',
                'juli' => '07', 'jul' => '07',
                'agustus' => '08', 'agu' => '08', 'ags' => '08',
                'september' => '09', 'sep' => '09',
                'oktober' => '10', 'okt' => '10',
                'november' => '11', 'nov' => '11',
                'desember' => '12', 'des' => '12',
            ];

            // Normalize all whitespace variants (non-breaking space, narrow nbsp, etc.) to regular space
            $clean = preg_replace('/[\x{00A0}\x{202F}\x{2007}\x{200B}\x{FEFF}]/u', ' ', $value);
            $clean = preg_replace('/\s+/', ' ', trim($clean));
            $clean = preg_replace('/^[A-Za-z]+,\s*/', '', $clean);

            if (preg_match('/^(\d{1,2})[\s\/\-]([A-Za-z]+)[\s\/\-](\d{4})$/', $clean, $m)) {
                $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                $monthKey = strtolower($m[2]);
                if (isset($months[$monthKey])) {
                    return "{$m[3]}-{$months[$monthKey]}-{$day}";
                }
            }

            try {
                return \Carbon\Carbon::parse($clean)->format('Y-m-d');
            } catch (\Exception $e) {
                return $value;
            }
        };

        $data = [];

        if (!$this->has('nomor_surat_tugas') && $this->has('nomor_surat')) {
            $data['nomor_surat_tugas'] = $this->nomor_surat;
        }
        if (!$this->has('tgl_mulai_dinas') && $this->has('tanggal_berangkat')) {
            $data['tgl_mulai_dinas'] = $parseIndonesianDate($this->tanggal_berangkat);
        }
        if (!$this->has('tgl_selesai_dinas') && $this->has('tanggal_kembali')) {
            $data['tgl_selesai_dinas'] = $parseIndonesianDate($this->tanggal_kembali);
        }
        if (!$this->has('kota_tujuan') && $this->has('tujuan')) {
            $data['kota_tujuan'] = $this->tujuan;
        }
        if (!$this->has('ppk_id') && $this->has('master_ppk_id')) {
            $data['ppk_id'] = $this->master_ppk_id;
        }
        if (!$this->has('isi_pointer') && $this->has('maksud_perjalanan')) {
            $data['isi_pointer'] = $this->maksud_perjalanan;
        }
        if (!$this->has('pelaksana')) {
            $data['pelaksana'] = [auth()->id() ?? 1];
        }

        if ($this->has('tanggal_surat_tugas')) {
            $val = $this->tanggal_surat_tugas;
            $data['tanggal_surat_tugas'] = !empty($val) ? $parseIndonesianDate($val) : null;
        }
        if ($this->has('tgl_mulai_dinas')) {
            $val = $this->tgl_mulai_dinas;
            $data['tgl_mulai_dinas'] = !empty($val) ? $parseIndonesianDate($val) : null;
        }
        if ($this->has('tgl_selesai_dinas')) {
            $val = $this->tgl_selesai_dinas;
            $data['tgl_selesai_dinas'] = !empty($val) ? $parseIndonesianDate($val) : null;
        }
        if ($this->has('tanggal_sppd')) {
            $val = $this->tanggal_sppd;
            $data['tanggal_sppd'] = !empty($val) ? $parseIndonesianDate($val) : null;
        }

        if ($this->has('schedules') && is_array($this->schedules)) {
            $schedules = $this->schedules;
            foreach ($schedules as $idx => $sched) {
                if (isset($sched['tanggal']) && !empty($sched['tanggal'])) {
                    $schedules[$idx]['tanggal'] = $parseIndonesianDate($sched['tanggal']);
                }
            }
            $data['schedules'] = $schedules;
        } elseif (!$this->has('schedules')) {
            $tglM = $data['tgl_mulai_dinas'] ?? ($this->tgl_mulai_dinas ?? ($this->tanggal_berangkat ?? now()->format('Y-m-d')));
            $data['schedules'] = [
                [
                    'tanggal' => $tglM,
                    'uraian_kegiatan' => $this->isi_pointer ?? ($this->maksud_perjalanan ?? 'Kegiatan Dinas'),
                ]
            ];
        }

        if ($this->has('expenses') && is_array($this->expenses)) {
            $expenses = $this->expenses;
            foreach ($expenses as $idx => $exp) {
                if (!isset($exp['expense_type']) && isset($exp['uraian'])) {
                    $expenses[$idx]['expense_type'] = $exp['uraian'];
                }
                if (!isset($exp['amount']) && isset($exp['nominal'])) {
                    $expenses[$idx]['amount'] = $exp['nominal'];
                }
                if (!isset($exp['payment_method']) && isset($exp['metode_pembayaran'])) {
                    $expenses[$idx]['payment_method'] = strtolower($exp['metode_pembayaran']) === 'cash' ? 'cash' : 'kkp';
                }
            }
            $data['expenses'] = $expenses;
        }

        if ($this->hasFile('dokumentasi') && !is_array($this->file('dokumentasi'))) {
            $data['dokumentasi'] = [$this->file('dokumentasi')];
        }

        $this->merge($data);
    }

    public function rules(): array
    {
        return [
            'nomor_surat_tugas' => 'required|string|max:100',
            'tanggal_surat_tugas' => 'nullable|date',
            'tgl_mulai_dinas' => 'required|date',
            'tgl_selesai_dinas' => 'required|date|after_or_equal:tgl_mulai_dinas',
            'tanggal_sppd' => 'nullable|date',
            'opsi_tiket' => 'nullable|in:kkp,cash',
            'kota_tujuan' => 'required|string|max:100',
            'ppk_id' => 'required|exists:master_ppk,id',
            'pelaksana' => 'nullable|array',
            'schedules' => 'nullable|array',
            'expenses' => 'nullable|array',
            'isi_pointer' => 'nullable|string',
            'pointer_substansi' => 'nullable|string',
            'spt' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'tiket_pergi' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'tiket_pulang' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'taksi_rumah_ke_bandara' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'taksi_bandara_ke_rumah' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'taksi_bandara_tujuan_ke_hotel' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'taksi_hotel_ke_bandara_tujuan' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
            'dokumentasi' => 'nullable|array|max:8',
            'dokumentasi.*' => 'nullable|file|mimes:jpg,jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'ppk_id.required' => 'Pejabat Pembuat Komitmen (PPK) wajib dipilih.',
            'schedules.required' => 'Minimal 1 rincian kegiatan harian wajib diisi.',
            'pelaksana.required' => 'Minimal 1 pelaksana perjalanan dinas wajib dipilih.',
            'isi_pointer.required' => 'Peta Proses Bisnis / Narasi Pointer wajib diisi.',
            'spt.required' => 'Dokumen Surat Tugas (SPT) wajib diunggah.',
            'expenses.required' => 'Rincian biaya & pengeluaran wajib diisi.',
            'expenses.*.payment_method.required' => 'Setiap komponen pengeluaran wajib memilih metode pembayaran (KKP atau Cash).',
            'dokumentasi.required' => 'Dokumentasi foto kegiatan wajib diunggah (minimal 1 foto).',
            'dokumentasi.max' => 'Dokumentasi foto kegiatan maksimal 8 foto.',
        ];
    }
}
