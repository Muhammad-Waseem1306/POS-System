<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getActionBadgeAttribute(): string
    {
        return match ($this->action) {
            'login'                 => '<span class="badge bg-success">Login</span>',
            'logout'                => '<span class="badge bg-secondary">Logout</span>',
            'failed_login'          => '<span class="badge bg-danger">Failed Login</span>',
            'create'                => '<span class="badge bg-primary">Create</span>',
            'update'                => '<span class="badge bg-warning text-dark">Update</span>',
            'delete'                => '<span class="badge bg-danger">Delete</span>',
            'payment'               => '<span class="badge bg-info">Payment</span>',
            'installment_change'    => '<span class="badge bg-purple" style="background:#6f42c1">Installment</span>',
            'inventory_adjustment'  => '<span class="badge bg-dark">Inventory</span>',
            default                 => '<span class="badge bg-secondary">' . ucfirst($this->action) . '</span>',
        };
    }
}
