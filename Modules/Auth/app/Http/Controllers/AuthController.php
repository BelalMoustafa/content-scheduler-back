<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Requests\UpdateProfileRequest;
use Modules\Auth\Services\AuthService;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AuthService $authService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        return $this->okResponse('Registration successful.', $result);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->input('email'),
            $request->input('password')
        );

        if (!$result) {
            return $this->unauthorizedResponse('Invalid credentials.');
        }

        return $this->okResponse('Login successful.', $result);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout(auth()->user());
        return $this->okResponse('Logged out successfully.');
    }

    public function me(): JsonResponse
    {
        return $this->okResponse('Profile retrieved successfully.', auth()->user());
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->authService->updateProfile(
            auth()->user(),
            $request->validated()
        );

        return $this->okResponse('Profile updated successfully.', $user);
    }
}
