<?php

namespace App\Http\Controllers\Foundation\System;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\System\SysLogLogic;


final class SysLogController extends AbstractController
{

    public function __construct()
    {   
        $this->title = '日志';
        $this->viewPath = 'foundation.sysLog';
        $this->routeGroup = 'sysLog';
        $this->permission = 'sysLog';
        parent::__construct();
    }

    public function setLogic()
    {   
        return new SysLogLogic($this->request);
    }


}
