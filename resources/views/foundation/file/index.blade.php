@extends('common.index')
@section('head')
<style>
.file-img{
    height : 100%;
    max-width : 100% !important;
}
.lay-img{
    height : 100%;
    width : 100%;
    cursor : pointer;
}
.lay-video{
    cursor : pointer;
}
</style>
@endsection
@section('table_in')
<div clas="layui-card-body">
    <fieldset class="table-search-fieldset">
        <legend>搜索信息</legend>
        <div style="margin: 10px 10px 10px 10px">
            <form class="layui-form layui-form-pane" lay-filter="data-search-filter" action="" onsubmit="event.preventDefault();">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">附件名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" autocomplete="off" placeholder="请输入完整名称" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">附件类型</label>
                        <div class="layui-input-inline">
                            <select name="s_type" lay-search lay-filter="on_select">
                                <option value="">请选择</option>
                                @foreach($data['file_type'] as $v)
                                <option value="{{$v['s_type']}}">{{$v['s_type']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                      <div class="layui-inline">
                        <label class="layui-form-label">传输端</label>
                        <div class="layui-input-inline">
                            <select name="side" lay-search >
                                <option value="">请选择</option>
                                <option value="1">前台上传</option>
                                <option value="0">后台上传</option>
                            </select>
                        </div>
                    </div>
                     <div class="layui-inline">
                        <label class="layui-form-label">附件排序</label>
                        <div class="layui-input-inline">
                            <select name="sort" lay-search lay-filter="on_select">
                                <option value="">按编号</option>
                                <option value="0">按大小</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="ext" value="">
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
@section('toolbar')
<a class="layui-btn layui-btn-sm bg-default" lay-event="download"><i class="layui-icon layui-icon-download-circle"></i>下载</a>
@endsection
@section('table_out')
<form method="get" action="" class="download-form" style="display:none"> 
<input type="hidden" name="id" value="" class="download-form-id">
<button type="submit"></button>
</form>
@endsection
@section('table')

frameSize.add_size = ["80%","500px"];
frameSize.update_size = ["70%","700px"];
frameSize.view_size = ["50%","500px"];


       var file_path = "{{$data['file_path']}}";
       cols = [
            [{
                type: 'checkbox', fixed: 'left'
            },
            {
                    field: 'id'
                    , title: '文件编号'
                    , minWidth: 100
                    , align: "center"
                     ,sort : true,
                }
                , {
                    field: 'name'
                    , title: "文件名称"
                    , width: 350
                    , align: "center"
                }
                , {
                    title: '文件预览'
                    , width: 80
                     , align: "center"
                     ,templet : function(d){
                        var host = window.location.hostname;

                        switch(true){
                            case d.m_type == 'image' : 
                            return `<div class="lay-img" layer-src="/${file_path+'/'+d.path}" ><img class="file-img" layer-pid="${d.id}"  src="/${file_path+'/'+d.path}" alt=""></div>`;
                            break;
                            case d.m_type == 'video' : 
                            return `<div class="lay-video" lay-video="/${file_path+'/'+d.path}" lay-name="${d.name}"><span >点击观看</span></div>`;
                            break;
                            default :
                           return '无法预览';
                            break;
                        }

                     } 
                }
                , {
                    field: 's_type'
                    , title: '文件类型'
                    , minWidth: 150
                     , align: "center"
                }
                  , {
                    field: 'ext'
                    , title: '文件后缀'
                    , minWidth: 150
                     , align: "center"
                }
                
                , {
                    field: 'size'
                    , title: '文件大小'
                    , minWidth: 100
                    , align: "center"
                    , sort: true
                    , templet : function(d){
                        return d.size;
                    }
                }
                ,
                {
                    field: 'created_at'
                    , title: '上传时间'
                    , minWidth: 230
                    , align: "center"   
                    }
                , {
                    fixed : 'right'
                    ,toolbar: '#tdToolbar'
                    , title: '操作面板'
                    , minWidth: 350
                    , align: "center"
                }
            ]
        ];

        table.render({
             limit: 30
            , even : true
            , page: true
            , height: 'full-170'
            , escape: false
            , cols: cols
            , done : function(){
            $('.lay-img').mouseenter(function () {
            let img = this.getAttribute('layer-src');
            layer.tips(`<img src="${img}" alt="" style="width:100%;height:100%">`,this,{time : 1000});
            });

        $('.lay-img').each(function(index){
            layer.photos({
            photos: $(this)
            ,anim: 5
        }); 
           })

 
        $('.lay-video').each(function(index){
            video = this.getAttribute('lay-video');
            name = this.getAttribute('lay-name');
            
            $(this).click(function(){
                option = {
                    "url" : video,
                    "size" : ["700px","700px"],
                    "title" : name
                };
                iframe_open(option);
            })
        })
     
    }
});

        sideMenu.extend = function(obj){
               let event = obj.event;
            
            if(event == 'download'){
                $('.download-form-id').attr('value',obj.data.id);
                $('.download-form').attr('action',"{{$route['downloadAction']}}");
                $('.download-form').find('button').click()
            }
        }

@endsection
