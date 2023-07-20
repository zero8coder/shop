<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use App\Models\Menu;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function before(Admin $admin)
    {
        if ($admin->can(PermissionEnum::MENU_MANAGE)) {
            return true;
        }
    }

    public function viewAny(Admin $admin)
    {
        return $admin->can(PermissionEnum::MENU_VIEW_ANY);
    }

    public function create(Admin $admin)
    {
        return $admin->can(PermissionEnum::MENU_CREATE);
    }

    public function update(Admin $admin, Menu $menu)
    {
        return $admin->can(PermissionEnum::MENU_UPDATE);
    }

    public function delete(Admin $admin, Menu $menu)
    {
        return $admin->can(PermissionEnum::MENU_DELETE);
    }

    public function restore(Admin $admin, Menu $menu)
    {
        //
    }

    public function forceDelete(Admin $admin, Menu $menu)
    {
        //
    }
}
