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
        Schema::create('auth_menu', function (Blueprint $table) {
            $table->id()->comment('编号')->autoIncrement();
            $table->unsignedMediumInteger('p_id')->comment('父级编号')->default(0);
            $table->string('title', 100)->comment('中文权限名称')->nullable();
            $table->string('auth_sig', 100)->comment('权限识别符')->nullable();
            $table->string('icon', 100)->comment('菜单图标')->nullable();
            $table->string('href', 100)->comment('页面地址')->nullable();
            $table->longText('json_data')->comment('其他内容')->nullable();
            $table->unsignedTinyInteger('rank')->comment('0 一级菜单, 1 二级菜单, 2 三级菜单')->default(0);
            $table->unsignedTinyInteger('status')->comment('状态')->default(0);
            $table->unsignedTinyInteger('is_deletable')->comment('是否可删除')->default(0);
            $table->unsignedMediumInteger('sort')->comment('排序')->default(0);
            $table->timestamps();
        });

        $prefix = env('DB_PREFIX')."auth_menu";
        DB::statement("alter table $prefix comment '后台菜单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_menu');
    }
};
