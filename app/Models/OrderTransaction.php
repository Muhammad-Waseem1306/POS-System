<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransaction extends Model
{
    use HasFactory;

    protected $table = 'order_transactions';

    protected $fillable = [
        'amount',
        'order_id',
        'user_id',
        'customer_id',
        'paid_by',
        'paid_at',
        'transaction_id',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentAllocations()
    {
        return $this->hasMany(InstallmentPaymentAllocation::class);
    }
}
