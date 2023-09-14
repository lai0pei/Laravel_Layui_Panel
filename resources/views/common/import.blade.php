@extends("base")
@section('body')
<body>
    <form class="layui-form" onsubmit="event.preventDefault();" lay-filter="importForm">
        <div class="admin-form-box">
            <div class="admin-form-container">
                @yield('form')
            </div>
        </div>
        <div class="admin-form-submit">
            <button type="button" class="layui-btn layui-btn-sm bg-default" lay-submit="" lay-filter="submit"><i class="layui-icon layui-icon-ok"></i>确认导入</button>
        </div>
    </form>
</body>
@endsection
@section('js')
@include('common.util')
<script>
    layui.use(['jquery', 'form'], function() {
        var $ = layui.jquery;
        var form = layui.form;

        form.on('submit(submit)', function(data) {
            let res = post_request("{{$route['importAction']}}", data.field, this);
            if (res.code == 1) {
                parent.window.tableReload();
            }

        })
        
        @yield('form_js')
    })
</script>
@yield('extra_js')
@endsection
