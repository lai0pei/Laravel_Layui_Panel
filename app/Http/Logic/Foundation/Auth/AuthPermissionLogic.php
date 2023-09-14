<?php

namespace App\Http\Logic\Foundation\Auth;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\Auth\AuthPermissionModel;
use App\Http\Models\Foundation\Auth\AuthMenuModel;

final class AuthPermissionLogic extends AbstractLogic
{
    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function setModel()
    {
        return new AuthPermissionModel($this->data);
    }

    public function rankTwoMenu()
    {
        return (new AuthMenuModel())->where('rank', 2)->get();
    }

    public function addLogic()
    {
        $data = $this->data;
        $res = self::getOne(['auth_sub_sig' => $data['auth_sub_sig'], 'menu_id' => $data['menu_id']]);
        if (!empty($res)) {
            throw new \Exception('此权限存在');
        }
        $this->model->setData(['is_deletable'=>1]);
        return parent::addLogic();
    }

    public function updateLogic()
    {
        $data = $this->data;
        
        $res1 = self::getOne(['auth_sub_sig' => $data['auth_sub_sig'], 'menu_id' => $data['menu_id']]);
        if (!empty($res1) && $data['id'] != $res1['id']) {
            throw new \Exception('此权限存在');
        }
        parent::updateLogic();
        (new AuthMenuLogic([]))->purgeMenuCache();
        return true;
    }

    public function deleteBulkLogic(){
        return false;
    }

    public function deleteLogic(){
        $res = self::getOne();
        if($res['is_deletable'] == 0){
            throw new \Exception('默认权限不可删除');
        }
        parent::deleteLogic();
        (new AuthMenuLogic([]))->purgeMenuCache();
        return true;
    }
}
