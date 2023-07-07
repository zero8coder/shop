<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowAdminTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    // 未授权不能查看
    public function test_not_authorization_can_not_see_admin_detail()
    {
        $response = $this->json('get', route('admin.v1.admins.show', ['admin' => $this->admin->id]));
        $response->assertStatus(401);
    }

    public function test_show_admin()
    {
        $this->signInAdmin($this->admin);
        $response = $this->json('get', route('admin.v1.admins.show', ['admin' => $this->admin->id]));
        $response->assertStatus(200);
        $responseAdmin = $response->json();
        $this->assertEquals($this->admin->name, $responseAdmin['data']['name']);
    }
}
