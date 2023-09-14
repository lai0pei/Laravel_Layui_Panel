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
        Schema::create('sys_log', function (Blueprint $table) {
            $table->id()->comment('编号')->autoIncrement();
            $table->string('title', 200)->comment('日志操作')->nullable()->default('');
            $table->string('ip', 200)->comment('ip 地址')->nullable()->default('');
            $table->tinyInteger('type')->comment('报错类型 0-系统,1-非系统')->default(0);
            $table->longText('json_data')->comment('其他内容')->nullable();
            $table->longText('content')->comment('日志内容')->nullable();
            $table->timestamps();
        });

        $prefix = env('DB_PREFIX') . "sys_log";
        DB::statement("alter table $prefix comment '系统日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_log');
    }
};
