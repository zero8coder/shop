<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportTasksTable extends Migration
{
    public function up()
    {
        Schema::create('export_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable('false')->default('')->comment('文件名称');
            $table->string('module', 16)->nullable('false')->default('')->comment('模块名称');
            $table->json('param')->comment('参数');
            $table->string('url')->nullable('false')->default('')->comment('下载路径');
            $table->tinyInteger('status')->nullable('false')->default(1)->comment('状态 1待处理 2成功 3失败');
            $table->bigInteger('admin_id')->nullable('false')->default(0)->comment('申请人id');
            $table->string('admin_name')->nullable('false')->default('')->comment('申请人名称');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_tasks');
    }
}
