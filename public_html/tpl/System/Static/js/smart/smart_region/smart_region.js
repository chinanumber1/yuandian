(function()
{
	if(typeof $=='undefined')return console.error('smart_region:Please load the jQuery');
	ieHandler();
	window['region']=function(_input,_opts)
	{
		var selector,input,nav,list,loader,currentLevel;//input:当前实例化的input,currentLevel:当前选中的级别
		var sets={};//绑定的input
		var input_height= 0,input_width=0;//宽高
		var area={0:{"name":"安徽省","id":1}};//当前选中级别的数据集合
		var opts={level:[1,2,3],url:"",type:"radio"};//level:0国家，1省，2市，3区县
		var html='<div style="display:none;" class="region_box"><ul class="region_box_nav"></ul><div class="region_box_content"><ul class="region_box_content_top" style="display:none;"></ul><ul class="region_box_content_list"></ul><div class="region_box_content_bottom"><a class="region_bottom_confirm" href="javascript:;">确认</a><a class="region_bottom_cancel" href="javascript:;">取消</a></div></div></div>';
		var that=this;
		this.show=show;//显示
		this.hide=hide;//隐藏
		function init()
		{
			input=$(_input);
			opts["url"]=typeof(Think)!="undefined"?Think.U("Home/Public/getAreaList"):"";
			opts["level"]=isset(input.attr("level"))?input.attr("level").split(","):opts["level"];//选用级别
			opts["type"]=isset(input.attr("region-type"))?input.attr("region-type"):opts["type"];//单选地址，多选地址
			opts["confirm"]=isset(input.attr("confirm"))?eval("("+input.attr("confirm")+")"):opts["confirm"];//确认回调
			opts["cancel"]=isset(input.attr("cancel"))?eval("("+input.attr("cancel")+")"):opts["cancel"];//取消回调
			opts["complete"]=isset(input.attr("complete"))?input.attr("complete"):"complete";//是否需要完整填写
			opts["url"]=isset(input.attr("url"))?input.attr("url"):opts["url"];//获取地址列表
			opts=$.extend(opts,_opts?_opts:{});
			input_height=input.outerHeight();
			input_width=input.outerWidth();
			input.attr("readonly","readonly");
			//绑定表单
			var tmp=opts.level.concat(["zip",'tel',"code"]);//opts.level的顺序要和names的顺序一致,用“-”隔开
			var names=input.val()?input.val().split("-"):[];//地名
			for(var i=0;i<tmp.length;i++)
			{
				var st=input.attr("set-"+String(tmp[i]));
				if(isset(st))
				{
					sets[tmp[i]]=$("[name='"+st+"']");
					area[tmp[i]]={"id":getVal(tmp[i]),"name":names[i]?names[i]:""};//读取值
				}
			}
			createBox();
			input.click(function()
			{
				show();
			});
			selector.find(".region_bottom_cancel").click(function()
			{
				hide();
			});
			selector.find(".region_bottom_confirm").click(function()
			{
				confirm();
			});
			input.data("region",that);
		}

		function isset(val)
		{
			return String(typeof(val)).toLowerCase()!="undefined";
		}

		function createBox()
		{
			selector=$(html);
			nav=selector.find(".region_box_nav");
			list=selector.find(".region_box_content_list");
			selector.css({"top":input.offset().top+input_height+10,"left":input.offset().left});
			input.parent().append(selector);
			for(var i=0;i<opts.level.length;i++)
			{
				if(opts.level[i]==0){nav.append('<li level="0">省级</li>')}
				if(opts.level[i]==1){nav.append('<li level="1">区域</li>')}
				if(opts.level[i]==2){nav.append('<li level="2">城市</li>')}
				if(opts.level[i]==3){nav.append('<li level="3">区县</li>')}
			}
			nav.find("li").width((nav.width()/opts.level.length)-1);
			nav.find("li").click(function(eve)
			{
				tabHandler($(eve.target).attr("level"));
			});
		}

		//显示选择面板
		function show()
		{
			selector.css({"top":input.offset().top+input_height+10,"left":input.offset().left});
			tabHandler(currentLevel);
			selector.show();
		}

		//切换选项卡并获取该选项卡下面的所有列表
		function tabHandler(level)
		{
			//默认选择最大级别的
			if(typeof level =="undefined")
			{
				level=toLevel("max");
			}
			var plevel=Number(level)-1;
			//不存在父级则返回
			if(!Boolean(area[plevel])||!Boolean(area[plevel]["id"]))
			{
				return false;
			}
			nav.find(".current").removeClass("current");
			nav.find("[level="+level+"]").addClass("current");
			currentLevel=Number(level);
			getList(area[plevel]['id']);//从服务器端获取列表数据
		}

		//根据父ID查询地址列表
		function getList(pid)
		{
			list.html("");//清空
			loader=$('<li class="region_list_load">正在加载数据...</li>');
			list.append(loader);
			var data={"pid":pid};
			$.post(opts.url,data,getListHandler);
		}

		function getListHandler(data)
		{
			if(Boolean(data)&&Boolean(data["status"])&&Boolean(data["data"]))
			{
				loader.remove();//移除加载提示
				createDom(data["data"]);
			}
			else if(Boolean(data["info"]))
			{
				loader.text(data["info"]);
			}
			else
			{
				loader.text("加载数据错误");
			}
		}

		function createDom(data)
		{
			for(var i=0;i<data.length;i++)
			{
				var tmp,obj=data[i];
				if(opts.type=="radio")
				{
					tmp=$('<li>'+obj["name"]+'</li>');
					tmp.attr("id",obj["id"]);
					tmp.attr("code",obj["code"]);
					tmp.attr("pid",obj["pid"]);
					tmp.attr("level",obj["level"]);
					tmp.attr("zip",obj["zip"]);
					tmp.attr("tel",obj["tel"]);
				}
				if(area[currentLevel])
				{
					if(area[currentLevel]['id']==obj['id'])tmp.addClass("current");
				}
				list.append(tmp);
			}
			if(opts.type=="radio")
			{
				list.find("li").click(radioClick);
			}
		}

		function radioClick(eve)
		{
			var name=$(eve.target).text();//ID
			var id=$(eve.target).attr("id");//ID
			var zip=$(eve.target).attr("zip");//邮编
			var tel=$(eve.target).attr("tel");//电话区号
			var code=$(eve.target).attr("code");//行政代码
			area[currentLevel]=area[currentLevel]?area[currentLevel]:{};
			//当当前的ID发生改变后，其子级的数据将重新赋值
			if(area[currentLevel]['id']!=id)
			{
				for(var i=0;i<opts.level.length;i++)
				{
					if(Number(currentLevel)<Number(opts.level[i]))
					{
						area[opts.level[i]]={};
					}
				}
			}
			area[currentLevel]['id']=id;
			area[currentLevel]['name']=name;
			area["zip"]=isset(zip)?zip:area["zip"];
			area["tel"]=isset(tel)?tel:area["tel"];
			area["code"]=isset(code)?code:area["code"];
			list.find("li.current").removeClass("current");
			$(eve.target).addClass("current");
			//当前级别已经是最小级别（0为最大）则自动确认，否则自动跳转到下一级别
			currentLevel>=toLevel("min")?confirm():tabHandler(currentLevel+1);
		}

		//to级别0为最大级别
		function toLevel(type)
		{
			var num= opts.level[0];
			type=type?type:"max"
			for(var i=0;i<opts.level.length;i++)
			{
				if(type=="min")
				{
					num=Math.max(num,Number(opts.level[i]));
				}
				else
				{
					num=Math.min(num,Number(opts.level[i]));
				}
			}
			return num;
		}

		//确认
		function confirm()
		{
			if(!isComplete())return false;
			if(typeof(opts["confirm"])=="function")
			{
				opts["confirm"](area,that);
			}
			setVal();
			hide();
		}

		//是否选择完整
		function isComplete()
		{
			if(opts['complete']=="complete")
			{
				for(var i=0;i<opts.level.length;i++)
				{
					if(!Boolean(area[opts.level[i]]["id"]))
					{
						tabHandler(opts.level[i]);//跳转到未选择的级别上
						return false;
					}
				}
			}
			return true;
		}

		//设置绑定的值
		function setVal()
		{
			var str="";
			//填充
			for(var i=0;i<opts.level.length;i++)
			{
				var level=opts.level[i];
				var val=area[level]?area[level]["id"]:"";
				var name=area[level]?area[level]["name"]:"";
				if(sets.hasOwnProperty(level))
				{
					var old=sets[level].val();
					sets[level].val(val);
					if(old!=val)sets[level].trigger("change");
					str=str+name+"-";
				}
			}
			// TODO 邮编、行政编码等
			str=str.replace(/\-$/,"");
			input.val(str);
		}

		//获取绑定的值
		function getVal(name)
		{
			if(!sets[name])return undefined;
			return sets[name].val();
		}

		function hide()
		{
			selector.hide();
		}
		init();
	}

	//IE兼容性处理
	function ieHandler()
	{
		if (!Array.prototype.indexOf)
		{
			Array.prototype.indexOf = function(elt /*, from*/)
			{
				var len = this.length >>> 0;
				var from = Number(arguments[1]) || 0;
				from = (from < 0)
					? Math.ceil(from)
					: Math.floor(from);
				if (from < 0)
					from += len;

				for (; from < len; from++)
				{
					if (from in this &&
						this[from] === elt)
						return from;
				}
				return -1;
			};
		}
	}
})()


$(function() {
    $('.region').each(function(index, element) {
		var url=$(element).attr('url');
		var tmp=new region(element,{url:url});
    });
	//图标
	$('.region_icon').each(function(index, element) {
		var ww=Number($(element).width());
        var posx=ww-10+5;//图标10px 5px左边距
		var rp=parseInt($(element).css("padding-right"));
		$(element).css({"background-position":posx+"px center","width":ww-15,"padding-right":15+rp});
    });
	//单击其他区域则自动收起
	$(".region,.region_box").click(function(e)
	{
		e.stopPropagation();
	});
	$(document).click(function()
	{
		$(".region").data("region").hide();
	});
});