<?php

namespace Modules\Platforms\Repositories;

use Illuminate\Support\Collection;
use Modules\Platforms\Models\Platform;
use Modules\Auth\Models\User;

interface PlatformRepositoryInterface
{
    /**
     * Get all platforms
     */
    public function all(): Collection;

    /**
     * Find platform by ID
     */
    public function find(int $id): ?Platform;

    /**
     * Find platform by type
     */
    public function findByType(string $type): ?Platform;

    /**
     * Create new platform
     */
    public function create(array $data): Platform;

    /**
     * Update platform
     */
    public function update(int $id, array $data): Platform;

    /**
     * Delete platform
     */
    public function delete(int $id): bool;

    public function getUserPlatforms(User $user): Collection;
    public function togglePlatform(User $user, Platform $platform, bool $isActive): void;
    public function isPlatformActive(User $user, Platform $platform): bool;
}
