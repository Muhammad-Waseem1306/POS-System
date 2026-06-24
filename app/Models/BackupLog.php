<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    protected $guarded = [];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'success'     => '<span class="badge bg-success">Success</span>',
            'failed'      => '<span class="badge bg-danger">Failed</span>',
            'in_progress' => '<span class="badge bg-warning text-dark">In Progress</span>',
            default       => '<span class="badge bg-secondary">' . $this->status . '</span>',
        };
    }
}
