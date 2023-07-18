<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use App\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    public function before(Admin $admin)
    {
        if ($admin->can(PermissionEnum::PERMISSIONS_MANAGE)) {
            return true;
        }
    }

    public function viewAny(Admin $admin)
    {
        return $admin->can(PermissionEnum::PERMISSIONS_VIEW_ANY);
    }

    public function create(Admin $admin)
    {
        return $admin->can(PermissionEnum::PERMISSIONS_CREATE);
    }

    public function update(Admin $admin, Permission $permission)
    {
        return $admin->can(PermissionEnum::PERMISSIONS_UPDATE);
    }

    public function delete(Admin $admin, Permission $permission)
    {
        return $admin->can(PermissionEnum::PERMISSIONS_DELETE);
    }

    public function export(Admin $admin)
    {
        return $admin->can(PermissionEnum::PERMISSIONS_EXPORT);
    }
}
