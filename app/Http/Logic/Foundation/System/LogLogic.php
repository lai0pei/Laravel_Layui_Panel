<?php

namespace App\Http\Logic\Foundation\System;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\System\LogModel;
final class LogLogic extends AbstractLogic
{

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function setModel()
    {
        return new LogModel($this->data);
    }



}
