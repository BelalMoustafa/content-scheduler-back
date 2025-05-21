<?php

namespace Modules\Platforms\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Platforms\Http\Requests\TogglePlatformRequest;
use Modules\Platforms\Services\PlatformService;

class PlatformController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PlatformService $platformService
    ) {}

    public function index(): JsonResponse
    {
        $platforms = $this->platformService->getAllPlatforms(auth()->user());
        return $this->okResponse('Platforms retrieved successfully.', $platforms);
    }

    public function toggle(TogglePlatformRequest $request): JsonResponse
    {
        $platform = $this->platformService->togglePlatform(
            auth()->user(),
            $request->input('platform_id'),
            $request->input('is_active')
        );

        return $this->okResponse(
            'Platform status updated successfully.',
            $platform
        );
    }
}
