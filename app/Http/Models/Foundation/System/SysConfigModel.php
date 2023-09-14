<?php

namespace App\Http\Models\Foundation\System;

use App\Http\Models\Foundation\AbstractModel;

final class SysConfigModel  extends AbstractModel
{
    protected $table = 'sys_config';

    protected $fillable = ['id', 'config_name','config_sig','content'];

    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }


    public function setColumn()
    {
        return ['id', 'config_name','config_sig','content'];
    }


}
