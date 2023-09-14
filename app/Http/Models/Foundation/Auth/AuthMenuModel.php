<?php
namespace App\Http\Models\Foundation\Auth;

use App\Http\Models\Foundation\AbstractModel;

final class AuthMenuModel extends AbstractModel{

    protected $table = 'auth_menu';

    protected $fillable = ['id','p_id', 'title','auth_sig', 'icon', 'href', 'sort', 'status', 'is_deletable','rank'];

    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }


    public function setColumn(){
        return ['id','p_id', 'title','auth_sig', 'icon', 'href', 'sort', 'status', 'is_deletable','rank','updated_at'];
    }

    /**
     * getUiMenu
     *
     * @return void
     */
    public function getAuthMenu()
    {
        return self::select($this->column)->orderby('sort')->get();
    }

}   