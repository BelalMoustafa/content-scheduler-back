<?php

namespace Modules\Auth\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\AssignRoleRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Services\PermissionService;

class RoleController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PermissionService $permissionService
    ) {}

    public function index(): JsonResponse
    {
        $roles = $this->permissionService->getAllRoles();
        return $this->okResponse('Roles retrieved successfully.', $roles);
    }

    public function assign(AssignRoleRequest $request): JsonResponse
    {
        $user = User::findOrFail($request->input('user_id'))->first();
        $roles = $request->input('roles');

        $this->permissionService->syncRoles($user, $roles);

        return $this->okResponse(
            'Roles assigned successfully.',
            $user->load('roles')
        );
    }

    public function remove(User $user, string $role): JsonResponse
    {
        $this->permissionService->removeRole($user, $role);

        return $this->okResponse(
            'Role removed successfully.',
            $user->load('roles')
        );
    }

    public function permissions(): JsonResponse
    {
        $permissions = $this->permissionService->getAllPermissions();
        return $this->okResponse('Permissions retrieved successfully.', $permissions);
    }
}
