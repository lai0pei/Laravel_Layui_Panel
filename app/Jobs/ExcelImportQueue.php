<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use App\Http\Logic\Foundation\System\FileLogic;
use Illuminate\Database\Eloquent\Model;
use Exception;

class ExcelImportQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $path;

    private $model;

    private $title;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path,$title, Model $model)
    {
        $this->path = $path;
        $this->title = $title;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        try{
            $model = $this->model;
            $storage = (new Filelogic([]))->disk();
            Excel::import($model, $storage->path($this->path));
        }catch(Exception $e){
            $model->addLog($this->title, $e->getMessage());
        }
    
    }
}
