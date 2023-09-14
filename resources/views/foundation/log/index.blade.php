@extends('common.index')
@section('table_in')
<div clas="layui-card-body">
    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" lay-filter="data-search-filter" action="" onsubmit="event.preventDefault();">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">操作人员</label>
                        <div class="layui-input-inline">
                            <select name="admin_id" lay-search lay-filter="on_select">
                                <option value="">请选择</option>
                                @foreach($data['admin'] as $v)
                                <option value="{{$v['id']}}">{{$v['username']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">访问地址</label>
                        <div class="layui-input-inline">
                            <input type="text" name="ip" autocomplete="off" placeholder="请输入IP地址" class="layui-input">
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
       cols = [
            [{
        type: 'checkbox', fixed: 'left'
    },{
                    field: 'id'
                    , title: '操作编号'
                    , width: 100
                    , align: "center"
                     ,sort : true,
                }
                   , {
                    field: 'admin'
                    , title: '操作账号'
                    , width: 150
                    , align: "center"
                     ,sort : true,
                }
                , {
                    field: 'title'
                    , title: "操作行为"
                    , width: 120
                    , align: "center"
                    ,sort : true,
                }
                 , {
                    field: 'ip'
                    , title: '访问地址'
                    , width: 150
                    , align: "center"
                     ,sort : true,
                }
                , {
                    field: 'content'
                    , title: '操作描述'
                    , align: "center"

                }
             
                ,{
                    field: 'created_at'
                    , title: '操作时间'
                    , align: "center" 
                     , width: 250 
                    }
                , {
                    fixed: 'right'
                    , toolbar: '#tdToolbar'
                    , title: '操作面板'
                    , width: 270
                    , align: "center"
                }
            ]
        ];

        table.render({
             limit: 30
            , even : true
            , page: true
            , height: 'full-60'
            , escape: false
            , cols: cols
        });

    
@endsection