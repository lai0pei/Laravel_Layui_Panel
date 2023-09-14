<?php

namespace App\Http\Models\Foundation\System;

use App\Http\Models\Foundation\AbstractModel;

final class SysLogModel  extends AbstractModel
{
    protected $table = 'sys_log';

    protected $fillable = ['id', 'title', 'ip', 'json_data', 'content','type'];

    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }


    public function setColumn()
    {
        return ['id', 'title', 'ip', 'json_data', 'content', 'created_at','type'];
    }


}
