<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KopSurat extends Model
{
    protected $table = 'kop_surat';

    protected $fillable = [
        'logo_path',
        'nama_kementerian',
        'nama_badan',
        'nama_unit_kerja',
        'alamat',
        'telepon',
        'email',
        'website',
    ];
}
