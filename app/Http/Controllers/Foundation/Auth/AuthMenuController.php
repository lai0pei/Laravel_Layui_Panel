<?php
namespace App\Http\Controllers\Foundation\Auth;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\Auth\AuthMenuLogic;

final class AuthMenuController extends AbstractController{

    public function __construct()
    {
        $this->viewPath = 'foundation.menu';
        $this->routeGroup= 'auth';
        $this->permission = 'auth';
        $this->title = '菜单';
        parent::__construct();
    }

    public function setLogic(){
        return new AuthMenuLogic($this->request);
    }

 
    public function setUpdateViewData(){
        return [
            'menu' => $this->logic->rankTwoMenuUpdate(),
            'sub_menu' => $this->logic->auth_sub_menu(),
            'data' => $this->logic->getOne(),
        ];
    }


    public function setAddViewData(){
        return [
            'menu' => $this->logic->rankTwoMenu(),
        ];
    }

}