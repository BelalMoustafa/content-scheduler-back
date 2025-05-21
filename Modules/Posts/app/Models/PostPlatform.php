<?php

namespace Modules\Posts\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Auth\Models\User;
use Modules\Platforms\Models\Platform;

class PostPlatform extends Pivot
{
    protected $table = 'post_platform';

    protected $fillable = [
        'post_id',
        'platform_id',
        'platform_status',
        'error_message'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function scopePending($query)
    {
        return $query->where('platform_status', 'pending');
    }

    public function scopePublished($query)
    {
        return $query->where('platform_status', 'published');
    }

    public function scopeFailed($query)
    {
        return $query->where('platform_status', 'failed');
    }

    public function markAsPublished()
    {
        $this->update([
            'platform_status' => 'published',
            'error_message' => null
        ]);
    }

    public function markAsFailed(string $error)
    {
        $this->update([
            'platform_status' => 'failed',
            'error_message' => $error
        ]);
    }
}
