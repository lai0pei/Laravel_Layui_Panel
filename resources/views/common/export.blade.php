@extends("base")
@section('body')
<body>
        <form class="layui-form" onsubmit="event.preventDefault();" lay-filter="importForm">
            <div class="admin-form-box">
                <div class="admin-form-container">
                    @yield('form')
                    <div class="layui-form-item">
                        <label class="layui-form-label admin-form-required">文件格式</label>
                        <div class="layui-input-block">
                            <input type="radio" name="format" value="0" title="XLSX" checked>
                            <input type="radio" name="format" value="1" title="CSV">
                            <input type="radio" name="format" value="2" title="ODS">
                            <input type="radio" name="format" value="3" title="XLS">
                            <input type="radio" name="format" value="4" title="HTML">
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-form-submit">
                <button type="button" class="layui-btn layui-btn-sm bg-default" lay-submit="" lay-filter="submit"><i class="layui-icon layui-icon-ok"></i>确认导出</button>
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
            let param = '';
            for(const [k,v] of Object.entries(data.field)){
                param += `${k}=${v}&`;
            }
            window.open("{{$route['exportAction']}}?"+param);
        })

        @yield('form_js')
    })

</script>
@yield('extra_js')
@endsection
