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

    public function getAmountAttribute()
    {
        return $this->attributes['amount'] ?? ($this->attributes['nominal'] ?? ($this->attributes['jumlah'] ?? 0));
    }

    public function getExpenseTypeAttribute()
    {
        return $this->attributes['expense_type'] ?? ($this->attributes['uraian'] ?? ($this->attributes['kategori'] ?? ''));
    }

    public function getPaymentMethodAttribute()
    {
        return strtolower($this->attributes['payment_method'] ?? ($this->attributes['metode_pembayaran'] ?? 'kkp'));
    }
}
