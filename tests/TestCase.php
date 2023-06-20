<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    // 创建密码方式的用户客户端
    public function createUserPasswordClient(): \Laravel\Passport\Client
    {
        $clientRepository = new ClientRepository();
        return $clientRepository->create(
            null,
            'shop-user',
            url('/redirect'),
            'users',
            false,
            true
        );
    }

    // 创建密码方式的管理员客户端
    public function createAdminPasswordClient(): \Laravel\Passport\Client
    {
        $clientRepository = new ClientRepository();
        return $clientRepository->create(
            null,
            'shop-admin',
            url('/redirect'),
            'admins',
            false,
            true
        );
    }

    // 登录用户
    protected function signIn($user = null)
    {
        $user = $user ?: User::factory()->create();
        Passport::actingAs($user);
        $this->actingAs($user);
        return $this;
    }
}
