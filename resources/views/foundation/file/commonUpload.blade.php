@extends("base")
@section('style')
<style>
    .main {
        display: flex;
        flex-wrap: wrap;
        padding-top: 12px;
        padding-bottom: 12px;
        align-items: center;
        justify-content: space-evenly;
        overflow: hidden;
        background: #fafafa;
    }

    .block {
        margin-bottom: 12px;
        height: 250px;
        width: 250px;
        border-radius: 5px;
        position: relative;
        background: white;
        cursor: pointer;
    }

    .block .img {
        height: 250px;
        width: 250px;
    }

    .block p {
        color: white;
        text-overflow: ellipsis;
        background: rgb(44 44 44 / 53%);
        text-align: center;
        font-size: 0.75em;
        overflow: hidden;
        width: 250px;
        bottom: 0;
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        position: absolute;
        height: 25px;
        line-height: 25px;
        white-space: nowrap;
    }

    .block .size {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 0.75em;
        background: rgb(44 44 44 / 53%);
        border-bottom-right-radius: 3px;
        border-top-left-radius: 5px;
        padding: 0 4px;
        color: white;
        line-height: 1rem;
    }

    .block .type {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 0.75em;
        background: rgb(44 44 44 / 53%);
        border-bottom-left-radius: 3px;
        border-top-right-radius: 5px;
        padding: 0 4px;
        color: white;
        line-height: 1rem;
    }

    .block-selected {
        background: #8080800f;
    }

    .block-selected p {
        background: var(--main-theme-color) !important;
    }

    .block-selected .size {
        background: var(--main-theme-color) !important;
    }

    .block-selected .type {
        background: var(--main-theme-color) !important;
    }

</style>
@endsection
@section('body')
<body>
    {{-- <form class="layui-form" onsubmit="event.preventDefault();" lay-filter="updateForm"> --}}
    <div class="admin-form-box">
        <div class="admin-form-container">
            <div class="layui-card">
                    <div class="layui-card-header">
                    <span>文件大小 - {{$data['max-size']}}</span>
                    </div>
                    <div class="layui-card-body">
                            <div class="layui-form-item">
                                <a class="layui-btn layui-btn-sm layui-btn-primary" id="film"><i class="layui-icon layui-icon-upload-circle"></i>选择附件</a>
                                <a class="layui-btn layui-btn-sm layui-btn-primary" id="fileSubmit"><i class="layui-icon layui-icon-upload-circle"></i>上传附件</a>
                                <table class="layui-table" style="padding-left:40px" lay-size="sm" lay-filter="table_upload">
                                <colgroup>
                                    <col width="350">
                                    <col width="80">
                                    <col width="80">
                                    <col>
                                    <col width="150">
                                    <col width="80">
                                    <col width="80">
                                </colgroup>
                                    <thead>
                                        <tr>
                                            <th>文件名称</th>
                                            <th>文件大小</th>
                                            <th>文件类型</th>
                                            <th>目录链接</th>
                                            <th>上传进度</th>
                                            <th>附件状态</th>
                                            <th>附件操作</th>
                                        </tr>
                                    </thead>
                                    <tbody id="fileStatus"></tbody>
                                </table>
                            </div>
                    </div>
                </div>
            <br>
            <div class="layui-card">
                <div class="layui-card-header" style="padding:9px 0 10px 16px;">
                    <form class="layui-form" onsubmit="event.preventDefault();" lay-filter="searchList">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="name" placeholder="请输入名称" maxlength="20">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-primary" lay-filter="searchFile" id="searchFile" lay-submit=""><i class="layui-icon"></i> 搜索附件
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="layui-card-body">
                    <div id="fileList"></div>
                    <div id="pagination"></div>
                </div>
            </div>

        </div>
    </div>
    <div class="admin-form-submit">
        <button type="button" class="layui-btn layui-btn-sm bg-default" lay-event="choose"><i class="layui-icon layui-icon-upload-circle"></i>选择附件</button>
        <button type="button" class="layui-btn layui-btn-sm layui-btn-primary" lay-event="close"><i class="layui-icon layui-icon-close"></i>关闭窗口</button>
    </div>
    {{-- </form> --}}
</body>
@endsection
@section('js')
<script>
    layui.use(['jquery', 'form', 'laypage', 'layer', 'util', 'upload', 'element'], function() {
        var $ = layui.jquery;
        var form = layui.form;
        var laypage = layui.laypage;
        var layer = layui.layer;
        var util = layui.util;
        var upload = layui.upload;
        var element = layui.element;

        var count = 0;
        var pageLimit = 20;
        var m_type = "{{$data['m_type']}}";
        var preview_dom = "{{$data['lay-preview']}}";
        var target_dom = "{{$data['lay-target']}}";
        var storage_path = "{{$data['path']}}";
        var ext = "{{$data['lay-ext']}}";
        var max = "{{$data['max-size']}}";

        $(document).ready(function() {
            let res = req_page(1, 1);
            laypage.render({
                elem: 'pagination'
                , limit: pageLimit
                , count: res.count
                , layout: ['count', 'prev', 'page', 'next', 'skip']
                , jump: function(obj) {
                    let res = req_page(obj.curr);
                    obj.count = res.count;
                    build_list(res.data);
                }
            });
        })

        util.event('lay-event', {
            'close': function() {
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            }
            , 'choose': function() {
                  choose_close();
            }
        })


        form.on('submit(searchFile)', function(data) {
            let res = req_page(1, 1, data.field.name);
            laypage.render({
                elem: 'pagination'
                , limit: pageLimit
                , count: res.count
                , layout: ['count', 'prev', 'page', 'next', 'skip']
                , jump: function(obj) {
                    let res = req_page(obj.curr, 20, data.field.name);
                    build_list(res.data);
                }
            });

        })


        function req_page(page = 1, limit = pageLimit, name = '') {
            let res;
            $.ajax({
                type: "POST"
                , async: false
                , dataType: 'json'
                , contentType: 'application/json; charset=utf-8'
                , url: "{{$route['listAction']}}"
                , data: JSON.stringify({
                    'page': page
                    , 'limit': limit
                    , 'searchParams': {
                        'name': name
                        , 'm_type': m_type
                        , 'ext': ext
                    }
                })
                , success: function(data) {
                    res = data;
                }
            });
            return res;
        }

        function build_list(data) {
            $('#fileList').html(`<div style="height:45vh;line-height:45vh;text-align:center;background:#fafafa">无${ext}类型文件, 请上传附件</div>`);
            if (data.length > 0) {
                let html = `<div class="main">`;
                for (i in data) {
                    if (data[i][',_type'] == 'image') {
                        html += `
                                <div class="block" path="${data[i]['path']}"><div class="img" style="background: url(/${storage_path}/${data[i]['path']}) no-repeat center;background-size : contain"></div><p>${data[i]['name']}</p><span class="size">${data[i]['size']}</span><span class="type">${data[i]['s_type']}</span></div>`;
                    } else {
                        html += `
                                <div class="block" path="${data[i]['path']}"><div class="img" style="background: url(/${storage_path}/${data[i]['path']}) no-repeat center;background-size : contain"></div><p>${data[i]['name']}</p><span class="size">${data[i]['size']}</span><span class="type">${data[i]['s_type']}</span></div>`;
                    }
                }
                html += `</div>`;
                $('#fileList').html(html);
                $('.block').click(function() {
                    $('.block').removeClass('block-selected');
                    $(this).addClass('block-selected');
                })
                 $('.block').dblclick(function() {
                    choose_close();
                })
            }
        }


        function choose_close(){
                let path = $('.block-selected').attr('path');
                if(path == null){
                    lay_error('选择一个文件');
                    return false;
                }
                
                if(target_dom != ''){
                    let tmp = window.parent.document.getElementById(target_dom);
                    if(tmp != null){
                        tmp.value = path;
                    }
                }

                if(preview_dom != ''){
                     let check = window.parent.document.getElementById(preview_dom);
                     if(check != null){
                        check.src = `/${storage_path}/${path}`;
                     }
                }
                parent.layer.close(parent.layer.getFrameIndex(window.name));
                
        }


         var uploadListIns = upload.render({
            elem: '#film'
            , multiple: false
            , elemList: $("#fileStatus")
            , url: '{{$route['toUpload']}}'
            , data : {
                "method" : "add",
                "side"  : 0
            }
            , accept: 'file'
            , exts : ext
            , size : max * 1024
            , auto: false //选择文件后不自动上传
            , bindAction: $('#fileSubmit') //指向一个按钮触发上传
            , number : 1
            , choose: function(obj) {
            
                var that = this;
                
                if(that.files != null && Object.keys(that.files).length >= 1){
                    lay_error('最多一个文件');
                    return true;
                }

              
                obj.preview(function(index, file, result) {
                    var size = convert(file.size);
                  
                    var tmp = file.type.split('/');
                    if(m_type != '' && m_type != tmp[0]){
                        lay_error('文件格式不一样1');
                        uploadListIns.config.elem.next()[0].value = '';
                        return true;
                    }

                    var tr = $(['<tr id="upload-' + index + '">'
                        , `<td>${file.name} </td>` 
                        , `<td>${size}</td>`
                        , `<td>${file.type}</td>`
                        , `<td class="url"><span>无</span></td>`
                        , '<td><div class="layui-progress" lay-filter="progress-file-' + index + '" ><div class="layui-progress-bar" lay-percent=""></div></div></td>'
                        , `<td class="status">未上传</td>`
                        , '<td class="del">'
                        , '<button class="layui-btn layui-btn-xs layui-btn-danger file-delete">删除</button>'
                        , '</td>'
                        , '</tr>'
                    ].join(''));

                    that.elemList.append(tr);
                    that.files = obj.pushFile();
                    //删除
                    tr.find('.file-delete').on('click', function() {
                        delete that.files[index]; //删除对应的文件
                        tr.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });
                    element.render('progress'); //渲染新加的进度条组件
                     
                    
                });

                   
            }
            , progress: function(n, elem, e, index) { //注意：index 参数为 layui 2.6.6 新增
                element.progress('progress-file-' + index, n + '%'); //执行进度条。n 即为返回的进度百分比
            },
            done : function(res,index,upload){
                var tr = '#upload-'+index;
                var url = $(tr).find('.url');
                var status = $(tr).find('.status');
                var name  = $(tr).find('.name');
             
                var that = this;
        
                if(res.code == 1){
                  $('.del').html('-');
                   url.html(res.data.path);
                    delete this.files[index];
                    $(status).html('<i class="layui-icon layui-icon-ok-circle" style="color:green;">成功</i>');
                }else{
                    $(status).html('<i class="layui-icon layui-icon-close" style="color:red;">失败</i>');
                    lay_error(res.msg);
                }

                setTimeout(function(){
                     $('#searchFile').click();
                },500);
            },
            error : function(index, upload){
                var tr = '#upload-'+index;
                var status = $(tr).find('.status');
                $(status).html('<i class="layui-icon layui-icon-close" style="color:red;">失败</i>');
                element.progress('progress-file-' + index, 0 + '%');
             
            }
        });
        
          function convert(size) {
            kb = size / 1024;
            if (kb > 1024) {
                mb = kb / 1024;
                return mb.toFixed(2) + "mb";
            }
            return kb.toFixed(2) + "kb";
        }


    })

</script>
@endsection
