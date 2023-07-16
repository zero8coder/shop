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

    }


    public function view(Admin $admin, Admin $processAdmin)
    {
        //
    }

    public function create(Admin $admin)
    {
        return $admin->can(PermissionEnum::ADMINS_CREATE);
    }

    public function update(Admin $admin, Admin $processAdmin)
    {
        //
    }


    public function delete(Admin $admin, Admin $processAdmin)
    {
        //
    }

    public function restore(Admin $admin, Admin $processAdmin)
    {
        //
    }

    public function forceDelete(Admin $admin, Admin $processAdmin)
    {
        //
    }
}
