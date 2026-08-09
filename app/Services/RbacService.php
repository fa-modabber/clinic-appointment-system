<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RbacService
{

    /*
|--------------------------------------------------------------------------
| Roles functions
|--------------------------------------------------------------------------
*/
    public function createRole(string $name): Role
    {
        return Role::create([
            'name' => $name,
            'guard_name' => 'sanctum'
        ]);
    }

    public function deleteRole(Role $role): void
    {
        $role->delete();
    }

    public function assignRole(User $user, string $roleName): User
    {
        $role = Role::findByName($roleName);
        $user->assignRole($role);
        return $user->load('roles');
    }

    public function removeRole(User $user, Role $role): User
    {
        $user->removeRole($role);
        return $user->load('roles');
    }


    /*
|--------------------------------------------------------------------------
| Permissions functions
|--------------------------------------------------------------------------
*/

    public function createPermission(string $name): Permission
    {
        return Permission::create([
            'name' => $name,
        ]);
    }

    public function deletePermission(Permission $permission): void
    {
        $permission->delete();
    }

    public function assignPermission(User $user, string $permissionName): User
    {
        $permission = Permission::findByName($permissionName);
        $user->givePermissionTo($permission);
        return $user->load('permissions');
    }

    public function removePermission(User $user, string $permissionName): User
    {
        $permission = Permission::findByName($permissionName);
        $user->revokePermissionTo($permission);
        return $user->load('permissions');
    }

    public function syncPermissions(
        Role $role,
        array $permissionNames
    ): Role {
        $role->syncPermissions($permissionNames);
        return $role->load('permissions');
    }
}
