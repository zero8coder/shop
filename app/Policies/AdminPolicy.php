<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use App\Models\User;
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


    public function view(User $user, Admin $admin)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Admin $admin)
    {
        //
    }


    public function delete(User $user, Admin $admin)
    {
        //
    }

    public function restore(User $user, Admin $admin)
    {
        //
    }

    public function forceDelete(User $user, Admin $admin)
    {
        //
    }
}
