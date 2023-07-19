<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Menu;
use Tests\TestCase;

class MenuTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // 设置菜单管理权限
        $this->setPermissions([PermissionEnum::MENU_MANAGE]);
    }

    public function test_menu_index()
    {
        $menu = Menu::factory()->create();
        $response = $this->authorizationJson('get', route('admin.v1.menus.index'));
        $response->assertStatus(200);
        $response->assertSee($menu->name);
    }

    public function test_menu_index_by_permission_view_any()
    {
        $menu = Menu::factory()->create();
        $this->setPermissions([PermissionEnum::MENU_VIEW_ANY]);
        $response = $this->authorizationJson('get', route('admin.v1.menus.index'));
        $response->assertStatus(200);
        $response->assertSee($menu->name);
    }

    public function test_menu_store()
    {
        $menu = Menu::factory()->make();
        $response = $this->authorizationJson('post', route('admin.v1.menus.store'),  $menu->toArray());
        $response->assertStatus(200);
        $response->assertSee($menu->name);

    }

    public function test_menu_store_by_permission_create()
    {
        $menu = Menu::factory()->make();
        $this->setPermissions(PermissionEnum::MENU_CREATE);
        $response = $this->authorizationJson('post', route('admin.v1.menus.store'), $menu->toArray());
        $response->assertStatus(200);
        $response->assertSee($menu->name);

    }

    public function test_menu_update()
    {
        $menu = Menu::factory()->create();
        $response = $this->authorizationJson('patch', route('admin.v1.menus.update', ['menu' => $menu->id]), ['name' => 'test_menu_1']);
        $response->assertStatus(200);
        $response->assertSee('test_menu_1');
    }

    public function test_menu_update_by_permission_update()
    {
        $menu = Menu::factory()->create();
        $this->setPermissions(PermissionEnum::MENU_UPDATE);
        $response = $this->authorizationJson('patch', route('admin.v1.menus.update', ['menu' => $menu->id]), ['name' => 'test_menu_1']);
        $response->assertStatus(200);
        $response->assertSee('test_menu_1');
    }

    public function test_menu_delete()
    {
        $menu = Menu::factory()->create();
        $response = $this->authorizationJson('delete', route('admin.v1.menus.destroy', ['menu' => $menu->id]), ['name' => 'test_menu_1']);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('menus', $menu->toArray());
    }

    public function test_menu_delete_by_permission_delete()
    {
        $menu = Menu::factory()->create();
        $this->setPermissions([PermissionEnum::MENU_DELETE]);
        $response = $this->authorizationJson('delete', route('admin.v1.menus.destroy', ['menu' => $menu->id]), ['name' => 'test_menu_1']);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('menus', $menu->toArray());
    }
}
