layui.define(['jquery', 'element', 'layer', 'util', 'theme'], function (exports) {
    'use-strict';
    MOD_NAME = 'admin';
    var $ = layui.$;
    var element = layui.element;
    var layer = layui.layer;
    var util = layui.util;
    var theme = layui.theme;

    var Admin = function () {
        this.menu = {};
        this.tabData = [];
    }


    Admin.prototype.render = async function (api) {
      
        if (api == null) {
            return true;
        }
        let res = await this.post(api);

        if (res.code == 0) {
            return true;
        }

        menu = res.data.menu;
        auth_id = res.data.auth;
        pendo = res.data.pendo;

        that = this;
        let session_tabData = getSession('tabData');
        if (session_tabData != null) {
            that.tabData = JSON.parse(session_tabData);
        }

        //pc
        let top_menu = $('.admin_panel > .layui-header > .layui-layout-left');
        $('.admin_panel > .layui-header > .layui-layout-left > .layui-nav-bar').remove()

        for (const i in menu) {
            top_menu.append(`<li class="layui-nav-item"><a href="#" data-id="${menu[i]['id']}">${menu[i]['title']}</a></li>`);
        }
        //mobile
        let mobile_top_menu = $('.admin-top-menu-mobile .layui-nav-child');
        for (const i in menu) {
            mobile_top_menu.append(`<dd><a href="#" data-id="${menu[i]['id']}">${menu[i]['title']}</a></dd>`);
        }

        element.render('nav', 'admin_header');

        element.on('nav(admin_header)', function () {
            let id = this.getAttribute('data-id');
            if (id == null || id == undefined) {
                return true;
            }
            let title = this.textContent;
            let get_menu = indexMenu(id, menu);
            let nodes = get_menu['children'];
            let left_menu = $('.admin-side-menu');
            $('.admin-side-menu > li').remove();
            $('.admin-top-menu-mobile > .layui-nav-item > a').html(title + '<i class="layui-icon layui-icon-down layui-nav-more"></i>');


            let menuShrink = '';
            if (isMenuShrink()) {
                menuShrink = 'side-menu-shrink';
            }
            for (const i in nodes) {
                let gm = `<li class="admin-menu layui-nav-item layui-nav-itemed">
                <a href="javascript:;" class="admin-menu-text layui-col-space2 ${menuShrink}" data-title="${nodes[i]['title']}">
                <i class="layui-icon ${nodes[i]['icon']}"></i>
                <cite>${nodes[i]['title']}</cite>
                </a>`;
                let cm = `<dl class="layui-nav-child">`;
                let sub_nodes = nodes[i]['children'];
                for (const c in sub_nodes) {
                    cm += `<dd class="admin-sub-menu-text">
                    <a href="javascript:;" class="layui-col-space2 ${menuShrink}" lay-id="${sub_nodes[c]['id']}" data-title="${sub_nodes[c]['title']}" data-href="${sub_nodes[c]['href']}">
                    <i class="layui-icon ${sub_nodes[c]['icon']}"></i><cite>${sub_nodes[c]['title']}</cite></a>
                    </dd>`;
                }
                cm += `</dl></li>`;
                gm += cm;
                left_menu.append(gm);
            }

            element.render('nav', 'admin_left_menu');

            let left_nav_id = getSession('currentTab');
            if (left_nav_id != null) {
                $('.admin-sub-menu-text > a[lay-id=' + left_nav_id + ']').parent('dd').addClass('layui-this');
            }


            if (isPc() && isMenuShrink()) {
                addTipsEffect();
            }
        })

        element.on('nav(admin_left_menu)', function () {
            let href = this.getAttribute('data-href');
            if (href == null) {
                return true;
            }

            let title = this.getAttribute('data-title');
            let id = this.getAttribute('lay-id');

            let isExit = isTabExist(id, that.tabData);

            if (isExit) {
                element.tabChange('admin_tab_menu', id);
                return true;
            }

            let tab = {
                'id': id,
                'title': title,
                "href": href,
            };

            that.tabData.push(tab);
            setSession('tabData', JSON.stringify(that.tabData));
            setSession("currentTab", id);
            that.addTab(tab);
            element.tabChange('admin_tab_menu', id);

        })

        element.on('tab(admin_tab_menu)', function () {
            let id = this.getAttribute('lay-id');
            setSession("currentTab", id);

            if (id == 0) {
                let default_menu = menu[0]['id'];
                $('.admin_panel > .layui-header > .layui-layout-left > .layui-nav-item > a[data-id=' + default_menu + ']').click();
            } else {
                let top_menu_session = findMenuGid(id, menu);
                $('.admin_panel > .layui-header > .layui-layout-left > .layui-nav-item > a[data-id=' + top_menu_session + ']').click();
                $('.admin-sub-menu-text > a[lay-id=' + id + ']').parent('dd').addClass('layui-this');
            }
            scrollToTab(id);
            return true;
        });

        element.on('tabDelete(admin_tab_menu)', function () {
            let id = $(this).parent('li').attr('lay-id');
            delTabData(id);
        })

        //触发点击 事件
        let tab_data = getSession('tabData');
        let currentTab = getSession('currentTab');
        let currentPendo = getSession('pendo');

        let homeTab = {
            id: 0,
            href: res.data.home,
            title: '<i class="layui-icon layui-icon-home"></i>'
        }

        that.addTab(homeTab);

        if (tab_data == null || JSON.parse(tab_data).length == 0) {
            let default_menu = menu[0]['id'];
            $('.admin_panel > .layui-header > .layui-layout-left > .layui-nav-item > a[data-id=' + default_menu + ']').click();
            element.tabChange('admin_tab_menu', 0);
        } else {
            let tabData = JSON.parse(tab_data);
            let valid = [];
            if (currentPendo != pendo) {
                for (const i in tabData) {
                    if (tabData[i] == null) {
                        continue;
                    }

                    if (auth_id.includes(parseInt(tabData[i]['id']))) {
                        valid.push(tabData[i]);
                    }
                }
                if (valid.length == 0) {
                    currentTab = 0;
                } else if (!valid.includes(parseInt(currentTab))) {
                    currentTab = valid[valid.length - 1]['id'];
                }

                setSession("pendo", pendo);
                setSession('tabData', JSON.stringify(valid));
                setSession('currentTab', currentTab);
            } else {
                valid = tabData;
            }

            for (const i in valid) {
                that.addTab({ id: valid[i]['id'], href: valid[i]['href'], title: valid[i]['title'] })
            }
            element.tabChange('admin_tab_menu', currentTab);
        }

        $('.admin_tab_header > .layui-tab-title').on('wheel', function (e) {
            this.scrollLeft += e.originalEvent.wheelDelta;
        })
        theme.setDayNight();

        setTimeout(function(){
            $('.admin-loader').css('display','none');
        },1000);
    }

    var setSession = function (key, value) {
        sessionStorage.setItem(key, value);
    }

    var getSession = function (key) {
        return sessionStorage.getItem(key);
    }

    var isTabExist = function (id, tabData) {
        let tm = tabData;
        if (tm == null) {
            return false;
        }

        for (const i in tm) {
            if (tm[i]['id'] == id) {
                return true;
            }
        }
        return false;
    }

    var delTabData = function (id) {
        if (id != undefined || id != null) {
            for (i in that.tabData) {
                if (id == that.tabData[i]['id']) {
                    that.tabData.splice(i, 1);
                    setSession('tabData', JSON.stringify(that.tabData));
                    return true;
                }
            }
        }
    }

    var findMenuGid = function (id, menu = []) {
        for (const g in menu) {
            for (const p in menu[g]['children']) {
                for (const c in menu[g]['children'][p]['children']) {
                    if (id == menu[g]['children'][p]['children'][c]['id']) {
                        return menu[g]['id'];
                    }
                }
            }
        }
        return 0;
    }

    var indexMenu = function (id, menu) {
        for (const g in menu) {
            if (menu[g]['id'] == id) {
                return menu[g];
            }
        }
    }
    Admin.prototype.responsive = function () {
        let admin_panel = $('.admin_panel');
        let isShrink = sessionStorage.getItem('isShrink');
        if ($(window).width() < 970) {
            admin_panel.addClass('mobile');
            admin_panel.removeClass("pc");
            Admin.prototype.Shrink();
        } else {
            admin_panel.addClass('pc');
            admin_panel.removeClass("mobile");
            if (isShrink == 1) {
                Admin.prototype.Shrink();
            } else {
                Admin.prototype.Unshrink();
            }
        }
    }

    Admin.prototype.deleteCurrentTab = function () {
        let currentTab = getSession('currentTab');
        if (currentTab != null && currentTab != 0 && currentTab != undefined) {
            element.tabDelete('admin_tab_menu', currentTab);
            delTabData(currentTab)
        }
    }

    Admin.prototype.deleteCurrentAllTab = function () {
        let tabData = JSON.parse(getSession('tabData'));
        if (tabData.length != 0) {
            for (const i in tabData) {
                element.tabDelete('admin_tab_menu', tabData[i]['id']);
            }
        }
        that.tabData = [];
        setSession('tabData', JSON.stringify([]));
    }

    Admin.prototype.addTab = function (options) {
        element.tabAdd('admin_tab_menu', {
            title: options.title
            , content: `<iframe data-id=${options.id}  src="${options.href}" frameborder="0" class="admin_iframe"></iframe>`
            , id: options.id
            , change : true
        });
    }

    Admin.prototype.post = async function (url, data) {
        return $.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(data),
            dataType: 'json',
            contentType: 'application/json',
            async: true,
        }).then(function (data) {
            return data;
        })

    }

    Admin.prototype.Toggle = function () {
        if (isMenuShrink()) {
            this.Unshrink();
        } else {
            this.Shrink();
        }
    }

    Admin.prototype.Shrink = function () {
        let is_shrink = $('.admin_panel');
        let rightSide = $('.admin_panel > .layui-header, .admin_panel > .layui-body');
        let leftSide = $('.admin_panel > .layui-side, .admin_panel > .layui-side > .layui-side-scroll');
        let leftMenu = $('.admin-side-menu > li > a');
        let leftSubMenu = $('.admin-side-menu > li > dl > dd > a');
        let shrinkIcon = $('.menuShrink > li > a > i');

        rightSide.addClass('menu-shrink-width');
        leftSide.addClass('side-shrink-width');
        leftMenu.addClass('side-menu-shrink');
        leftSubMenu.addClass('side-menu-shrink');
        shrinkIcon.attr('class', "layui-icon layui-icon-spread-left");
        is_shrink.addClass("Shrink");
        if (isMobile()) {
            $('.admin_mask').css("display", "none");
        } else {
            addTipsEffect();
        }
        $('.layui-logo > span').css("display", "none");
        setSession('isShrink', 1);
    }

    Admin.prototype.Unshrink = function () {
        let is_shrink = $('.admin_panel');
        let rightSide = $('.admin_panel > .layui-header, .admin_panel > .layui-body');
        let leftSide = $('.admin_panel > .layui-side, .admin_panel > .layui-side > .layui-side-scroll');
        let leftMenu = $('.admin-side-menu > li > a');
        let leftSubMenu = $('.admin-side-menu > li > dl > dd > a');
        let shrinkIcon = $('.menuShrink > li > a > i');

        rightSide.removeClass('menu-shrink-width');
        leftSide.removeClass('side-shrink-width');
        leftMenu.removeClass('side-menu-shrink');
        leftSubMenu.removeClass('side-menu-shrink');
        shrinkIcon.attr('class', "layui-icon layui-icon-shrink-right");
        is_shrink.removeClass("Shrink");

        if (isMobile()) {
            $('.admin_mask').css("display", "block");
        }
        $('.layui-logo > span').css("display", "unset");
        setSession('isShrink', 0);
        removeTipsEffect();
    }


    Admin.prototype.scrollTab = function (dir, distance) {
        let pos = $('.admin_tab_header > .layui-tab-title').scrollLeft();
        if (dir == "right") {
            $('.admin_tab_header > .layui-tab-title').animate({
                scrollLeft: pos + distance
            }, 200);
        } else {
            $('.admin_tab_header > .layui-tab-title').animate({
                scrollLeft: pos - distance
            }, 200);
        }

    }

    Admin.prototype.refreshCurrentTab = function () {
        let src = $('.admin_tab_header > .layui-tab-content > .layui-show > iframe').attr('src');
        $('.admin_tab_header > .layui-tab-content > .layui-show > iframe').attr('src', src);
         refreshTabEffect(getSession('currentTab'));
    }

    var refreshTabEffect = function(currentTab){
        if(currentTab != null && currentTab != 0 ){
            let i = $(`.admin_tab_header > ul > li[lay-id=${currentTab}]`);
            i.prepend('<i class="tmpLoad layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i>');
            setTimeout(function () {
                i.find('i:first').remove()
            }, 800);
        }
    }

    Admin.prototype.clearCache = function () {
        sessionStorage.removeItem('menu');
        sessionStorage.removeItem('tabData');
        sessionStorage.removeItem('currentTab');
    }


    var isMenuShrink = function () {
        return $('.admin_panel').hasClass("Shrink");
    }

    var isMobile = function () {
        return $('.admin_panel').hasClass("mobile");
    }

    var isPc = function () {
        return $('.admin_panel').hasClass("pc");
    }

    var addTipsEffect = function () {
        let q = $('.admin-side-menu a');
        q.bind('mouseenter', function () {
            let title = this.getAttribute('data-title');
            layer.tips(title, this, { time: 500 });
        });
    }

    var removeTipsEffect = function () {
        let q = $('.admin-side-menu a');
        q.unbind("mouseenter");
    }

    var scrollToTab = function (tabId) {

        if (tabId == 0) {
            return true;
        }

        let scrollBarWidth = $('.admin_tab_header > .layui-tab-title').width();
        let pos = $('.admin_tab_header > .layui-tab-title').scrollLeft();

        let tabData = JSON.parse(getSession('tabData'));
        let index = 1;
        for (const i in tabData) {
            if (tabData[i]['id'] == tabId) {
                index = parseInt(i) + 1;
                break;
            }
        }

        let tabInnerWidth = $('.admin_tab_header > .layui-tab-title > li:nth-child(2)').width();
        let leftPadding = $('.admin_tab_header > .layui-tab-title > li:nth-child(2)').css('padding-left').split('px')[0];
        let rightPadding = $('.admin_tab_header > .layui-tab-title > li:nth-child(2)').css('padding-right').split('px')[0];
        let tabWidth = tabInnerWidth + parseInt(leftPadding) + parseInt(rightPadding) + 1;
        let firstHomeIcon = $('.admin_tab_header > .layui-tab-title > li').width() + 1;


        let tabDist = (index * tabWidth) + firstHomeIcon;
        let nowiam = scrollBarWidth + pos;
        if (tabDist > nowiam) {
            that.scrollTab("right", tabDist - nowiam);
        } else if (nowiam > tabDist) {
            if ((nowiam - tabDist) > (scrollBarWidth * 0.7)) {
                that.scrollTab("left", nowiam - tabDist);
            }

        }

    }
    
    var success = function(msg){
        layer.msg(msg, { icon: 6 });
    }

    var fail = function(msg){
        layer.msg(msg, { icon: 5 });
    }

    util.event('lay-event', {
        menuShrink: function () {
            Admin.prototype.Toggle();
        },
        leftPage: function () {
            Admin.prototype.scrollTab("left", 450);
        },
        rightPage: function () {
            Admin.prototype.scrollTab("right", 450);

        },
        bigMask: function () {
            Admin.prototype.Shrink();
        },
        delOneTab: function () {
            Admin.prototype.deleteCurrentTab();
        },
        delAllTab: function () {
            Admin.prototype.deleteCurrentAllTab();
        },
        refreshThis: function () {
            i = $(this).find('i');
            i.addClass('layui-anim layui-anim-rotate layui-anim-loop');
            Admin.prototype.refreshCurrentTab();
            setTimeout(function () {
                i.removeClass('layui-anim layui-anim-rotate layui-anim-loop');
            }, 1000);
            success('刷新完毕');
        },
        refreshAllTab : function(){
            let srcs = $('.admin_tab_header > .layui-tab-content > .layui-tab-item> iframe');
            for(i of srcs){
                $(i).attr('src',$(i).attr('src'));
                let id = $(i).attr('data-id');
                if(id > 0){
                refreshTabEffect(id);
                }     
            }
            success('刷新完毕');
        }
    });

    Admin.prototype.responsive();
    $(window).resize(function () {
        Admin.prototype.responsive();
    })
    exports(MOD_NAME, new Admin());
});


