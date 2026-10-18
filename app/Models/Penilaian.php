<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $guarded = [];

    public function getIdAttribute()
    {
        return $this->attributes['id'] ?? ($this->attributes['id_penilaian'] ?? null);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function getTotalAttribute()
    {
        return ($this->nilai_kedisiplinan ?? 0) 
            + ($this->kinerja ?? 0) 
            + ($this->sikap ?? 0) 
            + ($this->kepemimpinan ?? 0) 
            + ($this->loyalitas ?? 0) 
            + ($this->it ?? 0);
    }

    public function getRataRataAttribute()
    {
        if (isset($this->attributes['rata_rata']) && $this->attributes['rata_rata'] > 0) {
            return (float) $this->attributes['rata_rata'];
        }
        return round($this->total / 6, 2);
    }

    public function getPredikatAttribute()
    {
        if (!empty($this->attributes['predikat'])) {
            return $this->attributes['predikat'];
        }
        $avg = $this->rata_rata;
        if ($avg >= 85) return 'Sangat Baik (A)';
        if ($avg >= 75) return 'Baik (B)';
        if ($avg >= 60) return 'Cukup (C)';
        return 'Kurang (D)';
    }
}
