<?php

namespace App\Http\Logic\Foundation\Auth;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\Auth\AdminModel;
use App\Http\Models\Foundation\Auth\AuthPermissionModel;
use App\Http\Models\Foundation\Auth\AuthMenuModel;
use App\Http\Models\Foundation\Auth\RoleModel;

final class RoleLogic extends AbstractLogic
{

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function setModel()
    {
        return new RoleModel($this->data);
    }

    public function addLogic()
    {
        $data = $this->data;
        $res = self::getOne(['role_name' => $data['role_name']], 'role_name');
        if (!empty($res)) {
            throw new \Exception('此组合名已存在');
        }
        $this->model->setData('is_deletable',1);
        parent::addLogic();
        return true;
    }

    public function updateLogic(){
        $data = $this->data;
        $res = self::getOne(['role_name' => $data['role_name']]);

        if (!empty($res) && $res['id'] != $data['id']) {
            throw new \Exception('此组合名已存在');
        }
        parent::updateLogic();
        (new AuthMenuLogic([]))->purgeMenuCache();
        return true;
    }

    public function deleteLogic(){
        $data = $this->data;
        $use = (new AdminModel())->getOne(['role_id'=>$data['id']],['id']);
        if (!empty($use)) {
            throw new \Exception('先删除 使用该管理组的 管理员');
        }
        parent::deleteLogic();
        (new AuthMenuLogic([]))->purgeMenuCache();
        return true;

    }

    public function setAuthLogic(){
        parent::updateLogic();
        (new AuthMenuLogic([]))->purgeMenuCache();
        return true;
    }

    public function getAuth(){
        $auth = idAsKey((new AuthMenuModel())->where(['rank'=>2,'status'=>1])->select(['title','id'])->get()->toArray());
        $per = (new AuthPermissionModel())->get();
       foreach($auth as &$v){
        $v['permission'] = $per->where('menu_id',$v['id'])->where('status',1)->toArray();
       }
        return $auth;
    }
}
