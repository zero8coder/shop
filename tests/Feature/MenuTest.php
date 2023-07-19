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
        $response = $this->authorizationJson('post', route('admin.v1.menus.store', $menu->toArray()));
        $response->assertStatus(200);
        $response->assertSee($menu->name);

    }
}
