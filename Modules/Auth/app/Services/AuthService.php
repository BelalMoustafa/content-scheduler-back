<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\User;
use Modules\Auth\Repositories\AuthRepositoryInterface;

class AuthService
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private PermissionService $permissionService
    ) {}

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->authRepository->create($data);

        // Assign default role
        $this->permissionService->assignDefaultRole($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->load('roles'),
            'token' => $token
        ];
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->load('roles'),
            'token' => $token
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function updateProfile(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->authRepository->update($user, $data);
    }
}
