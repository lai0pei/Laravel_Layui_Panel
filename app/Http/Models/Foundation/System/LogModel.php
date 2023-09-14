<?php

namespace App\Http\Models\Foundation\System;

use App\Http\Models\Foundation\AbstractModel;
use App\Http\Models\Foundation\Auth\AdminModel;

final class LogModel  extends AbstractModel
{
    protected $table = 'log';

    protected $fillable = ['id', 'title', 'ip', 'json_data', 'content','admin_id'];

    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }


    public function setColumn()
    {
        return ['id', 'title', 'ip', 'json_data', 'content', 'admin_id','created_at'];
    }

    public function list_mani($data){
        $admin = idAsKey((new AdminModel())->select(['id','username'])->get()->toArray());
      
        foreach($data as &$v){
            $v['admin'] =$v['admin_id']? $admin[$v['admin_id']]['username']:'无';
        }
        return $data;
    }

}
