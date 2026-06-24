<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo('reference');
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'purchase'    => '<span class="badge bg-success">Purchase</span>',
            'sale'        => '<span class="badge bg-primary">Sale</span>',
            'adjustment'  => '<span class="badge bg-warning text-dark">Adjustment</span>',
            'return'      => '<span class="badge bg-info">Return</span>',
            'damage'      => '<span class="badge bg-danger">Damage</span>',
            default       => '<span class="badge bg-secondary">' . ucfirst($this->type) . '</span>',
        };
    }
}
