<?php

namespace Modules\Platforms\Repositories;

use Illuminate\Support\Collection;
use Modules\Platforms\Models\Platform;
use Modules\Auth\Models\User;

class PlatformRepository implements PlatformRepositoryInterface
{
    public function __construct(
        private Platform $model
    ) {}

    /**
     * Get all platforms
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find platform by ID
     */
    public function find(int $id): ?Platform
    {
        return $this->model->find($id);
    }

    /**
     * Find platform by type
     */
    public function findByType(string $type): ?Platform
    {
        return $this->model->where('type', $type)->first();
    }

    /**
     * Create new platform
     */
    public function create(array $data): Platform
    {
        return $this->model->create($data);
    }

    /**
     * Update platform
     */
    public function update(int $id, array $data): Platform
    {
        $platform = $this->find($id);
        if (!$platform) {
            throw new \Exception('Platform not found');
        }

        $platform->update($data);
        return $platform->fresh();
    }

    /**
     * Delete platform
     */
    public function delete(int $id): bool
    {
        $platform = $this->find($id);
        if (!$platform) {
            throw new \Exception('Platform not found');
        }

        return $platform->delete();
    }

    public function getUserPlatforms(User $user): Collection
    {
        return Platform::with(['users' => function ($query) use ($user) {
            $query->where('users.id', $user->id);
        }])->get();
    }

    public function togglePlatform(User $user, Platform $platform, bool $isActive): void
    {
        $user->platforms()->syncWithoutDetaching([
            $platform->id => ['is_active' => $isActive]
        ]);
    }

    public function isPlatformActive(User $user, Platform $platform): bool
    {
        return $user->platforms()
            ->where('platforms.id', $platform->id)
            ->wherePivot('is_active', true)
            ->exists();
    }
}
