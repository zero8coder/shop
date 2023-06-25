<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class AddAdminTest extends TestCase
{
    // 未授权
    public function test_unauthorized_add_admin()
    {
        $response = $this->json('post', route('admin.v1.admins.store'), []);
        $response->assertStatus(401);
    }

    // 添加管理员
    public function test_add_admin()
    {
        $this->signInAdmin();
        $data = [
            'name'     => 'libai',
            'password' => '123456',
            'phone'    => '13160675344',
            'sex'      => '1',
        ];
        $response = $this->json('post', route('admin.v1.admins.store'), $data);
        $response->assertStatus(200);
        $response->assertsee($data['name']);
    }

}
