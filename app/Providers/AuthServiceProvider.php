<?php

namespace App\Providers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model\Admin' => 'App\Policies\AdminPolicy',
        'App\Model\Role' => 'App\Policies\RolePolicy',
        'App\Model\Permission' => 'App\Policies\PermissionPolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 超级管理员跳过权限检测
        Gate::before(function ($user, $ability) {
            return $user->hasRole(RoleEnum::SUPER_ADMIN) ? true : null;
        });

        // Passport 的路由
        Passport::routes();
        // access_token 过期时间
        Passport::tokensExpireIn(now()->addDays(15));
        // refreshTokens 过期时间
        Passport::refreshTokensExpireIn(now()->addDays(17));


    }
}
