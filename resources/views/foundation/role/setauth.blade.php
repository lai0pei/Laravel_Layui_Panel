@extends("base")
@section('body')
<body>
    <form class="layui-form" onsubmit="event.preventDefault();">
        <div class="admin-form-box">
        <input type='hidden' name="id" value="{{$data['data']['id']??''}}">
            <div class="admin-form-container">
                @foreach($data['auth'] as $v)
                @if(!empty($v['permission']))
                <div class="layui-form-item">
                    <label class="layui-form-label admin-form-required">{{$v['title']}}</label>
                    <div class="layui-input-block">
                        @foreach($v['permission'] as $per)
                        <input type="checkbox" class="layui-input" lay-skin="tag" name="auth_id" value="{{$per['id']}}" title="{{$per['title']}}" {{in_array($per['id'],$data['cur_auth'])?"checked":""}}>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach

            </div>
        </div>
        <div class="admin-form-submit">
            <button type="button" class="layui-btn layui-btn-sm bg-default" lay-submit="" lay-filter="submit"><i class="layui-icon layui-icon-ok"></i>立即提交</button>
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
            let f = data.field;
            let auth_id = [];
            $("input:checkbox[name='auth_id']:checked").each(function(i, v) {
                auth_id.push($(this).val());
            });
            f.auth_id = auth_id.join(',');
            let res = post_request("{{$route['toSetAuth']}}", f, this);
            if (res.code == 1) {
                parent.window.tableReload();
            }

        })
    })

</script>
@endsection
