<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AuthMenu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->menu() as $v) {
            $v['created_at'] = now();
            $v['updated_at'] = now();
            DB::table('auth_menu')->insert($v);
        }
    }

    private function menu(): array
    {
        return  [
            //level 1 node
            ['id' => 1, 'p_id' => 0, 'title' => "系统管理",'auth_sig'=>'','icon' => "", 'sort' => 0, 'href' => '', 'status' => 1, 'is_deletable' => 0, 'rank' => 0],
            //reserved 50 id

            //level 2 node
            ["id" => 20, 'p_id' => 1, "title" => "系统配置",'auth_sig'=>'',"icon" => "layui-icon-set", 'sort' => 0, "href" => '', 'status' => 1, 'is_deletable' => 0, 'rank' => 1],
            ["id" => 21, 'p_id' => 1, "title" => "权限管理",'auth_sig'=>'',"icon" => "layui-icon-set", 'sort' => 0, "href" => '', 'status' => 1, 'is_deletable' => 0, 'rank' => 1],
            ["id" => 22, 'p_id' => 1, "title" => "后台人员",'auth_sig'=>'',"icon" => "layui-icon-set", 'sort' => 0, "href" => '', 'status' => 1, 'is_deletable' => 0, 'rank' => 1],


            //reserved 50 id

            //level 3 node
            ["id" => 50, 'p_id' => 20, "title" => "系统参数",'auth_sig'=>'vars', "icon" => "layui-icon-set", 'sort' => 0, "href" => 'key_management', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 51, 'p_id' => 20, "title" => "操作日志",'auth_sig'=>'adminLog',"icon" => "layui-icon-form", 'sort' => 0, "href" => 'f_admin_log', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 52, 'p_id' => 20, "title" => "系统文件",'auth_sig'=>'file',"icon" => "layui-icon-carousel", 'sort' => 0, "href" => 'f_system_file', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 53, 'p_id' => 21, "title" => "系统菜单",'auth_sig'=>'auth',"icon" => "layui-icon-layouts", 'sort' => 0, "href" => 'f_auth_menu', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 60, 'p_id' => 21, "title" => "访问权限",'auth_sig'=>'permission',"icon" => "layui-icon-vercode", 'sort' => 0, "href" => 'f_auth_permission', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 61, 'p_id' => 22, "title" => "管理账号",'auth_sig'=>'admin',"icon" => "layui-icon-username", 'sort' => 0, "href" => 'f_admin', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 62, 'p_id' => 22, "title" => "管理组合",'auth_sig'=>'role',"icon" => "layui-icon-username", 'sort' => 0, "href" => 'f_auth_role', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 63, 'p_id' => 20, "title" => "系统日志",'auth_sig'=>'sysLog',"icon" => "layui-icon-form", 'sort' => 0, "href" => 'f_system_log', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],
            ["id" => 64, 'p_id' => 20, "title" => "系统参数",'auth_sig'=>'sysConfig',"icon" => "layui-icon-set", 'sort' => 0, "href" => 'f_system_config', 'status' => 1, 'is_deletable' => 0, 'rank' => 2],



        ];
    }
}
