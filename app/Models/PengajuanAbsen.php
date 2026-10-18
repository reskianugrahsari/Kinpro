<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanAbsen extends Model
{
    protected $table = 'pengajuan_absen';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
