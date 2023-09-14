@extends("common.view")
@section('form')
<div>
    <div class="layui-form-item">
        <input type="hidden" class="layui-input" name="id" maxlength="12" value="{{$data['data']['id']??''}}">
        <label class="layui-form-label admin-form-required">附件名称</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" name="name" placeholder="请输入附件名称" maxlength="12" lay-verify="name|required" value="{{$data['data']['name']??''}}">
            <p class="admin-form-tips">请填写名称 ( 如：Logo2.png)，建议字符不要太长</p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">附件类型</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input"  value="{{$data['data']['s_type']??''}}" disabled>
            <p class="admin-form-tips">文件选择后自动获取</p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">附件大小</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" value="{{$data['data']['size']??''}}" disabled>
            <p class="admin-form-tips">文件选择后自动获取</p>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label admin-form-required">访问路径</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input"  value="域名/{{$data['file_path'].'/'.$data['data']['path']??''}}" disabled>
            <p class="admin-form-tips">Tips : https://www.baidu.cn/{{$data['file_path'].'/'.$data['data']['path']??''}}</p>
        </div>
    </div>
       @if($data['data']['m_type'] == 'image')
                    <div class="layui-form-item">
                        <label class="layui-form-label admin-form-required">图片预览</label>
                        <div class="layui-input-block">
                            <img src="/{{$data['file_path'].'/'.$data['data']['path']??''}}?v={{time()}}" alt="" style="max-height : 20rem;max-width:100%">
                        </div>
                    </div>
         @endif

</div>
@endsection
