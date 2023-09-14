@extends("common.add")
@section('form')
<div>
 <div class="layui-form-item">
                    <label class="layui-form-label admin-form-required">上级</label>
                    <div class="layui-input-block">
                        <select name="p_id" lay-verify="required" lay-search lay-filter="on_select">
                            <option value="">请选择上级</option>
                            @foreach($data['menu'] as $v)
                            <option value="{{$v['id']}}" rank="{{$v['rank']}}">{{$v['title']}}</option>
                            @endforeach
                        </select>
                        <p class="admin-form-tips">请选择上级菜单或顶级菜单 ( 目前最多支持三级菜单 )</p>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label admin-form-required">名称</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="title" placeholder="请输入菜单名称" maxlength="20" lay-verify="required">
                        <p class="admin-form-tips">请填写菜单名称 ( 如：系统管理 )，建议字符不要太长，一般 4-6 个汉字</p>
                    </div>
                </div>

                <div class="layui-form-item auth">
                    <label class="layui-form-label admin-form-required">识别</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="auth_sig" placeholder="请输入识别符" maxlength="20">
                        <p class="admin-form-tips">请填写菜单识别符 ( 如：admin, system )，建议字符不要太长，一般 1-20 个英文字</p>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">图标</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="iconPicker" name="icon" class="myIcon" placeholder="选择图标" lay-event="iconPicker" value="layui-icon-circle-dot">
                        <p class="admin-form-tips">选择 菜单图标</p>
                    </div>
                </div>
                <div class="layui-form-item auth">
                    <label class="layui-form-label">模块</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="href" placeholder="请输入菜单模块">
                        <p class="admin-form-tips">对应 Route 里面 模块名称 或是 链接</p>
                    </div>
                </div>
</div>
@endsection
@section('form_js')
      form.on('select(on_select)', function(data) {
            rank = $(data.elem).find(':selected').attr('rank');
            if (rank == 0) {
                $('.auth').css("display", "none");
            } else {
                $('.auth').css("display", "block");
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


