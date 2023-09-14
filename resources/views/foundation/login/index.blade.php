@extends("base")
@section('head')
<style>
    html {
        background-color: #f2f2f2;
    }

       body {
            background-repeat: no-repeat;
            background-color: whitesmoke;
            background-size: 100%;
            height: 100%;
            background: url("/foundation/img/bg.svg");
        }

    .login {
        width: 375px;
        margin: 0 auto;
        padding: 15rem 0;
    }

    .login .title {
        text-align: center;
        margin-bottom: 10px;
        font-weight: 300;
        font-size: 30px;
        color: #000;
        padding: 20px;
    }

    .login .layui-input-block {
        margin-left: 0;
    }

    .login .layui-form-item {
        margin-bottom: 15px;
    }

    .login .layui-input {
        padding-left: 36px;
    }

    .login label {
        position: absolute;
        z-index: 1;
        line-height: 38px;
        height: 38px;
        width: 38px;
        text-align: center;
    }

    .login .layui-form {
        padding: 20px;
    }

    .captcha {
        margin-left: 10px;
        text-align: right;
    }

    .captcha img {
        height: 38px;
        cursor: pointer;
    }

</style>
@endsection
@section('body')
<body>
    <div class="layui-container login">
        <div class="bg"></div>
        <form class="layui-form" onsubmit="event.preventDefault();">
            <div class="layui-form-item title">后台搭建模版</div>
            <div class="layui-form-item">
                <label><i class="layui-icon layui-icon-username"></i></label>
                <div class="layui-input-block">
                    <input type="text" name="account" lay-verify="required|account" maxlength="8" placeholder="账号名称" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label lay-event="reveal" style="cursor:pointer;"><i class="layui-icon layui-icon-password"></i></label>
                <div class="layui-input-block">
                    <input type="password" name="password" id="pass" lay-verify="required|pass" placeholder="账号密码" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layui-icon layui-icon-vercode"></label>
                        <input type="text" name="vercode" placeholder="请填写验证码" class="layui-input" lay-verify="required" >
                    </div>
                    <div class="layui-col-xs5">
                        <div class="captcha">
                            <img src="{{$route['captcha']}}" id="captcha" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit="" lay-filter="login" style="background : #65a8da"><i class="layui-icon layui-icon-right"></i>点击登录</button>
            </div>


        </form>

    </div>
</body>

@endsection
@section('js')
<script>
    layui.use(['jquery', 'form', 'util', 'layer','admin'], function() {
        var $ = layui.jquery;
        var form = layui.form;
        var util = layui.util;
        var layer = layui.layer;
        var admin = layui.admin;
        let url = "{{$route['login']}}";

        form.on('submit(login)', function(data) {
            let res = post_request(url, data.field, this);
            if (res.code == 1) {
                admin.clearCache();
                setTimeout(() => {
                    window.location.reload();
                }, SUCCESS);
            }else{
                $('#captcha').click();
            }
        })

        $('#captcha').click(function() {
            captcha = "{{$route['captcha']}}";
            captcha += '?t=' + Math.random();
            $(this).attr('src', captcha)
        });


        util.event('lay-event', {
            reveal: function() {
                if ($('#pass').attr('type') == 'text') {
                    $('#pass').attr('type', 'password');
                } else {
                    $('#pass').attr('type', 'text');
                }

            }
        })
    })

</script>
@endsection
