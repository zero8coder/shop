<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;

class ShowMeAdminTest extends TestCase
{
    // 未授权不能查看自己的详情
    public function test_not_authorization_can_not_see_my_detail()
    {
        $response = $this->json('get', route('admin.v1.admins.me'));
        $response->assertStatus(401);
    }

    public function test_see_my_detail()
    {
        $admin = Admin::factory()->create(['name' => 'xiaoli']);
        $this->signInAdmin($admin);
        $response = $this->json('get', route('admin.v1.admins.me'));
        $response->assertStatus(200);
        $responseAdmin = $response->json();
        $this->assertEquals('xiaoli', $responseAdmin['data']['name']);
    }


}
