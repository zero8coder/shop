<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function before(Admin $admin)
    {
        if ($admin->can(PermissionEnum::ADMINS)) {
            return true;
        }
    }

    public function viewAny(Admin $admin)
    {
        if ($admin->can(PermissionEnum::ADMINS_VIEW_ANY)) {
            return true;
        }
    }


    public function view(Admin $admin, Admin $processAdmin)
    {
        if ($admin->can(PermissionEnum::ADMINS_VIEW_ANY)) {
            return true;
        }
    }

    public function create(Admin $admin)
    {
        return $admin->can(PermissionEnum::ADMINS_CREATE);
    }

    public function update(Admin $admin, Admin $processAdmin)
    {
        return $admin->can(PermissionEnum::ADMINS_UPDATE);
    }


    public function delete(Admin $admin, Admin $processAdmin)
    {
        return $admin->can(PermissionEnum::ADMINS_DELETE);
    }

    public function export(Admin $admin)
    {
        return $admin->can(PermissionEnum::ADMINS_EXPORT);
    }

}
