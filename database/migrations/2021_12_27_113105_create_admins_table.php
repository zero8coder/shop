<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('姓名');
            $table->string('avatar')->nullable('false')->default('')->comment('头像');
            $table->string('phone')->unique()
                ->nullable(false)
                ->default('')
                ->comment('手机号');
            $table->string('email')->unique()
                ->nullable(false)
                ->default('')
                ->comment('邮箱');
            $table->string('email_verified_at')->nullable()->comment('邮箱确认');
            $table->string('password')->comment('密码');
            $table->tinyInteger('sex')->comment('性别');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
