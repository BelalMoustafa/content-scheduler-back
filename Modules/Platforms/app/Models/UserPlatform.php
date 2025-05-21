<?php

namespace Modules\Platforms\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Auth\Models\User;

class UserPlatform extends Pivot
{
    protected $table = 'user_platform';

    protected $fillable = [
        'user_id',
        'platform_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
