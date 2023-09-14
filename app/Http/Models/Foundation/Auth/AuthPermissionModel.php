<?php
namespace App\Http\Models\Foundation\Auth;

use App\Http\Models\Foundation\AbstractModel;

final class AuthPermissionModel extends AbstractModel{

    protected $fillable = ['id','title','auth_sub_sig','menu_id','is_deletable'];

    protected $table = 'auth_sub_menu';

    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }


    public function setColumn(){
        return ['id', 'title' ,'auth_sub_sig','status','menu_id','updated_at','is_deletable'];
    }

}   