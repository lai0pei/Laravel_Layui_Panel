<?php

namespace App\Http\Controllers\Foundation;

use Exception;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

abstract class AbstractController extends BaseController
{
    protected $logic;
    protected $viewPath = '';
    protected $index;
    protected $add_view;

    protected $update_view;

    protected $view;

    protected $request = '';

    protected $title = '';

    protected $secure = '';

    protected $routeGroup = '';

    protected $auth = '';

    protected $import_view = '';

    protected $export_view = '';

    protected $upload_view = '';

    protected $permission = '';

    protected function __construct()
    {
        $this->request = request()->all();
        $this->secure = config('conf.secure_url');
        $this->default_view();
        $this->logic = $this->setLogic();
    }

    abstract function setLogic();

    public function setRoute()
    {
        return [];
    }

    public function setUpdateViewData()
    {
        return [];
    }

    public function setIndexData()
    {
        return [];
    }

    public function setAddViewData()
    {
        return [];
    }

    final protected static function json($data)
    {
        return response()->json($data);
    }

    protected function default_route()
    {
        return [
            "listAction" => self::f_route('toList'),
            'addView' => self::f_route('addView'),
            'addAction' => self::f_route('toAdd'),
            'updateView' => self::f_route('updateView'),
            'updateAction' => self::f_route('toUpdate'),
            'importView' => self::f_route("importView"),
            'importAction' => self::f_route('toImport'),
            'exportView' => self::f_route('exportView'),
            'exportAction' => self::f_route('toExport'),
            'uploadView' => self::f_route('uploadView'),
            'uploadAction' => self::f_route('toUpload'),
            'deleteAction' => self::f_route('toDelete'),
            'deleteBulkAction' => self::f_route('toDeleteBulk'),
            'view' => self::f_route('view'),
        ];
    }

    protected function default_view()
    {
        $this->index = self::f_view('index');
        $this->add_view = self::f_view('add');
        $this->update_view = self::f_view('update');
        $this->import_view = self::f_view('import');
        $this->export_view = self::f_view('export');
        $this->upload_view = self::f_view('upload');
        $this->view = self::f_view('view');
    }

    final protected static function success($data = [], $msg = '', $code = 1)
    {
        $result['code'] = $code;
        $result['msg'] = ($msg == "") ? __('foundation.json_success') : $msg;
        $result['data'] = $data;

        return response()->json($result);
    }

    final protected static function fail($data = [], $msg = '', $code = 0)
    {
        $result['code'] = $code;
        $result['msg'] = ($msg == "") ? __('foundation.json_fail') : $msg;
        $result['data'] = $data;

        return response()->json($result);
    }

    protected function listControl()
    {
        $data['code'] = 0;
        $data['count'] = 0;
        $data['data'] = 0;
        if (!is_auth($this->permission . '_index')) {
            return $this->json($data);
        }
        $fetch = $this->logic->list();
        $data['count'] = $fetch['count'];
        $data['data'] = $fetch['data'];
        return $this->json($data);
    }

    protected function abstract_view($view, $data = [])
    {
        return view($view, [
            'data' => array_merge(['asset' => asset('')], $data),
            'route' => array_merge($this->setRoute() ?? [], $this->default_route()),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    public function index()
    {
        if (!is_auth($this->permission . '_index')) {
            return abort(403);
        }
        return view($this->index, [
            'data' => array_merge(['asset' => asset('')], $this->setIndexData() ?? []),
            'route' => array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }


    protected function add_view_validator(): bool
    {
        return false;
    }

    public function add_view()
    {
        if (!is_auth($this->permission . '_add') || $this->add_view_validator()) {
            return abort(403);
        }

        return view($this->add_view, [
            'data' => array_merge(['asset' => asset('')], $this->setAddViewData() ?? []),
            'route' => array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    protected function update_view_validator(): bool
    {
        return Validator::make($this->request, ['id' => 'required|numeric|min:1'])->fails();
    }

    public function update_view()
    {
        if (!is_auth($this->permission . '_edit') || $this->update_view_validator()) {
            return abort(403);
        }

        $data = $this->logic->getOne();

        return view($this->update_view, [
            'data' => array_merge(['asset' => asset('')], $this->setUpdateViewData() ?? [], ['data' => $data->toArray()]),
            'route' =>  array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    public function view()
    {
        if (!is_auth($this->permission . '_view') || $this->update_view_validator()) {
            return abort(403);
        }

        $data = $this->logic->getOne();

        return view($this->view, [
            'data' => array_merge(['asset' => asset('')], $this->setUpdateViewData() ?? [], ['data' => $data->toArray()]),
            'route' =>  array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    protected function upload_view_validator(): bool
    {
        return false;
    }

    public function upload_view()
    {
        if (!is_auth($this->permission . '_upload') || $this->upload_view_validator()) {
            return abort(403);
        }

        return view($this->upload_view, [
            'data' => array_merge(['asset' => asset('')]),
            'route' => array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    protected function import_view_validator(): bool
    {
        return false;
    }

    public function import_view()
    {
        if (!is_auth($this->permission . '_import') || $this->import_view_validator()) {
            return abort(403);
        }

        return view($this->import_view, [
            'data' => array_merge(['asset' => asset('')]),
            'route' => array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    protected function export_view_validator(): bool
    {
        return false;
    }

    public function export_view()
    {
        if (!is_auth($this->permission . '_export') || $this->export_view_validator()) {
            return abort(403);
        }

        return view($this->export_view, [
            'data' => array_merge(['asset' => asset('')]),
            'route' => array_merge($this->default_route(), $this->setRoute() ?? []),
            'title' => $this->title,
            'permission' => $this->permission
        ]);
    }

    protected function add_control_validator(): bool
    {
        return false;
    }

    public function addControl()
    {
        try {

            $this->post_auth_check('_add','无添加权限');

            if ($this->add_control_validator()) {
                throw new Exception('添加参数报错');
            }

            return ($this->logic->addLogic()) ? self::success([], __('foundation.add_success')) : self::fail([], __('foundation.add_fail'));
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }

    protected function update_control_validator(): bool
    {
        return Validator::make($this->request, ['id' => 'required|numeric|min:1'])->fails();
    }

    public function updateControl()
    {
        try {

            $this->post_auth_check('_edit','无更新权限');
            if ($this->update_control_validator()) {
                throw new Exception('更新参数报错');
            }

            return ($this->logic->updateLogic()) ? self::success([], __('foundation.update_success')) : self::fail([], __('foundation.update_fail'));
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }

    protected function delete_control_validator()
    {
        return Validator::make($this->request, ['id' => 'required|numeric|min:1'])->fails();
    }
    public function deleteControl()
    {
        try {
            $this->post_auth_check('_delete','无删除权限');
            if ($this->delete_control_validator()) {
                throw new Exception('删除参数报错');
            }

            return ($this->logic->deleteLogic()) ? self::success([], __('foundation.delete_success')) : self::fail([], __('foundation.delete_fail'));
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }

    public function deleteBulkControl()
    {
        try {
            $this->post_auth_check('_bulkDelete','无批量删除权限');
            return ($this->logic->deleteBulkLogic()) ? self::success([], __('foundation.delete_success')) : self::fail([], __('foundation.delete_fail'));
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }

    public function exportControl()
    {
        try {
            $this->post_auth_check('_export','无导出权限');
            return $this->logic->exportLogic();
        } catch (Exception $e) {
            return self::fail([], $e->getMessage());
        }
    }

    final protected function validator($rules = '', $message = '')
    {
        $msg = ($message == '') ? __('foundation.json_error') : $message;
        $rul = ($rules == '') ? ['id' => 'required|numeric|min:1'] : $rules;
        if (Validator::make($this->request, $rul)->fails()) {
            throw new Exception($msg);
        }
    }

    final protected function f_route($name)
    {
        return '/'.$this->secure . '/' . $this->routeGroup . '/' . $name;
    }

    final protected function f_view($name)
    {
        return $this->viewPath . '.' . $name;
    }

    final protected function post_auth_check($permission, $msg){
        if (!is_auth($this->permission . $permission)) {
            throw new Exception($msg);
        }
    }
}
