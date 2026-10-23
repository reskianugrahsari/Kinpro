<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPpk extends Model
{
    protected $table = 'master_ppk';
    protected $guarded = [];

    public function getNamaAttribute()
    {
        return $this->attributes['nama_ppk'] ?? ($this->attributes['nama'] ?? '');
    }

    public function getNipAttribute()
    {
        return $this->attributes['nip_ppk'] ?? ($this->attributes['nip'] ?? '');
    }
}
