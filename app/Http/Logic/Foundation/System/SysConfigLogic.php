<?php

namespace App\Http\Logic\Foundation\System;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\System\SysConfigModel;

final class SysConfigLogic extends AbstractLogic
{

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function setModel()
    {
        return new SysConfigModel($this->data);
    }

    public function updateConfig($config)
    {
        return $this->model->updateOne(['content' => serialize($this->data)], ['config_sig' => $config]);
    }
}
