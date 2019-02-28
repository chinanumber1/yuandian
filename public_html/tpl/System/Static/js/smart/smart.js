(function()
{
    if(typeof $=='undefined')return console.error('smart:Please load the jQuery');
    /**********$s对象*********/
    window['smart']=window['$s']=$['smart']=new function()
    {
        var $s=this;
        //配置
        var config= {};

        //初始化
        this.init=function()
        {
            config= typeof window['smart_config']!='undefined'?$.extend(config,window['smart_config']):config;
        }

        //错误提示
        this.error=function (message,time)
        {
            time=$s.isset(time)?time:2000;
            if(typeof config['error']=='function')
            {
                config['error'](message,time);
            }
            else if(typeof layer!='undefined')
            {
                layer.msg(message,{time:time,icon:8});
            }
            else
            {
                alert(message);
            }
        }

        //成功提示
        this.success=function (message,time)
        {
            time=$s.isset(time)?time:2000;
            if(typeof config['success']=='function')
            {
                config['success'](message,time);
            }
            else if(typeof layer!='undefined')
            {
                layer.msg(message,{time:time,icon:9});
            }
            else
            {
                alert(message);
            }
        }

        //询问提示
        this.confirm=function (title,message,yes,no)
        {

            if(typeof config['confirm']=='function')
            {
                config['confirm'](title,message,yes,no);
            }
            else if(typeof layer!='undefined')
            {
                layer.confirm(message,{title:title,btn:['确认','取消']},yes,no);
            }
            else
            {
                window.confirm(title+'：'+message)?(typeof yes=='function'?yes():''):(typeof no=='function'?no():'');
            }
        }

        //加载动画
        this.loading=function (selector)
        {
            return new function()
            {
                var loader=typeof config['loading']=="function"?config['loading'](selector):null;
                this.close=function()
                {
                    if(loader)
                    {
                        loader.close();
                        loader=null;
                    }
                }
            }
        }

        //设置配置
        this.set=function(name,value)
        {
            var path=name.split('.');
            config[path[0]]=loopSet(0,config[path[0]],path,value);
        }

        //循环设置
        function loopSet(layer,data,path,value)
        {
            if(layer+1<path.length)
            {
                if(typeof data=='undefined')return undefined;
                data[path[layer+1]]=loop_set(layer+1,data[path[layer+1]],path,value);
            }
            else
            {
                return value;
            }
            return data;
        }

        //获取配置
        this.get=function(name)
        {
            var path=name.split('.');
            var tmp=config[path[0]];
            for(var i=1;i<path.length;i++)
            {
                if(typeof tmp!='object')return undefined;
                tmp=tmp[path[i]];
            }
            return tmp;
        }

        //解析json格式的数据
        this.parseJson=function (data,successHandler,errorHandler)
        {
            try{data=JSON.parse(data);}catch (e){}
            function tip(reload)
            {
                $s.resJson(data,reload);
            }
            var autoSuccess=Boolean(successHandler)||typeof successHandler=='undefined';
            var autoError=Boolean(errorHandler)||typeof errorHandler=='undefined';
            if(typeof data=='object')
            {
                if(data['status']=='1')
                {
                    typeof successHandler=='function'?successHandler(data['data'],tip):(autoSuccess?$s.resJson(data):'');
                }
                else
                {
                    typeof errorHandler=='function'?errorHandler(data['data'],tip):(autoError?$s.resJson(data):'');
                }
            }
            else
            {
                typeof errorHandler=='function'?errorHandler(undefined,tip):(autoError?$s.resJson(data):'');
            }
        }

        //响应json数据
        this.resJson=function (data,reload)
        {
            try{data=JSON.parse(data);}catch (e){}
            var status,info="请求数据出错！",url,app_list,app_num= 0,tiping=false;
            if(typeof data=='object')
            {
                status=data['status'];
                info=data['info'];
                url=data['url'];
                app_list=data['app_list']?data['app_list']:[];
                reload=typeof reload=='undefined'?(status=="1"):reload;
                if(info)
                {
                    var str=url?info+"，页面即将自动跳转~":info;
                    status=="1"?$s.success(str):$s.error(str);
                    tiping=true;
                    setTimeout(function()
                    {
                        tiping=false;
                        urlHandler();
                    },2000);
                }
                else
                {
                    urlHandler();
                }
                //调用同步地址
                if(app_list.length>0)
                {
                    for(var i=0;i<app_list.length;i++)
                    {
                        var app_url=Boolean(app_list[i].indexOf("?")>-1)?app_list[i]+'&callback=?':app_list[i]+"?callback=?";
                        $.getJSON(app_url,function(syncResult)
                        {
                            app_num++;
                            if(app_num>=app_list.length)
                            {
                                urlHandler();
                            }
                        });
                    }
                }
            }
            else
            {
                $s.error(info);
            }
            //URL跳转
            function urlHandler()
            {
                if(app_num>=app_list.length&&!tiping)
                {
                    if(url)
                    {
                        location.href=url;
                    }
                    else if(reload)
                    {
                        location.reload();
                    }
                }
            }
        }

        //dom绑定ajax
        this.ajaxDom=function (_selector)
        {
            return new function()
            {
                var selector=$(_selector);//绑定的DOM
                var that=this,type,url,targetForm;
                var timelimit,listid,tagName,confirm_msg,verify,validator,beforeHandler,successHandler,errorHandler,data,timer,is_loading=false,myLoading,reload;
                init();
                //初始化
                function init()
                {
                    tagName=selector.prop('tagName').toLowerCase();
                    var href=selector.attr('href');
                    var isPrevent=tagName=='a'&&$s.isset(href)&&href!='javascript:;';
                    if(tagName=="form")
                    {
                        selector.submit(function(event)
                        {
                            event.preventDefault();
                            submit(event);
                        });
                    }
                    else
                    {
                        selector.click(function(event)
                        {
                            if(isPrevent)event.preventDefault();
                            submit(event);
                        });
                    }
                }

                //创建对象
                function create()
                {
                    type=selector.attr('ajax')!=""?selector.attr('ajax'):'post';//默认为post类型
                    var target_form=selector.attr('target-form');
                    //先指定form、所在的form、自身
                    targetForm=target_form?$('.'+target_form):selector.parents('form');
                    targetForm=targetForm.length>0?targetForm:selector;
                    url=selector.attr('url')?selector.attr('url'):selector.attr('href');
                    url=(url&&url!='javascript:;')?url:targetForm.attr('action');
                    timelimit=selector.attr('timelimit');//时间限制
                    listid=$s.isset(selector.attr('listid'));
                    confirm_msg=selector.attr('confirm');
                    verify=$s.isset(selector.attr('verify'));
                    validator=$s.isset(selector.attr('validator'));
                    beforeHandler=selector.attr("ajax-before")?eval("("+selector.attr("ajax-before")+")"):undefined;
                    successHandler=selector.attr("ajax-success")?eval("("+selector.attr("ajax-success")+")"):undefined;
                    errorHandler=selector.attr("ajax-error")?eval("("+selector.attr("ajax-error")+")"):undefined;
                    reload=selector.attr("reload")?eval("("+selector.attr("reload")+")"):undefined;
                }

                //提交第一步
                function submit(event)
                {
                    if(is_loading)return false;//正在提交
                    create();
                    if($s.isset(timelimit)&&parseInt(timelimit)>0)return false;//时间限制中
                    //选择列表ID
                    if(listid&&$s.getListIds(targetForm).length<=0)
                    {
                        $s.error('请选择要操作的记录');
                        return false;
                    }
                    if($s.isset(confirm_msg))
                    {
                        confirm_msg=confirm_msg?confirm_msg:'您是否确认执行此操作？';
                        var title=tagName=="input"?selector.val():(selector.text()?selector.text():selector.attr('title'));
                        title=title?title:"提示";
                        $s.confirm(title,confirm_msg,submit2);
                    }
                    else
                    {
                        submit2();
                    }
                }

                //提交第二步
                function submit2(event)
                {
                    if(typeof event=='object')
                    {
                        event.popup.close();
                    }
                    //在提交之前处理
                    if(typeof(beforeHandler)=="function")
                    {
                        if(!beforeHandler(that))return false;
                    }
                    //验证表单
                    if(validator)
                    {
                        targetForm.trigger("validate");
                        if(!targetForm.data("validator").isFormValid())return false;
                        submit3();
                    }
                    else if(verify)
                    {
                        targetForm.trigger("verify");
                        targetForm.data("verify").verify(function(result)
                        {
                            if(result)submit3();
                        })
                    }
                    else
                    {
                        submit3();
                    }
                }

                //提交第三步
                function submit3()
                {
                    //序列化数据
                    var tags=['input','select','form','button'];
                    var tmp=targetForm.prop("tagName").toLowerCase();
                    if(tags.indexOf(tmp)>-1)
                    {
                        data=targetForm.serialize();
                    }
                    else
                    {
                        data=targetForm.find("input,select,button").serialize();
                    }
                    is_loading=true;
                    timer=setTimeout(function()
                    {
                        myLoading=$s.loading();
                    },800);
                    $.ajax({url:url,success:successCallback,type:type,error:errorCallback,data:data});
                }

                //清除加载层
                function clearLoad()
                {
                    is_loading=false;
                    if(timer)
                    {
                        clearTimeout(timer);
                        timer=null;
                    }
                    if(myLoading)
                    {
                        myLoading.close();
                        myLoading=null;
                    }
                }

                //成功回调
                function successCallback(data)
                {
                    clearLoad();
                    if(typeof(successHandler)=="function")
                    {
                        if(!successHandler(data,that)){return false;}
                    }
                    $s.resJson(data,reload);
                }

                //失败回调,这里指的是Ajax出错,如404
                function errorCallback()
                {
                    clearLoad();
                    if(typeof(errorHandler)=="function")
                    {
                        if(!errorHandler(data,that)){return false;}
                    }
                    $s.resJson(data,reload);
                }
            }
        }

        //获取列表中的所有ID
        this.getListIds=function (selector,name)
        {
            selector=$s.isset(selector)?selector:'body';
            selector=$(selector);
            name=$s.isset(name)?name:'ids[]';
            var ids=[];
            var tmp=selector.prop("tagName").toLowerCase();
            if(tmp=='input')
            {
                selector.filter("[type=checkbox][name='"+name+"']:checked").each(function(index,element)
                {
                    if($(element).val())ids.push($(element).val());
                });
            }
            else
            {
                selector.find("input[type=checkbox][name='"+name+"']:checked").each(function(index,element)
                {
                    if($(element).val())ids.push($(element).val());
                });
            }
            return ids;
        }

        //是否设置值
        this.isset=function (data)
        {
            return typeof data!='undefined'&&typeof data!='null';
        }

        //URL编码
        this.urlencode=function (str)
        {
            return encodeURIComponent(str);
        }

        //移动光标到文本末尾
        this.moveTextend=function (element)
        {
            var obj=$(element).get(0);
            obj.focus();
            var len = obj.value.length;
            if (document.selection)
            {
                var sel = obj.createTextRange();
                sel.moveStart('character',len);
                sel.collapse();
                sel.select();
            }else if(typeof obj.selectionStart == 'number' && typeof obj.selectionEnd == 'number')
            {
                obj.selectionStart = obj.selectionEnd = len;
            }
        }

        //时间限制控件
        this.timeLimit=function(element)
        {
            return new function()
            {
                var timelimit=0,downtip,comtip,timer,type,that=this;
                $(element).data('timelimit', that);
                this.init=init;
                function init()
                {
                    type=$(element).prop("tagName").toLowerCase();
                    timelimit=parseInt($(element).attr("timelimit"));
                    downtip=$(element).attr("downtip");
                    comtip=$(element).attr("comtip");
                    if(timelimit>0)
                    {
                        setText(downtip.replace("$_t",timelimit));
                        timer=setInterval(timerHandler,1000);
                    }
                    else
                    {
                        setText(comtip.replace("$_t",timelimit));
                    }
                }
                init();
                function timerHandler()
                {
                    timelimit--;
                    setText(downtip.replace("$_t",timelimit));
                    $(element).attr("timelimit",timelimit);
                    if(timelimit<=0)
                    {
                        clearInterval(timer);
                        timer=null;
                        $(element).attr("timelimit",timelimit);
                        setText(comtip.replace("$_t",timelimit));
                    }
                }
                function setText(str)
                {
                    if(type=="input"||type=="textarea")
                    {
                        $(element).val(String(str));
                    }
                    else
                    {
                        $(element).text(String(str));
                    }
                }
            }
        }

        //格式化金额字符串
        this.moneyFormat=function(element,val)
        {
            var type=String($(element).prop("tagName")).toUpperCase();
            var left=val;
            var right,str="";
            var dot=val.lastIndexOf(".");
            if(dot>=0)
            {
                left=val.substring(0,dot);
                right=val.substr(dot+1);
            }
            if((left.length%3)>0)
            {
                str=left.substr(0,left.length%3)+",";
                left=left.substr(left.length%3);
            }
            for(var i=0;i<left.length;i++)
            {
                str+=left[i];
                if((i+1)%3==0){str+=",";}
            }
            str=str.replace(/\,?$/,"");
            if(Boolean(right)){str+="."+right};
            Boolean(type=="INPUT"||type=="TEXTAREA")?$(element).val(str):$(element).text(str);
            $(element).attr("data-money",val);
        }

        //搜索控制器
        this.finderController=function(_selector,url,object,before)
        {
            return new function()
            {
                var selector,that=this;
                this.finder_item=finder_item;
                this.finder_start=finder_start;
                init();
                function init()
                {
                    selector=$(_selector);
                    url=Boolean(url)?url:location.href.replace(/\?.*$/,"");
                    //是否有域名
                    if(url.indexOf(location.protocol+"//"+location.host)<=-1)
                    {
                        url=location.protocol+"//"+location.host+url;
                    }
                    selector.each(function(index,element){
                        finder_item(element);
                    });
                }

                function finder_item(element)
                {
                    var jq=$(element);
                    var type=jq.prop("type");//标签类型
                    type=typeof(type)=="undefined"?jq.attr("type"):type;
                    var tag=String(jq.prop("tagName")).toLowerCase();//标签名
                    var tc=typeof(jq.attr("type-json"))!="undefined";//分类控件
                    if(["text","number","password"].indexOf(type)>-1||tag=="textarea")
                    {
                        //keyup13
                        jq.keyup(function(eve)
                        {
                            if(eve.keyCode==13)finder_start();
                        });
                    }
                    else if(type=="button"||tag=="button")
                    {
                        //click
                        jq.click(finder_start);
                    }
                    else if(tag=="select"||["checkbox","radio","hidden"].indexOf(type)>-1)
                    {
                        jq.change(finder_start);
                    }
                    else if(type=="set-value")
                    {
                        jq.click(function()
                        {
                            var set=$("."+$(this).attr("set"));
                            var val=$(this).attr("value");
                            if(set.val()!=val)
                            {
                                set.val(val);
                                finder_start();
                            }
                        });
                    }
                    else if(tc)
                    {
                        jq.bind("type-change",finder_start);
                    }
                    else
                    {
                        jq.bind("finder-start",finder_start);
                    }
                }
                //开始搜索
                function finder_start()
                {
                    var request=url;
                    var param=selector.serialize();
                    //附加的对象
                    if(typeof(object)=="object")
                    {
                        var str= $.param(object);
                        param=Boolean(param)?param+"&"+str:str;
                    }
                    //搜索前执行
                    if(Boolean(before))
                    {
                        if(!before(param,selector,object,url))return false;
                    }
                    //组装URL
                    if(request.indexOf("$P")>-1)
                    {
                        request=request.replace("$P",param);
                    }
                    else
                    {
                        request=Boolean(request.indexOf("?")>-1)?request+'&'+param:request+"?"+param;
                    }
                    if(request!=location.href)
                    {
                        location.href=request;
                    }
                }
            }
        }

        //分类控制器
        this.typeController=function(_selector)
        {
            return new function()
            {
                var selector,listJq,that=this,url,isPost=false;
                init();
                function init()
                {
                    selector=$(_selector);
                    url=selector.attr('url')?selector.attr('url'):config['typeController']['url'];
                    listJq=$('<div class="type_controller_list"></div>');
                    selector.append(listJq);
                    getPath()?getTreeByPath(getPath()):getTree('');
                    //选择分类
                    listJq.on('change','select',function()
                    {
                        $(this).nextAll().remove();
                        setVal();
                        var val=$(this).find('option:selected').val();
                        if(val!='')getTree(val);
                    })
                }

                function getPath()
                {
                    var valJq=selector.find('.type_val_path');
                    if(valJq.length>0&&valJq.val()!="")
                    {
                        return valJq.val();
                    }
                    var selectJq=selector.find('.type_val_select');
                    if(selectJq.length>0&&selectJq.val()!='')
                    {
                        return selectJq.val();
                    }
                    var lastJq=selector.find('.type_val_last');
                    if(lastJq.length>0&&lastJq.val()!="")
                    {
                        return lastJq.val();
                    }
                    var arr=[];
                    for(var i=0;i<10;i++)
                    {
                        var seter=selector.find('.type_val_'+i);
                        if(seter.length>0&&seter.val()!="")
                        {
                            arr[i]=seter.val();
                        }
                    }
                    if(arr.length>0)return arr.join(',');
                }

                function getTreeByPath(path)
                {
                    $.post(url,{path:path},function(result)
                    {
                        if(result&&result['status']=='1')
                        {
                            for(var i=0;i<result['data'].length;i++)
                            {
                                createSelect(result['data'][i]);
                            }
                            setVal();
                        }
                    });
                }

                function getTree(pid)
                {
                    if(isPost)return false;
                    isPost=true;
                    $.post(url,{pid:pid},function(result)
                    {
                        if(result&&result['status']=='1')
                        {
                            if(result['data'].length>0)createSelect(result['data']);
                            setVal();
                        }
                        isPost=false;
                    });
                }

                function createSelect(data)
                {
                    var jq=$('<select pid="'+data[0]['pid']+'"></select>');
                    jq.append('<option value="" >请选择</option>');
                    for(var i=0;i<data.length;i++)
                    {
                        var opt=$('<option value="'+data[i]['id']+'" >'+data[i]['name']+'</option>');
                        if(data[i]['current']=='current')opt.prop('selected',true);
                        jq.append(opt);
                    }
                    listJq.append(jq);
                }

                function setVal()
                {
                    var tmp=[];
                    listJq.find('select').each(function(index,element)
                    {
                        var id=$(element).find('option:selected').val();
                        tmp.push(id);
                        var seter=selector.find('.type_val_'+index);
                        if(seter)
                        {
                            var old=seter.val();
                            seter.val(id);
                            if(old!=id)seter.trigger('change');
                        }
                    });
                    var valJq=selector.find('.type_val_path');
                    if(valJq.length>0)
                    {
                        valJq.val(tmp.join(','));
                        valJq.trigger('change');
                    }
                    var lastJq=selector.find('.type_val_last');
                    if(lastJq.length>0)
                    {
                        var old=lastJq.val();
                        lastJq.val(tmp[tmp.length-1]);
                        if(old!=tmp[tmp.length-1])lastJq.trigger('change');
                    }
                    var selectJq=selector.find('.type_val_select');
                    if(selectJq.length>0)
                    {
                        var tmpId='';
                        for(var i=tmp.length-1;i>-1;i--)
                        {
                            if(tmp[i]!='')
                            {
                                tmpId=tmp[i];
                                break;
                            }
                        }
                        selectJq.val(tmpId);
                    }
                }

            }
        }

        //全选
        this.checkAll=function(element)
        {
            return new function()
            {
                var checkall=$(element).attr("checkall");
                $(element).click(function()
                {
                    $('.'+checkall).prop("checked",$(this).prop("checked"));
                });
                $('.'+checkall).click(function()
                {
                    if($('.'+checkall+':checked').length==$('.'+checkall).length)
                    {
                        $(element).prop("checked",true);
                    }
                    else
                    {
                        $(element).prop("checked",false);
                    }
                });
            }
        }

        //tab选项卡
        this.tabNav=function(element)
        {
            return new function()
            {
                var selector;
                init();
                function init()
                {
                    selector=$(element);
                    var current_dom=selector.attr('current-dom')?selector.attr('current-dom'):selector.find('[show-dom]:first').attr('show-dom');
                    if(typeof current_dom!='undefined')
                    {
                        select(current_dom);
                    }
                    var current=selector.attr('current');
                    if(current)
                    {
                        selector.find('[show]').each(function(index,element3)
                        {
                            var tmp=$(element3).attr('show');
                            (current==tmp)?$(element3).addClass('current'):$(element3).removeClass('current');
                        });
                    }
                    selector.on('click','[show-dom]',function()
                    {
                        var dom=$(this).attr('show-dom');
                        select(dom);
                    });
                }

                function select(dom)
                {
                    selector.find('[show-dom]').each(function(index,element2)
                    {
                        var tmp=$(element2).attr('show-dom');
                        var jq=$("[tab-dom='"+tmp+"']");
                        if(tmp==dom)
                        {
                            jq.show();
                            $(element2).addClass('current');
                        }
                        else
                        {
                            jq.hide();
                            $(element2).removeClass('current');
                        }
                    });
                }
            }
        }
    }();

    /**********下面是绑定到jQuery的快捷方法*********/
    $.fn.moveTextend=function()
    {
        $s.moveTextend(this);
    }

    $.fn.isset=function()
    {
        return $s.isset($(this).val());
    }

    $.fn.getListIds=function()
    {
        return $s.getListIds(this);
    }

    $.fn.checkAll=function()
    {
        return $s.checkAll(this);
    }

    /**********下面是绑定到DOM的属性*********/
    $(function()
    {
        //ajax请求
        $("[ajax]").each(function(index, element) {
            $(element).data('ajaxDom',$s.ajaxDom(element));
        });

        //时间限制
        $('[timelimit]').each(function(index,element){
            $(element).data('timeLimit',$s.timeLimit(element));
        })

        //分类控制器
        $('.type_controller').each(function(index,element){
            $(element).data('typeController',$s.typeController(element));
        });

        //多选默认值
        $("[checkedval]").each(function(index, element)
        {
            var cname=String($(element).attr("name"));
            var checkedval=$(element).attr("checkedval");
            if(cname.indexOf("[]")>-1)
            {
                var arr=checkedval.split(",");
                //多个值
                $(document.getElementsByName(cname)).each(function(index, element2) {
                    if(arr.indexOf($(element2).val())>-1){$(element2).prop("checked",true)};
                });
            }
            else
            {
                //单个值
                if(checkedval==$(element).val()){$(element).prop("checked",true)};
            }
        });

        //下拉菜单默认值
        $("[selectedval]").each(function(index, element)
        {
            var val=$(element).attr("selectedval");
            $(element).find("option").each(function(index2, element2) {
                if($(element2).attr("value")==val)
                {
                    $(element2).prop("selected",true);
                }
            });
        });

        //格式化金额字符串
        $('.money_format').each(function(index, element)
        {
            var val="";
            var type=String($(element).prop("tagName")).toLowerCase();
            Boolean(type=="input"||type=="textarea")?val=$(element).val():val=$(element).text();
            $s.moneyFormat(element,val);
        });

        //全选
        $('[checkall]').each(function(index, element) {
            $s.checkAll(element);
        });

        $('.tab_nav').each(function(index,element)
        {
            $(element).data('tabNav',$s.tabNav(element));
        });

    })

})();