<?php

namespace App\Http\Logic\Foundation\Auth;

use App\Http\Logic\Foundation\AbstractLogic;
use App\Http\Models\Foundation\Auth\AuthMenuModel;
use App\Http\Models\Foundation\Auth\AuthPermissionModel;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Foundation\Auth\RoleModel;
use Illuminate\Support\Facades\Cache;


final class AuthMenuLogic extends AbstractLogic
{

    private $auth_id;

    private $permission;

    private $menu;
    public function __construct($data)
    {
        $this->data = $data;
        $this->menu = '_system_menu';
        $this->permission = '_auth_sub_permission';
        parent::__construct();
    }

    public function setModel()
    {
        return new AuthMenuModel($this->data);
    }

    public function getAuthMenu()
    {   
        $role_id = get_credential('role_id');
        $menu = Cache::get($role_id.$this->menu);
  
        if (!empty($menu)) {
            return $menu;
        }
        $this->setAuth();
        $auth_id =  $this->auth_id;

        if (empty($auth_id)) {
            return [];
        }

        $sub_menu = (new AuthPermissionModel())->whereIn('id', $auth_id)->select('menu_id')->distinct()->get()->toArray();
        $sub_menu_id = array_column($sub_menu, 'menu_id');
        // $menu = base64_encode(json_encode($this->buildMenu($this->model->getAuthMenu(),  $sub_menu_id)));
        $menu = $this->buildMenu($sub_menu_id);
        $res = ['menu'=>$menu,'home'=>route('f_blank'),'auth'=>$sub_menu_id,'pendo'=>time()];
        Cache::put($role_id.$this->menu,$res);
        return $res;
    }
    
    public function toFilter($data)
    {

        $auth = Collect($data);
        $list = [];
        foreach ($auth->where('rank', 0)->sortByDesc('sort')->all() as &$gv) {
            $gv['href'] = $this->parseUrl(($gv['href']));
            array_push($list, $gv);

            foreach ($auth->where('rank', 1)->where('p_id', $gv['id'])->sortByDesc('sort')->all() as &$pv) {
                $pv['href'] = $this->parseUrl(($pv['href']));

                array_push($list, $pv);
                foreach ($auth->where('rank', 2)->where('p_id', $pv['id'])->sortByDesc('sort')->all() as &$cv) {
                    $cv['href'] = $this->parseUrl(($cv['href']));
                    array_push($list, $cv);
                }
            }
        }
        return $list;
    }

    public function updateLogic()
    {
        if ($this->data['method'] == 'sort') {
            session()->forget($this->menu);
        } elseif ($this->data['method'] == 'status') {
            $d =  parent::getOne();
            if ($d['is_deletable'] == 0) {
                throw new \Exception("菜单不可屏蔽");
            }
            self::setData(['status'=>!$d['status']]);
        } else {
            $tmp = $this->data;

            $this->data['rank'] = 0;
            if ($tmp['p_id'] > 0) {
                $rank = $this->model->getOne(['id' => $tmp['p_id']], ['rank']);
                $this->data['rank'] = $rank['rank'] + 1;
            }

            $self = $this->model->getOne(['auth_sig' => $tmp['auth_sig']], ['id']);
            if ($this->data['rank'] == 2 && !empty($self) && $self['id'] != $tmp['id']) {
                throw new \Exception('此识别符已存在');
            }

            if (!empty($this->data['sub_menu'])) {
                $sub_menu = new AuthPermissionModel([]);
                $sub_menu->where('menu_id', $this->data['id'])->get();
                if ($this->data['rank'] == 2 && !empty($this->data['sub_menu'])) {
                    $sub_menu->where('menu_id', $this->data['id'])->update(['status' => 0]);
                    foreach ($this->data['sub_menu'] as $v) {
                        $sub_menu->where('id', $v)->update(['status' => 1]);
                    }
                }
            }
            self::setData(['rank'=>$this->data['rank']]);
        }
        parent::updateLogic();
        $this->purgeMenuCache();
        return true;
    }

    public function addLogic()
    {
        $tmp = $this->data;
        $self = $this->model->getOne(['auth_sig' => $tmp['auth_sig']], ['id']);

        $this->data['rank'] = 0;
        if ($tmp['p_id'] > 0) {
            $rank = $this->model->getOne(['id' => $tmp['p_id']], ['rank']);
            $this->data['rank'] = $rank['rank'] + 1;
        }
        if ($this->data['rank'] == 2) {
            if (!empty($self)) {
                throw new \Exception('此识别符已存在');
            }
            $self = $this->model->getOne(['title' => $tmp['title']], ['id']);
            if (!empty($self)) {
                throw new \Exception('此识名称已存在');
            }
        }
        self::setData([
            'status' => 1,
            'is_deletable' => 1,
        ]);
        parent::addLogic();
        return true;
    }


    public function deleteLogic()
    {
        if (parent::getOne()['is_deletable'] == 0) {
            throw new \Exception('此菜单不可删除');
        }

        $has_child = $this->model->getOne(['p_id' => $this->data['id']]);
        if (!empty($has_child)) {
            throw new \Exception('先吃删除子菜单!');
        }
        if (parent::deleteLogic()) {
            (new AuthPermissionModel([]))->where('menu_id', $this->data['id'])->delete();
        }
        $this->purgeMenuCache();
        return true;
    }


    public function rankTwoMenu()
    {
        $auth = $this->model->getAuthMenu();
        $list = [];
        array_push($list, ['title' => "顶部菜单", 'id' => 0, 'rank' => 0]);
        foreach ($auth->where('rank', 0)->sortByDesc(['sort'])->all() as $gv) {
            array_push($list, $gv);
            foreach ($auth->where('rank', 1)->where('p_id', $gv['id'])->sortByDesc(['sort'])->all() as $pv) {
                $pv['title'] = 'ㅤ├ㅤ' . $pv['title'];
                array_push($list, $pv);
            }
        }
        return $list;
    }
    public function rankTwoMenuUpdate()
    {
        $auth = parent::getOne();
        $has_child = $this->model->getOne(['p_id' => $this->data['id']]);

        if (empty($has_child) && $auth['rank'] != 2) {
            return $this->rankTwoMenu();
        }

        $auth_menu = $this->model->getAuthMenu();
        $list = [];
        $rank = $auth['rank'];

        switch (true) {
            case $rank == 0:
                array_push($list, ['title' => "顶部菜单", 'id' => 0, 'rank' => 0]);
                break;
            case $rank == 1:
                foreach ($auth_menu->where('rank', 0)->sortByDesc(['sort'])->all() as $gv) {
                    array_push($list, $gv);
                }
                break;
            case $rank == 2:
                if ((new AuthPermissionModel([]))->where('menu_id', $auth['id'])->select('id')->get()->isEmpty()) {
                    return $this->rankTwoMenu();
                }
                foreach ($auth_menu->where('rank', 1)->sortByDesc(['sort'])->all() as $gv) {
                    array_push($list, $gv);
                }
                break;
        }
        return $list;
    }


    public function auth_sub_menu()
    {
        $tmp = $this->data;
        return (new AuthPermissionModel([]))::where('menu_id', $tmp['id'])->select(['id', 'title', 'status'])->get();
    }

    private function setAuth()
    {
        $auth_id = (new RoleModel())->getAuthId();
        $exp_id = explode(',', $auth_id['auth_id']);
        $this->auth_id = $exp_id;
        $permission = (new AuthPermissionModel())->whereIn('id', $exp_id)->where('status', 1)->select('auth_sub_sig')->get();
        $auth_sub = [];
        if (!$permission->isEmpty()) {
            $auth_sub = array_column($permission->toArray(), 'auth_sub_sig');
        }
        if(!empty($auth_sub)){
            $role_id = get_credential('role_id');
            Cache::put($role_id.$this->permission,$auth_sub);
        }
        return $auth_sub;
    }

    public function is_auth_menu_valid($name){
        $role_id = get_credential('role_id');
        $auth = Cache::get($role_id.$this->permission);
        if(empty($auth)){
            $auth = $this->setAuth();
            if(empty($auth)){
                return false;
            }
        }
        if(!in_array($name,$auth)){
            return false;
        }
        return true;
    }


    private function MenuDesign($auth_menu)
    {
        return [
            'id' => $auth_menu->id,
            'title' => $auth_menu->title,
            'icon' => $auth_menu->icon,
            'sort' => $auth_menu->sort,
            'href' => ($auth_menu->rank == 2) ? $this->parseUrl($auth_menu['href']) : "",
            'children' => [],
        ];
    }


    /**
     * buildMenu
     *
     * @param  mixed $auth_menu
     */
    private function buildMenu($auth_id)
    {
        $auth_menu = $this->model->getAuthMenu();
        foreach ($auth_menu->where('rank', 0)->sortByDesc(['sort'])->all() as $gk => $gv) {
            if (empty($gv)) {
                continue;
            }
            $grand[$gk] = $this->MenuDesign($gv);
            $parent = [];
            foreach ($auth_menu->where('rank', 1)->where('p_id', $gv['id'])->sortByDesc(['sort'])->all() as $pk => $pv) {
                if (empty($pv)) {
                    continue;
                }
                $parent[$pk] = $this->MenuDesign($pv);
                $child = [];

                foreach ($auth_menu->where('rank', 2)->where('p_id', $pv['id'])->sortByDesc(['sort'])->all() as $ck => $cv) {
                    if (empty($cv) || !in_array($cv['id'], $auth_id)) {
                        continue;
                    }
                    $child[$ck] = $this->MenuDesign($cv);
                }
                if (empty($child)) {
                    unset($parent[$pk]);
                } else {
                    $parent[$pk]['children'] = array_values($child);
                }
            }

            if (empty($parent)) {
                unset($grand[$gk]);
            } else {
                $grand[$gk]['children'] = array_values($parent);
            }
        }

        return array_values($grand);
    }

    private function parseUrl($href)
    {
        $url = parse_url($href);
        if (!empty($url['scheme'])) {
            return $href;
        }
        return (empty($href) || !Route::has($href)) ? '' : parse_url(route($href))['path'];
    }

    public function purgeMenuCache(){
        $auth_id = (new RoleModel())->getAllRecord()->toArray();
        if(empty($auth_id)){
            return true;
        }
        foreach($auth_id as $v){
            Cache::forget($v['id'].$this->menu);
        }
    }
}
