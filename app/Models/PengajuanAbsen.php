<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanAbsen extends Model
{
    protected $table = 'pengajuan_absen';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal_lupa' => 'date',
            'tanggal_pengajuan' => 'date',
            'tanggal_absen' => 'date',
        ];
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function atasan()
    {
        return $this->belongsTo(Pegawai::class, 'atasan_id');
    }

    public function getHariLupaAttribute()
    {
        $date = $this->tanggal_lupa ?? $this->tanggal_absen;
        if (!$date) return '-';
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $enDay = $date->format('l');
        return $days[$enDay] ?? $enDay;
    }
}
