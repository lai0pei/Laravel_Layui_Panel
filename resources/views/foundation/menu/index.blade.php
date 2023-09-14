@extends('common.index')
@section('table')
       cols = [
            [{
                    field: 'id'
                    , title: '菜单编号'
                    , width: 100
                    , align: "center"
                     ,sort : true,
                }
                , {
                    field: 'sort'
                    , title: "权重排序"
                    , width: 120
                    , align: "center"
                    , templet: function(data) {
                        return `<input type="number" class="layui-input text-center" id="menu_sort"  max=1000 min=0 value=${data.sort} data-id=${data.id}>`;
                    }
                    ,sort : true,
                }
                , {
                    field: 'icon'
                    , title: '菜单图标'
                    , width: 80
                    , align: "center"
                    , templet: function(data) {
                        return `<i class="layui-icon ${data['icon']}"></i>`;
                    }
                }
                , {
                    field: 'title'
                    , title: '菜单名称'
                    , width: 200
                    , templet: function(data) {
                        if (data.rank == 1) {
                            return `<span>ㅤ├ㅤ </span>` + data.title;
                        } else if (data.rank == 2) {
                            return `<span>ㅤ├ㅤㅤ├ㅤ</span>` + data.title;
                        } else {
                            return data.title;
                        }
                    }
                }
                , {
                    field: 'href'
                    , title: '路由链接'
                    , width: 200
                    , align: "center"
                    , templet: function(data) {
                        if (data.href == '') {
                            return '#';
                        } else {
                            return data.href;
                        }
                    }
                }
                , {
                    field: 'status'
                    , title: '菜单状态'
                    , width: 150
                    , align: "center"
                    , templet: function(data) {
                        let dis = '';
                        if(data.is_deletable == 0){
                            dis = 'disabled';
                        }
                        return `<input type="checkbox" name="status" id="${data.id}" lay-filter="status" ${dis} lay-skin="switch" lay-text="使用|禁用" ${(data.status == 1)?'checked':''}>`;
                    }
                     ,sort : true,
                }
                ,{
                    field: 'updated_at'
                    , title: '修改时间'
                    , align: "center"   
                    }
                , {
                    fixed: 'right'
                    , toolbar: '#tdToolbar'
                    , title: '操作面板'
                    , width: 270
                    , align: "center"
                }
            ]
        ];

        table.render({
             limit: 30
            , even : true
            , page: true
            , height: 'full-60'
            , escape: false
            , cols: cols
            , done: function() {
                var a = document.querySelectorAll('#menu_sort');

                for (i = 0; i < a.length; i++) {
                    a[i].addEventListener('change', function() {
                        var res = post_request("{{$route['updateAction']}}", {
                            'id': this.getAttribute('data-id')
                            , 'sort': this.value
                            , 'method': 'sort'
                        });
                        if (res.code == 1) {
                            window.tableReload();
                        }

                    })
                }

            }
        });

        form.on('switch(status)', function(obj) {
            var res = post_request("{{$route['updateAction']}}", {
                'id': obj.elem.id
                , 'method': 'status'
            });
             window.tableReload();
        })
@endsection