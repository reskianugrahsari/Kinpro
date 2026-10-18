<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportExpense extends Model
{
    protected $table = 'report_expenses';
    protected $guarded = [];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
