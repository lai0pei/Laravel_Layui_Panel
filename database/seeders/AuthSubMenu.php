<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Http\Logic\Foundation\Auth\AuthMenuLogic;

class AuthSubMenu extends Seeder
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
            DB::table('auth_sub_menu')->insert($v);
        }
    }

    public function menu()
    {
        return [
            ['menu_id' => 61, 'auth_sub_sig'=> 'admin_index','title' => '账号模块','status'=>1],
            ['menu_id' => 61, 'auth_sub_sig'=> 'admin_add','title' => '账号添加','status'=>1],
            ['menu_id' => 61, 'auth_sub_sig'=> 'admin_edit','title' => '账号编辑','status'=>1],
            ['menu_id' => 61, 'auth_sub_sig'=> 'admin_delete','title' => '账号删除','status'=>1],
            ['menu_id' => 61, 'auth_sub_sig'=> 'admin_view','title' => '账号查看','status'=>1],
            ['menu_id' => 61, 'auth_sub_sig'=> 'admin_setPassword','title' => '密码修改','status'=>1],

            ['menu_id' => 53, 'auth_sub_sig'=> 'auth_index','title' => '菜单模块','status'=>1],
            ['menu_id' => 53, 'auth_sub_sig'=> 'auth_add','title' => '菜单添加','status'=>1],
            ['menu_id' => 53, 'auth_sub_sig'=> 'auth_edit','title' => '菜单编辑','status'=>1],
            ['menu_id' => 53, 'auth_sub_sig'=> 'auth_delete','title' => '菜单删除','status'=>1],
            ['menu_id' => 53, 'auth_sub_sig'=> 'auth_view','title' => '菜单查看','status'=>1],

            ['menu_id' => 60, 'auth_sub_sig'=> 'permission_index','title' => '权限模块','status'=>1],
            ['menu_id' => 60, 'auth_sub_sig'=> 'permission_add','title' => '权限添加','status'=>1],
            ['menu_id' => 60, 'auth_sub_sig'=> 'permission_edit','title' => '权限编辑','status'=>1],
            ['menu_id' => 60, 'auth_sub_sig'=> 'permission_delete','title' => '权限删除','status'=>1],
            ['menu_id' => 60, 'auth_sub_sig'=> 'permission_view','title' => '权限查看','status'=>1],

            ['menu_id' => 62, 'auth_sub_sig'=> 'role_index','title' => '管理组模块','status'=>1],
            ['menu_id' => 62, 'auth_sub_sig'=> 'role_add','title' => '管理组添加','status'=>1],
            ['menu_id' => 62, 'auth_sub_sig'=> 'role_edit','title' => '管理组编辑','status'=>1],
            ['menu_id' => 62, 'auth_sub_sig'=> 'role_delete','title' => '管理组删除','status'=>1],
            ['menu_id' => 62, 'auth_sub_sig'=> 'role_view','title' => '管理组查看','status'=>1],
            ['menu_id' => 62, 'auth_sub_sig'=> 'role_setAuth','title' => '管理组查看','status'=>1],

            ['menu_id' => 52, 'auth_sub_sig'=> 'file_index','title' => '文件模块','status'=>1],
            ['menu_id' => 52, 'auth_sub_sig'=> 'file_add','title' => '文件添加','status'=>1],
            ['menu_id' => 52, 'auth_sub_sig'=> 'file_edit','title' => '文件编辑','status'=>1],
            ['menu_id' => 52, 'auth_sub_sig'=> 'file_delete','title' => '文件删除','status'=>1],
            ['menu_id' => 52, 'auth_sub_sig'=> 'file_view','title' => '文件查看','status'=>1],
            ['menu_id' => 52, 'auth_sub_sig'=> 'file_bulkDelete','title' => '文件批量删除','status'=>1],

            ['menu_id' => 51, 'auth_sub_sig'=> 'adminLog_index','title' => '管理员日志模块','status'=>1],
            ['menu_id' => 51, 'auth_sub_sig'=> 'adminLog_delete','title' => '管理员日志删除','status'=>1],
            ['menu_id' => 51, 'auth_sub_sig'=> 'adminLog_view','title' => '管理员日志查看','status'=>1],
            ['menu_id' => 51, 'auth_sub_sig'=> 'adminLog_bulkDelete','title' => '管理员日志批量删除','status'=>1],

            ['menu_id' => 63, 'auth_sub_sig'=> 'sysLog_index','title' => '系统日志模块','status'=>1],
            ['menu_id' => 63, 'auth_sub_sig'=> 'sysLog_delete','title' => '系统日志删除','status'=>1],
            ['menu_id' => 63, 'auth_sub_sig'=> 'sysLog_view','title' => '系统日志查看','status'=>1],
            ['menu_id' => 63, 'auth_sub_sig'=> 'sysLog_bulkDelete','title' => '系统日志批量删除','status'=>1],

            
            ['menu_id' => 64, 'auth_sub_sig'=> 'sysConfig_edit','title' => '系统配置更新','status'=>1],
            ['menu_id' => 64, 'auth_sub_sig'=> 'sysConfig_index','title' => '系统配置模块','status'=>1],
        ];
    }
}
