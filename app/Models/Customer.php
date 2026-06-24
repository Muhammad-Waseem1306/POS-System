<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'cnic', 'address'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function guarantors()
    {
        return $this->hasMany(CustomerGuarantor::class);
    }

    public function documents()
    {
        return $this->hasMany(CustomerDocument::class);
    }

    public function installmentPlans()
    {
        return $this->hasMany(InstallmentPlan::class);
    }

    public function installmentSchedules()
    {
        return $this->hasMany(InstallmentSchedule::class);
    }
}
