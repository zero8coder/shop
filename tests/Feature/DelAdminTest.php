<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class DelAdminTest extends TestCase
{
    // 未授权删除
    public function test_unauthorized_del_admin()
    {
        $response = $this->json('delete', route('admin.v1.admins.destroy', ['admin' => 1]), []);
        $response->assertStatus(401);
    }

    // 删除管理员
    public function test_del_admin()
    {
        $this->signInAdmin();
        $admin = Admin::factory()->create();
        $response = $this->json('DELETE', route('admin.v1.admins.destroy', ['admin' => $admin->id]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
    }
}
