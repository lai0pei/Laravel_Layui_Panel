@extends("base")

@section('body')
<body class="layui-layout-body">
    @yield('table_out')
    <div class="layui-card admin_card">
        <div class="layui-card-body">
            <div>
                @yield('table_in')
                <table id="table_id" lay-filter="table_filter"></table>

                <script type="text/html" id="tableToolbar">
                    <div class="layui-btn-container">
                        @if(is_auth($permission.'_add'))
                        <a class="layui-btn layui-btn-normal layui-btn-sm bg-default" lay-event="add"><i class="layui-icon layui-icon-add-circle"></i>添加{{$title}}</a>
                        @endif
                        @yield('topbar')
                        @if(is_auth($permission.'_bulkDelete'))
                        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="batchdelete"><i class="layui-icon layui-icon-delete"></i>批量删除</a>
                        @endif
                        @if(is_auth($permission.'_import'))
                        <a class="layui-btn layui-btn-sm layui-btn-primary" lay-event="import"><i class="layui-icon layui-icon-export"></i>导入{{$title}}</a>
                        @endif
                        @if(is_auth($permission.'_export'))
                        <a class="layui-btn layui-btn-sm layui-btn-primary" lay-event="export"><i class="layui-icon layui-icon-export"></i>导出{{$title}}</a>
                        @endif
                    </div>

                </script>

                <script type="text/html" id="tdToolbar">
                    @if(is_auth($permission.'_view'))
                    <a class="layui-btn layui-btn-sm bg-default" lay-event="view"><i class="layui-icon layui-icon-about"></i>查看</a>
                    @endif
                    @yield('toolbar')
                    @if(is_auth($permission.'_edit'))
                    <a class="layui-btn layui-btn-sm bg-info" lay-event="edit"><i class="layui-icon layui-icon-set-sm"></i>编辑</a>
                    @endif
                    @if(is_auth($permission.'_delete'))
                    <a class="layui-btn layui-btn-sm bg-danger" lay-event="delete"><i class="layui-icon layui-icon-delete"></i>删除</a>
                    @endif

                </script>
            </div>
        </div>
    </div>
</body>
@endsection
@section('js')
<script>
    layui.use(['jquery', 'table', 'form', 'layer', 'element'], function() {
        var $ = layui.jquery;
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        var element = layui.element;
        let frameSize = (function() {
            let size = {};
            size.view_size = ["700px", "700px"];
            size.add_size = ["700px", "700px"];
            size.update_size = ["700px", "700px"];
            size.import_size = ["700px", "700px"];
            size.export_size = ["700px", "700px"];
            return size;
        })();

        table.set({
            elem: "#table_id"
            , toolbar: "#tableToolbar"
            , url: "{{$route['listAction']}}"
            , method: 'POST'
            , toolbar: "#tableToolbar"
            , loading: true
            , cellMinWidth: 90
            , css: 'thead{color:var(--main-theme-color);background:var(--theme-second)}th{font-weight : bold;font-size : 13.5px; text-align: center;}'
            , scrollPos: "fixed"
            , lineStyle: "height : 40px; font-size : 12.5px; "
            , className: "form-table"
            , text: {
                none: "暂无相关{{$title}}数据"
            }
            , title: "{{$title}}"
            , defaultToolbar: [{
                title: '刷新'
                , layEvent: 'lay_refresh'
                , icon: 'layui-icon-refresh'
            }]
        });


        form.on('submit(table_search)', function(data) {
            table.reloadData('table_id', {
                page: {
                    curr: 1
                }
                , where: {
                    searchParams: data.field
                }
            }, 'data');

            return false;
        });
        //上方菜单 
        let topMenu = (function() {
            let view = {};
            view.add = function() {
                iframe_open({
                    "url": "{{$route['addView']}}"
                    , "title": "新增{{$title}}数据"
                    , "data": 'id=-1'
                    , "size": frameSize.add_size
                , });
            }
            view.batchdelete = function() {
                var ids = table.checkStatus('table_id');
                if (ids.data.length == 0) {
                    layer.msg("请至少选择一个{{$title}}删除", {
                        icon: 7
                    });
                    return true;
                }
                var colect_id = [];
                for (const i in ids.data) {
                    colect_id.push(ids.data[i]['id']);
                }
                lay_confirm(`是否删除 ${ids.data.length}行{{$title}}数据?`, function() {
                    post_request("{{$route['deleteBulkAction']}}", {
                        "bulkDelete": colect_id
                    });
                    window.tableReload();
                });
            }
            view.import = function() {
                iframe_open({
                    "url": "{{$route['importView']}}"
                    , "title": "导入{{$title}}数据"
                    , "size": frameSize.import_size
                });
            }
            view.export = function() {
                iframe_open({
                    "url": "{{$route['exportView']}}"
                    , "title": "导出{{$title}}数据"
                    , "size": frameSize.export_size
                });
            }
            view.refresh = function(that) {
                var iclass = $(that).find('i');
                iclass.addClass('layui-anim layui-anim-rotate layui-anim-loop');
                window.tableReload();
                setTimeout(function() {
                    iclass.removeClass('layui-anim layui-anim-rotate layui-anim-loop');
                }, 1000);
            }
            view.extend = function(obj) {
                lay_alert('功能还未添加');
            }
            return view;
        })();
        //右侧菜单
        let sideMenu = (function() {
            let action = {};
            action.update = function(obj) {
                iframe_open({
                    "url": "{{$route['updateView']}}"
                    , "title": "编辑{{$title}}数据"
                    , "data": 'id=' + obj.data.id
                    , "size": frameSize.update_size
                });
            }
            action.view = function(obj) {
                iframe_open({
                    "url": "{{$route['view']}}"
                    , "title": "查看{{$title}}数据"
                    , "data": 'id=' + obj.data.id
                    , "size": frameSize.view_size
                });
            }
            action.delete = function(obj) {
                lay_confirm("是否删除, 此{{$title}}?", function() {
                    post_request("{{$route['deleteAction']}}", {
                        'id': obj.data.id
                    });
                    window.tableReload();
                });
            }
            action.extend = function(obj) {
                lay_alert('功能还未添加');
            }
            return action;
        })();

        //上方菜单
        table.on('toolbar(table_filter)', function(obj) {
            let event = obj.event;

            switch (true) {
                case event == 'add':
                    topMenu.add();
                    break;
                case event == 'batchdelete':
                    topMenu.batchdelete();
                    break;
                case event == 'import':
                    topMenu.import();
                    break;
                case event == 'export':
                    topMenu.export();
                    break;
                case event == 'lay_refresh':
                    topMenu.refresh(this);
                    break;
                default : 
                   topMenu.extend(obj);
            }
        });

        //右侧菜单
        table.on('tool(table_filter)', function(obj) {
            let event = obj.event;
            switch (true) {
                case event == 'edit':
                    sideMenu.update(obj);
                    break;
                case event == 'view':
                    sideMenu.view(obj);
                    break;
                case event == 'delete':
                    sideMenu.delete(obj);
                    break;
                default : 
                    sideMenu.extend(obj);
            }
        });

        window.tableReload = function() {
            table.reloadData('table_id');
        }

        @yield('table')
    })

</script>

@endsection
 