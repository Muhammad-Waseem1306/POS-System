<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const SALE_TYPE_CASH = 'cash';
    public const SALE_TYPE_INSTALLMENT = 'installment';

    protected $guarded = [];
    protected $appends = ['total_item'];

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function transactions()
    {
        return $this->hasMany(OrderTransaction::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function installmentPlan()
    {
        return $this->hasOne(InstallmentPlan::class, 'sale_id');
    }

    public function getTotalItemAttribute()
    {
        return $this->products()->sum('quantity');
    }
}
