@extends('common.index')
@section('toolbar')
@if(is_auth($permission.'_setAuth'))
<a class="layui-btn layui-btn-sm bg-normal" lay-event="setAuth"><i class="layui-icon layui-icon-auz"></i>授权</a>
@endif
@endsection
@section('table')

frameSize.add_size = ["500px","500px"];
frameSize.update_size = ["500px","500px"];
frameSize.view_size = ["500px","500px"];

cols = [    
    [
    {
         field: 'id'
        , title: '组合编号'
        , width: 80
        , align: "center"
    }
    ,{
        field: 'role_name'
        , title: "组合名称"
        , align: "center"
        , width : 200
    ,
    }
    ,{  
        title: "权限数量"
        , align: "center"
        , width : 200
        , templet : function(d){
            if(d.auth_id != null){
            return d.auth_id.split(',').length;
            }else{
             return 0;
            }
            
        }
    ,
    }
    , {
        field: 'status'
        , title: '组合状态'
        , align: "center"
        , style : "font-weight : bold; color : #2F4056;font-size : 13px"
        ,templet : function(d){
            if(d.status == 1){
                return '开启';
            }else{
                return '禁用';
            }
        }
    }
    , {
        field: 'description'
        , title: '组合描述'
        , align: "center"
    }
    ,{
        field: 'updated_at'
        , title: '修改时间'
        , align: "center"   
        , width : 250
    }
    , {
        fixed: 'right'
        , toolbar: '#tdToolbar'
        , title: '操作面板'
        , width: 350
        , align: "center"
    }
    ]
];

table.render({
     limit: 30
    , page: true
    , height: 'full-100'
    , escape: true
    , cols: cols

});
 
    sideMenu.extend = function(obj){
          let event = obj.event;
         
           if(event == 'setAuth'){
                iframe_open({
                        "url": "{{$route['authView']}}"
                        , "title": "设置{{$title}}权限"
                        , "data": 'id=' + obj.data.id
                        , "size" : ["900px","800px"]
                });
           }
    }

@endsection
