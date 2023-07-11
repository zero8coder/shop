<?php

namespace Tests;

use App\Models\Admin;
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

    // 登录管理员用户
    protected function signInAdmin($admin = null)
    {
        $admin = $admin ?: Admin::factory()->create();
        Passport::actingAs($admin, [], 'admin');
        $this->actingAs($admin, 'admin');
        return $this;
    }

    // 登录后的json请求
    public function AuthorizationJson($method, $uri, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        // 检验链接未授权不能访问
        $response = $this->json($method, $uri, $data, $headers);
        $response->assertStatus(401);
        // 授权登录
        $this->signInAdmin();
        return $this->json($method, $uri, $data, $headers);
    }

}
