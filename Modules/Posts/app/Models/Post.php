<?php

namespace Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Auth\Models\User;
use Modules\Platforms\Models\Platform;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'image_url',
        'scheduled_time',
        'status',
        'user_id'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'post_platform')
            ->withPivot('platform_status', 'error_message')
            ->withTimestamps();
    }

    public function pendingPlatforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'post_platform')
            ->wherePivot('platform_status', 'pending')
            ->withPivot('error_message')
            ->withTimestamps();
    }

    public function publishedPlatforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'post_platform')
            ->wherePivot('platform_status', 'published')
            ->withPivot('error_message')
            ->withTimestamps();
    }

    public function failedPlatforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'post_platform')
            ->wherePivot('platform_status', 'failed')
            ->withPivot('error_message')
            ->withTimestamps();
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'scheduled']);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}
