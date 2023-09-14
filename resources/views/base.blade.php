<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/foundation/js/layui/css/layui.css" rel="stylesheet">
    <link href="/foundation/css/admin.css" rel="stylesheet">
    <link href="/foundation/css/theme.css" rel="stylesheet">
    <script src="/foundation/js/layui/layui.js"></script>
    @yield('head')
    @yield('style')
    <script>
        layui.config({
            base: '/foundation/js/modules/'
        });
        layui.use(['jquery'], function() {
            var $ = layui.jquery;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                , }
            });
        })
        layui.use(['theme']);
        var SUCCESS = 1000;
        var FAIL = 1000;
    </script>
    <script src="/foundation/js/admin/layui_utility.js"></script>
</head>
@yield('body')
@yield('js')
</html>
