	jQuery.cookie = function (key, value, options) {
    if (arguments.length > 1 && (value === null || typeof value !== "object")){
			options = jQuery.extend({}, options);
			if (value === null) {
				options.expires = -1;
			}
			if (typeof options.expires === 'number'){
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}
			return (document.cookie = [
				encodeURIComponent(key), '=',
				options.raw ? String(value) : encodeURIComponent(String(value)),
				options.expires ? '; expires=' + options.expires.toUTCString() : '',
				options.path ? '; path=' + options.path : '',
				options.domain ? '; domain=' + options.domain : '',
				options.secure ? '; secure' : ''
			].join(''));
		}
		options = value || {};
		var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
		return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
	};
	
	function set_wallet_time(c_name,time,content){
		var tmpdate = new Date();
		tmpdate.setTime(tmpdate.getTime() + time);
		$.cookie(c_name,content,{expires:tmpdate,path:'/'});
	}
	
	function CountDown(){
		if(typeof(verify_end_time)!='undefined'&&verify_end_time!=0){
			set_wallet_time('verify_time',3600000,verify_end_time);
		}
		if($.cookie('verify_time')){
			var verify_time = $.cookie('verify_time');
			var nowtime = (new Date).getTime();
			var maxtime = parseInt((verify_time*1000-nowtime)/1000);
			
		}
		if(maxtime>=0){   
			$('#pwd').attr('disabled','disabled');
			minutes = Math.floor(maxtime/60);   
			seconds = Math.floor(maxtime%60); 
			msg = "验证锁定!"+minutes+"分"+seconds+"秒后重试";
			if(maxtime==0){
				$('.verify_pwd .tips').html('');	
				$('#pwd').val('');
			}else{	
				$('.verify_pwd .tips').html(msg);
			}
		} else{   
			$('#pwd').removeAttr('disabled');
			clearInterval(timer); 			
		}   
	}   
	timer = setInterval("CountDown()",1000);   
	
	var verify_num=2;
	$('#verify').click(function(){
		$(this).attr('disabled',true);
		var type = $('#pwd_type').val();
		if(type==1){
			var obj = $('.verify_pwd');
			var code = $('input[name="pwd"]').val();
		}else if(type==2){
			var obj = $('.verify_sms');
			var code = $('input[name="sms_code"]').val();
		}if($.cookie('verify_time')){
			$('.verify_button').css('background-color','rgba(242, 242, 242, 1)');  
			$('.verify_button p').css('color','#949494');  
		}else if(code==''){
			$('.verify_pwd .tips').html("请输入密码！");
		}else{
			$.post("{pigcms{$config.site_url}/index.php?g=Index&c=Verify&a=verify", {type: type,code:code}, function(data, textStatus, xhr) {
				data = JSON.parse(data);
				if(!data.error_code){
					$(this).css('display','none');
					$('#pwd_verify').css('display','none');
					$('#pwd_bg').css('display','none');
					$('#pay-form').submit();
				}else{
					if(type==1){
						$(this).attr('disabled',false);
						if(typeof(data.end_time)!='undefined'){
							set_wallet_time('verify_time',3600000,data.end_time);
							setInterval("CountDown()",1000);   
							$('#pwd').attr('disabled','disabled');
							$('.verify_button').css('background-color','rgba(242, 242, 242, 1)');  
							$('.verify_button p').css('color','#949494');  
						}
						$('.verify_pwd .tips').html(data.msg)	
					}else if(type==2){
						$(this).attr('disabled',false);
						$('.verify_sms .tips').html("验证码错误！请重新输入")	
					}
				}
			});
		}
	});
	
	$('#pwd_code').click(function(){
		$('.verify_sms').css('margin-left','268px');
		$('.verify_sms').css('display','none');
		$('.verify_sms').css('right','');
		$(".verify_pwd").animate({right:"255px"});
		$('.verify_pwd').css('display','block');
		$(this).css('background-color','rgba(73, 180, 79, 1)');
		$(this).css('color','#fff');
		$('#sms_code').css('background-color','#fff');
		$('#sms_code').css('color','rgba(73, 180, 79, 1)');
		$('#pwd_type').val('1');
	});
	
	$('#sms_code').click(function(){
		$('.verify_pwd').css('display','none');
		$('.verify_pwd').css('margin-left','268px');
		$('.verify_pwd').css('right','');
		$(".verify_sms").animate({right:"255px"});
		$('.verify_sms').css('display','block');
		$(this).css('background-color','rgba(73, 180, 79, 1)');
		$(this).css('color','#fff');
		$('#pwd_code').css('background-color','#fff');
		$('#pwd_code').css('color','rgba(73, 180, 79, 1)');
		$('#pwd_type').val('2');
	});
	
	$('.cancle').click(function(){
		$('#pwd_bg').css('display','none');
		$('#pwd_verify').css('display','none');
		$("button.mj-submit").removeAttr("disabled");
		$("button.mj-submit").html("确认支付");
	});
	$('#pwd_bg').click(function(){
		$(this).css('display','none');
		$('#pwd_verify').css('display','none');
		$("button.mj-submit").removeAttr("disabled");
		$("button.mj-submit").html("确认支付");
	});
	
	$('#pwd').bind('input propertychange', function() {  
		$('.verify_button').css('background-color','rgb(73, 180, 79)');  
		$('.verify_button p').css('color','#fff');  
	});
