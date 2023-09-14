<?php

namespace App\Http\Models\Foundation;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Foundation\System\LogMarkModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;


abstract class AbstractModel extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $column = [];

    protected $data;

    protected $order = 'desc';

    protected $sortKey = 'id';

    protected function __construct()
    {
        $this->column = $this->setColumn();
    }

    public function set($key, $value)
    {
        $this->$key = $value;
    }
    abstract function setColumn();

    public function list($where = [], $whereBetween = [], $column = [])
    {
        try {

            if (empty($column)) {
                $column = $this->column;
            }
            $rwhere = [];
            $query = self::select($column);

            if (!empty($where['user'])) {
                foreach ($where['user'] as $k => $v) {
                    if (!in_array($v[0], $this->column)) {
                        unset($where['user'][$k]);
                    }
                }
            }
            
            $rwhere = array_merge($where['define'],$where['user']);

            if (!empty($whereBetween)) {
                $query = self::select($column)
                    ->where($rwhere)->whereBetween($this->primaryKey, $whereBetween);
            }else{

                $query = self::select($column)
                ->where($rwhere);
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


    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H时:i分:s秒');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y年-m月-d日 H时:i分:s秒');
    }
    protected function list_mani($data)
    {
        return $data;
    }

    /**
     * getOne
     *
     * @param  mixed $where
     * @param  mixed $column
     */
    public function getOne($where = [], $column = [])
    {

        try {
            if (empty($where)) {
                $where = [$this->primaryKey => $this->data[$this->primaryKey]];
            }

            if (empty($column)) {
                $column = $this->column;
            }
            return self::where($where)->select($column)->first();
        } catch (Exception $e) {
            $this->addLog('单获取数据', $e->getMessage());
            return [];
        }
    }

    public function updateOne($data = [], $where = [])
    {
        try {

            if (empty($data)) {
                $data = $this->data;
            }

            if (empty($where)) {
                $where = [$this->primaryKey => $data[$this->primaryKey]];
            }
            DB::beginTransaction();
            $res =  (!$this->filterColumn($data)) ? false : self::where($where)->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            $this->addLog('更新', $e->getMessage());
            throw new Exception($e->getMessage());
        }
        DB::commit();
        return $res;
    }



    public function addRecord($data = [])
    {
        try {

            if (empty($data)) {
                $data = $this->data;
            }
            DB::beginTransaction();
            if (!$this->filterColumn($data)) {
                return false;
            }
            $record = self::create($data);
        } catch (Exception $e) {
            DB::rollBack();
            $this->addLog('添加', $e->getMessage());
            throw new Exception($e->getMessage());
        }
        DB::commit();
        return $record;
    }

    public function deleteRecord($where = [])
    {
        try {
            if (empty($where)) {
                $where = [$this->primaryKey => $this->data[$this->primaryKey]];
            }
            return self::where($where)->delete();
        } catch (Exception $e) {
            $this->addLog('删除', $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function deleteBulkRecord()
    {
        try {
            $bulk = $this->data['bulkDelete'];
            foreach ($bulk as $v) {
                if (is_array($v)) {
                    return false;
                }
            }
            DB::beginTransaction();
            $res = self::whereIn($this->primaryKey, $bulk)->delete();
        } catch (Exception $e) {
            DB::rollBack();
            $this->addLog('批量删除', $e->getMessage());
            throw new Exception($e->getMessage());
        }

        DB::commit();
        return $res;
    }

    private function filterColumn(&$data)
    {
        if (!is_array($data)) {
            return false;
        }

        foreach (array_keys($data) as &$value) {
            if (!in_array($value, $this->column)) {
                unset($data[$value]);
            }
        }

        return true;
    }


    public function getAllRecord($where = [], $column = [])
    {
        try {
            if (empty($column)) {
                $column = $this->column;
            }
            return self::where($where)->select($column)->get();
        } catch (Exception $e) {
            $this->addLog('多获取', $e->getMessage());
            return [];
        }
    }

    public function addLog($title, $msg)
    {
        $mar = $this->table . '表-' . $title . '操作';
        $logger = new LogMarkModel();
        return $logger->systemLog($mar, $msg);
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data = [])
    {
        if (!is_array($data)) {
            return false;
        }
        foreach ($data as $k => $v) {
            $this->data[$k] = $v;
        }
        return true;
    }
}
