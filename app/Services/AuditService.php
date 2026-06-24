<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public static function log(
        string $action,
        ?string $module = null,
        ?int $recordId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): AuditLog {
        $user = Auth::user();
        $req  = $request ?? request();

        return AuditLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name,
            'action'      => $action,
            'module'      => $module,
            'record_id'   => $recordId,
            'description' => $description,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'ip_address'  => $req->ip(),
            'user_agent'  => $req->userAgent(),
            'url'         => $req->fullUrl(),
            'method'      => $req->method(),
        ]);
    }

    public static function logLogin(string $email, bool $success = true, ?string $ipAddress = null): void
    {
        $user = Auth::user();
        AuditLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name ?? $email,
            'action'      => $success ? 'login' : 'failed_login',
            'module'      => 'auth',
            'description' => $success
                ? "Successful login for {$email}"
                : "Failed login attempt for {$email}",
            'ip_address'  => $ipAddress ?? request()->ip(),
            'user_agent'  => request()->userAgent(),
            'url'         => request()->fullUrl(),
            'method'      => 'POST',
        ]);
    }

    public static function logLogout(): void
    {
        $user = Auth::user();
        AuditLog::create([
            'user_id'    => $user?->id,
            'user_name'  => $user?->name,
            'action'     => 'logout',
            'module'     => 'auth',
            'description'=> "User {$user?->name} logged out",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url'        => request()->fullUrl(),
            'method'     => 'GET',
        ]);
    }

    public static function logCreate(string $module, int $recordId, string $description, ?array $newValues = null): void
    {
        static::log('create', $module, $recordId, $description, null, $newValues);
    }

    public static function logUpdate(string $module, int $recordId, string $description, ?array $oldValues = null, ?array $newValues = null): void
    {
        static::log('update', $module, $recordId, $description, $oldValues, $newValues);
    }

    public static function logDelete(string $module, int $recordId, string $description, ?array $oldValues = null): void
    {
        static::log('delete', $module, $recordId, $description, $oldValues, null);
    }

    public static function logPayment(int $orderId, float $amount, string $method, string $description): void
    {
        static::log('payment', 'orders', $orderId, $description, null, [
            'amount' => $amount,
            'method' => $method,
        ]);
    }

    public static function logInstallmentChange(int $planId, string $description, ?array $oldValues = null, ?array $newValues = null): void
    {
        static::log('installment_change', 'installments', $planId, $description, $oldValues, $newValues);
    }

    public static function logInventoryAdjustment(int $productId, int $quantityBefore, int $quantityAfter, string $reason): void
    {
        static::log('inventory_adjustment', 'products', $productId,
            "Stock adjusted: {$quantityBefore} → {$quantityAfter}. Reason: {$reason}",
            ['quantity' => $quantityBefore],
            ['quantity' => $quantityAfter]
        );
    }
}
