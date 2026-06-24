<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentSchedule extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';

    protected $fillable = [
        'installment_plan_id',
        'sale_id',
        'customer_id',
        'installment_number',
        'due_date',
        'amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'installment_number' => 'integer',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function sale()
    {
        return $this->belongsTo(Order::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentAllocations()
    {
        return $this->hasMany(InstallmentPaymentAllocation::class);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [today(), today()->endOfWeek()]);
    }

    public function scopeOverdue($query)
    {
        return $query->whereDate('due_date', '<', today())
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PARTIAL, self::STATUS_OVERDUE]);
    }
}
