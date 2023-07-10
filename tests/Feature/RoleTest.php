<?php

namespace Tests\Feature;

use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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

    public function test_unauthorized_role_edit()
    {
        $role = (new RoleFactory())->create();
        $response = $this->json('GET', route('admin.v1.roles.edit', ['role' => $role->id]));
        $response->assertStatus(401);
    }

    public function test_role_edit()
    {
        $this->signInAdmin();
        $role = (new RoleFactory())->create();
        $permission1 = (new PermissionFactory())->create(['name' => 'pp3']);
        $permission2 = (new PermissionFactory())->create(['name' => 'pp4']);
        $role->syncPermissions([
            $permission1->id,
            $permission2->id,
        ]);
        $response = $this->json('GET', route('admin.v1.roles.edit', ['role' => $role->id]));
        $response->assertStatus(200);
        $response->assertSee($role->name);
        $response->assertSee($permission1->name);
    }

    public function test_unauthorized_role_update()
    {
        $role = (new RoleFactory())->create();
        $response = $this->json('PUT', route('admin.v1.roles.update', ['role' => $role->id]));
        $response->assertStatus(401);
    }

    public function test_role_update()
    {
        $this->signInAdmin();
        $role = (new RoleFactory())->create();
        $permission1 = (new PermissionFactory())->create(['name' => 'pp3']);
        $permission2 = (new PermissionFactory())->create(['name' => 'pp4']);
        $permission3 = (new PermissionFactory())->create(['name' => 'pp5']);
        $permission4 = (new PermissionFactory())->create(['name' => 'pp6']);
        $role->syncPermissions([
            $permission1->id,
            $permission2->id,
        ]);

        $data = [
            'name' => 'kkk',
            'permissions' => [
                $permission3->id,
                $permission4->id,
            ]
        ];
        $response = $this->json('PUT', route('admin.v1.roles.update', ['role' => $role->id]), $data);
        $response->assertStatus(200);
        $response->assertSee('kkk');
        $response->assertSee($permission3->name);
        $response->assertSee($permission4->name);
    }

    public function test_unauthorized_role_destroy()
    {
        $role = (new RoleFactory())->create();
        $response = $this->json('DELETE', route('admin.v1.roles.destroy', ['role' => $role->id]));
        $response->assertStatus(401);
    }

    public function test_role_destroy()
    {
        $this->signInAdmin();
        $role = (new RoleFactory())->create();
        $response = $this->json('DELETE', route('admin.v1.roles.destroy', ['role' => $role->id]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', $role->toArray());
    }

    public function test_unauthorized_role_index()
    {
        $response = $this->json('GET', route('admin.v1.roles.index'));
        $response->assertStatus(401);
    }

    public function test_role_index()
    {
        $this->signInAdmin();
        $role = (new RoleFactory())->create();
        $response = $this->json('GET', route('admin.v1.roles.index'));
        $response->assertStatus(200);
        $response->assertSee($role->name);
    }

    public function test_role_index_by_name()
    {
        $this->signInAdmin();
        $role = (new RoleFactory())->create(['name' => 'kkk3']);
        (new RoleFactory())->count(3)->create();
        $response = $this->json('GET', route('admin.v1.roles.index'), ['name' => 'kkk3']);
        $response->assertStatus(200);
        $response->assertSee($role->name);
        $data = $response->json();
        $this->assertEquals(1, count($data['data']['list']));
    }
}
