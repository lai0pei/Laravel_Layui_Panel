<?php

namespace App\Http\Logic\Foundation\Auth;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\Auth\AdminModel;
use Illuminate\Support\Facades\Hash;
use LogicException;

final class LoginLogic extends AbstractLogic
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

    public function login()
    {
        $account = $this->data['account'];
        $pass = $this->data['password'];

        $admin = self::getOne(['account' => $account]);
       
        if ($admin == null || !Hash::check($pass, $admin->password)) {
            throw new LogicException('登录失败');
        }

        if ($admin->status == 0) {
            throw new LogicException('账号屏蔽');
        }

        $admin->login_count += 1;
        $credential = $admin->toArray();
        $credential['admin_id'] = $admin->id;
        session(['admin_credential' => $credential]);
        $admin->save();
    }

    public function logout(){
        session()->flush();
    }
}
