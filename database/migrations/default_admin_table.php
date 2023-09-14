<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id()->comment('管理员编号')->autoIncrement();
            $table->string('account',100)->comment('管理员');
            $table->string('password',200)->comment('密码');
            $table->string('username',100)->comment('昵称');
            $table->string('reg_ip', 100)->nullable()->comment('注册Ip')->nullable();
            $table->string('description', 100)->nullable()->comment('备注')->nullable();
            $table->longText('json_data')->comment('其他内容')->nullable();
            $table->tinyInteger('status')->comment('1=正常，0=禁止')->default(1);
            $table->unsignedMediumInteger('role_id')->comment('角色编号');
            $table->unsignedBigInteger('login_count')->nullable()->comment('登录次数')->default(0);
            $table->timestamps();
        });
        
        $prefix = env('DB_PREFIX')."admin";
        DB::statement("alter table $prefix comment '后台管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
};
