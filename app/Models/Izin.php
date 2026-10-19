<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_pegawai) && !empty($model->pegawai_id)) {
                $model->id_pegawai = $model->pegawai_id;
            } elseif (empty($model->pegawai_id) && !empty($model->id_pegawai)) {
                $model->pegawai_id = $model->id_pegawai;
            }
        });
    }

    public function getIdAttribute()
    {
        return $this->attributes['id'] ?? ($this->attributes['id_izin'] ?? null);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
