<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use App\Http\Requests\RolePermission\PermissionRequest;
use App\Http\Requests\RolePermission\RoleRequest;
use App\Http\Requests\RolePermission\SyncPermissionsRequest;
use App\Models\User;
use App\Services\RbacService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RbacController extends ApiController
{

    public function __construct(protected RbacService $rbacService) {}


    /*
|--------------------------------------------------------------------------
| Roles functions
|--------------------------------------------------------------------------
*/
    public function createRole(RoleRequest $request)
    {
        $data = $this->rbacService->createRole($request->validated('role'));
        return $this->responseSuccess(
            201,
            'created successfully',
            $data
        );
    }

    public function deleteRole(Role $role)
    {
        $this->rbacService->deleteRole($role);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }

    public function assignRole(RoleRequest $request, User $user)
    {
        $data = $this->rbacService->assignRole($user, $request->validated('role'));
        return $this->responseSuccess(
            200,
            'assigned successfully',
            $data
        );
    }

    public function removeRole(Role $role, User $user)
    {
        $data = $this->rbacService->removeRole($user, $role);
        return $this->responseSuccess(
            200,
            'removed successfully',
            $data
        );
    }



    /*
|--------------------------------------------------------------------------
| Permissions functions
|--------------------------------------------------------------------------
*/
    public function createPermission(PermissionRequest $request)
    {
        $data = $this->rbacService->createPermission($request->validated('permission'));
        return $this->responseSuccess(
            201,
            'created successfully',
            $data
        );
    }
    public function deletePermission(Permission $permission)
    {
        $this->rbacService->deletePermission($permission);
        return $this->responseSuccess(
            200,
            "deleted successfully"
        );
    }

    public function assignPermission(PermissionRequest $request, User $user)
    {
        $data = $this->rbacService->assignPermission(
            $user,
            $request->validated('permission')
        );
        return $this->responseSuccess(
            200,
            'assigned successfully',
            $data
        );
    }

    public function removePermission(Permission $permission, User $user)
    {
        $data = $this->rbacService->removePermission($user, $permission);
        return $this->responseSuccess(
            200,
            'removed successfully',
            $data
        );
    }

    public function syncPermissions(SyncPermissionsRequest $request, Role $role)
    {
        $data = $this->rbacService->syncPermissions(
            $role,
            $request->validated('permissions')
        );
        return $this->responseSuccess(
            200,
            'synced successfully',
            $data
        );
    }
}
