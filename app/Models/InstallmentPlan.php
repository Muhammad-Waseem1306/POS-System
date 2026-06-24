<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerGuarantor;

class InstallmentPlan extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_DEFAULTED = 'defaulted';

    protected $fillable = [
        'sale_id',
        'guarantor_id',
        'customer_id',
        'cash_price',
        'installment_price',
        'total_amount',
        'down_payment',
        'financed_amount',
        'installment_months',
        'monthly_installment',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'cash_price' => 'decimal:2',
        'installment_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'guarantor_id' => 'integer',
        'down_payment' => 'decimal:2',
        'financed_amount' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'installment_months' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function sale()
    {
        return $this->belongsTo(Order::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function guarantor()
    {
        return $this->belongsTo(CustomerGuarantor::class, 'guarantor_id');
    }

    public function schedules()
    {
        return $this->hasMany(InstallmentSchedule::class);
    }

    public function paymentAllocations()
    {
        return $this->hasMany(InstallmentPaymentAllocation::class);
    }
}
