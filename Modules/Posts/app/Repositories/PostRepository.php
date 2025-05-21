<?php

namespace Modules\Posts\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Posts\Models\Post;
use Modules\Auth\Models\User;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        private Post $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->with(['platforms']);

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['date_from'])) {
            $query->where('scheduled_time', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('scheduled_time', '<=', $filters['date_to']);
        }

        return $query->get();
    }

    public function find(int $id): ?Post
    {
        return $this->model->with(['platforms'])->find($id);
    }

    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $post = $this->model->create($data);

            if (isset($data['platform_ids'])) {
                $this->attachPlatforms($post, $data['platform_ids']);
            }

            return $post->load('platforms');
        });
    }

    public function update(int $id, array $data): Post
    {
        return DB::transaction(function () use ($id, $data) {
            $post = $this->find($id);
            if (!$post) {
                throw new \Exception('Post not found');
            }

            $post->update($data);

            if (isset($data['platform_ids'])) {
                $this->syncPlatforms($post, $data['platform_ids']);
            }

            return $post->fresh(['platforms']);
        });
    }

    public function delete(int $id): bool
    {
        $post = $this->find($id);
        if (!$post) {
            throw new \Exception('Post not found');
        }

        return $post->delete();
    }

    public function getScheduledPostsCount(User $user, string $date): int
    {
        return $this->model
            ->where('user_id', $user->id)
            ->where('status', 'scheduled')
            ->whereDate('scheduled_time', $date)
            ->count();
    }

    public function getDuePosts(): Collection
    {
        return $this->model
            ->where('status', 'scheduled')
            ->where('scheduled_time', '<=', now())
            ->with(['platforms'])
            ->get();
    }

    public function attachPlatforms(Post $post, array $platformIds): void
    {
        $post->platforms()->attach($platformIds, [
            'platform_status' => 'pending'
        ]);
    }

    public function syncPlatforms(Post $post, array $platformIds): void
    {
        $post->platforms()->sync($platformIds);
    }
}
