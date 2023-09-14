<?php

namespace App\Http\Controllers\Foundation\Index;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\Auth\AdminLogic;
use App\Http\Logic\Foundation\Auth\AuthMenuLogic;
use Exception;

final class IndexController extends AbstractController
{

    public function __construct()
    {   
        $this->title = '菜单';
        $this->viewPath = 'foundation.index';
        $this->routeGroup = 'index';
        parent::__construct();
    }

    public function setLogic()
    {
        return new AuthMenuLogic($this->request);
    }

    public function setRoute()
    {
        return [
            'getMenu' => self::f_route("getMenu"),
            'logout' => self::f_route("logout"),
            'clearCache' => self::f_route('clearCache'),
            'setPasswordView' => self::f_route('selfPassView'),
            'toSetSelfPassword' => self::f_route('toSelfPassword'),
        ];
    }

    public function setIndexData()
    {
        return [
            'username' => get_credential('username'),
            'title' => config('conf.name'),
        ];
    }

    public function menu()
    {
        return self::success($this->logic->getAuthMenu());
    }

    public function clearCache(){
        session()->forget(['auth_id','auth_menu','admin_menu']);
        return self::success([],'数据清理完成');
    }

    public function index()
    {   
        return view($this->index, [
            'data' => array_merge(['asset' => asset('')], $this->setIndexData() ?? []),
            'route' => array_merge(parent::default_route(), $this->setRoute()??[]),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    public function listControl()
    {   
        $fetch = $this->logic->list();
        $data['code'] = 0;
        $data['count'] = $fetch['count'];
        $data['data'] = $fetch['data'];
        return $this->json($data);
    }

    public function setSelfPassword()
    {
        try {
            (new AdminLogic($this->request))->setSelfPassword();
            return self::success([], '修改成功');
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }


    public function selfPasswordView()
    {
        return $this->abstract_view(self::f_view('password'));
    }

    public function blank()
    {
        return $this->abstract_view(self::f_view('blank'));
    }
}
