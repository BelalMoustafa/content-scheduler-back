<?php

namespace Modules\Platforms\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Auth\Models\User;
use Modules\Platforms\Models\Platform;
use Modules\Platforms\Repositories\PlatformRepositoryInterface;

class PlatformService
{
    public function __construct(
        private PlatformRepositoryInterface $platformRepository
    ) {}

    /**
     * Get all platforms with optional user status
     */
    public function getAllPlatforms(?User $user = null): Collection
    {
        $platforms = $this->platformRepository->all();

        if ($user) {
            return $platforms->map(function ($platform) use ($user) {
                $platform->is_active = $platform->users()
                    ->where('user_id', $user->id)
                    ->where('is_active', true)
                    ->exists();
                return $platform;
            });
        }

        return $platforms;
    }

    /**
     * Get platform with all its users
     */
    public function getPlatformWithUsers(int $id): Platform
    {
        $platform = $this->platformRepository->find($id);
        if (!$platform) {
            throw new \Exception('Platform not found');
        }
        return $platform->load('users');
    }

    /**
     * Create a new platform
     */
    public function createPlatform(string $name, string $type): Platform
    {
        $platform = $this->platformRepository->create([
            'name' => $name,
            'type' => $type
        ]);

        Log::info('Platform created', [
            'platform_id' => $platform->id,
            'name' => $name,
            'type' => $type
        ]);

        return $platform;
    }

    /**
     * Update platform details
     */
    public function updatePlatform(int $id, string $name, string $type): Platform
    {
        $platform = $this->platformRepository->find($id);
        if (!$platform) {
            throw new \Exception('Platform not found');
        }

        $platform = $this->platformRepository->update($id, [
            'name' => $name,
            'type' => $type
        ]);

        Log::info('Platform updated', [
            'platform_id' => $id,
            'name' => $name,
            'type' => $type
        ]);

        return $platform;
    }

    /**
     * Delete a platform
     */
    public function deletePlatform(int $id): void
    {
        $platform = $this->platformRepository->find($id);
        if (!$platform) {
            throw new \Exception('Platform not found');
        }

        $this->platformRepository->delete($id);

        Log::info('Platform deleted', [
            'platform_id' => $id
        ]);
    }

    /**
     * Toggle platform status for a user
     */
    public function togglePlatform(User $user, int $platformId, bool $isActive): Platform
    {
        $platform = $this->platformRepository->find($platformId);
        if (!$platform) {
            throw new \Exception('Platform not found');
        }

        $platform->users()->syncWithoutDetaching([
            $user->id => ['is_active' => $isActive]
        ]);

        Log::info('Platform status toggled', [
            'user_id' => $user->id,
            'platform_id' => $platformId,
            'is_active' => $isActive
        ]);

        return $platform;
    }

    /**
     * Get platform analytics
     */
    public function getPlatformAnalytics(): array
    {
        $platforms = $this->platformRepository->all();

        return $platforms->map(function ($platform) {
            return [
                'id' => $platform->id,
                'name' => $platform->name,
                'type' => $platform->type,
                'total_users' => $platform->users()->count(),
                'active_users' => $platform->users()->wherePivot('is_active', true)->count(),
                'inactive_users' => $platform->users()->wherePivot('is_active', false)->count(),
                'created_at' => $platform->created_at,
                'updated_at' => $platform->updated_at
            ];
        })->toArray();
    }
}
