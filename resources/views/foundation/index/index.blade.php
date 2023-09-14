@extends("base")
@section('head')
<title>layout 管理系统大布局 - Layui</title>
<script src="/foundation/js/admin/pace.min.js"></script>
@endsection
@section('body')
<body class="layui-layout-body">
    <div class="admin-loader"><div class="loader-inner"></div></div>
    <div class="layui-layout layui-layout-admin admin_panel">
        <div class="layui-header">
            <ul class="layui-nav menuShrink ">
                <li class="layui-nav-item lay-unselect" lay-event="menuShrink" lay-unselect>
                    <a href="javascript:;"><i class="layui-icon layui-icon-shrink-right"></i></a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-left pc_menu" lay-filter="admin_header">
            </ul>
            <ul class="layui-nav admin-top-menu-mobile mobile_menu" lay-filter="admin_header">
                <li class="layui-nav-item">
                    <a href="javascript:;"></a>
                    <dl class="layui-nav-child">
                    </dl>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right pc_menu">
                <li class="layui-nav-item" lay-unselect>
                    <a href="#" lay-event="refreshThis"><i class="layui-icon layui-icon-refresh"></i></a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="#" lay-event="clearCache"><i class="layui-icon layui-icon-delete"></i></a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="#" lay-event="setTheme"><i class="layui-icon layui-icon-theme"></i></a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="#" lay-event="themeScheme" id="themeScheme"></a>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">{{$data['username']}}</a>
                    <dl class="layui-nav-child">
                        <dd><a href="#" lay-event="chpass">修改密码</a></dd>
                        <dd><a href="#" lay-event="logout">{{__('template.logout')}}</a></dd>
                    </dl>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right mobile_menu">
                <li class="layui-nav-item">
                    <a href="javascript:;">{{__('template.setting')}}</a>
                    <dl class="layui-nav-child">
                        <dd><a href="#" lay-event="refreshThis">{{__('template.refresh')}}</dd>
                        <dd><a href="#" lay-event="clearCache">{{__('template.clear_cache')}}</dd>
                        <dd> <a href="#" lay-event="setTheme">{{__('template.theme')}}</a></dd>
                        <dd><a href="#" lay-event="themeScheme">{{__('template.day_night')}}</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">{{$data['username']}}</a>
                    <dl class="layui-nav-child">
                        <dd><a href="">后台模版</a></dd>
                        <dd><a href="#" lay-event="logout">{{__('template.logout')}}</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
        <div class="layui-side">
            <div class="layui-side-scroll">
                <div class="layui-logo"><span>{{$data['title']}}</span></div>
                <ul class="layui-nav layui-nav-tree admin-side-menu" lay-filter="admin_left_menu">
                </ul>
            </div>
        </div>
        <div class="layui-body">
            <div class="layui-tab admin_tab_header" lay-allowClose="true" lay-filter="admin_tab_menu">
                <ul class="layui-tab-title">
                </ul>
                <ul class="admin-tab-control">
                    <li class="layui-icon layui-icon-prev tab-prev" lay-event="leftPage"></li>
                    <li class="layui-icon layui-icon-next tab-next" lay-event="rightPage"></li>
                    <li class="layui-icon tab-down">
                        <ul class="layui-nav">
                            <li class="layui-nav-item">
                                <a href="javascript:;"> </a>
                                <dl class="layui-nav-child">
                                    <dd><a href="javascript:;" lay-event="refreshAllTab">刷新所有页面</a></dd>
                                    <dd><a href="javascript:;" lay-event="delOneTab">{{__('template.tab_close')}}</a></dd>
                                    <dd><a href="javascript:;" lay-event="delAllTab">{{__('template.tab_close_all')}}</a></dd>
                                </dl>
                            </li>
                        </ul>
                    </li>

                </ul>
                <div class="layui-tab-content"></div>
            </div>
        </div>
        <div class="admin_mask" lay-event="bigMask"></div>
    </div>
</body>
@endsection
@section('js')
<script>
    layui.use(['jquery', 'admin', 'util', 'layer'], function() {
        var $ = layui.jquery;
        var admin = layui.admin;
        var layer = layui.layer;
        var util = layui.util;

        admin.render('{{$route["getMenu"]}}');

        util.event('lay-event', {
            logout: function() {
                lay_confirm("{{__('template.to_confirm',['title'=>__('template.logout')])}}", function() {
                    post_request('{{$route["logout"]}}', [])
                    admin.clearCache();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
            }
            , clearCache: function() {
                lay_confirm("{{__('template.to_confirm',['title'=>__('template.clear_cache')])}}", function() {
                    post_request('{{$route["clearCache"]}}', [])
                    admin.clearCache();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
            }
            , chpass: function() {
                iframe_open({
                    "url": "{{$route['setPasswordView']}}"
                    , "title": `修改密码`
                    , "data": []
                    , "size": ["500px", "300px"]
                });
            }
        });
    });

</script>
@endsection
