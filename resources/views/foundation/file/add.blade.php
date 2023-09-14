@extends("base")
@section('body')
<body>
    <form class="layui-form" onsubmit="event.preventDefault();">
        <div class="admin-form-box">
            <div class="admin-form-container">
                <div class="layui-card">
                    <div class="layui-card-header">
                    <span>文件大小 - {{$data['max_size']}}</span>
                    <span>, 批量上传 - 20个</span>
                    </div>
                    <div class="layui-card-body">
                            <div class="layui-form-item">
                                <a class="layui-btn layui-btn-sm layui-btn-primary" id="film"><i class="layui-icon layui-icon-upload-circle"></i>选择附件</a>
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
                </div>
            </div>
        </div>
        <div class="admin-form-submit">
            <button type="button" class="layui-btn layui-btn-sm bg-default" lay-submit="" id='fileSubmit'><i class="layui-icon layui-icon-ok"></i>开始上传</button>
        </div>
    </form>

</body>
@endsection
@section('js')
<script>
    layui.use(['jquery', 'form', 'upload', 'element','table','layer'], function() {
        var $ = layui.jquery;
        var form = layui.form;
        var upload = layui.upload;
        var element = layui.element;
        var table = layui.table;
        var max_size = "{{$data['max_size']}}";
        var total_count = 0;

       

        var uploadListIns = upload.render({
            elem: '#film'
             ,multiple: true
            , elemList: $("#fileStatus")
            , url: '{{$route['toUpload']}}'
            , data : {
                "method" : "add",
                "side"  : 0
            }
            , auto: false //选择文件后不自动上传
            , bindAction: $('#fileSubmit') //指向一个按钮触发上传
            , accept: 'file'
            , number : 20
            , choose: function(obj) {
             
                var that = this;
                that.files = obj.pushFile();
                obj.preview(function(index, file, result) {
                    var size = convert(file.size);
                    if(file.size  > max_size * 1024 * 1024){
                        lay_error('最大限制 '+max_size+"MB, "+file.name+" 文件, "+size+"超过上传限制");
                        delete that.files[index];
                        uploadListIns.config.elem.next()[0].value = '';
                    }else{
                   
                    var tr = $(['<tr id="upload-' + index + '">'
                        , `<td>${file.name} </td>` 
                        , `<td>${size}</td>`
                        , `<td>${file.type}</td>`
                        , `<td class="url"><span>无</span></td>`
                        , '<td><div class="layui-progress" lay-filter="progress-file-' + index + '" ><div class="layui-progress-bar" lay-percent=""></div></div></td>'
                        , `<td class="status">未上传</td>`
                        ,'<td class="del">'
                        , '<button class="layui-btn layui-btn-xs layui-btn-danger file-delete">删除</button>'
                        , '</td>'
                        , '</tr>'
                    ].join(''));

                    that.elemList.append(tr);
                    
                    //删除
                    tr.find('.file-delete').on('click', function() {
                        delete that.files[index]; //删除对应的文件
                        tr.remove();
                        total_count--;
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });
                    element.render('progress'); //渲染新加的进度条组件
                     total_count++;
                    }
                });

                   
            },
            before : function(){
                that = this;
                if(Object.keys(that.files).length < 1){
                        lay_error('请选择至少一个文件');
                }else{
                    animateThis(document.getElementById('fileSubmit'));
                }
              
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
                   url.html(res.data.path);
                    delete this.files[index];
                    $(status).html('<i class="layui-icon layui-icon-ok-circle" style="color:green;">成功</i>');
                }else{
                    $(status).html('<i class="layui-icon layui-icon-close" style="color:red;">失败</i>');
                    lay_error(res.msg);
                }
              total_count--;
                close();
             
            
            },
            error : function(index, upload){
                var tr = '#upload-'+index;
                var status = $(tr).find('.status');
                $(status).html('<i class="layui-icon layui-icon-close" style="color:red;">失败</i>');
                element.progress('progress-file-' + index, 0 + '%');
                total_count--;
                close();
             
            }
        });

        function close(){
            if(total_count == 0){
                unanimateThis(document.getElementById('fileSubmit'),1000);
                    setTimeout(function(){
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                    parent.window.tableReload();
                },1000);
        }
        }

        function convert(size) {
            kb = size / 1024;
            if (kb > 1024) {
                mb = kb / 1024;
                return mb.toFixed(2) + "mb";
            }
            return kb.toFixed(2) + "kb";
        }

     
    });

  

</script>
@endsection
