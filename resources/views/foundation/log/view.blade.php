@extends("common.view")
@section('form')
<div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">操作行为</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" lay-verify="required" value="{{$data['data']['title']??''}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">访问地址</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" maxlength="6" lay-verify="required" value="{{$data['data']['ip']??''}}">
        </div>
    </div>
       <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">操作账号</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" maxlength="6" lay-verify="required" value="{{$data['data']['admin']??''}}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">操作时间</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" maxlength="6" lay-verify="required" value="{{$data['data']['created_at']??''}}">
        </div>
    </div>
      <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">操作描述</label>
        <div class="layui-input-block">
            <textarea style="height:20rem"  placeholder="请输入内容" class="layui-textarea">{{$data['data']['content']??''}}</textarea>
        </div>
    </div>

</div>
@endsection
