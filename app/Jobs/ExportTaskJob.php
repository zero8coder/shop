<?php

namespace App\Jobs;

use App\Models\ExportTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class ExportTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    public function __construct(ExportTask $task)
    {
        $this->task = $task;
    }

    public function handle()
    {
        // 不是等待状态的任务跳过
        if (!$this->task->isWait()) {
            Log::error('不是等待状态的任务', $this->task->toArray());
            return 0;
        }
        // 导出文件类
        $exportFileClass = ExportTask::$moduleMap[$this->task->module] ?? '';
        if (empty($exportFileClass)) {
            Log::error('导出文件类为空', $this->task->toArray());
            return 0;
        }

        // 处理命名空间
        if (!class_exists($exportFileClass)) {
            $exportFileClass = '\\App\\Export\\' . $exportFileClass;
        }
        if (!class_exists($exportFileClass)) {
            Log::error('不存在的导出类', [$this->task->toArray(), $exportFileClass]);
            return 0;
        }

        // 处理导出
        return (new $exportFileClass($this->task))->handle();

    }
}
