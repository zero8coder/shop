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
        if ($admin->can(PermissionEnum::ROLES)) {
            return true;
        }
    }

    public function viewAny(Admin $admin)
    {
        //
    }

    public function view(Admin $admin, Role $role)
    {
        //
    }


    public function create(Admin $admin)
    {
        //
    }

    public function update(Admin $admin, Role $role)
    {
        //
    }


    public function delete(Admin $admin, Role $role)
    {
        //
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
        //
    }
}