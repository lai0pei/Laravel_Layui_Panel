@extends("common.view")
@section('form')
<div>
    <div class="layui-form-item">
        <input type="hidden" class="layui-input" name="id" value="{{$data['data']['id']}}">
        <label class="layui-form-label admin-form-required">账号名称</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" name="account" placeholder="请输入账号名称" maxlength="20" lay-verify="required" value="{{$data['data']['account']  ?? ''}}">
            <p class="admin-form-tips">请填写账号名称 建议 使用英文字符 ,不要太长, </p>
        </div>
    </div>
     <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">账号昵称</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" name="username" placeholder="请输入账号昵称" maxlength="20" lay-verify="required" value="{{$data['data']['username']??''}}">
            <p class="admin-form-tips">请填写账号昵称</p>
        </div>
    </div>
    
     <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">账号状态</label>
        <div class="layui-input-block">
            <input type="radio" class="layui-input" name="status" title="开启" value="1" {{($data['data']['status'] == 1)?"checked":""}}>
            <input type="radio" class="layui-input" name="status" title="禁用" value="0"  {{($data['data']['status'] == 0)?"checked":""}}>
            <p class="admin-form-tips">默认选择开启</p>
        </div>
    </div>
        <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">权限组合</label>
        <div class="layui-input-block">
        <select name="role_id" lay-verify="required">
         <option >选择组合</option>
           @foreach($data['role'] as $v)
           <option value="{{$v['id']}}" {{($data['data']['role_id'] == $v['id'])?"selected":""}}>{{$v['role_name']}}</option>
           @endforeach
        </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">账号描述</label>
        <div class="layui-input-block">
          <textarea name="description" placeholder="请输入描述" class="layui-textarea"> {{$data['data']['description'] ?? ''}}</textarea>
        <p class="admin-form-tips">描述内容不要过大</p>
        </div>
    </div>

</div>
@endsection


