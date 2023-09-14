<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DefaultSystemConfig extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       foreach($this->defaultConfig() as $v){
        DB::table('sys_config')->insert($v);
       }
    }

    public function defaultConfig(){
        return [[
            'config_name' => '网站配置',
            'config_sig' => 'website',
            'content' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]];
    }

}
