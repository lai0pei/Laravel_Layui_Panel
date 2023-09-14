<?php
namespace App\Http\Models\Foundation\Auth;

use App\Http\Models\Foundation\AbstractModel;

final class AdminModel extends AbstractModel{


    protected $table = 'admin';

    protected $fillable = ['username','account','password','status','description','role_id'];

    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }


    public function setColumn(){
        return ['id','account','password','username','status','role_id','login_count','description','created_at','updated_at'];
    }

}   