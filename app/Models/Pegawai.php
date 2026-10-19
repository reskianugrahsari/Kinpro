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

    public function getFotoUrlAttribute()
    {
        $foto = $this->attributes['foto_profil'] ?? null;
        if (!empty($foto)) {
            if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
                return $foto;
            }
            if (file_exists(public_path('uploads/' . $foto))) {
                return asset('uploads/' . $foto);
            }
            if (file_exists(public_path('storage/' . $foto))) {
                return asset('storage/' . $foto);
            }
            if (file_exists(public_path('images/' . $foto))) {
                return asset('images/' . $foto);
            }
            if (file_exists(public_path($foto))) {
                return asset($foto);
            }
            return asset('uploads/' . $foto);
        }

        return null;
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

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'pegawai_id');
    }

    public function latestPenilaian()
    {
        return $this->hasOne(Penilaian::class, 'pegawai_id')->latestOfMany();
    }

    public function getStatusKepegawaianAttribute()
    {
        if (!empty($this->pangkat_gol) && str_contains(strtoupper($this->pangkat_gol), 'PPPK')) {
            return 'PPPK';
        }
        if (!empty($this->nip) && strlen($this->nip) >= 14 && substr($this->nip, 12, 2) === '21') {
            return 'PPPK';
        }
        return 'PNS';
    }
}
