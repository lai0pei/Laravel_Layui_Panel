<?php

namespace App\Http\Models\Foundation\Auth;

use App\Http\Models\Foundation\AbstractModel;


final class RoleModel extends AbstractModel
{   
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';

    protected $fillable = ['role_name','description','status'];

    /**
     * __construct
     *
     * @param  mixed $data
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }

    /**
     * setColumn
     *
     * @return array
     */
    public function setColumn() : array
    {
       return ['id', 'role_name', 'status', 'description', 'auth_id','updated_at'];
    }
    
    /**
     * getCurrentRoleId
     *
     */
    public function getAuthId() {
        return $this->getOne(['id'=>get_credential('role_id')]);
    }
}
