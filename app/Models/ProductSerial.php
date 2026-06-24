<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_SOLD = 'sold';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_SERVICE = 'service';

    protected $fillable = [
        'product_id',
        'purchase_item_id',
        'order_product_id',
        'serial_number',
        'warranty_period_months',
        'warranty_starts_at',
        'warranty_ends_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'warranty_period_months' => 'integer',
        'warranty_starts_at' => 'date',
        'warranty_ends_at' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }
}
