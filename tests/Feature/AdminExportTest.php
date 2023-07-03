<?php

namespace Tests\Feature;

use App\Export\AdminExport;
use App\Export\Xlswriter;
use App\Jobs\ExportTaskJob;
use App\Models\Admin;
use App\Models\ExportTask;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminExportTest extends TestCase
{
    public function test_admin_export()
    {
        Queue::fake();
        $this->signInAdmin();
        $response = $this->json('post', route('admin.v1.admins.exportTask'), []);
        $response->assertStatus(200);
        Queue::assertPushed(ExportTaskJob::class);
    }

    public function test_export_task_job_handle()
    {
        $admin = Admin::factory()->create([
            'name'  => 'libai',
            'phone' => '13160674344',
            'email' => '812419916@qq.com',
            'sex'   => Admin::SEX_MAN
        ]);
        $this->signInAdmin($admin);
        $name = '导出管理员' . ExportTask::exportFilesSuffix();
        $task = ExportTask::addTask($name, 'admin', []);
        $job = new ExportTaskJob($task);
        $result = $job->handle();
        // 判断是否执行成功
        $this->assertEquals(1, $result);
        $filePath = base_path() . '/public/uploads/excel/' . $name . '.xls';
        // 判断文件是否存在
        $this->assertFileExists($filePath);
        $expectedData = [
            AdminExport::$header,
            [
                $admin->name,
                $admin->sex_name,
                $admin->phone,
                $admin->email
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

    public function readExcelData($filePath)
    {

    }
}
