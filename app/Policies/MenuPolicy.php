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

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Admin $admin, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Admin $admin)
    {
        return  $admin->can(PermissionEnum::MENU_CREATE);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Admin $admin, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Admin $admin, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(Admin $admin, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(Admin $admin, Menu $menu)
    {
        //
    }
}
