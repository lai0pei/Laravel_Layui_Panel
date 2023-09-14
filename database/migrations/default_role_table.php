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
        Schema::create('role', function (Blueprint $table) {
            $table->id()->comment('编号')->autoIncrement();
            $table->string('role_name',100)->comment('角色名称')->nullable();
            $table->tinyInteger('status')->comment('1=正常，0=禁止')->default(1);
            $table->string('description',200)->nullable()->comment('描述')->nullable();
            $table->string('auth_id',200)->nullable()->comment('权限')->nullable();
            $table->timestamps();
        });

        $prefix = env('DB_PREFIX')."role";
        DB::statement("alter table $prefix comment '角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
};
