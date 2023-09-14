<?php
namespace App\Http\Controllers\Foundation\Auth;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\Auth\AuthPermissionLogic;
final class AuthPermissionController extends AbstractController{

    public function __construct()
    {
        $this->viewPath = 'foundation.permission';
        $this->routeGroup= 'permission';
        $this->title = '权限';
        $this->permission = 'permission';
        parent::__construct();
    }

    public function setLogic(){
        return new AuthPermissionLogic($this->request);
    }
    
    public function setUpdateViewData(){
        return [
            'menu' => $this->logic->rankTwoMenu(),
        ];
    }

    public function setIndexData(){
        return [
            'menu' => $this->logic->rankTwoMenu(),
        ];
    }

    public function setAddViewData(){
        return [
            'menu' => $this->logic->rankTwoMenu(),
        ];
    }

}