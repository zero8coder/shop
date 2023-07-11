<?php

namespace App\Export;

use App\Filters\RoleFilters;
use App\Models\ExportTask;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleExport
{
    private $task;

    public function __construct(ExportTask $task)
    {
        $this->task = $task;
    }

    public static $header = [
        'id',
        '角色',
        '权限',
        '创建时间',
        '修改时间'
    ];

    public function handle()
    {
        $excel = Xlswriter::getExcel();
        $excel = $excel->fileName($this->task->name . '.xls')->header(self::$header);

        $request = (new Request())->replace($this->task->param);
        $filters = new RoleFilters($request);

        $speed = 1000;
        $index = 0;
        Role::latest()
            ->with('permissions')
            ->filter($filters)
            ->chunkById($speed, function ($roles) use (&$index, $excel) {
                $roles->each(function ($role) use (&$index, $excel) {
                    $excel->insertText($index + 1, 0, $role->id);
                    $excel->insertText($index + 1, 1, $role->name);
                    $excel->insertText($index + 1, 2, $role->permissions->pluck('name')->join('|'));
                    $excel->insertText($index + 1, 3, $role->created_at->toDateTimeString());
                    $excel->insertText($index + 1, 4, $role->updated_at->toDateTimeString());
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
