<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSchedule extends Model
{
    protected $fillable = ['report_id', 'tanggal', 'uraian_kegiatan', 'lokasi_keterangan'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
