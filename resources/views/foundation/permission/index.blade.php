@extends('common.index')
@section('table_in')
<div clas="layui-card-body">
    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" lay-filter="data-search-filter" action="" onsubmit="event.preventDefault();">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">权限名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="title" autocomplete="off" placeholder="请输入名称" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">权限菜单</label>
                        <div class="layui-input-inline">
                            <select name="menu_id" lay-search lay-filter="on_select">
                                <option value="">请选择</option>
                                @foreach($data['menu'] as $v)
                                <option value="{{$v['id']}}">{{$v['title']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                      <div class="layui-inline">
                        <label class="layui-form-label">权限状态</label>
                        <div class="layui-input-inline">
                            <select name="status" lay-search lay-filter="on_select">
                                <option value="">请选择</option>
                                <option value="0">未使用</option>
                                <option value="1">已使用</option>
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button class="layui-btn layui-btn-primary" lay-filter="table_search" lay-submit=""><i class="layui-icon"></i> 搜索{{$title}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
</div>
</div>
@endsection
@section('table')


frameSize.add_size = ["60%","500px"];
frameSize.update_size = ["70%","700px"];
frameSize.view_size = ["50%","500px"];

cols = [    
    [
    {
         field: 'id'
        , title: '权限编号'
        , width: 80
        , align: "center"
    }
    ,{
        field: 'title'
        , title: "权限名称"
        , align: "center"
        , width : 200
        , edit : 'text'
    ,
    }
        , {
        field: 'auth_sub_sig'
        , title: '权限识别'
        , align: "center"
    }
    , {
        field: 'status'
        , title: '权限状态'
        , align: "center"
        , style : "font-weight : bold; color : #2F4056"
        ,templet : function(d){
            if(d.status == 1){
                return '已使用';
            }else{
                return '未使用';
            }
        }
    }
    ,{
        field: 'updated_at'
        , title: '修改时间'
        , align: "center"   
    }
    , {
        fixed: 'right'
        , toolbar: '#tdToolbar'
        , title: '操作面板'
        , width: 300
        , align: "center"
    }
    ]
];

table.render({
     limit: 30
    , page: true
    , height: 'full-180'
    , escape: true
    , cols: cols

});
 
@endsection
