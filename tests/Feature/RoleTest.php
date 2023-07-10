<?php

namespace Tests\Feature;

use Database\Factories\PermissionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function test_unauthorized_role_create()
    {
        $response = $this->json('GET', route('admin.v1.roles.create'));
        $response->assertStatus(401);
    }

    public function test_role_create()
    {
        $this->signInAdmin();
        $response = $this->json('GET', route('admin.v1.roles.create'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'message',
            'status',
            'data' => [
                'permissions'
            ]
        ]);
    }

    public function test_unauthorized_role_store()
    {
        $response = $this->json('POST', route('admin.v1.roles.store'));
        $response->assertStatus(401);
    }

    public function test_role_store()
    {
        $this->signInAdmin();
        $permission1 = (new PermissionFactory())->create(['name' => 'pp3']);
        $permission2 = (new PermissionFactory())->create(['name' => 'pp4']);

        $data = [
            'name' => 'role1',
            'permissions' => [
                $permission1->id,
                $permission2->id
            ]
        ];

        $response = $this->json('POST', route('admin.v1.roles.store'), $data);
        $response->assertStatus(200);
        $response->assertSee('role1');
        $this->assertDatabaseCount('role_has_permissions', 2);
    }

}
