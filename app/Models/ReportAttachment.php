<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAttachment extends Model
{
    protected $fillable = ['report_id', 'category', 'file_path'];

    public const CATEGORY_LABELS = [
        'spt' => 'Surat Perintah Tugas (SPT)',
        'tiket_pergi' => 'Tiket Pesawat Pergi',
        'tiket_pulang' => 'Tiket Pesawat Pulang',
        'taksi_rumah_ke_bandara' => 'Taksi Rumah ke Bandara Asal',
        'taksi_bandara_ke_rumah' => 'Taksi Bandara Asal ke Rumah',
        'taksi_bandara_tujuan_ke_hotel' => 'Taksi Bandara Tujuan ke Hotel',
        'taksi_hotel_ke_bandara_tujuan' => 'Taksi Hotel ke Bandara Tujuan',
        'taksi_berangkat' => 'Taksi Rumah ke Bandara Asal',
        'taksi_pulang' => 'Taksi Bandara Asal ke Rumah',
        'taksi_tujuan_pergi' => 'Taksi Bandara Tujuan ke Hotel',
        'taksi_tujuan_pulang' => 'Taksi Hotel ke Bandara Tujuan',
        'dokumentasi' => 'Dokumentasi Foto Kegiatan',
    ];

    public function getLabelAttribute(): string
    {
        return self::CATEGORY_LABELS[$this->category] ?? ucwords(str_replace('_', ' ', $this->category));
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
