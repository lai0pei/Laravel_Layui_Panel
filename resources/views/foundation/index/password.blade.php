@extends("base")
@section('body')
<body>
    <form class="layui-form" onsubmit="event.preventDefault();">
        <div class="admin-form-box">
            <div class="admin-form-container">
                <div class="layui-form-item">
                    <label class="layui-form-label admin-form-required">账号密码</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="password" placeholder="请输入账号密码" maxlength="20" lay-verify="required">
                        <p class="admin-form-tips">请填写账号密码 建议 不要太长, </p>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label admin-form-required">再次输入</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="password" placeholder="请再次输入账号密码" maxlength="20" lay-verify="required">
                        <p class="admin-form-tips">账号密码 建议 不要太长, </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="admin-form-submit">
            <button type="button" class="layui-btn layui-btn-sm bg-default" lay-submit="" lay-filter="submit"><i class="layui-icon layui-icon-ok"></i>确认修改</button>
            <button type="reset" class="layui-btn layui-btn-sm layui-btn-primary" ><i class="layui-icon layui-icon-refresh"></i>重置数据</button>
        </div>
    </form>

</body>
@endsection
@section('js')
<script>
    layui.use(['jquery', 'form'], function() {
        var $ = layui.jquery;
        var form = layui.form;

        form.on('submit(submit)', function(data) {
              let res = post_request("{{$route['toSetSelfPassword']}}", data.field, this);
        })
    })

</script>
@endsection
