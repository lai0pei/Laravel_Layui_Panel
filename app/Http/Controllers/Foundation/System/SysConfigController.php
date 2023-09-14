<?php

namespace App\Http\Controllers\Foundation\System;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\System\SysConfigLogic;
use Exception;


final class SysConfigController extends AbstractController
{

    public function __construct()
    {
        $this->title = '参数';
        $this->viewPath = 'foundation.sysConfig';
        $this->routeGroup = 'sysConfig';
        $this->permission = 'sysConfig';
        parent::__construct();
    }

    public function setLogic()
    {
        return new SysConfigLogic($this->request);
    }

    public function setIndexData()
    {
        $data = idAsKey($this->logic->getAll(), 'config_sig');
        $website = unserialize($data['website']['content']);
        return [
            'config' => $data,
            'path' => config('conf.file_path'),
            'website' => $website,
        ];
    }

    public function setRoute(){
        return [
            'sysConfig' => self::f_route('toSysConfig'),
        ];
    }

    public function toSysConfig(){
        try{
            return $this->success($this->logic->updateConfig('website'),'更新成功, 请刷新页面');
        }catch(Exception $e){
            return self::fail([],$e->getMessage());
        }
    }
}
