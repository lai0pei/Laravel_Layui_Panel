<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DefaultAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin')->insert([
            'account' => 'admin',
            'password' => '$2y$10$yFlrO5kdL.3jDXyPqvX/4eZhsPFh8Z.axQsLXlxHKspANggddLjd2',
            'username' => '管理员',
            'reg_ip' => request()->ip(),
            'status' => 1,
            'role_id' =>1
        ]);
    }
}
