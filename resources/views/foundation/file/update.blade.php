@extends("base")
@section('body')
<body>
    <form class="layui-form" onsubmit="event.preventDefault();" lay-filter="file_form">
        <div class="admin-form-box">
            <div class="admin-form-container">
                <div>
                    <div class="layui-form-item">
                        <input type="hidden" class="layui-input" name="id" maxlength="12" value="{{$data['data']['id']??''}}">
                        <label class="layui-form-label admin-form-required">附件名称</label>
                        <div class="layui-input-block">
                            <input type="text" class="layui-input" name="name" placeholder="请输入附件名称" maxlength="12" lay-verify="name|required" value="{{$data['data']['name']??''}}">
                            <p class="admin-form-tips">请填写名称 ( 如：Logo2.png)，建议字符不要太长</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label admin-form-required">附件类型</label>
                        <div class="layui-input-block">
                            <input type="text" class="layui-input" value="{{$data['data']['s_type']??''}}" disabled>
                            <p class="admin-form-tips">文件选择后自动获取</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label admin-form-required">附件大小</label>
                        <div class="layui-input-block">
                            <input type="text" class="layui-input" value="{{$data['data']['size']??''}}" disabled>
                            <p class="admin-form-tips">文件选择后自动获取</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label admin-form-required">访问路径</label>
                        <div class="layui-input-block">
                            <input type="text" class="layui-input" value="域名/{{$data['file_path'].'/'.$data['data']['path']??''}}" disabled>
                            <p class="admin-form-tips">Tips : https://www.baidu.cn/{{$data['file_path'].'/'.$data['data']['path']??''}}</p>
                        </div>
                    </div>
                    @if($data['data']['m_type'] == 'image')
                    <div class="layui-form-item">
                        <label class="layui-form-label admin-form-required">图片预览</label>
                        <div class="layui-input-block">
                            <img src="/{{$data['file_path'].'/'.$data['data']['path']??''}}?v={{time()}}" alt="" style="max-height : 10rem;max-width:100%">
                        </div>
                    </div>
                    @endif
                    <div class="layui-form-item">
                        <label class="layui-form-label admin-form-required">替换附件</label>
                        <div class="layui-input-block">
                            <a class="layui-btn layui-btn-sm layui-btn-primary" id="film"><i class="layui-icon layui-icon-upload-circle"></i>选择附件</a>
                            <table class="layui-table" style="padding-left:40px" lay-size="sm" lay-filter="table_upload">
                                <colgroup>
                                    <col width="100">
                                    <col width="100">
                                    <col width="180">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>文件名称</th>
                                        <th>上传进度</th>
                                        <th>附件状态</th>
                                    </tr>
                                </thead>
                                <tbody id="fileStatus"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="admin-form-submit">
            <button type="button" class="layui-btn layui-btn-sm bg-default" lay-submit="" lay-filter="fileSubmit" id="fileSubmit"><i class="layui-icon layui-icon-upload-circle"></i>更新{{$title}}</button>
            <button type="reset" class="layui-btn layui-btn-sm layui-btn-primary" ><i class="layui-icon layui-icon-refresh"></i>重置数据</button>
        </div>
    </form>

</body>
@endsection
@section('js')
<script>
    layui.use(['jquery', 'form', 'upload', 'element'], function() {
        var $ = layui.jquery;
        var form = layui.form;
        var upload = layui.upload;
        var element = layui.element;
        var max_size = "{{$data['max_size']}}";
        var file_name = "{{$data['data']['path']??''}}".split('.');
        var uploadApi = 0;

        form.on('submit(fileSubmit)', function(data) {
            if (uploadApi == 0) {
                let res = post_request("{{$route['updateAction']}}", data.field, this);
                if (res.code == 1) {
                    parent.window.tableReload();
                }
            }
        })

        var uploadListIns = upload.render({
            elem: '#film'
            , multiple: false
            , elemList: $("#fileStatus")
            , url: '{{$route['toUpload']}}'
            , data: {
                "method": "update"
                , "id": "{{$data['data']['id']??''}}"
                , 'name': ''
                , "side"  : 0
            , }
            , auto: false //选择文件后不自动上传
            , bindAction: $('#fileSubmit') //指向一个按钮触发上传
            , accept: "file"
            , exts : "{{$data['data']['ext']}}"
            , size: max_size * 1024
            , choose: function(obj) {
                var that = this;
                that.files = obj.pushFile();


                obj.preview(function(index, file, result) {

                    if (Object.keys(that.files).length > 1) {
                        lay_error('最多选择一个附件');
                        delete that.files[index];
                        uploadListIns.config.elem.next()[0].value = '';
                        return true;
                    }

                    var size = convert(file.size);
                    if (file.size > max_size * 1024 * 1024) {
                        lay_error('最大限制 ' + max_size + "MB, " + file.name + " 文件, " + size + "超过上传限制");
                        delete that.files[index];
                        uploadListIns.config.elem.next()[0].value = '';
                    } else {

                        var tr = $(['<tr id="upload-' + index + '">'
                            , `<td>${file.name} </td>`
                            , '<td><div class="layui-progress" lay-filter="progress-file-' + index + '" ><div class="layui-progress-bar" lay-percent=""></div></div></td>'
                            , `<td class="status">未上传</td>`
                            , '</tr>'
                        ].join(''));

                        that.elemList.append(tr);


                        element.render('progress'); //渲染新加的进度条组件
                        uploadApi = 1;
                    }
                });

            }
            , progress: function(n, elem, e, index) { //注意：index 参数为 layui 2.6.6 新增
                element.progress('progress-file-' + index, n + '%'); //执行进度条。n 即为返回的进度百分比
            }
            , before: function() {
                that = this;
                if (Object.keys(that.files).length > 0) {
                    animateThis(document.getElementById('fileSubmit'));
                    var form_data = form.val('file_form');
                  
                    uploadListIns.config.data.name = form_data.name;
                } else {
                    form.on('submit(fileSubmit)', function(data) {
                        if (uploadApi == 0) {
                            let res = post_request("{{$route['updateAction']}}", data.field);
                            if (res.code == 1) {
                                parent.window.tableReload();
                            }
                        }
                    })
                }

            }
            , done: function(res, index, upload) {
                var tr = '#upload-' + index;
                var status = $(tr).find('.status');
                var name = $(tr).find('.name');

                var that = this;

                if (res.code == 1) {
                    delete this.files[index];
                    $(status).html('<i class="layui-icon layui-icon-ok-circle" style="color:green;">成功</i>');
                    parent.window.tableReload();
                } else {
                    $(status).html('<i class="layui-icon layui-icon-close" style="color:red;">失败</i>');
                    lay_error(res.msg);
                }
                lay_success(res.msg);
               close();


            }
            , error: function(index, upload) {
                var tr = '#upload-' + index;
                var status = $(tr).find('.status');
                $(status).html('<i class="layui-icon layui-icon-close" style="color:red;">失败</i>');
                element.progress('progress-file-' + index, 0 + '%');
                lay_error('请重新上传');
                unanimateThis(document.getElementById('fileSubmit'), 1000);
                close();
            }
        });

        function close(){
            setTimeout(function() {
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                }, SUCCESS);

                unanimateThis(document.getElementById('fileSubmit'), 1000);
        }

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
