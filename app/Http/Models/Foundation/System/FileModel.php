<?php
namespace App\Http\Models\Foundation\System;

use App\Http\Models\Foundation\AbstractModel;
use Exception;

final class FileModel extends AbstractModel{


    protected $table = 'file';

    protected $fillable = ['id','name','path','m_type','s_type','size','raw_size','side','ext'];

    public function __construct($data = [])
    {
        $this->data = $data;
        parent::__construct();
    }


    public function setColumn(){
        return ['id','name','path','m_type','s_type','size','raw_size','ext','created_at','updated_at','side'];
    }

    public function list($where = [], $whereBetween = [], $column = [])
    {
        try {

            if (empty($column)) {
                $column = $this->column;
            }

            $query = self::select($column);

            if (!empty($where)) {
                foreach ($where as $k => $v) {
                    if (!in_array($v[0], $this->column)) {
                        unset($where[$k]);
                    }
                }
                $query = self::select($column)
                    ->where($where);
            }
            $data = $this->data;
            if(!empty($data['searchParams'])){
                $ext = $data['searchParams']['ext'];
                if(!empty($ext)){
                    $wherein = explode('|',$ext);
                    $query = self::select($column)
                    ->where($where)->whereIn('ext',$wherein);
                }
            }
          
            $count = $query->count($this->primaryKey);
            $res = $query->orderby($this->sortKey, $this->order)->simplePaginate($this->data['limit'] ?? 15, "*", 'page', $this->data['page'] ?? 1)->toArray()['data'];
            return [
                'data' => $this->list_mani($res),
                'count' => $count
            ];
        } catch (Exception $e) {
            $this->addLog('搜索', $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

}   