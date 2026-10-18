<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $guarded = [];

    public function getIdAttribute()
    {
        return $this->attributes['id'] ?? ($this->attributes['id_pegawai'] ?? null);
    }

    public function getNamaAttribute()
    {
        return $this->attributes['nama_lengkap'] ?? ($this->attributes['nama'] ?? '');
    }

    public function getNamaLengkapAttribute()
    {
        return $this->attributes['nama_lengkap'] ?? ($this->attributes['nama'] ?? '');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ratings()
    {
        return $this->hasMany(RatingKomentar::class, 'pegawai_id');
    }
}
