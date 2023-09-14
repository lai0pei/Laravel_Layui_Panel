function lay_confirm(msg, callback, cancel = "") {
    layer.open({
        content: msg
        , title: "提示"
        , type: 0
        , icon: 7
        , btn: ["确认", "取消"]
        , yes: callback
        , cancel: cancel
        ,
    })
}

function lay_error(msg) {
    layer.msg(msg, { icon: 5, anim: 6 })
}
function lay_alert(msg) {
    layer.alert(msg);
}
function iframe_open(arg) {

    if (arg.data == null) {
        path = arg.url;
    } else {
        path = `${arg.url}?${arg.data}`;
    }
    if (window.screen.width < 769) {
        arg.size = ["100%", "100%"];
    }
    layer.open({
        content: path
        , anim: 0
        , type: 2
        , resize: 2
        , area: arg.size == null ? ["700px", "700px"] : arg.size
        , title: arg.title
        , maxmin: true
        , shadeClose: true
        , btnAlign: 'c'
        , shade: 0.01
    });
}

function post_request(url, data, dom = null, close = 1) {
    let res;
    var $ = layui.jquery;


    $.ajax({
        type: "POST",
        async: false,
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        url: url,
        data: JSON.stringify(data),
        beforeSend: function () {
            animateThis(dom);
        },
        error: function (data) {
            unanimateThis(dom)
        },
        success: function (data) {

            if (data.code == 1) {
                lay_success(data.msg);
                if (close == 1) {
                    setTimeout(function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    }, SUCCESS);
                }
            } else {
                lay_fail(data.msg);
            }
            res = data;
            unanimateThis(dom);
        }
    });

    return res;

}

function get_request(url, data, dom = null, close = 1) {
    let res;
    var $ = layui.jquery;
    $.ajax({
        type: "GET",
        async: false,
        dataType: 'json',
        contentType: 'application/json; charset=utf-8',
        url: url,
        data: data,
        beforeSend: function () {
            animateThis(dom);
        },
        error: function (data) {
            unanimateThis(dom)
        },
        success: function (data) {

            if (data.code == 1) {
                lay_success(data.msg);
                if (close == 1) {
                    setTimeout(function () {
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    }, SUCCESS);
                }
            } else {
                lay_fail(data.msg);
            }
            res = data;
            unanimateThis(dom);
        }
    });
    return res;
}

function lay_success(msg) {
    layer.msg(msg, { icon: 6 });
}

function lay_fail(msg) {
    layer.msg(msg, { icon: 5 });
}

function showAnimate(othis, icon = 'layui-icon-loading-1', time = 500) {
    animateThis(othis, icon);
    unanimateThis(othis, time);
}

function animateThis(othis, icon = 'layui-icon-loading-1') {
    if (othis != null) {
        othis.querySelector('.layui-icon').style.display = "none";
        othis.setAttribute('disabled', true);
        let html = `<i class="admin-loading layui-icon ${icon} layui-anim layui-anim-rotate layui-anim-loop"></i>`;
        othis.insertAdjacentHTML('afterbegin', html);
    }

}


function unanimateThis(othis, time = 1500) {
    if (othis != null) {
        setTimeout(function () {
            othis.querySelector('.admin-loading').remove();
            othis.querySelector('.layui-icon').style.display = "unset";
            othis.removeAttribute('disabled', false);
        }, time);
    }

}


function cacheGet(k) {
    return localStorage.getItem(k);
}

function cacheSet(k, d) {
    localStorage.setItem(k, d);
}

