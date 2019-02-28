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
	
	var options;
	var verify_num=3;
	var cookie_;
	
	$('#verify').click(function(){
		$(this).attr('disabled',true);
		var type = $('#pwd_type').val();
		if(type==1){
			var obj = $('.verify_pwd');
			var code = $('input[name="pwd"]').val();
		}else if(type==2){
			var obj = $('.verify_sms');
			var code = $('input[name="sms_code"]').val();
		}
		if(verify_num>=0){
			
			$.post("{pigcms{$config.site_url}/index.php?g=Index&c=Verify&a=verify", {type: type,code:code}, function(data, textStatus, xhr) {
				data = JSON.parse(data);
				if(!data.error_code){
					$(this).css('display','none');
					$('#pwd_verify').css('display','none');
					$('#pwd_bg').css('display','none');
					options_div(options);
				}else{
					if(type==1){
						if(verify_num==0){	
							//window.location.reload();
						}
						$(this).attr('disabled',false);
						$('.verify_pwd p').html("密码错误！您还有"+verify_num+"次机会")	
						verify_num--;
					}else if(type==2){
						$(this).attr('disabled',false);
						$('.verify_sms p').html("验证码错误！请重新输入")	
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
	});
	
	
	function bio_verify(option){
		layer.open({type:2,content:'验证加载中..',shadeClose:false});
		this.options = {
			'location' : '',
			'twice'  : true,
			'hide'	 : '',
			'visible': '',
			'submit' : '',
			'cookie' : '',
			'func'   : '',
		}
		for (var i in option){
			if(i=='hide'||i=='visible'||i=='submit'){
				this.options[i] = option[i].split(',');
			}else{
				this.options[i] = option[i]
			}
		}
		
		options = this.options;
		cookie_ = options.cookie;
		jump_url = options.location;
		if($.cookie('my_wallet_time')){
			var in_time = true;
		}else{
			var in_time = false;
		}
		if(options.twice && (!in_time||cookie_=='')){
			if(typeof(wxSdkLoad) != "undefined"){
				wx.invoke('getSupportSoter', {}, function (res) {
				  if(res.support_mode=='0x01'){
					wx.invoke('requireSoterBiometricAuthentication', {
					  auth_mode: '0x01',
					  challenge: 'test',
					  auth_content: '请进行指纹验证,取消将切换验证方式'  //指纹弹窗提示
					}, function (res) {
						if(res.err_code==0){
							layer.closeAll();
							options_div(options);
							
						}else if (res.err_code==90009){
							layer.closeAll();
							$('#pwd_bg').css('display','block');
							$('#pwd_verify').css('display','block');
						}else{
							alert(res.err_code);
						}
					})
				  }else{
					 // 密码验证
					layer.closeAll();
					$('#pwd_bg').css('display','block')		
					$('#pwd_verify').css('display','block')		
				  }
				})
			}else{
				layer.closeAll();
				$('#pwd_bg').css('display','block');
				$('#pwd_verify').css('display','block');
			}
		 
		}else{
			layer.closeAll();
			options_div(options);
		}
	}
	
	function options_div(options){
		if(options.cookie!=''){
			set_wallet_time();
		}
		if(options.hide!=''){
			for(var h in options.hide){
				$(options.hide[h]).css('display','none');
			}
		}
		if(options.visible!=''){
			for(var v in options.visible){
				$(options.visible[v]).css('display','block');
			}
		}
		if(options.submit!=''){
			for(var s in options.submit){
				$(options.submit[s]).submit();
			}
		}
		if(options.location != ''){
			location.href = options.location;
		}
	}
	
	function set_wallet_time(){
		var tmpdate = new Date();
		tmpdate.setTime(tmpdate.getTime() + 60000);
		$.cookie('my_wallet_time','time_out',{expires:tmpdate,path:'/'});
	}
	var countdown = 60;
	function sendsms(val){
		$('#pwd_type').val('2');
		var phone = "{pigcms{$user_session['phone']}";
		if(countdown==60){
			$.ajax({
				url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send',
				type: 'POST',
				dataType: 'json',
				data: {phone: phone},
				success:function(){
					//$(".verify_sms span").css('display','inline');
					//$("#verify_phone").html(phone.substring(0,3)+"****"+phone.substring(8,11));
				}

			});
		}
		if (countdown == 0) {
			val.removeAttribute("disabled");
			val.innerText="重新获取";
			countdown = 60;
			//clearTimeout(t);
		} else {
			val.setAttribute("disabled", true);
			val.innerText="重新发送(" + countdown + ")";
			countdown--;
			setTimeout(function() {
				sendsms(val);
			},1000)
		}
	}
	
	
