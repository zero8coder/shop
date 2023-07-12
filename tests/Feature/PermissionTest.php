<?php

namespace Tests\Feature;

use App\Models\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_unauthorized_add_permission()
    {
        $response = $this->json('post', route("admin.v1.permissions.store"));
        $response->assertStatus(401);
    }

    public function test_add_permission()
    {
        $this->signInAdmin();
        $permission = Permission::factory()->make();
        $response = $this->json('post', route("admin.v1.permissions.store"), $permission->toArray());
        $response->assertStatus(201);
        $response = $response->json();
        $this->assertEquals($permission['name'], $response['data']['name']);
    }

    public function test_unauthorized_permission_index()
    {
        $response = $this->json('get', route("admin.v1.permissions.index"));
        $response->assertStatus(401);
    }

    public function test_permission_index()
    {
        $this->signInAdmin();
        $permission = Permission::factory()->create();
        $response = $this->json('get', route("admin.v1.permissions.index"));
        $response->assertStatus(200);
        $response->assertSee($permission->name);
    }


    public function test_permission_index_set_perPage()
    {
        Permission::factory()->count(10)->create();
        $this->signInAdmin();
        $perPage = 3;
        $response = $this->json('get', route("admin.v1.permissions.index"), ['perPage' => 3]);
        $response->assertStatus(200);
        $response = $response->json();
        $this->assertEquals($perPage, $response['data']['perPage']);
    }

    public function test_permission_index_by_name()
    {
        Permission::factory()->count(10)->create();
        Permission::factory()->create(['name' => 'pp1']);

        $this->signInAdmin();
        $response = $this->json('get', route("admin.v1.permissions.index"), ['name' => 'pp1']);
        $response->assertStatus(200);
        $response = $response->json();
        $this->assertCount(1, $response['data']['list']);
    }

    public function test_unauthorized_permission_update()
    {
        $response = $this->json('PUT', route("admin.v1.permissions.update", ['permission' => 1]));
        $response->assertStatus(401);
    }

    public function test_permission_update()
    {
        $this->signInAdmin();
        $permission = Permission::factory()->create(['name' => 'pp1']);
        $response = $this->json('PUT', route("admin.v1.permissions.update", ['permission' => $permission->id]), ['name' => '李白']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'code',
            'message',
            'status',
            'data'
        ]);
    }

    public function test_unauthorized_permission_destroy()
    {
        $response = $this->json('DELETE', route("admin.v1.permissions.destroy", ['permission' => 1]));
        $response->assertStatus(401);
    }

    public function test_permission_destroy()
    {
        $this->signInAdmin();
        $permission = Permission::factory()->create(['name' => 'pp1']);
        $this->assertDatabaseCount('permissions', 1);
        $response = $this->json('DELETE', route("admin.v1.permissions.destroy", ['permission' => $permission->id]));
        $response->assertStatus(200);
        $this->assertDatabaseCount('permissions', 0);
        $response->assertJsonStructure([
            'code',
            'message',
            'status',
            'data'
        ]);
    }

    public function test_unauthorized_permission_edit()
    {
        $response = $this->json('GET', route("admin.v1.permissions.edit", ['permission' => 1]));
        $response->assertStatus(401);
    }

    public function test_permission_edit()
    {
        $this->signInAdmin();
        $permission = Permission::factory()->create(['name' => 'pp1']);
        $response = $this->json('GET', route("admin.v1.permissions.edit", ['permission' =>  $permission->id]));
        $response->assertStatus(200);
        $response->assertSee($permission->name);

    }

}
