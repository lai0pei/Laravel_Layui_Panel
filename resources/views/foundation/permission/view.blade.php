@extends('common.view')
@section('form')
<div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">菜单名称</label>
        <div class="layui-input-block">
            <select name="menu_id" id="menu_id" lay-verify="required" lay-search ay-filter="menu_select">
                <option value="">请选择</option>
                @foreach($data['menu'] as $v)
                <option value="{{$v['id']}}" sig="{{$v['auth_sig'] ?? ''}}" {{($data['data']['menu_id'] == $v['id'])?"selected":""}}>{{$v['title']}}</option>
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
            <input type="text" class="layui-input" name="title" placeholder="请输入权限名称" maxlength="20" lay-verify="required" value="{{$data['data']['title'] ?? ''}}">
            <p class="admin-form-tips">请填写权限名称 ( 如：系统添加权限 )</p>
        </div>
    </div>

    <div class="layui-form-item auth">
        <label class="layui-form-label admin-form-required">权限识别</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" name="auth_sub_sig" placeholder="请输入权限识别" maxlength="20" value="{{$data['data']['auth_sub_sig'] ?? ''}}">
        <p class="admin-form-tips" id="menu_sig_tips">请填写菜单识别符 ( 如：admin_add 为 管理员添加, admin_edit 为 管理员编辑, admin_delete 为 管理员删除)</p>        </div>
    </div>
</div>
@endsection
@section('form_js')

    $(document).ready(function(){
         menu_sig = $('#menu_id').find(':selected').attr('sig');
        $('#menu_sig').val(menu_sig);
        $('#menu_sig_tips').text(`请填写菜单识别符 ( 如：${menu_sig}_add 为 添加, ${menu_sig}_edit 为 编辑, ${menu_sig}_delete 为 删除, ${menu_sig}_import 为 导入, ${menu_sig}_export 为 导出)`);
    })

@endsection
