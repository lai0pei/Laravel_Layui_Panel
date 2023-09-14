@extends("common.add")
@section('form')
<div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">菜单名称</label>
        <div class="layui-input-block">
            <select name="menu_id" lay-verify="required" lay-search lay-filter="menu_select">
                <option value="">请选择</option>
                @foreach($data['menu'] as $v)
                <option value="{{$v['id']}}" sig="{{$v['auth_sig'] ?? ''}}">{{$v['title']}}</option>
                @endforeach
            </select>
            <p class="admin-form-tips">赋予菜单相应的权限</p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">菜单识别</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" id="menu_sig" value="" disabled>
            <p class="admin-form-tips">菜单识别, 添加权限时 必要</p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">权限名称</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" name="title" placeholder="请输入权限名称" maxlength="20" lay-verify="required">
            <p class="admin-form-tips">请填写权限名称 ( 如：系统添加权限 )</p>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">权限识别</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" name="auth_sub_sig" placeholder="请输入权限识别" maxlength="20">
            <p class="admin-form-tips" id="menu_sig_tips">请填写菜单识别符 ( 如：add 为 添加, edit 为 编辑, delete 为 删除, import 为 导入, export 为 导出)</p>
        </div>
    </div>
</div>
@endsection
@section('form_js')
  
    form.on('select(menu_select)', function(data){
        menu_sig = $(data.elem).find(':selected').attr('sig');
        $('#menu_sig').val(menu_sig);
        $('#menu_sig_tips').text(`请填写菜单识别符 ( 如：${menu_sig}_add 为 添加, ${menu_sig}_edit 为 编辑, ${menu_sig}_delete 为 删除, ${menu_sig}_import 为 导入, ${menu_sig}_export 为 导出)`);
    })

@endsection
