<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    protected $guarded = [];

    public function getIdAttribute()
    {
        return $this->attributes['id'] ?? ($this->attributes['id_izin'] ?? null);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
