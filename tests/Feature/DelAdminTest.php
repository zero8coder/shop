<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use Tests\TestCase;

class DelAdminTest extends TestCase
{

    // 用管理权限删除后台人员
    public function test_del_admin()
    {
        $admin = Admin::factory()->create();
        $this->setPermissions([PermissionEnum::ADMINS_MANAGE]); // 设置管理权限
        $response = $this->authorizationJson('DELETE', route('admin.v1.admins.destroy', ['admin' => $admin->id]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }

    // 用删除权限删除后台人员
    public function test_del_admin_by_permission_delete()
    {
        $admin = Admin::factory()->create();
        $this->setPermissions([PermissionEnum::ADMINS_DELETE]); // 设置删除权限
        $response = $this->authorizationJson('DELETE', route('admin.v1.admins.destroy', ['admin' => $admin->id]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }


}
