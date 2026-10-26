<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';
    protected $guarded = [];

    public function getNamaAttribute()
    {
        return $this->attributes['nama_unit'] ?? ($this->attributes['nama'] ?? '');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'unit_kerja_id');
    }
}
