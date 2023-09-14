<?php

namespace App\Http\Logic\Foundation\Auth;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\Auth\AdminModel;
use App\Http\Models\Foundation\Auth\RoleModel;
use Illuminate\Support\Facades\Hash;

final class AdminLogic extends AbstractLogic
{

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function setModel()
    {
        return new AdminModel($this->data);
    }

    public function addLogic()
    {
        $data = $this->data;
        $res = $this->model->getOne(['account' => $data['account']], 'account');
        if (!empty($res)) {
            throw new \Exception('此账号已存在');
        }
        parent::addLogic();
        return true;
    }

    public function updateLogic()
    {
        $data = $this->data;
        $res = $this->model->getOne(['account' => $data['account']], ['id']);

        if (!empty($res) && $res['id'] != $data['id']) {
            throw new \Exception('此账号已存在');
        }
        parent::updateLogic();
        return true;
    }

    public function deleteLogic()
    {
        $res = self::getOne();
        if ($res['id'] == 1) {
            throw new \Exception('总管理员, 不可删除');
        }
        parent::deleteLogic();
        return true;
    }

    public function role()
    {
        return (new RoleModel())->where('status', 1)->get()->toArray();
    }

    public function setPassword()
    {
        $data = $this->data;
        $this->model->setData(['password' => Hash::make($data['password'])]);
        parent::updateLogic();
        return true;
    }

    public function setSelfPassword()
    {
        $data = $this->data;
        $this->model->setData(['id' => get_credential('admin_id')]);
        $this->model->setData(['password' => Hash::make($data['password'])]);
        parent::updateLogic();
        return true;
    }
}
