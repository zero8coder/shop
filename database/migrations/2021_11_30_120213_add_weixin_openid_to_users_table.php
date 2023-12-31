<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeixinOpenidToUsersTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('weixin_openid')->unique()->nullable()->after('password');
            $table->string('weixin_unionid')->unique()->nullable()->after('weixin_openid');
            $table->string('password')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['weixin_openid', 'weixin_unionid']);
            $table->string('password')->nullable(false)->change();
        });
    }
}
