<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $table = 'license';

    protected $guarded = [];

    protected $casts = [
        'license_expires_at' => 'date',
        'is_active'          => 'boolean',
    ];

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->license_expires_at) return null;
        return now()->diffInDays($this->license_expires_at, false);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->license_expires_at) return false;
        return $this->license_expires_at->isPast();
    }

    public function getExpiryStatusAttribute(): string
    {
        $days = $this->days_until_expiry;
        if ($days === null)   return 'lifetime';
        if ($days < 0)        return 'expired';
        if ($days <= 7)       return 'critical';
        if ($days <= 30)      return 'warning';
        return 'active';
    }

    public static function current(): ?self
    {
        return static::first();
    }
}
