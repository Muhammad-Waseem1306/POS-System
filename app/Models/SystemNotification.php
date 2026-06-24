<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $guarded = [];

    public function readBy()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function getSeverityIconAttribute(): string
    {
        return match ($this->severity) {
            'danger'  => 'fas fa-exclamation-circle text-danger',
            'warning' => 'fas fa-exclamation-triangle text-warning',
            'success' => 'fas fa-check-circle text-success',
            default   => 'fas fa-info-circle text-info',
        };
    }

    public function getSeverityClassAttribute(): string
    {
        return match ($this->severity) {
            'danger'  => 'border-left-danger',
            'warning' => 'border-left-warning',
            'success' => 'border-left-success',
            default   => 'border-left-info',
        };
    }

    public static function unreadCount(): int
    {
        return static::where('is_read', false)->count();
    }

    public static function createNotification(string $type, string $title, string $message, string $severity = 'info', ?string $actionUrl = null, ?string $referenceType = null, ?int $referenceId = null): self
    {
        return static::create([
            'type'           => $type,
            'title'          => $title,
            'message'        => $message,
            'severity'       => $severity,
            'action_url'     => $actionUrl,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
        ]);
    }
}
