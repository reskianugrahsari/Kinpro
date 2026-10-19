<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessProcess extends Model
{
    protected $fillable = [
        'tahun',
        'kategori',
        'kode',
        'nama_proses',
        'parent_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BusinessProcess::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(BusinessProcess::class, 'parent_id');
    }

    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'report_business_processes');
    }
}
