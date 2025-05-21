<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    public function assignDefaultRole(User $user): void
    {
        $user->assignRole('user');
        Log::info('Default role assigned to user', ['user_id' => $user->id, 'role' => 'user']);
    }

    public function assignRole(User $user, string $role): void
    {
        if (!Role::where('name', $role)->exists()) {
            throw new \InvalidArgumentException("Role {$role} does not exist");
        }

        $user->assignRole($role);
        Log::info('Role assigned to user', ['user_id' => $user->id, 'role' => $role]);
    }

    public function removeRole(User $user, string $role): void
    {
        $user->removeRole($role);
        Log::info('Role removed from user', ['user_id' => $user->id, 'role' => $role]);
    }

    public function syncRoles(User $user, array $roles): void
    {
        $user->syncRoles($roles);
        Log::info('Roles synced for user', ['user_id' => $user->id, 'roles' => $roles]);
    }

    public function getAllRoles(): Collection
    {
        return Role::with('permissions')->get();
    }

    public function getAllPermissions(): Collection
    {
        return Permission::all();
    }

    public function createRole(string $name, array $permissions = []): Role
    {
        $role = Role::create(['name' => $name]);

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        Log::info('New role created', ['role' => $name, 'permissions' => $permissions]);
        return $role;
    }

    public function createPermission(string $name): Permission
    {
        $permission = Permission::create(['name' => $name]);
        Log::info('New permission created', ['permission' => $name]);
        return $permission;
    }

    public function assignPermissionToRole(string $roleName, string $permissionName): void
    {
        $role = Role::findByName($roleName);
        $permission = Permission::findByName($permissionName);

        $role->givePermissionTo($permission);
        Log::info('Permission assigned to role', ['role' => $roleName, 'permission' => $permissionName]);
    }
}
