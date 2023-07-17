<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Admin;
use Tests\TestCase;

class LookAdminListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setPermissions([PermissionEnum::ADMINS_MANAGE]);
    }

    // 获取列表
    private function get_admin_list($postData = []): \Illuminate\Testing\TestResponse
    {
        return $this->authorizationJson('get', route('admin.v1.admins.index'), $postData);
    }

    // 看管理员列表
    public function test_look_admin_list()
    {
        $admin = Admin::factory()->create();
        $this->setPermissions([PermissionEnum::ADMINS_MANAGE]);
        $response = $this->get_admin_list();
        $response->assertStatus(200);
        $response->assertSee($admin->name);
    }

    // 看管理员列表设置分页
    public function test_look_admin_list_set_perPage()
    {
        $perPage = 3;
        $this->setPermissions([PermissionEnum::ADMINS_VIEW_ANY]);
        $response = $this->get_admin_list(['perPage' => $perPage]);
        $response->assertStatus(200);
        $response = $response->json();
        $this->assertEquals($perPage, $response['data']['perPage']);
    }

    // 根据姓名搜索管理员列表
    public function test_search_admin_list_by_name()
    {
        $admin = Admin::factory()->create(['name' => '李白']);
        $response = $this->get_admin_list(['name' => $admin->name]);
        $response->assertStatus(200);
        $response = $response->json();
        $this->assertCount(1, $response['data']['list']);
    }

    // 根据手机号搜索管理员列表
    public function test_search_admin_list_by_phone()
    {
        $admin = Admin::factory()->create(['phone' => '13160675343']);
        $response = $this->get_admin_list(['phone' => $admin->phone]);
        $response->assertStatus(200);
        $response = $response->json();
        $this->assertCount(1, $response['data']['list']);
    }

    // 根据性别搜索管理员列表
    public function test_search_admin_list_by_sex()
    {
        $admin = Admin::factory()->create(['sex' => Admin::SEX_WOMAN]);
        $response = $this->get_admin_list(['sex' => $admin->sex]);
        $response->assertStatus(200);
        $response = $response->json();
        $this->assertCount(1, $response['data']['list']);
    }
}
