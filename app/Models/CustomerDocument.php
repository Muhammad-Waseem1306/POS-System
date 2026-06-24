<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDocument extends Model
{
    use HasFactory;

    public const TYPE_CUSTOMER_PHOTO = 'customer_photo';
    public const TYPE_CNIC_FRONT = 'cnic_front';
    public const TYPE_CNIC_BACK = 'cnic_back';
    public const TYPE_UTILITY_BILL = 'utility_bill';
    public const TYPE_GUARANTOR_DOCUMENT = 'guarantor_document';

    protected $fillable = [
        'customer_id',
        'customer_guarantor_id',
        'document_type',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'notes',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function guarantor()
    {
        return $this->belongsTo(CustomerGuarantor::class, 'customer_guarantor_id');
    }
}
