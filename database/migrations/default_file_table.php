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
        Schema::create('file', function (Blueprint $table) {
            $table->id()->comment('附件编号')->autoIncrement();
            $table->string('name',200)->comment('原文件名');
            $table->string('path',500)->comment('路径');
            $table->bigInteger('raw_size')->comment('原文件大小')->nullable();
            $table->tinyInteger('side')->comment('1=前台，0=服务器')->default(1);
            $table->string('m_type',100)->comment('主文件类型')->nullable();
            $table->string('s_type',100)->comment('二级文件类型')->nullable();
            $table->string('ext',100)->comment('文件后缀')->nullable();
            $table->string('size', 100)->comment('文件大小')->nullable();
            $table->longText('json_data')->comment('其他内容')->nullable();
            $table->timestamps();
        });
        
        $prefix = env('DB_PREFIX')."file";
        DB::statement("alter table $prefix comment '系统附件表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file');
    }
};
