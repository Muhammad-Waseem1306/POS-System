<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPaymentAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_transaction_id',
        'installment_plan_id',
        'installment_schedule_id',
        'amount',
        'allocated_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'allocated_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(OrderTransaction::class, 'order_transaction_id');
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function installmentSchedule()
    {
        return $this->belongsTo(InstallmentSchedule::class);
    }
}
