<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingKomentar extends Model
{
    protected $table = 'rating_komentar';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }
}
