@extends("common.view")
@section('form')
<div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">组合名称</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" name="title" placeholder="请输入组合名称" maxlength="20" lay-verify="required"  value="{{$data['data']['role_name']??""}}">
            <p class="admin-form-tips">请填写组合名称 ( 如：超级管理组合, 二级管理组合 )，建议字符不要太长</p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">组合状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="开启" {{($data['data']['status'] == 1 )?"checked":""}}>
            <input type="radio" name="status" value="0" title="禁用" {{($data['data']['status'] == 0 )?"checked":""}} >
            <p class="admin-form-tips">是否开始 使用组合状态</p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">组合描述</label>
        <div class="layui-input-block">
          <textarea name="description" placeholder="请输入描述" class="layui-textarea">{{$data['data']['description']}}</textarea>
        </div>
    </div>

</div>
@endsection


