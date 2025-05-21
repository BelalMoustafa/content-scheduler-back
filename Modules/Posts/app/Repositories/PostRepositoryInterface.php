<?php

namespace Modules\Posts\Repositories;

use Illuminate\Support\Collection;
use Modules\Posts\Models\Post;
use Modules\Auth\Models\User;

interface PostRepositoryInterface
{
    /**
     * Get all posts with optional filters
     */
    public function all(array $filters = []): Collection;

    /**
     * Find post by ID
     */
    public function find(int $id): ?Post;

    /**
     * Create new post
     */
    public function create(array $data): Post;

    /**
     * Update post
     */
    public function update(int $id, array $data): Post;

    /**
     * Delete post
     */
    public function delete(int $id): bool;

    /**
     * Get user's scheduled posts count for a specific date
     */
    public function getScheduledPostsCount(User $user, string $date): int;

    /**
     * Get posts due for publishing
     */
    public function getDuePosts(): Collection;

    /**
     * Attach platforms to post
     */
    public function attachPlatforms(Post $post, array $platformIds): void;

    /**
     * Sync platforms for post
     */
    public function syncPlatforms(Post $post, array $platformIds): void;
}
