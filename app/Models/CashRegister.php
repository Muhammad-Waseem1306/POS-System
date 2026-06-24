<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $guarded = [];

    protected $casts = [
        'register_date' => 'date',
        'opened_at'     => 'datetime',
        'closed_at'     => 'datetime',
    ];

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function getVarianceColorAttribute(): string
    {
        if ($this->variance === null) return 'text-secondary';
        if ($this->variance > 0)     return 'text-success';
        if ($this->variance < 0)     return 'text-danger';
        return 'text-secondary';
    }
}
