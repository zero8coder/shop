<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use Tests\TestCase;

class AddAdminTest extends TestCase
{
    // 未登录用户
    public function test_unauthorized_add_admin()
    {
        $response = $this->addAdmin();
        $response->assertStatus(401);
    }

    // 无权限用户
    public function test_no_permission_add_admin()
    {
        $this->signInAdmin();
        $response = $this->addAdmin();
        $response->assertStatus(403);
    }

    // 添加管理员
    public function test_add_admin()
    {
        $admin = Admin::factory()->create();
        $this->signInAdmin($admin);
        // 添加权限
        $admin->givePermissionTo(PermissionEnum::ADMINS_CREATE);
        $response = $this->addAdmin();
        $response->assertStatus(200);
        $response->assertsee('libai');
    }

    public function test_add_admin_manage_permission()
    {
        $admin = Admin::factory()->create();
        $this->signInAdmin($admin);
        // 管理后台人员权限
        $admin->givePermissionTo(PermissionEnum::ADMINS_MANAGE);
        $response = $this->addAdmin();
        $response->assertStatus(200);
        $response->assertsee('libai');
    }

    private function addAdmin()
    {
        $data = [
            'name'     => 'libai',
            'password' => '123456',
            'phone'    => '13160675344',
            'sex'      => '1',
            'email'    => '812429293@qq.com'
        ];
        return $this->json('post', route('admin.v1.admins.store'), $data);
    }
}
