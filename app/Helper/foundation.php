<?php

function get_credential($key = '')
{   
    if(empty($key)){
        return session('admin_credential');
    }
    return session('admin_credential')[$key] ?? '';
}

function is_auth($name){
    return (new App\Http\Logic\Foundation\Auth\AuthMenuLogic([]))->is_auth_menu_valid($name);
}

function recordLog($title, $content = ''){
    $d = [
        'title' => $title,
        'content' => $content,
        'ip' => request()->ip(),
        'admin_id' => get_credential('admin_id'),

    ];
    (new \App\Http\Models\Foundation\System\LogModel())->addRecord($d);
}