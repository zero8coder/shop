<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use Tests\TestCase;

class UpdateAdminTest extends TestCase
{
    // 修改后台人员信息
    public function test_update_admin()
    {
        $admin = Admin::factory()->create(['phone' => '13160675341','email' => '812419391@qq.com', 'sex' => Admin::SEX_MAN]);
        $admin->phone = '13160675342';
        $admin->email = '812419393@qq.com';
        $admin->sex = Admin::SEX_WOMAN;
        $this->authorizationJson('patch', route('admin.v1.admins.update', ['admin' => $admin->id]), $admin->toArray());
        tap($admin->fresh(), function ($admin) {
            $this->assertEquals('13160675342', $admin->phone);
            $this->assertEquals('812419393@qq.com', $admin->email);
            $this->assertEquals(Admin::SEX_WOMAN, $admin->sex);
        });
    }

    // 测试修改权限
    public function test_update_admin_update_permission()
    {
        $loginAdmin = Admin::factory()->create();
        $this->signInAdmin($loginAdmin);
        $admin = Admin::factory()->create(['phone' => '13160675341','email' => '812419391@qq.com', 'sex' => Admin::SEX_MAN]);
        $admin->phone = '13160675342';
        $admin->email = '812419393@qq.com';
        $admin->sex = Admin::SEX_WOMAN;
        $response = $this->json('patch', route('admin.v1.admins.update', ['admin' => $admin->id]), $admin->toArray());
        // 没有权限
        $response->assertStatus(403);
        $loginAdmin->givePermissionTo(PermissionEnum::ADMINS_UPDATE);
        $response2 = $this->json('patch', route('admin.v1.admins.update', ['admin' => $admin->id]), $admin->toArray());
        $response2->assertStatus(200);
    }

    // 测试修改权限
    public function test_update_admin_magnet_permission()
    {
        $loginAdmin = Admin::factory()->create();
        $this->signInAdmin($loginAdmin);
        $admin = Admin::factory()->create(['phone' => '13160675341','email' => '812419391@qq.com', 'sex' => Admin::SEX_MAN]);
        $admin->phone = '13160675342';
        $admin->email = '812419393@qq.com';
        $admin->sex = Admin::SEX_WOMAN;
        $response = $this->json('patch', route('admin.v1.admins.update', ['admin' => $admin->id]), $admin->toArray());
        // 没有权限
        $response->assertStatus(403);
        $loginAdmin->givePermissionTo(PermissionEnum::ADMINS);
        $response2 = $this->json('patch', route('admin.v1.admins.update', ['admin' => $admin->id]), $admin->toArray());
        $response2->assertStatus(200);
    }
}
