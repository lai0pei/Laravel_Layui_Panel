@extends("common.view")
@section('form')
<div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">触发行为</label>
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
        <label class="layui-form-label admin-form-required">触发时间</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" maxlength="6" lay-verify="required" value="{{$data['data']['created_at']??''}}">
        </div>
    </div>
      <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">触发原因</label>
        <div class="layui-input-block">
            <textarea style="height:20rem"  class="layui-textarea">{{$data['data']['content']??''}}</textarea>
        </div>
    </div>

</div>
@endsection
