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
        Schema::create('sys_config', function (Blueprint $table) {
            $table->id()->comment('编号')->autoIncrement();
            $table->string('config_name',200)->comment('配置名称')->nullable();
            $table->string('config_sig',100)->comment('配置识别')->nullable();
            $table->longText('content')->comment('内容')->nullable();
            $table->timestamps();
        });

        $prefix = env('DB_PREFIX')."sys_config";
        DB::statement("alter table $prefix comment '系统参数表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_config');
    }
};
