<?php

namespace App\Export;

use App\Models\Admin;
use App\Models\ExportTask;

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

        $param = $this->task->param;
        $name = $param['name'] ?? '';
        $phone = $param['phone'] ?? '';
        $sex = $param['sex'] ?? '';
        Admin::latest()
            ->when($name, function ($q) use ($name) {
                return $q->where('name', 'like', '%' . $name . '%');
            })
            ->when($phone, function ($q) use ($phone) {
                return $q->where('phone', 'like', $phone . '%');
            })
            ->when($sex, function ($q) use ($sex) {
                return $q->where('sex', $sex);
            })->cursor()->each(function ($admin, $index) use ($excel) {
                $excel->insertText($index + 1, 0, $admin->name);
                $excel->insertText($index + 1, 1, $admin->sex_name);
                $excel->insertText($index + 1, 2, $admin->phone);
                $excel->insertText($index + 1, 3, $admin->email);
            });
        $excel->output();
        return 1;
    }
}
