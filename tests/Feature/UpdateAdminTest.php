<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use Tests\TestCase;

class UpdateAdminTest extends TestCase
{
    public $admin;
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create(['phone' => '13160675341','email' => '812419391@qq.com', 'sex' => Admin::SEX_MAN]);
    }

    // 修改后台人员信息
    public function test_update_admin()
    {
        $this->admin->phone = '13160675349';
        $this->admin->email = '812419393@qq.com';
        $this->admin->sex = Admin::SEX_WOMAN;
        // 设置管理权限
        $this->setPermissions([PermissionEnum::ADMINS_MANAGE]);
        $response = $this->authorizationJson('patch', route('admin.v1.admins.update', ['admin' => $this->admin->id]), $this->admin->toArray());
        $response->assertStatus(200);
        tap($this->admin->fresh(), function () {
            $this->assertEquals('13160675349', $this->admin->phone);
            $this->assertEquals('812419393@qq.com', $this->admin->email);
            $this->assertEquals(Admin::SEX_WOMAN, $this->admin->sex);
        });
    }

    // 检测修改权限
    public function test_update_admin_permission_update()
    {
        $this->admin->phone = '13160675347';
        $this->admin->email = '812419393@qq.com';
        $this->admin->sex = Admin::SEX_WOMAN;
        $this->setPermissions([PermissionEnum::ADMINS_UPDATE]);

        $response = $this->authorizationJson('patch', route('admin.v1.admins.update', ['admin' => $this->admin->id]), $this->admin->toArray());
        $response->assertStatus(200);
    }



}
