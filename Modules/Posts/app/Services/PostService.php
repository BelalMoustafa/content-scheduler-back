<?php

namespace Modules\Posts\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Modules\Posts\Models\Post;
use Modules\Posts\Repositories\PostRepositoryInterface;

class PostService
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {}

    /**
     * Get all posts with optional filters
     */
    public function getAllPosts(array $filters = []): Collection
    {
        return $this->postRepository->all($filters);
    }

    /**
     * Get post by ID
     */
    public function getPost(int $id): Post
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new \Exception('Post not found');
        }
        return $post;
    }

    /**
     * Create new post
     */
    public function createPost(User $user, array $data): Post
    {
        $data['user_id'] = $user->id;
        $data['status'] = isset($data['scheduled_time']) ? 'scheduled' : 'draft';

        $post = $this->postRepository->create($data);

        Log::info('Post created', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'status' => $post->status
        ]);

        return $post;
    }

    /**
     * Update post
     */
    public function updatePost(int $id, array $data): Post
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new \Exception('Post not found');
        }

        if (!$post->isEditable()) {
            throw new \Exception('Cannot update published post');
        }

        // Update status if scheduled_time is provided
        if (isset($data['scheduled_time'])) {
            $data['status'] = 'scheduled';
        }

        $post = $this->postRepository->update($id, $data);

        Log::info('Post updated', [
            'post_id' => $id,
            'status' => $post->status
        ]);

        return $post;
    }

    /**
     * Delete post
     */
    public function deletePost(int $id): void
    {
        $post = $this->postRepository->find($id);
        if (!$post) {
            throw new \Exception('Post not found');
        }

        if (!$post->isEditable()) {
            throw new \Exception('Cannot delete published post');
        }

        $this->postRepository->delete($id);

        Log::info('Post deleted', [
            'post_id' => $id
        ]);
    }

    /**
     * Publish post to platforms
     */
    public function publishPost(Post $post): void
    {
        if (!$post->isScheduled()) {
            throw new \Exception('Only scheduled posts can be published');
        }

        $success = true;
        foreach ($post->platforms as $platform) {
            try {
                // Simulate publishing to platform
                $this->simulatePublishToPlatform($post, $platform);
                $platform->pivot->markAsPublished();
            } catch (\Exception $e) {
                $success = false;
                $platform->pivot->markAsFailed($e->getMessage());
                Log::error('Failed to publish post to platform', [
                    'post_id' => $post->id,
                    'platform_id' => $platform->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Update post status based on platform publishing results
        if ($success) {
            $post->update(['status' => 'published']);
            Log::info('Post published successfully', ['post_id' => $post->id]);
        } else {
            Log::warning('Post partially published', ['post_id' => $post->id]);
        }
    }

    /**
     * Simulate publishing to a platform
     */
    private function simulatePublishToPlatform(Post $post, $platform): void
    {
        // Simulate API call delay
        sleep(1);

        // Simulate random failures (10% chance)
        if (rand(1, 100) <= 10) {
            throw new \Exception("Failed to publish to {$platform->name}");
        }
    }
}
