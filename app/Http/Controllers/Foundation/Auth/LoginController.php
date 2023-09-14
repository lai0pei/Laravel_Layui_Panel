<?php

namespace App\Http\Controllers\Foundation\Auth;

use Exception;
use LogicException;
use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\Auth\LoginLogic;


final class LoginController extends AbstractController
{

    public function __construct()
    {
        $this->viewPath = 'foundation.login';
        $this->title = '登录页面';
        parent::__construct();
    }

    public function setLogic()
    {
        return new LoginLogic($this->request);
    }

    public function setRoute()
    {
        return [
            'login' => route('f_login_action'),
            'captcha' => route('f_captcha'),
        ];
    }

    public function index()
    {
        if (!empty(get_credential('admin_id'))) {
            return redirect()->route('f_home');
        }

        return view($this->index, [
            'data' => array_merge(['asset' => asset('')], $this->setIndexData() ?? []),
            'route' => array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
        ]);
    }
    public function login()
    {

        try {
            self::validator([
                'account' => 'required',
                'password' => 'required',
                'vercode' => 'required',
            ]);
  
            if (!captcha_check($this->request['vercode'])) {
                return  self::fail([], '验证码错误');
            }
            $this->logic->login();
            return self::success([], __('login.logging_in'));
        } catch (LogicException $e) {
            return self::fail([], $e->getMessage());
        } catch (Exception $e) {
            return self::fail([], __('login.logging_fail'));
        }
    }

    public function logout()
    {
        return self::success($this->logic->logout(), __('login.logging_out'));
    }


    public function captcha()
    {
        return captcha('login');
    }
}
