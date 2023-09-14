<?php

namespace App\Http\Controllers\Foundation\System;

use App\Http\Controllers\Foundation\AbstractController;
use App\Http\Logic\Foundation\System\FileLogic;
use Exception;

final class FileController extends AbstractController
{

    public function __construct()
    {   
        $this->title = '文件';
        $this->viewPath = 'foundation.file';
        $this->routeGroup = 'file';
        $this->permission = 'file';
        parent::__construct();
    }

    public function setLogic()
    {   
        return new FileLogic($this->request);
    }

    public function setRoute()
    {
        return [
            'uploadView' => self::f_route('uploadView'),
            'toUpload' => self::f_route('toUpload'),
            'downloadAction' => self::f_route('toDownload'),
            'toDeleteFile' => self::f_route('toDeleteFile'),
        ];
    }

    public function setIndexData()
    {
        return [
            'file_path' => $this->logic->file_path(),
            'file_type' => $this->logic->file_type(),
        ];
    }

    public function setAddViewData(){
        return [
            'max_size' => 2,
        ];
    }

    public function setUpdateViewData(){
        return [
            'file_path' => $this->logic->file_path(),
            'max_size' => 2,
        ];
    }

    public function upload_file_view(){
        return $this->abstract_view(self::f_view('upload'));
    }

    public function toUpload(){
        try{
            return self::success( $this->logic->uploadSystemFile());
        }catch(Exception $e){
            return self::fail([],$e->getMessage());
        }
      
    }

    public function toDownload(){
        try{
            $down  = $this->logic->download();
            $file = $down['file'];
            $disk = $down['disk'];
            return $disk->download($file['path'],$file['name']);
            }catch(Exception $e){
            return self::fail([],$e->getMessage());
        }
    }

    public function toDeleteFile(){
        try{
            return $this->success($this->logic->deleteFile(),'服务器文件删除成功');
        }catch(Exception $e){
            return self::fail([],$e->getMessage());
        }
    }

    public function commonUploadView(){
        $request = $this->request;

        return $this->abstract_view(self::f_view('commonUpload'),
        [
            'path'=>$this->logic->file_path(),
            'm_type'=>$request['lay-type'],
            'lay-target' => $request['lay-target'],
            'lay-preview' => $request['lay-preview'],
            'lay-ext' => $request['lay-ext'],
            'max-size' => 2,
        ]
    );
    }

}
