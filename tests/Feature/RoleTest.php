<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Export\RoleExport;
use App\Export\Xlswriter;
use App\Jobs\ExportTaskJob;
use App\Models\ExportTask;
use App\Models\Role;
use Database\Factories\PermissionFactory;
use Database\Factories\RoleFactory;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RoleTest extends TestCase
{

    public function test_role_create()
    {
        $response = $this->authorizationJson('GET', route('admin.v1.roles.create'));
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

    public function test_role_store()
    {
        $permission1 = (new PermissionFactory())->create(['name' => 'pp3']);
        $permission2 = (new PermissionFactory())->create(['name' => 'pp4']);

        $data = [
            'name' => 'role1',
            'permissions' => [
                $permission1->id,
                $permission2->id
            ]
        ];

        $response = $this->authorizationJson('POST', route('admin.v1.roles.store'), $data);
        $response->assertStatus(200);
        $response->assertSee('role1');
        $this->assertDatabaseCount('role_has_permissions', 2);
    }


    public function test_role_edit()
    {
        $role = $this->create_role_has_permissions();
        $response = $this->authorizationJson('GET', route('admin.v1.roles.edit', ['role' => $role->id]));
        $response->assertStatus(200);
        $response->assertSee($role->name);
        $response->assertSee('permission1');
    }

    public function test_role_update()
    {
        $role = $this->create_role_has_permissions();

        $permission3 = (new PermissionFactory())->create(['name' => 'pp5']);
        $permission4 = (new PermissionFactory())->create(['name' => 'pp6']);
        $data = [
            'name' => 'kkk',
            'permissions' => [
                $permission3->id,
                $permission4->id,
            ]
        ];
        $response = $this->authorizationJson('PUT', route('admin.v1.roles.update', ['role' => $role->id]), $data);
        $response->assertStatus(200);
        $response->assertSee('kkk');
        $response->assertSee($permission3->name);
        $response->assertSee($permission4->name);
    }

    public function test_role_destroy()
    {
        $role = Role::factory()->create();
        $response = $this->authorizationJson('DELETE', route('admin.v1.roles.destroy', ['role' => $role->id]));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', $role->toArray());
    }

    public function test_role_index()
    {
        $role = Role::factory()->create();
        $this->setRoles([]); // 清空登录用户的角色
        $this->setPermissions([PermissionEnum::ROLES]);
        $response = $this->authorizationJson('GET', route('admin.v1.roles.index'));
        $response->assertStatus(200);
        $response->assertSee($role->name);
    }

    public function test_role_index_by_permission_view_any()
    {
        $role = Role::factory()->create();
        $this->setRoles([]); // 清空登录用户的角色
        $this->setPermissions([PermissionEnum::ROLES_VIEW_ANY]); //查看所有角色权限
        $response = $this->authorizationJson('GET', route('admin.v1.roles.index'));
        $response->assertStatus(200);
        $response->assertSee($role->name);
    }

    public function test_role_index_by_name()
    {
        $role = Role::factory()->create(['name' => 'kkk3']);
        Role::factory()->count(3)->create();
        $response = $this->authorizationJson('GET', route('admin.v1.roles.index'), ['name' => 'kkk3']);
        $response->assertStatus(200);
        $response->assertSee($role->name);
        $data = $response->json();
        $this->assertEquals(1, count($data['data']['list']));
    }

    public function test_role_export()
    {
        Queue::fake();
        $response = $this->authorizationJson('post', route('admin.v1.roles.exportTask'), []);
        $response->assertStatus(200);
        Queue::assertPushed(ExportTaskJob::class);
    }

    public function test_role_export_task_job_handle()
    {
        $role = $this->create_role_has_permissions();
        $this->signInAdmin();
        $name = '导出角色测试' . ExportTask::exportFilesSuffix();
        $task = ExportTask::addTask($name, 'role', ['name' => $role->name]);
        $job = new ExportTaskJob($task);
        $result = $job->handle();
        // 判断是否执行成功
        $this->assertEquals(1, $result);
        $filePath = base_path() . '/public/uploads/excel/' . $name . '.xls';
        // 判断文件是否存在
        $this->assertFileExists($filePath);

        $expectedData = [
            RoleExport::$header,
            [
                $role->id,
                $role->name,
                $role->permissions->pluck('name')->join('|'),
                $role->created_at->toDateTimeString(),
                $role->updated_at->toDateTimeString()
            ]
        ]; // 预期的数据

        $excel = Xlswriter::getExcel();
        // 实际数据
        $actualData = $excel->openFile($name . '.xls')
            ->openSheet()
            ->getSheetData();

        // 判断数据是否符合预期
        $this->assertEquals($expectedData, $actualData);
        // 删除生成的文件
        unlink($filePath);

    }

    private function create_role_has_permissions()
    {
        $role = Role::factory()->create();
        $permission1 = (new PermissionFactory())->create(['name' => 'permission1']);
        $permission2 = (new PermissionFactory())->create(['name' => 'permission2']);
        $role->syncPermissions([
            $permission1->id,
            $permission2->id,
        ]);
        $role->load('permissions');
        return $role;
    }



}
