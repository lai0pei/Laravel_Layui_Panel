@extends("base")
@section('head')
<style>
    .layui-form-item .layui-input-inline {
        max-width: auto !important;
    }

</style>
@endsection
@section('body')
<body class="layui-layout-body">
    <div class="layui-card admin_card">
        <div class="layui-card-body">
            <div>
                <form class="layui-form" onsubmit="event.preventDefault();" lay-filter="website">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            {{$data['config']['website']['config_name']}}
                        </div>
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space20">
                                <div class="layui-col-md6">
                                    <label class="config-label">网站标题</label>
                                    <input type="text" class="layui-input" name="website_title" placeholder="请输入网站标题" value="{{$data['website']['website_title']??''}}" maxlength="20" lay-verify="required">
                                    <p class="admin-form-tips">请填写网站标题 ( 如：员工管理 )，建议字符不要太长，一般 4-6 个汉字</p>
                                </div>
                                <div class="layui-col-md6">
                                    <label class="config-label">系统名称</label>
                                    <input type="text" class="layui-input" name="sys_title" placeholder="请输入系统名称" value="{{$data['website']['sys_title']??''}}" maxlength="20" lay-verify="required">
                                    <p class="admin-form-tips">请填写系统名称 ( 如：管理后台 )，建议字符不要太长，一般 4-6 个汉字</p>
                                </div>
                            </div>
                            <div class="layui-row layui-col-space20">
                                <div class="layui-col-md6">
                                    <label class="config-label">浏览器小图标</label>
                                    <input type="text" class="layui-input" name="website_ico" id="website_ico" placeholder="请输入网站标题" maxlength="20" lay-verify="required" value="{{$data['website']['website_ico']??''}}" disabled>
                                    <p class="admin-form-tips">建议上传 48x48 , 96x96 的ico 文件</p>
                                </div>
                                <div class="layui-col-md2">
                                    <label class="config-label">上传</label>
                                    <button type="button" class="layui-btn bg-default" lay-event="fileUpload" lay-type="" lay-ext="ico" lay-target="website_ico" lay-preview='ico_preview'><i class="layui-icon layui-icon-upload-circle"></i></button>
                                </div>
                                <div class="layui-col-md4">
                                    <label class="config-label">预览</label>
                                    @php
                                        $link = '';
                                        if(!empty($data['website']['website_ico']??'')){
                                            $link = "/".$data['path']."/".($data['website']['website_ico']??'');
                                        }
                                    @endphp
                                    <div><img src="{{$link}}" alt="" id="ico_preview" style="height:60px"></div>
                                </div>

                            </div>
                            <div class="layui-row">
                                <button type="button" class="layui-btn layui-btn-sm bg-default" lay-submit="" lay-filter="website"><i class="layui-icon layui-icon-ok"></i>保存配置</button>
                                <button type="reset" class="layui-btn layui-btn-sm layui-btn-primary"><i class="layui-icon layui-icon-refresh"></i>重置数据</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</body>
@endsection
@section('js')
@include('common.util')
<script>
    layui.use(['jquery', 'table', 'form', 'layer'], function() {
        var form = layui.form;
        var $ = layui.jquery;

        var configApi = "{{$route['sysConfig']}}"

        form.on('submit(website)', function(data) {
            let a = function() {
                post_request(configApi, data.field);
            };
            lay_confirm('是否保存', a);
        })

    })

</script>

@endsection
