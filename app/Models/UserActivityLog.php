<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_active_at' => 'datetime',
        'is_active'      => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
