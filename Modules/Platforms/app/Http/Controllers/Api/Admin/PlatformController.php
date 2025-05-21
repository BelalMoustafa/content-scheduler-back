<?php

namespace Modules\Platforms\Http\Controllers\Api\Admin;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Platforms\Services\PlatformService;
use Modules\Platforms\Http\Requests\TogglePlatformRequest;

class PlatformController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PlatformService $platformService
    ) {}

    /**
     * Get all platforms with their users
     */
    public function index(): JsonResponse
    {
        $platforms = $this->platformService->getAllPlatforms();
        return $this->okResponse('Platforms retrieved successfully.', $platforms);
    }

    /**
     * Get platform details with all users
     */
    public function show(int $id): JsonResponse
    {
        $platform = $this->platformService->getPlatformWithUsers($id);
        return $this->okResponse('Platform details retrieved successfully.', $platform);
    }

    /**
     * Create a new platform
     */
    public function store(Request $request): JsonResponse
    {
        $platform = $this->platformService->createPlatform(
            $request->input('name'),
            $request->input('type')
        );
        return $this->createdResponse('Platform created successfully.', $platform);
    }

    /**
     * Update platform details
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $platform = $this->platformService->updatePlatform(
            $id,
            $request->input('name'),
            $request->input('type')
        );
        return $this->okResponse('Platform updated successfully.', $platform);
    }

    /**
     * Delete a platform
     */
    public function destroy(int $id): JsonResponse
    {
        $this->platformService->deletePlatform($id);
        return $this->okResponse('Platform deleted successfully.');
    }

    /**
     * Toggle platform status for a user
     */
    public function toggleUserPlatform(TogglePlatformRequest $request): JsonResponse
    {
        $platform = $this->platformService->togglePlatform(
            $request->input('user_id'),
            $request->input('platform_id'),
            $request->input('is_active')
        );
        return $this->okResponse('Platform status updated successfully.', $platform);
    }

    /**
     * Get platform analytics
     */
    public function analytics(): JsonResponse
    {
        $analytics = $this->platformService->getPlatformAnalytics();
        return $this->okResponse('Platform analytics retrieved successfully.', $analytics);
    }
}
