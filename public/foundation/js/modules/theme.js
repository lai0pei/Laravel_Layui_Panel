layui.define(['jquery', 'layer', 'util'], function (exports) {
    'use-strict';
    MOD_NAME = 'theme';
    var $ = layui.jquery;
    var layer = layui.layer;
    var util = layui.util;

    var Theme = function () {}
    
    
    Theme.prototype.setAllIframeTheme = function () {
        let sc = localStorage.getItem("themeScheme");
        if (sc == 1) {
            nightIcon();
            Theme.prototype.Night();
        } else {
            dayIcon();
            Theme.prototype.Day();
        }
    }

    Theme.prototype.setDayNight = function () {
        let sc = localStorage.getItem("themeScheme");
        if (sc == 1) {
            nightIcon();
        } else {
            dayIcon();
        }
    }

    Theme.prototype.setCurrentTheme = function () {
        let sc = localStorage.getItem("themeScheme");
        let theme = null;
        if (sc == 1) {
            theme = nightTheme();
        } else {
            theme = dayTheme();
        }
        setStyle(theme);
    }

    Theme.prototype.Day = function () {
        localStorage.setItem('themeScheme', 0);
        renderTheme(dayTheme());
    }

    Theme.prototype.Night = function () {
        localStorage.setItem('themeScheme', 1);
        renderTheme(nightTheme());
    }

    var renderTheme = function (theme) {
        setStyle(theme);

        let i = document.getElementsByTagName('iframe');
        for (const [k, f] of Object.entries(i)) {
            if (k > 0) {
                try {
                    let innerFrame = f.contentWindow.document.querySelector('html').querySelector('iframe');
                    if (innerFrame != null) {
                        setStyle(theme, innerFrame.contentWindow.document);
                    }
                    setStyle(theme, f.contentWindow.document);
                } catch (e) {
                    console.log(`${f} 链接渲染失败`)
                }
            }
        }
    }

    var setStyle = function (theme, html = document) {
        if(html != null){
            let style = document.createElement('style');
            style.appendChild(document.createTextNode(theme));
            html.head.appendChild(style)
        }
    }

    var dayTheme = function () {
        let id = localStorage.getItem('themeColors') ?? 0;
        let cs = colorScheme();
        let mainTheme = cs[id]['theme'];
        let secondTheme = cs[id]['secondTheme'];

        let layui_light = '#fafafa';
        let layui_deep = '#f7f7f7';

        return `:root{background:${layui_light};--theme-bg:white;--theme-text:darkslategray;--main-theme-color:${mainTheme};--border-and-active:whitesmoke;--theme-second:${secondTheme};--theme-menu:${layui_deep};--theme-sub-menu:white;--header-bg:white;--side-menu:white}`;
    }

    var nightTheme = function () {
        let id = localStorage.getItem('themeColors') ?? 0;
        let cs = colorScheme();
        let mainTheme = cs[id]['theme'];

        let layui_light = '#2f363c';
        let layui_deep = '#23292e';

        return `:root{background:${layui_light};--theme-bg:${layui_deep};--theme-text:#ffffffcf;--main-theme-color:${mainTheme};--border-and-active:#2c2c2c;--theme-second:#393D49;--theme-menu:#20222A;--theme-sub-menu:#0c0f13;--header-bg:${layui_deep};--side-menu:#000000}`;
    }

    var dayIcon = function () {
        $('#themeScheme').html('<i class="layui-icon layui-icon-light"></i>');
    }

    var nightIcon = function () {
        $('#themeScheme').html('<i class="layui-icon layui-icon-star-fill"></i>');
    }


    var colorScheme = function () {
        return [
            {
                "theme": '#2d8cf0',
                "secondTheme": '#ecf5ff',
            },
            {
                "theme": '#009688',
                "secondTheme": '#f0f9eb',
            },
            {
                "theme": '#f6ad55',
                "secondTheme": '#fdf6ec',
            },
            {
                "theme": '#f56c6c',
                "secondTheme": '#fef0f0',
            },
            {
                "theme": '#2f363c',
                "secondTheme": '#e9e9e9',
            },
            {
                "theme": '#a233c6',
                "secondTheme": '#f3f0fe',
            },
            {
                "theme": '#f56cbc',
                "secondTheme": '#fef0fb',
            }
        ]
    }

    util.event('lay-event', {
        setTheme: function () {
            let color = colorScheme();
            let tm = localStorage.getItem('themeColors') ?? 0;
            let html = '<div><div style="display: flex;justify-content: space-between;">';
            for (i in color) {
                if (tm == i) {
                    html += `<span color-id=${i} lay-event="themeChoose" class="layui-icon layui-icon-ok admin_theme_chooser" style="background-color:${color[i]['theme']};margin:0.2rem;"></span>`;
                } else {
                    html += `<span color-id=${i} lay-event="themeChoose" class="admin_theme_chooser" style="background-color:${color[i]['theme']};margin:0.2rem;"></span>`;
                }
            }
            html += "</div></div>";

            layer.open({
                title: '配色方案',
                content: html,
                btnAlign: 'c',
                btn: ['确定'],
            })
        },
        themeChoose: function () {
            let id = this.getAttribute('color-id');
            localStorage.setItem('themeColors', id);
            Theme.prototype.setAllIframeTheme();
            $(this).siblings().removeClass('layui-icon layui-icon-ok');
            $(this).addClass('layui-icon layui-icon-ok')
        },
        themeScheme: function () {
            let sc = localStorage.getItem("themeScheme");

            if (sc == 1) {
                localStorage.setItem('themeScheme', 0);
                dayIcon();
                Theme.prototype.Day();
            } else {
                localStorage.setItem('themeScheme', 1);
                nightIcon();
                Theme.prototype.Night();

            }
        }
    });
    Theme.prototype.setCurrentTheme();
    exports(MOD_NAME, new Theme());
});


