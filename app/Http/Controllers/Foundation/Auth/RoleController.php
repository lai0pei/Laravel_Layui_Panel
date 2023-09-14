<?php

namespace App\Http\Controllers\Foundation\Auth;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\Auth\RoleLogic;

final class RoleController extends AbstractController
{

    public function __construct()
    {
        $this->viewPath = 'foundation.role';
        $this->title = '组合';
        $this->routeGroup = 'role';
        $this->permission = 'role';
        parent::__construct();
    }

    public function setLogic()
    {
        return new RoleLogic($this->request);
    }

    public function setRoute()
    {
        return [
            'toSetAuth' => url($this->secure . '/' . $this->routeGroup . '/toSetAuth'),
            'authView' => url($this->secure . '/' . $this->routeGroup . '/authView'),
        ];
    }

    public function setAuthControl()
    {
        try {
            $this->validator(['id' => "required|min:0"]);
            $this->logic->setAuthLogic();
            return self::success([], '授权成功');
        } catch (\Exception $e) {
            return self::fail([], '授权失败');
        }
    }

    public function authView()
    {
        $auth_view = $this->viewPath . '.setauth';
        $auth_res = [];
        $auth_id = $this->logic->getOne();
        if (!empty($auth_id['auth_id'])) {
            $auth_res = explode(',', $auth_id['auth_id']);
        }

        return self::abstract_view($auth_view, ['auth' => $this->logic->getAuth(), 'cur_auth' => $auth_res, 'data' => $auth_id]);
    }
}
