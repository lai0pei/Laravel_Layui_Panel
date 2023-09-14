<?php

namespace App\Http\Logic\Foundation\System;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\System\FileModel;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;

final class FileLogic extends AbstractLogic
{

    public function __construct($data)
    {
        $this->data = $data;
        parent::__construct();
    }

    public function setModel()
    {
        return new FileModel($this->data);
    }

    public function uploadSystemFile()
    {
        $file = $this->data['file'];
        $method = $this->data['method'];
        $data = $this->data;

        try {
            if ($method == 'add') {
                return $this->addFile($file,$data['side']);
            } else if ($method == 'update') {
                $disk = $this->disk();
                $saved = self::getOne([], 'path');
                $info = $this->fileProperty($file, $saved['path']);
                $cut_file = explode('/', $saved['path']);
                $disk->delete($saved['path']);
                $disk->putFileAs($cut_file[0], $file, $cut_file[1]);
                $setData = [
                    'name' => $data['name'],
                    'size' => $info['size'],
                    'm_type' => $info['m_type'],
                    's_type' => $info['s_type'],
                    'raw_size' => $info['raw_size'],
                    'side' => $data['side'],
                ];
                $this->model->setData($setData);
                return parent::updateLogic();
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // public function create_thumb_image($full_path,$file_name){

    //     $file = $full_path.'/'.$file_name;
    //     $img = Image::make($file);
    //     $width = $img->width();
    //     $height = $img->height();
    //     $img->resize($width * 0.8, $height * 0.8, function ($constraint) {
    //         $constraint->aspectRatio();
    //         $constraint->upsize();
    //     });
    //     $file_cut = explode('.',$file_name);
    //     $compress_name = $full_path.'/'.$file_cut[0].'_compress.'.$file_cut[1];
    //     $img->save($compress_name);
    //     dd($compress_name);

    // }


    public function addFile($file,$side = 0)
    {
        $disk = $this->disk();
        $path = Carbon::now()->format('Y-m-d');
        $file_path = $disk->put($path, $file);
        $info = $this->fileProperty($file, $file_path);
        $info['side'] = $side;
        $new_file_path = $this->reextension($file_path, $info['ext']);
        $disk->move($file_path, $new_file_path);
        $info['path'] = $new_file_path;
        $this->model->addRecord($info);
        return ['path' => $new_file_path,'fileInfo'=>$info];
    }
    private function fileProperty($file, $file_path)
    {
        $path = explode('.', $file_path);
        $ext = $file->getClientOriginalExtension();
        $type = explode('/', $file->getClientMimeType());
        $data['m_type'] = $type[0] ?? '无法检测';
        $data['s_type'] = $type[1] ?? '无法检测';
        $data['raw_size'] = $file->getSize();
        $data['size'] = $this->calSize($data['raw_size']);
        $data['name'] = $file->getClientOriginalName();
        $data['ext'] = (empty($ext)) ? $path[1] : $ext;
        return $data;
    }

    private function reextension($file_path, $ext)
    {
        if (!empty($ext)) {
            $tmp = explode('.', $file_path);
            return $tmp[0] . '.' . $ext;
        }
        return $file_path;
    }
    private function calSize($size)
    {
        $kb = $size / 1024;
        if ($kb > 1024) {
            $mb = $kb / 1024;
            return round($mb, 2) . 'mb';
        }
        return round($kb, 2) . 'kb';
    }

    public function deleteLogic()
    {
        $res = self::getOne([], ['path']);
        $disk = $this->disk();
        if ($disk->exists($res['path'])) {
            $disk->delete($res['path']);
        }
        parent::deleteLogic();
        return true;
    }

    public function disk()
    {
        return Storage::build([
            'driver' => 'local',
            'root' => storage_path(config('conf.real_file_path')),
            'url' => env('APP_URL') . '/'.config('conf.file_path'),
            'visibility' => 'public',
            'throw' => false,
        ]);
    }

    public function file_path()
    {
        $local = true;
        if ($local) {
            return config('conf.file_path');
        }
    }

    public function download()
    {

        try {
            $res = self::getOne([], ['path', 'name']);
            return [
                'disk' => $this->disk(),
                'file' => $res,
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteBulkLogic()
    {
        $data = $this->data;
        $disk = $this->disk();

        $dat = $this->model->whereIn('id', $data['bulkDelete'])->get();
        foreach ($dat as $v) {
            if ($disk->exists($v['path'])) {
                $disk->delete($v['path']);
            }
            $this->model->where('id', $v['id'])->delete();
        }
        return true;
    }

    public function deleteFile()
    {
        $data = $this->data;
        $disk = $this->disk();
        try {
            if ($disk->exists($data['path'])) {
                $disk->delete($data['path']);
            }
        } catch (Exception $e) {
            throw new Exception('服务器文件删除失败');
        }

        return true;
    }

    public function file_type()
    {
        return $this->model->select('s_type')->distinct()->get();
    }

    protected function whereBuild()
    {
        $where = [];

        if (empty($this->data['searchParams'])) {
            return $where;
        }

        foreach ($this->data['searchParams'] as $key => $value) {
            if (!is_null($value)) {
                if ($key == 'sort') {
                    $this->model->set('sortKey', 'raw_size');
                } else
                if ($key == 'ext') {
                    continue;
                } else {
                    $where[] = [$key, 'like', strval($value) . '%'];
                }
            }
        }

        return $where;
    }
}
