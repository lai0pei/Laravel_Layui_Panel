@extends("base")
@section('body')
<body>
    <form class="layui-form" onsubmit="event.preventDefault();"  lay-filter="viewForm">
        <div class="admin-form-box">
            <div class="admin-form-container">
                @yield('form')
            </div>
        </div>
    </form>
</body>
@endsection
@section('js')
<script>
    layui.use(['jquery', 'form'], function() {
        var $ = layui.jquery;
        var form = layui.form;

        $(document).ready(function(){
            node = $('.layui-form-item input');
            node.prop('disabled',true);
        });

        @yield('form_js')
    })
</script>
@yield('extra_js')
@endsection
