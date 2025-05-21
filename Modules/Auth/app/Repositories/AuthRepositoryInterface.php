<?php

namespace Modules\Auth\Repositories;

use Modules\Auth\Models\User;

interface AuthRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
    public function update(User $user, array $data): User;
}
