<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\SystemNotification;

class StockService
{
    public static function recordMovement(
        int    $productId,
        string $type,
        int    $quantityChange,
        ?string $reason       = null,
        ?string $notes        = null,
        ?string $referenceType = null,
        ?int   $referenceId   = null,
        ?float $unitCost      = null
    ): StockMovement {
        $product        = Product::findOrFail($productId);
        $quantityBefore = $product->quantity;
        $quantityAfter  = $quantityBefore + $quantityChange;

        $movement = StockMovement::create([
            'product_id'      => $productId,
            'type'            => $type,
            'reference_type'  => $referenceType,
            'reference_id'    => $referenceId,
            'quantity_before' => $quantityBefore,
            'quantity_change' => $quantityChange,
            'quantity_after'  => $quantityAfter,
            'unit_cost'       => $unitCost,
            'reason'          => $reason,
            'notes'           => $notes,
            'user_id'         => auth()->id(),
        ]);

        static::checkLowStock($product, $quantityAfter);

        return $movement;
    }

    public static function checkLowStock(Product $product, int $currentQty): void
    {
        $threshold = $product->low_stock_threshold ?? 5;

        if ($currentQty <= $threshold && $currentQty >= 0) {
            $existing = SystemNotification::where('type', 'low_stock')
                ->where('reference_type', Product::class)
                ->where('reference_id', $product->id)
                ->where('is_read', false)
                ->exists();

            if (!$existing) {
                SystemNotification::createNotification(
                    'low_stock',
                    'Low Stock Alert',
                    "Product \"{$product->name}\" has only {$currentQty} unit(s) remaining (threshold: {$threshold}).",
                    $currentQty === 0 ? 'danger' : 'warning',
                    route('backend.admin.products.edit', $product->id),
                    Product::class,
                    $product->id
                );
            }
        }
    }

    public static function checkAllLowStock(): int
    {
        $count = 0;
        Product::where('track_stock', true)->chunk(100, function ($products) use (&$count) {
            foreach ($products as $product) {
                $existing = SystemNotification::where('type', 'low_stock')
                    ->where('reference_id', $product->id)
                    ->where('is_read', false)
                    ->exists();

                if (!$existing && $product->quantity <= $product->low_stock_threshold) {
                    static::checkLowStock($product, $product->quantity);
                    $count++;
                }
            }
        });
        return $count;
    }

    public static function canSell(int $productId, int $quantity): bool
    {
        $product = Product::find($productId);
        if (!$product) return false;
        if (!$product->track_stock) return true;
        return $product->quantity >= $quantity;
    }
}
