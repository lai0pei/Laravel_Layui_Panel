<?php

namespace App\Http\Models\Foundation\System;

use Illuminate\Database\Eloquent\Model;


final class LogMarkModel  extends Model
{

    protected $primary_key = 'id';
    protected $table = 'sys_log';
    public $timestamps = true;
    protected $fillable = ['id', 'title', 'ip', 'json_data', 'content','type'];

    public function __construct()
    {

    }


    public function setColumn()
    {
        return ['id', 'title', 'ip', 'json_data', 'content','type', 'created_at'];
    }


    public function systemLog($title, $msg)
    {
        $data = [
            'title' => $title,
            'content' => $msg,
            'ip' => request()->ip(),
            'type' => 0
        ];
        return self::create($data);
    }

    public function externalLog($title, $msg, $json = null)
    {
        $data = [
            'title' => $title,
            'content' => $msg,
            'ip' => request()->ip(),
            'json' => $json,
            'type' => 1
        ];
        return self::create($data);
    }

}
