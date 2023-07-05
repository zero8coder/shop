<?php

namespace App\Export;

use App\Filters\AdminFilters;
use App\Models\Admin;
use App\Models\ExportTask;
use Illuminate\Http\Request;

class AdminExport
{
    private $task;

    public function __construct(ExportTask $task)
    {
        $this->task = $task;
    }

    public static $header = [
        '姓名',
        '性别',
        '手机号',
        '邮箱'
    ];

    public function handle()
    {
        $excel = Xlswriter::getExcel();
        $excel = $excel->fileName($this->task->name . '.xls')->header(self::$header);

        $request = (new Request())->replace($this->task->param);
        $filters = new AdminFilters($request);

        $speed = 1000;
        Admin::latest()
            ->filter($filters)
            ->chunkById($speed, function ($admins) use (&$index, $excel) {
                $admins->each(function ($admin) use (&$index, $excel) {
                    $excel->insertText($index + 1, 0, $admin->name);
                    $excel->insertText($index + 1, 1, $admin->sex_name);
                    $excel->insertText($index + 1, 2, $admin->phone);
                    $excel->insertText($index + 1, 3, $admin->email);
                });
            });
        $excel->output();
        $this->task->url = Xlswriter::DOWNLOAD_PATH . '/' . $this->task->name . '.xls';
        $this->task->status = ExportTask::STATUS_SUCCESS;
        $this->task->save();
        return 1;
    }
}
