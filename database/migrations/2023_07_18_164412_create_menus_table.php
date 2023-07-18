<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pid')->nullable(false)->default(0)->comment('父级ID 0表示顶级');
            $table->string('name')->nullable(false)->default('')->comment('名称');
            $table->string('url')->nullable(false)->default('')->comment('链接');
            $table->integer('sort')->nullable(false)->default(0)->comment('排序 小的排前面');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
