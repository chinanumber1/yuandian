/**
 * 验证表单类
 * @xulinyang 1181412758@qq.com
 * @param  {[type]} namespece [description]
 * @return {[type]}           [description]
 */
(function(namespece)
{
	if(typeof $=='undefined')return console.error('smart_verify:Please load the jQuery');
	ieHandler();
	var plugins={};
	/*内置插件****************************/
	//字符长度
	plugins["_length"]=function(parm,data,callback)
	{
		var range=parm.split("-");
		var len=0;
		for(var i=0;i<data.length;i++)
		{
			len+=Boolean(data.charCodeAt(i)>255)?3:1;
		}
		if(range[0]!=""&&len<range[0])
		{
			return callback(false);
		}
		if(range[1]!=""&&len>range[1])
		{
			return callback(false);
		}
		return callback(true);
	}
	//不能为空
	plugins["_nem"]=function(parm,data,callback)
	{
		if(typeof(data)=="object"&&data.length<=0)
		{
			return callback(false);
		}
		else if(data=="")
		{
			return callback(false);
		}
		return callback(true);
	}
	//选择数量
	plugins["_selectnum"]=function(parm,data,callback)
	{
		var range=parm.split("-");
		var len=data["length"]?data.length:0;
		if(range[0]!=""&&len<range[0])
		{
			return callback(false);
		}
		if(range[1]!=""&&len>range[1])
		{
			return callback(false);
		}
		return callback(true);
	}
	//不能包含空格
	plugins["_nsp"]=function(parm,data,callback)
	{
		if(data.match(/\s/))
		{
			return callback(false);
		}
		return callback(true);
	}
	plugins["_regexp"]=function(parm,data,callback)
	{
		var reg=new RegExp(parm,"gi");
		return callback(reg.test(data));
	}
	plugins["_common"]=function(parm,data,callback)
	{
		var obj={
			"mobile":"^1[3-8]+\\d{9}$",//手机号
			"email":'^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+',//邮箱
			"url":"^((https|http|ftp|rtsp|mms)?://)"
			+ "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?"//ftp的user@ 
			+ "(([0-9]{1,3}\\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184 
			+ "|" // 允许IP和DOMAIN（域名）
			+ "([0-9a-z_!~*'()-]+\\.)*" // 域名- www. 
			+ "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\\." // 二级域名 
			+ "[a-z]{2,6})" // first level domain- .com or .museum 
			+ "(:[0-9]{1,4})?" // 端口- :80 
			+ "((/?)|" // a slash isn't required if there is no file name 
			+ "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$",
			"int":"^\\d+$",//整数
			"money":"^[0-9]+\\.{0,1}[0-9]{0,2}$",//金额,保留两位小数
			"a_1":"^[0-9_a-zA-Z]*$"//英文字母和数字下划线
		}
		var reg=new RegExp(obj[parm],"gi");
		return callback(reg.test(data));
	}
	plugins["_compare"]=function(parm,data,callback,field_name,that)
	{
		var com=parm.match(/^[\=\>\<]+/);
		var name=parm.replace(com,"");
		var val=that.selector.find('[name='+name+']').val();
		return callback(eval('(Boolean("'+data+'"'+com+'"'+val+'"))'));
	}
	//远程验证
	plugins["_remote"]=function(parm,data,callback,field_name,that)
	{
		var pdata={};
		pdata[field_name]=data;
		that.show_tip("icon-cloud-upload2 icon_gray","正在进行验证...");
		$.post(parm,pdata,function(data)
		{
			if(Boolean(data)&&Boolean(data["status"]))
			{
				callback(true,data["info"]);
			}
			else if(Boolean(data["info"]))
			{
				callback(false,data["info"]);
			}
			else
			{
				callback(false,"远程验证失败");
			}
		})
	}
	/*内置插件结束**************************/

	namespece.verify=verify;
	var config={}

	/**
	 * 主类
	 * @return {[type]} [description]
	 */
	function verify(_selector,data,_config)
	{
		var selector=$(_selector);//选择区域
		var current,callback,result,list,arr;
		var that=this;
		config=$.extend({
			"error_class":"icon-cancel-circle icon_red",
			"success_class":"icon-checkmark-circle icon_green",
			"tip_class":"icon-info2 icon_gray",
			"container":"",
			"tip":"",
			"auto":true
		},_config);
		this.verify=verify;
		this.verify_once=verify_once;
		this.destroy=destroy;//销毁,解除事件绑定，移除DOM结构
		//初始化
		function init()
		{
			current=0;callback=null;result=false;list={};arr=[];
			//实例化每一项
			for(var key in data)
			{
				list[key]=new verify_item(selector,key,data[key]);
				arr.push(list[key]);
			}
			selector.data("verify",that);
		}
		init();
		function verify(call)
		{
			result=false;current=0;callback=call;
			start();
		}
		//验证单个项目
		function verify_once(oncename,call)
		{
			list[oncename].verify(call);
		}
		function start()
		{
			arr[current].verify(complete)
		}
		function complete(res,info)
		{
			if(!res)
			{
				result=false;
				if(typeof(callback)=="function")callback(result,info);
				return;
			}
			else if(current<arr.length-1)
			{
				current++;
				start();
			}
			else
			{
				result=true;
				if(typeof(callback)=="function")callback(result,info);
				return;
			}
		}
		//销毁实例
		function destroy()
		{
			for(var i=0;i<arr.length;i++)
			{
				arr[i].destroy();
			}
			list={};
			arr=[];
			selector.data("verify",null);
		}
	}

	/**
	 * 验证单元
	 * @param  {[type]} selector
	 * @param  {[type]} field_name
	 * @param  {[type]} data
	 * @return {[type]}
	 */
	function verify_item(selector,field_name,data)
	{
		var jq,checkbox,hidden,tipbox,icon,text,result,current=0,callback,handler_callback;
		var rules=data["rules"];//验证规则
		var that=this;
		var condition=isset(data['condition'])?data['condition']:"exist";//验证条件,exist:存在，value值不为空，must必须验证
		init();
		this.verify=function(call)
		{
			active();
			createTip();
			handler(call);
		};
		this.show_tip=show_tip;
		this.selector=selector;
		this.destroy=destroy;
		
		function init()
		{
			//聚焦
			selector.on("focus","[name='"+field_name+"']",focusHandler);
			//离开事件
			selector.on("blur","[name='"+field_name+"']",blurHandler);
		}

		function focusHandler()
		{
			//触发事件 todo
			//jq.trigger('verify_focus',[jq]);
			active();
			createTip();
			show_tip(config["tip_class"],data["tip"]);
			if(!checkbox)
			{
				selector.on("keyup","[name='"+field_name+"']",handler);
			}
			else
			{
				selector.on("change","[name='"+field_name+"']",handler);
			}
		}

		//离开时移除事件并验证
		function blurHandler()
		{
			if(!checkbox)
			{
				selector.off("keyup","[name='"+field_name+"']",handler);
			}
			else
			{
				selector.off("change","[name='"+field_name+"']",handler);
			}
			handler();
		}

		//是否满足条件
		function meet()
		{
			if(condition=="exist")
			{
				return Boolean(jq.length>0);
			}
			else if(condition=="value")
			{
				var val=getVal();
				return checkbox?val.length>0:val!="";
			}
			else if(condition=="must")
			{
				return true;
			}
		}

		//当前验证对象
		function active()
		{
			jq=selector.find("[name='"+field_name+"']");//当前对象
			checkbox=Boolean(jq.prop("type")=="checkbox"||jq.prop("type")=="radio");//选择框
			hidden=Boolean(jq.prop("type")=="hidden");//隐藏表单
		}

		//创建提示
		function createTip()
		{
			if(!Boolean(config['auto']))return false;
			if(Boolean(config["container"]))
			{
				tipbox=jq.parents(config["container"]).find(config["tip"]);
			}
			else if(jq.length>0)
			{
				if(Boolean(tipbox))tipbox.remove();//清除已有的
				//动态设置
				tipbox=$('<div class="verify_tip verify_name_'+field_name+'"><span class="verify_tip_icon"></span><span class="verify_tip_text"></span></div>');
				var cur=(checkbox||hidden)?jq.parent():jq;
				if(checkbox&&cur.prop("tagName").toLocaleLowerCase()=="label")
				{
					cur=cur.parent();
				}
				cur.parent().append(tipbox);
				var diff=(cur.outerHeight()-tipbox.outerHeight())/2;
				var offset_left=isset(data["offset_left"])?data["offset_left"]:20;
				var offset_top=isset(data["offset_top"])?data["offset_top"]:diff;
				tipbox.css({left:cur.offset().left+cur.outerWidth()+offset_left,top:cur.offset().top+offset_top})
			}
			if(tipbox)
			{
				icon=tipbox.find(".verify_tip_icon");
				text=tipbox.find(".verify_tip_text");
				icon.prop("className","verify_tip_icon");
				text.text("");
			}
		}

		//销毁
		function destroy()
		{
			if(Boolean(tipbox))
			{
				tipbox.remove();
			}
			else if(Boolean(config["container"]))
			{
				icon.prop("className","verify_tip_icon");
				text.text("");
			}
			//关闭所有事件
			selector.off("focus","[name='"+field_name+"']",focusHandler);
			selector.off("blur","[name='"+field_name+"']",blurHandler);
			selector.off("keyup","[name='"+field_name+"']",handler);
			selector.off("change","[name='"+field_name+"']",handler);
		}
		
		//获取值
		function getVal()
		{
			var val=jq.val();
			if(checkbox)
			{
				val=[];
				jq.each(function(index, element) {
					if($(element).prop("checked"))
					{
						val.push($(element).val());
					}
				});
			}
			return val;
		}
		
		//是否设置值
		function isset(val)
		{
			return typeof(val)!="undefined";
		}
		
		//展示提示信息
		function show_tip(type,str)
		{
			if(!Boolean(tipbox))return false;
			icon.prop("className","verify_tip_icon");
			if(type==config["error_class"])
			{
				//之前的focus有bug
			}
			icon.addClass(type);
			text.text(str);
			tipbox.show();
		}

		//事件处理
		function handler(call)
		{
			result=false;
			handler_callback=call;
			if(!meet())
			{
				show_tip(config["success_class"],data["success"]);
				if(typeof(handler_callback)=="function")
				{
					handler_callback(true,data["success"]);
				}
				return true;
			}
			verify(function(res,info)
			{
				if(res)
				{
					show_tip(config["success_class"],info);
				}
				else
				{
					show_tip(config["error_class"],info);
				}
				result=res;
				if(typeof(handler_callback)=="function")
				{
					handler_callback(result,info);
				}
			})
		}
		//验证
		function verify(call)
		{
			current=0;
			callback=call;
			start();
		}
		//开始验证
		function start()
		{
			var fun=rules[current]?rules[current][2]:"";
			if(typeof(fun)!="function")
			{
				fun=plugins[fun];
			}
			var parm=rules[current]?rules[current][0]:"";
			fun(parm,getVal(),complete,field_name,that);
		}
		//完成一条验证
		function complete(res,inf)
		{
			var info='';
			if(!res)
			{
				var info=inf?inf:rules[current][1];
				return callback(false,info);
			}
			else if(current<rules.length-1)
			{
				current++;
				start();
			}
			else
			{
				info=inf?inf:data["success"];
				return callback(true,info);
			}
		}
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

})(smart);