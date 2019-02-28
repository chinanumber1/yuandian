/**
* Alion js for WZ.MOBILE
* 2015-07-24
* update 2015-09-19
*/
var Alion =   function(On_){
    var l = this;
    l.version       =   '0.3.9';
    l.appDir        =   'app/';
    l.debug         =   true;
    l.currentPanel  =   null;
    l.controllers   =   new Array();
    l.controllerFile=   new Array();
    l.views         =   new Array();
    l.config        =   {};
    var lin =   {
            eln     :   null,
            app     :   'index',
            apps    :   {},
            lastPanel:  null,
            go      :   false,
            history :   '',
            cache   :
                {
                    views   :   {},
                    ctrls   :   {},
                    css     :   {},
                },
            get     :   new Array(),
            paths   :   {},
            level   :   new Array(),
            param   :   {},
    };
    lin.stat    =   {
        count   :   {
            click   :   {},
        },
        time    :   60000,
        url     :   ''
    };
    
    // 新进 oEln 
    lin.amtIn   =   function(oEln, sEffect, fEnd, fStart)
    {
        var sEffect    =   (!sEffect)?'right':sEffect;
        var sAmtCls     =   'amt-' + sEffect + '-in';
        var oOld    =   $('.active');
//l.d('准备动画', sEffect, oEln.attr('id'));
        oEln.show()
            .addClass('fixed '+ sAmtCls)
            .one('webkitAnimationStart', function(){
                $(this).addClass('active');
                if(typeof fStart === 'function')
                {
                    fStart();
                }
//l.d('正在动画……', sEffect);
            })
            .one('webkitAnimationEnd', function(){
                // 动画结束
                $(this).addClass('active').removeClass('fixed ' + sAmtCls);
                oOld.removeClass('active').hide();
                if(typeof fEnd === 'function')
                {
                    fEnd();
                }
//l.d('结束动画', sEffect);
            });
    };
    /**
    *  退出当前元素并跳到 oEln 元素
    * @type function
    */
    lin.amtOut  =   function(oEln, sEffect, fEnd, fStart)
    {
        var oOld    =   $('.active');
        var _sAmt   =   oOld.attr('a-amt');
        var sAmtCls =   'amt-' + _sAmt + '-out';
        if(!oEln.hasClass('app'))
        {
            console.error('回到一个无效元素');
            return false;
        }
        
        oEln.show();
//l.d('准备关闭当前界面……', sAmtCls, oEln.attr('id'));
        oOld.addClass('fixed ' + sAmtCls)
            .one('webkitAnimationStart', function(){
                if(typeof fStart === 'function')
                {
                    fStart();
                }
//l.d('关闭当前界面开始');
            })
            .one('webkitAnimationEnd', function(){
                $(this).removeClass('fixed active ' + sAmtCls).hide();
                oEln.addClass('active');
                if(typeof fEnd === 'function')
                {
                    fEnd();
                }
//l.d('关闭当前界面结束');
            });
    };
    
    // 刷新某个控制内部事件
    lin.refresh =   function(sKey)
    {
        if(!l.controllers[sKey])
        {
            return null;
        }
        if(typeof l.controllers[sKey].__init === 'function')
        {
            l.controllers[sKey].__init();
        }
    }

    // loading 界面锁死，不能任何操作所以很busy
    l.busy      =   function(bOn)
    {
        if(bOn == false)
        {
            var oBusy   =   $('.busy');
            if(oBusy.is('table'))
            {
                oBusy.hide();
            }
        }else{
            var oBusy   =   $('.busy');
            if(oBusy.is('table'))
            {
                oBusy.show();
            }else{
                var sBusy   =   '<table width="100%" height="100%" class="busy"><tr><td valign="middle" align="center"><div class="mask"></div><div class="bg"><div class="busy-r"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div><br><span>处理中</span></div></td></tr></table>';
                $(document.body).append(sBusy);
            }
        }
        return l;
    };
    
    // 对话提示
    l.msg       =   function(sStr)
    {
        var oMsg    =   $('.a-message');
        if(!oMsg.is('div'))
        {
            var sMsg    =   '<div class="a-center"><div class="a-message fade absolute-cnter">'+ sStr +'</div></div>';
            $(document.body).append(sMsg);
            oMsg    =   $('.a-message');
        }else{
            oMsg.text(sStr);
            oMsg.removeClass('in');
        }
        oMsg.addClass('in');
        setTimeout(function()
        {
            $('.a-message').removeClass('in');
        },2500);
        return l;
    };
    
    // 自动同步统计
    l.autoStat  =   function()
    {
        if(!l.empty(lin.stat.url))
        {
            $.post(lin.stat.url, lin.stat.count, function(x)
            {
                // 汇报完初始化
                lin.stat.count  =   {};
            });    
        }
        
    };
    // 获取APP路劲
    l.appPath   =   function(){return l.appDir + lin.app + '/';};
    
    // 设置/获取全局配置
    l.c      =   function(sKey, sVal)
    {
        if(l.empty(sVal))
        {
            return l.config[sKey];    
        }else{
            l.config[sKey]  =   sVal;
            return true;
        }
    };
    
    // 统计
    l.stat   =   function(sKey, sType, iNum)
    {
        var iNum    =   (!iNum)?1:iNum;
        var sType   =   (!sType)?'click':sType;
        if(!lin.stat.count[sType])
        {
            lin.stat.count[sType]   =   {};
            lin.stat.count[sType][sKey]    =   iNum;
        }else{
            lin.stat.count[sType][sKey]    +=   iNum;
        }

        if(!l.empty(lin.stat.url) && lin.stat.time == 0)
        {
            l.autoStat();
        }
    };
    
    // 获取$_GET参数
    l.get    =   function(sKey)
    {
        return lin.get[sKey];
    };
    
    // 加载CSS/JS文件 调用 lazyload
    l.load   =   function(sFile, fnCallback)
    {
        var _param  =   sFile.split('?');
        var _aFileInfo   =   _param[0].split('.');
        var _sExt   =   (_aFileInfo[_aFileInfo.length-1]).toLowerCase();
        if (_sExt == "js"){
            ll.js(sFile, fnCallback);
        }else if (_sExt == "css"){
            ll.css(sFile, fnCallback);
        } 
        return l;
    };
    
    // 新建窗口
    l.add   =   function(sPanelId, fnComplete, sEffect)
    {
        var sAPP    =   lin.app;
        if(!lin.panels[sPanelId])
        {                             
            if(lin.level.length == 0){
                sPanelId    =   'index';
            }else{
                console.error('错误#2001', 'Alion().add(sPanelId), 参数 sPanelId 无效, 请预先在config里配置', sPanelId);
                return false;
            }
        }
        if($('#a_' + sPanelId).hasClass('app'))
        {
            // 该面板已经加载过，退出当前窗口

            return true;
        }
        
        if(!lin.panels[sPanelId].view)
        {
            console.error('错误#2003', '[' + l.appDir + lin.app + '/app.js]中panels.'+ sPanelId +'.view 未定义');
            return false;
        }

        var sView   =   l.appDir + lin.app + '/' + lin.panels[sPanelId].view;
        var sCtrl   =   (lin.panels[sPanelId].ctrl)?(l.appDir + lin.app + '/' + lin.panels[sPanelId].ctrl):'';
        var sCss    =   (lin.panels[sPanelId].css)?(l.appDir + lin.app + '/' + lin.panels[sPanelId].css):'';
//lin.level.push(22);
        if(!sEffect)
        {
            // 默认动画
            sEffect =   'right';
        }
        if(lin.level.length == 0)
        {
            // 第一个视图始终是淡入动画
            sEffect =   'fade';
        }

        var _sNewPanel =   '<div class="app" id="a_'+ sPanelId +'" a-amt="' + sEffect + '"></div>';
//l.d('添加', sPanelId);
        lin.eln.append(_sNewPanel);
        var oPanel  =   $('#a_' + sPanelId);
        var oOld    =    $('.active');

        // transition必须间隔才有动画是个BUG，故采用animation
        lin.amtIn(oPanel, sEffect, function(){
            if(oOld.hasClass('app'))
            {
                oOld.removeClass('active').hide();
            }
        }, function(){
            if(oOld.hasClass('app'))
            {
                oOld.css('z-index',1);    
            }
        });
        
        lin.level.push(sPanelId);
        lin.paths[sPanelId] =   lin.level.length;
        
        lin.lastPanel   =   oPanel;

        var oHtml   =   null;
        var _sViewUrl=  sView.replace(/[\.\/\\\?\#\&\=]/ig, '_');
        var _sCtrlUrl=  sCtrl.replace(/[\.\/\\\?\#\&\=]/ig, '_');
        if(!l.empty(sCss))
        {
            l.load(sCss);
        }
        
        // 检查缓冲, 存在就直接使用
        if(lin.cache.views[_sViewUrl])
        {
            sViewHtml=  lin.cache.views[_sViewUrl];
            if(lin.cache.ctrls[_sCtrlUrl])
            {
                sCtrJs  =   lin.cache.ctrls[_sCtrlUrl];
                try
                {
                    oPanel.append(oHtml);
                    oHtml  =    l.render(sViewHtml, sCtrJs, oPanel, sCtrl);
                    oPanel.removeClass('loading');
                    
                    if(typeof fnComplete === 'function')
                    {
                        fnComplete();
                    }
                }  
                catch (e)  
                {  
                    var loc =   e.stack.replace(/Error\n/).split(/\n/)[1].replace(/^\s+|\s+$/, "");
                    var li  =   loc.split(':');
                    var iLineNo =   li[li.length - 2];
                    // #1000 调用controller文件执行时出错，注意清查该文件js
                    console.error('错误#1000', '发生在', sCtrl + " 行 " + iLineNo, '\n----------\n', e.message);
                }
            }else{
                oPanel.append(sViewHtml).removeClass('loading');
            }
            return l;
        }
        
        // 新建
        $.get(sView, function(sHtml)
        {
            sViewHtml    =   sHtml;
            
            lin.cache.views[_sViewUrl]  =   sViewHtml;
            
            // 装载控制器，所以这里会要求控制模块必须有视图
            if(!l.empty(sCtrl))
            {
                $.ajax({
                    method  :   'get'   ,
                    url     :   sCtrl,
                    dataType:   'text',
                    success :   function(sCtrJs)
                    {
                        // 加载外部js成功
                        sCtrJs  =   sCtrJs.replace(/\.\s*controller\s*\(/ig, '.controller("'+ sCtrl +'", ');
                        lin.cache.ctrls[_sCtrlUrl]  =   sCtrJs;
                        try  
                        {  
                            oHtml  =    l.render(sViewHtml, sCtrJs, oPanel, sCtrl);
                            if(typeof fnComplete === 'function')
                            {
                                fnComplete();
                            }
                        }  
                        catch (e)  
                        {  
                            var loc =   e.stack.replace(/Error\n/).split(/\n/)[1].replace(/^\s+|\s+$/, "");
                            var li  =   loc.split(':');
                            var iLineNo =   li[li.length - 2];
                            // #010 调用controller文件执行时出错，注意清查该文件js
                            console.error('错误#1000', '发生在', sCtrl + " 行 " + iLineNo, '\n----------\n', e.message);
                        }
                    },
                    
                });
            }else
            {
                oHtml  =    l.render(sViewHtml, null, oPanel, sView);
            }
        });
        return l;
    };
    // 打开新panel界面
    l.go    =   function()
    {
        var sPanelId=   '';
        var jData   =   null;
        var bHref   =   false;
        var iEffect =   0;
        // 窗口动画
        var aEffect =   ['right', 'left', 'fade'];
        for(var vi in arguments)
        {
            var _arg    =   arguments[vi];
            switch(typeof(_arg))
            {
                case 'string':
                    sPanelId    =   _arg;
                break;
                case 'number':
                    iEffect     =   _arg;
                break;
                case 'object':
                    jData       =   _arg;
                break;
                case 'boolean':
                    bHref       =   _arg;
                break;
            }
        }
        var sEffect =   aEffect[iEffect];
        if(!sPanelId)
        {
            console.error('错误#2000', 'Alion().go(sPanelId), 参数 sPanelId 无效', sPanelId);
            return false;
        }
        
        if(!lin.panels[sPanelId])
        {
            console.error('错误#2002', 'Alion().go(sPanelId), sPanelId不在有效配置项中', sPanelId);
            return false;
        }
        if(jData !== null)
        {
            lin.param[sPanelId] =   jData;
        }else{
            lin.param[sPanelId] =   {};
        }
        var _sOldAmt    =   sEffect;
        var _sCurrentId =   l.current();
        var oOld    =   $('.active');
        if(oOld.length == 1)
        {
            _sOldAmt    =   oOld.attr('a-amt');
        }
        if(bHref)
        {
            _sCurrentId =   (oOld.attr('id')).substr(2);
        }else{
            if(sPanelId == _sCurrentId)
            {
                // 已经在当前面板
                return false;
            }
        }
        if(l.empty(_sCurrentId))
        {
            _sCurrentId =   'index';
        }
        // todo: bug
        if(lin.paths[sPanelId]){
            // 面板存在,
            var oNew    =   $('#a_' + sPanelId);
            
//l.d('go', sPanelId, lin.paths[sPanelId], lin.paths[_sCurrentId]);
                if(lin.paths[sPanelId] > lin.paths[_sCurrentId])
                {
                    // 前进，打开新窗口
                    lin.amtIn(oNew, sEffect, null, function(){
                        // 触发刷新
                        lin.refresh(sPanelId);
                    });
                }else{
                    // 向后，关闭当前窗口
                    lin.amtOut(oNew, sEffect, null, function(){
                        // 触发刷新
                        lin.refresh(sPanelId);
                    });
                }
            lin.lastPanel   =   oNew;
        }else{
            l.add(sPanelId, null, sEffect);
        }
        
        lin.go  =   true;
        if(bHref !== true){
            location.href = location.origin + location.pathname + location.search + '#'+sPanelId;
        }
    };
    // 回到首页
    l.home  =   function()
    {
        location.href = location.origin + location.search;
        return location.href;
    };
    
    
    // 实例化控制器
    l.controller  =   function(sCtrJsFile, sKey, oModule)
    {
        l.controllers['_last_'] =   l.controllers[sKey]   =   new oModule();
        lin.refresh(sKey);
        l.controllerFile[sKey]  =   sCtrJsFile;
        return sKey;
    };
    // 获取a.go传递的参数
    l.g =   function(sKey)
    {
        if(!lin.param[sKey])
        {
            return null;
        }else{
            return lin.param[sKey];
        }
    }
    // 初始化
    l.init  =   function()
    {
        if(On_){
            var $On_ = $('#' + On_ );
            if($On_.length > 0)
            {
                if(!$On_.hasClass('alion'))
                {
                    $On_.addClass('alion');
                    lin.eln =   $On_;
                }
            }else{
                console.error('错误#0005 不存在<* id="'+On_+'">元素');
                return false;    
            }
        }else{
            console.error('错误#0007 var a = new Alion(id) 参数id不能为空 ');
            return false;
        }
        
        if(l.empty(lin.eln)){console.error('错误#0006 无法找到alion元素');return false;}
        var url = location.search;
        var strs    =   new Array();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
                lin.get[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
            }
        }
        if("ontouchstart" in document.documentElement) {$(document.documentElement).addClass("touch");}

        lin.app   =   l.get('a') || 'index';
        var _sAppCfg    =   l.appDir + lin.app + '/js/app.js?v='+ new Date().getTime();
        $.ajax({
            url :   _sAppCfg,
            method : 'get',
            dataType : 'text',
            success : function(sCfg)
            {
                if(!l.empty(sCfg))
                {
                    eval(sCfg);
                }
            },
            error : function (e){
                console.error('错误#0004', '没有找到基本配置文件【' + _sAppCfg + '】', e.status, e.statusText);
                console.info('默认跳转到app/index');
                _sAppCfg    =   l.appDir + 'index/js/app.js?v='+ new Date().getTime();
                lin.app     =   'index';
                $.ajax({
                    url :   _sAppCfg,
                    method : 'get',
                    dataType : 'text',
                    success : function(sCfg)
                    {
                        if(!l.empty(sCfg))
                        {
                            eval(sCfg);
                        }
                    },
                    error : function (e){
                        console.error('错误#0005', '没有找到默认APP【' + _sAppCfg + '】', e.status, e.statusText);
                    }
                });
            }
        });
        $(window).bind('hashchange', function()
        {
            var sPanelId   =   location.hash.substr(1);
            if(!lin.go)
            {
                sPanelId    =   l.empty(sPanelId)?'index':sPanelId;
                l.go(sPanelId, null, true);
            }
            lin.go  =   false;
        });
    
    };
    
    // 开始执行，仅执行一次
    l.run     =   function(oCfg, fnInit)
    {
        if(lin.eln.length == 0)
        {
            console.error('错误#0003 请先绑定一个元素作为主容器 a = Alion(容器ID)');
            return false;
        }

        // 构建路由
        if(oCfg.panels)
        {
            lin.panels  =   oCfg.panels;
        }

        // 统计任务
        if(oCfg.stat)
        {
            lin.stat  =   oCfg.stat;
            if(!l.empty(lin.stat.url) && lin.stat.time > 100)
            {
                lin.stat.timer  =   setInterval(l.autoStat, lin.stat.time);
            }
        }
        // 加载用户定义配置
        if(oCfg.config)
        {
            l.config    =   oCfg.config;
        }
        var _sFirst =   location.hash.substr(1);
        if(!lin.panels[_sFirst])
        {
            l.add('index');
        }else{
            l.add(_sFirst);
        }
        // 执行用户定义方法
        if(typeof fnInit === 'function')
        {
            fnInit();
        }
        return l;
    };
    
    // 获取当前面板
    l.current   =   function()
    {
        return location.hash.substr(1);
    };
    
    l.compile   =   function(sHtml, sCtrKey){
        var oEln    =   $(sHtml);
        var fCtr    =   l.controllers[sCtrKey];
        if(typeof fCtr !== 'object')
        {
            fCtr    =   l;
        }
        $('[a-hover]', oEln).each(function(){
            var eln =   $(this);
            if($(document.documentElement).hasClass("touch"))
            {
                // 模拟按下样式
                eln
                    .on('touchstart', function(e){
                        var sHoverClass =   eln.attr('a-hover');
                        if(l.empty(sHoverClass)){return null;}
                        if(e.type == 'touchstart') {
                            //e.stopImmediatePropagation();
                            //e.preventDefault();
                        }
                        eln.toggleClass(sHoverClass);
                    })
                    .on('touchend', function(e)
                    {
                        var sHoverClass =   eln.attr('a-hover');
                        if(l.empty(sHoverClass)){return null;}
                        eln.removeClass(sHoverClass);
                    });
            }else{
                return false;
            }
        });
        
        // 模拟点击
        $('[a-click]', oEln).click(function(){
            var eln =   $(this);
            // 统计
            var sStKey  =   eln.attr('a-stat');
            if(!l.empty(sStKey))
            {
                l.stat(sStKey);
            }
            var sClick  =   eln.attr('a-click');
            var _sTest  =   sClick.replace("(",";//");
            // 已定义的方法可被调用
            var _sTestPublic    =   'typeof ' + _sTest;
            var sType   =   eval(_sTestPublic);
            var sExcute =   '';
            if(sType === 'function')
            {
                sExcute=    sClick;
            }else{
                if(fCtr !== null)
                {
                    // 模块内部方法
                    var _sTestCtr   =   'typeof fCtr.' + _sTest;
                    if(eval(_sTestCtr) === 'function')
                    {
                        sExcute=    'fCtr.' + sClick;
                    }else{
                        // 未定义的方法
                        console.error('错误#1002', 'a-click="', sClick, '" 方法未定义 \n-----------\n', eln);
                    }
                }
            }
            
            if(!l.empty(sExcute))
            {
                try
                {
                    var xResult = eval(sExcute);
                }  
                catch (e)  
                {  
                    var loc =   e.stack.replace(/Error\n/).split(/\n/)[1].replace(/^\s+|\s+$/, "");
                    var li  =   loc.split(':');
                    var iLineNo =   li[li.length - 2];
                    // #010 调用controller文件执行时出错，注意清查该文件js
                    console.error('错误#1001', '发生在', _sCtrJsFile + " 行 " + iLineNo, '\n-----------\n', e.message, e);
                }
            }
        });
        return oEln;
    };
    
    // 渲染模版
    l.render    =   function(sHtml, sCtrJs, oPanel, _sCtrJsFile)
    {
        var oHtml   =   $(sHtml);
        var fCtr    =   null;
        if(oPanel)
        {
            oPanel.append(oHtml);
            var sCtrKey =   null;
            if(!l.empty(sCtrJs))
            {
                sCtrKey    =   eval(sCtrJs);
                fCtr    =   l.controllers[sCtrKey]; 
            }
            l.compile(oPanel, sCtrKey);

        }else{
            console.error('错误#1004', "渲染面板时没有提供主容器对象");
            return false;
        }
        
        
        return oHtml;
    };

    // 调试信息，同 console.log() 方法
    l.d     =   function()
    {
        if(l.debug == true){console.log.apply(console, arguments)};
    };
    
    // 是否为空
    l.empty   =   function(xVar)
    {
        if(!xVar){return true;}
        if(typeof(xVar) ==='undefined'){return true;}
        switch(xVar)
        {
            case 'boolean':
                return xVar === false;
            break;
            case 'array':
                return xVar.length == 0;
            break;
            default:
                return xVar == '';
            break;
        }
    };
    
    // Thanks 
    if(navigator.userAgent.indexOf('MSIE') >= 0){console.info("On, welcome to Alion v" + l.version);}else{console.info("%cOn, welcome to Alion v" + l.version,'color:#0051A2;font-weight:bold;border-bottom:1px solid #34C4ED;padding:1px;font-size:14px;');}
    // 初始化
    l.init();
    return l;
};var a =   new Alion('wzm');