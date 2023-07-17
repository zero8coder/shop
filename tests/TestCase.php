<?php

namespace Tests;

use App\Enums\RoleEnum;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    public $roles = [];
    public $permissions = [];

    public function runSeeder()
    {
        Artisan::call('db:seed', ['--class' => 'PermissionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RolesTableSeeder']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->runSeeder();

    }

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
    public function authorizationJson($method, $uri, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        // 判断接口需要登录
        $response = $this->json($method, $uri, $data, $headers);
        $response->assertStatus(401);

        // 用户登录
        $this->signInAdmin();

        // 判断接口用户权限问题
        $response = $this->json($method, $uri, $data, $headers);
        $response->assertStatus(403);

        // 默认设置超级管理员
        auth('admin')->user()->syncRoles($this->roles);
        // 设置权限
        auth('admin')->user()->syncPermissions($this->permissions);

        return $this->json($method, $uri, $data, $headers);
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }


}
