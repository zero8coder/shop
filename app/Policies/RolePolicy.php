<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(Admin $admin)
    {
        if ($admin->can(PermissionEnum::ROLES_MANAGE)) {
            return true;
        }
    }

    public function viewAny(Admin $admin)
    {
        return $admin->can(PermissionEnum::ROLES_VIEW_ANY);
    }

    public function view(Admin $admin, Role $role)
    {

    }


    public function create(Admin $admin)
    {
        return $admin->can(PermissionEnum::ROLES_CREATE);
    }

    public function update(Admin $admin, Role $role)
    {
        return $admin->can(PermissionEnum::ROLES_UPDATE);
    }


    public function delete(Admin $admin, Role $role)
    {
        return $admin->can(PermissionEnum::ROLES_DELETE);
    }

    public function restore(Admin $admin, Role $role)
    {
        //
    }

    public function forceDelete(Admin $admin, Role $role)
    {
        //
    }

    public function export(Admin $admin)
    {
        return $admin->can(PermissionEnum::ROLES_EXPORT);
    }
}
