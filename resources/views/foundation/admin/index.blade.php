@extends('common.index')
@section('toolbar')
@if(is_auth($permission.'_setPassword'))
<a class="layui-btn layui-btn-sm bg-normal" lay-event="setPassword"><i class="layui-icon layui-icon-password"></i>密码</a>
@endif
@endsection

@section('table')
cols = [    
    [
    {
         field: 'id'
        , title: '人员编号'
        , width: 80
        , align: "center"
    }
    ,{
        field: 'account'
        , title: "登录账号"
        , align: "center"
        , width : 200
    ,
    }
    ,{  
        field: 'username'
        , title: "显示昵称"
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
        , title: '账号状态'
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
        , title: '账号描述'
        , align: "center"
    }
    , {
        field: 'login_count'
        , title: '登录次数'
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
           if(event == 'setPassword'){
                iframe_open({
                        "url": "{{$route['allPasswordView']}}"
                        , "title": `修改${obj.data.account}密码`
                        , "data": 'id=' + obj.data.id
                        , "size" : ["500px","300px"]
                });
           }
    }
 
@endsection
