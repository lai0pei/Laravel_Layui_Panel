<?php

namespace App\Http\Logic\Foundation\System;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\System\SysLogModel;
final class SysLogLogic extends AbstractLogic
{

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function setModel()
    {
        return new SysLogModel($this->data);
    }



}
