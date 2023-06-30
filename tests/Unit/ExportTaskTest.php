<?php

namespace Tests\Unit;


use App\Models\ExportTask;
use Tests\TestCase;

class ExportTaskTest extends TestCase
{
    public function test_add_task()
    {
        $this->signInAdmin();
        ExportTask::addTask('导出管理员', 'admin', ['id' => 1]);
        $this->assertDatabaseCount('export_tasks', 1);
    }
}
