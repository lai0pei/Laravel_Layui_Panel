<?php

namespace App\Http\Controllers\Foundation\Auth;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\Auth\AdminLogic;
use Exception;

final class AdminController extends AbstractController
{

    public function __construct()
    {
        $this->viewPath = 'foundation.admin';
        $this->title = '账号';
        $this->routeGroup = 'admin';
        $this->permission = 'admin';
        parent::__construct();
    }

    public function setLogic()
    {
        return new AdminLogic($this->request);
    }


    public function setUpdateViewData()
    {
        return [
            'role' => $this->logic->role(),
        ];
    }


    public function setAddViewData()
    {
        return [
            'role' => $this->logic->role(),
        ];
    }

    public function setRoute()
    {
        return [
            "toAllPassword" => self::f_route('toSetPassword'),
            'allPasswordView' => self::f_route('allPasswordView'),
        ];
    }

    public function allPasswordView()
    {
        return $this->abstract_view(self::f_view('password'), ['data' => $this->logic->getOne([], 'id')]);
    }

    public function setSelfPassword()
    {
        try {
            $this->logic->setSelfPassword();
            return self::success([], '修改成功');
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }


    public function setPassword(){
        try {
            $this->logic->setPassword();
            return self::success([], '修改成功');
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }

   
}
