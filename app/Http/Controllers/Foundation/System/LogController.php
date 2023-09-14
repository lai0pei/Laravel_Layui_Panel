<?php

namespace App\Http\Controllers\Foundation\System;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\System\LogLogic;
use App\Http\Logic\Foundation\Auth\AdminLogic;

final class LogController extends AbstractController
{

    public function __construct()
    {   
        $this->title = '记录';
        $this->viewPath = 'foundation.log';
        $this->routeGroup = 'adminLog';
        $this->permission = 'adminLog';
        parent::__construct();
    }

    public function setLogic()
    {   
        return new LogLogic($this->request);
    }

    public function setIndexData()
    {
       return [
        'admin' => (new AdminLogic([]))->getAll([],['id','username']),
       ];
    }


}
