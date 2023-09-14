@extends("common.update")
@section('form')
<div>
<div class="layui-form-item">
                    <input type="hidden" class="layui-input" name="id" maxlength="12" value="{{$data['data']['id']??''}}">
                     <input type="hidden" class="layui-input" name="method" maxlength="12" value="full_update">
                    <label class="layui-form-label admin-form-required">上级菜单</label>
                    <div class="layui-input-block">
                        <select name="p_id" lay-verify="required" id="auth_menu" lay-search lay-filter="on_select">
                            @foreach($data['menu'] as $v)
                            <option value="{{$v['id']}}" rank="{{$v['rank']}}" {{$data['data']['p_id'] == $v['id']?"selected":''}}>{{$v['title']}}</option>
                            @endforeach
                        </select>
                        <p class="admin-form-tips">请选择上级菜单或顶级菜单 ( 目前最多支持三级菜单 )</p>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label admin-form-required">菜单名称</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="title" placeholder="请输入菜单名称" maxlength="12" lay-verify="required" value="{{$data['data']['title']??''}}">
                        <p class="admin-form-tips">请填写菜单名称 ( 如：系统管理 )，建议字符不要太长，一般 4-6 个汉字</p>
                    </div>
                </div>

                <div class="layui-form-item auth">
                    <label class="layui-form-label admin-form-required">菜单识别符</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="auth_sig" placeholder="请输入识别符" maxlength="20" value="{{$data['data']['auth_sig']??''}}">
                        <p class="admin-form-tips">请填写菜单识别符 ( 如：admin, system )，建议字符不要太长，一般 1-20 个英文字</p>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">菜单图标</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="iconPicker" class="myIcon" name="icon" placeholder="选择图标" lay-event="iconPicker" value="{{$data['data']['icon']??''}}">
                        <p class="admin-form-tips">选择 菜单图标</p>
                    </div>
                </div>
                <div class="layui-form-item auth">
                    <label class="layui-form-label">菜单模块</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="href" placeholder="请输入菜单模块" value="{{$data['data']['href']??''}}">
                        <p class="admin-form-tips">对应 Route 里面 模块名称 或是 链接</p>
                    </div>
                </div>
                @if(!$data['sub_menu']->isEmpty())
                <div class="layui-form-item auth">
                    <label class="layui-form-label">权限节点</label>
                    <div class="layui-input-block">
                        @foreach($data['sub_menu'] as $k => $v)
                        <input type="checkbox" name="sub_menu" lay-skin="tag" title="{{$v['title']}}" value="{{$v['id']}}" {{($v['status'] == 1)?"checked":""}}>
                        @endforeach
                        <p class="admin-form-tips">权限节点 可选项, 仅开发时用</p>
                    </div>
                </div>
                @endif

</div>
@endsection
@section('form_js')

        $(document).ready(function() {
            rank = $('#auth_menu').find(':selected').attr('rank');
            if (rank == 0) {
                $('.auth').css("display", "none");
            } else {
                $('.auth').css("display", "block");
            }
        });

        form.on('select(on_select)', function(data) {
            rank = $(data.elem).find(':selected').attr('rank');
            if (rank == 0) {
                $('.auth').css("display", "none");
            } else {
                $('.auth').css("display", "block");
            }
        })

        form.on('submit(submit)', function(data) {
            let f = data.field;
            let auth_id = [];
            $("input:checkbox[name='sub_menu']:checked").each(function(i, v) {
                auth_id.push($(this).val());
            });
            f.sub_menu = auth_id;
            let res = post_request("{{$route['updateAction']}}", f,this);
            if (res.code == 1) {
                parent.window.tableReload();
            }
        })
@endsection
@section('extra_js')
<script>
    layui.use(['iconPicker','jquery'],function(){
        var iconPicker = layui.iconPicker;
        $ = layui.jquery;
        iconPicker.render({
            elem: '#iconPicker'
            , type: 'fontClass'
            , search: true
            , page: false
            , limit: 16
            , click: function(data) {
                $('.myIcon').val('layui-icon ' + data.icon)
            }
        , });
})
</script>
@endsection

