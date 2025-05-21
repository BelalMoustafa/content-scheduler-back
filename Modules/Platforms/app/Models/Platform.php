<?php

namespace Modules\Platforms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Auth\Models\User;

class Platform extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_platform')
            ->using(UserPlatform::class)
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function activeUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_platform')
            ->using(UserPlatform::class)
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    public function inactiveUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_platform')
            ->using(UserPlatform::class)
            ->wherePivot('is_active', false)
            ->withTimestamps();
    }
}
