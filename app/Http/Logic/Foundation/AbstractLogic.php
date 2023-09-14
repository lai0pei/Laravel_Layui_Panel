<?php

namespace App\Http\Logic\Foundation;

abstract class AbstractLogic
{
    protected $model;

    protected $data;

    protected $whereBetweenRule = ['start', 'end'];

    public function __construct()
    {
        $this->model = $this->setModel();
    }

    abstract function setModel();

    public function list()
    {
        $where = $this->whereBuild();
        $whereBetween = $this->whereBetweenBuild();
        $result = $this->model->list($where, $whereBetween, $this->customColumn());
        return ['data' => $this->toFilter($result['data']), 'count' => $result['count']];
    }

    protected function toFilter($data)
    {
        return $data;
    }

    protected function customColumn()
    {
        return [];
    }

    protected function whereBuild()
    {
        $where = [];
        $where['define'] = [];
        $where['user'] = [];

        if (empty($this->data['searchParams'])) {
            return $where;
        }
        foreach ($this->data['searchParams'] as $key => $value) {
            if(is_null($value)){
                continue;
            }
            $where['user'][] = [$key, '=', strval($value)];
        }
        return $where;
    }

    protected function whereBetweenBuild()
    {
        $where = [];
        if (empty($this->data['searchParams'])) {
            return $where;
        }
        foreach ($this->data['searchParams'] as $key => $value) {
            if (!is_null($value) && in_array($key, $this->whereBetweenRule)) {
                $where[] = [$key, '=',strval($value)];
            }
        }
      
        return $where;
    }

    public function getOne($where = [], $column = [])
    {
        return $this->model->getOne($where, $column);
    }

    public function updateLogic()
    {
        return $this->model->updateOne();
    }

    public function addLogic()
    {
        return $this->model->addRecord();
    }

    public function deleteLogic()
    {
        return $this->model->deleteRecord();
    }

    public function deleteBulkLogic()
    {
        return $this->model->deleteBulkRecord();
    }

    public function getAll($where = [], $column = [])
    {
        return $this->model->getAllRecord($where, $column);
    }

    public function log($title, $msg)
    {
        return $this->model->addLog($title, $msg);
    }

    public function setData($data = [])
    {   
        if (!is_array($data)) {
            return false;
        }
        foreach ($data as $k => $v) {
            $this->data[$k] = $v;
        }
        
        return $this->model->setData($data);
    }

    public function getData()
    {
        return $this->model->getData();
    }

    public function exportLogic()
    {   
        $tmp = $this->exportConfig()??[];
        $ext = $this->exportType($this->data['format']);
        return \Maatwebsite\Excel\Facades\Excel::download($tmp['export_model'], $tmp['title'].'.'.strtolower($ext), $ext);
    }

    protected function exportConfig(){
        throw new \Exception("Not Implemented!");
    }

    final protected function exportType($int)
    {
        $type = [
            \Maatwebsite\Excel\Excel::XLSX,
            \Maatwebsite\Excel\Excel::CSV,
            \Maatwebsite\Excel\Excel::ODS,
            \Maatwebsite\Excel\Excel::XLS,
            \Maatwebsite\Excel\Excel::HTML
        ];
        return $type[$int] ?? \Maatwebsite\Excel\Excel::CSV;
    }

    public function run_queue(){
        return [];
    }
}
