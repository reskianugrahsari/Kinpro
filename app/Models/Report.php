<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function masterPpk()
    {
        return $this->belongsTo(MasterPpk::class, 'master_ppk_id');
    }

    public function expenses()
    {
        return $this->hasMany(ReportExpense::class, 'report_id');
    }
}
