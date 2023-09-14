@extends('common.index')
@section('table_in')
<div clas="layui-card-body">
    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" lay-filter="data-search-filter" action="" onsubmit="event.preventDefault();">
                    <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">报错类型</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-search lay-filter="on_select">
                                <option value="">请选择</option>
                                  <option value="0">系统错误</option>
                                  <option value="1">其他错误</option>
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
                    , title: '日志编号'
                    , width: 100
                    , align: "center"
                     ,sort : true,
                }
                , {
                    field: 'title'
                    , title: "触发行为"
                    , width: 180
                    , align: "center"
                    ,sort : true,
                }
                , {
                    field: 'type'
                    , title: "日志类型"
                    , width: 120
                    , align: "center"
                    ,sort : true,
                    templet : function(d){
                        if(d.type == 0){
                            return `<span style="color:#1E9FFF;font-weight:500">系统报错</span>`;
                        }else{
                             return `<span style="color:brown;font-weight:500">其他报错</span>`;
                        }
                    }
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
                    , title: '触发原因'
                    , align: "center"

                }
             
                ,{
                    field: 'created_at'
                    , title: '触发时间'
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