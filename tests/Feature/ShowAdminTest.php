<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use Tests\TestCase;

class ShowAdminTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    public function test_show_admin()
    {
        $this->setRoles([]); // 清空角色
        $this->setPermissions([PermissionEnum::ADMINS]); // 设置管理权限
        $response = $this->authorizationJson('get', route('admin.v1.admins.show', ['admin' => $this->admin->id]));
        $response->assertStatus(200);
        $responseAdmin = $response->json();
        $this->assertEquals($this->admin->name, $responseAdmin['data']['name']);
    }

    // 检测查看所有权限
    public function test_show_admin_by_permission_view_any()
    {
        $this->setRoles([]); // 清空角色
        $this->setPermissions([PermissionEnum::ADMINS_VIEW_ANY]); // 设置查看所有权限
        $response = $this->authorizationJson('get', route('admin.v1.admins.show', ['admin' => $this->admin->id]));
        $response->assertStatus(200);
        $responseAdmin = $response->json();
        $this->assertEquals($this->admin->name, $responseAdmin['data']['name']);
    }
}
