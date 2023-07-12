<?php

namespace App\Export;

use App\Filters\PermissionFilters;
use App\Models\ExportTask;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionExport
{
    private $task;

    public function __construct(ExportTask $task)
    {
        $this->task = $task;
    }

    public static $header = [
        'id',
        '权限名称',
        '创建时间',
        '修改时间'
    ];

    public function handle()
    {
        $excel = Xlswriter::getExcel();
        $excel = $excel->fileName($this->task->name . '.xls')->header(self::$header);

        $request = (new Request())->replace($this->task->param);
        $filters = new PermissionFilters($request);

        $speed = 1000;
        $index = 0;
        Permission::latest()
            ->filter($filters)
            ->chunkById($speed, function ($permissions) use (&$index, $excel) {
                $permissions->each(function ($permission) use (&$index, $excel) {
                    $excel->insertText($index + 1, 0, $permission->id);
                    $excel->insertText($index + 1, 1, $permission->name);
                    $excel->insertText($index + 1, 2, $permission->created_at->toDateTimeString());
                    $excel->insertText($index + 1, 3, $permission->updated_at->toDateTimeString());
                    $index++;
                });
            });
        $excel->output();
        $this->task->url = Xlswriter::DOWNLOAD_PATH . '/' . $this->task->name . '.xls';
        $this->task->status = ExportTask::STATUS_SUCCESS;
        $this->task->save();
        return 1;
    }
}
