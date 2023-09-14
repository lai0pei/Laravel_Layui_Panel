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
        Schema::create('auth_sub_menu', function (Blueprint $table) {
            $table->id()->comment('编号')->autoIncrement();
            $table->string('title', 100)->comment('权限名称')->nullable();
            $table->string('auth_sub_sig', 100)->comment('识别符')->nullable();
            $table->unsignedTinyInteger('status')->comment('0 未添加 1 已添加')->nullable()->default(0);
            $table->unsignedTinyInteger('is_deletable')->comment('0 不可删除 1 可删除')->nullable()->default(0);
            $table->unsignedMediumInteger('menu_id')->comment('对应权限编号')->nullable();
            $table->timestamps();
        });

        $prefix = env('DB_PREFIX')."auth_sub_menu";
        DB::statement("alter table $prefix comment '后台菜单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_sub_menu');
    }
};
