<?php

namespace Tests\Feature;

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
        $role = $this->create_role_has_permissions();
        $response = $this->json('GET', route('admin.v1.roles.edit', ['role' => $role->id]));
        $response->assertStatus(200);
        $response->assertSee($role->name);
        $response->assertSee('permission1');
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

    public function test_role_export()
    {
        Queue::fake();
        $this->signInAdmin();
        $response = $this->json('post', route('admin.v1.roles.exportTask'), []);
        $response->assertStatus(200);
        Queue::assertPushed(ExportTaskJob::class);
    }

    public function test_role_export_task_job_handle()
    {
        $role = $this->create_role_has_permissions();
        $this->signInAdmin();
        $name = '导出角色测试' . ExportTask::exportFilesSuffix();
        $task = ExportTask::addTask($name, 'role', []);
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
