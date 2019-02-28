if(common.checkWeixin()){
	$('.mui-bar-nav').remove();
}


var config = {}
common.http('Merchantapp&a=config', {}, function(data){
	common.setCache('config',data,true);
	config=common.getCache('config',true); 
	config_loaded(data);
});



function config_loaded(config){
	if(config.open_score_fenrun && config.open_score_fenrun == '1'){
		$('.spread_code_box').show();
	}
	if(config.merchant_verify && config.merchant_verify == '1'){
		$('.merchant_verify').show();
		$('#site_phone').html(config.site_phone).attr('href','tel:'+config.site_phone);
	}
	if(config.store_register_agreement && config.store_register_agreement != ''){
		$('.register_agreement_box').show();
	}
	
	if(config.open_score_fenrun == '1'){
		$('.spread_code_box').show();
	}

	if(config.open_admin_code == '1'){
		$('.invit_code').show();
	}

	if(config.open_merchant_reg_sms == '1'){
		$('.smscode').show();
	}


	if(config.open_score_fenrun == '1'){
		$('.spread_code_box').show();

	}
	
	if(config.open_distributor == '1'){
		$('.spread_code_box').show();

	}

	if(config.international_phone == '1'){
		$('.phone_country').show();

	}
	
	if(config.open_distributor == '1' && typeof(urlParam.spread_code)!='undefined' && urlParam.spread_code!=''){
		$('#spread_code').val(urlParam.spread_code)
	}

}







mui.init();
(function($, doc) {
	$.init();
	$.ready(function(){
		common.http('Merchantapp&a=cityList',{}, function(data){
			var cityPicker3 = new $.PopPicker({
				layer: 3
			});
			cityPicker3.setData(data);
			console.log(data);
			var showCityPickerButton = doc.getElementById('showCityPicker');
			showCityPickerButton.addEventListener('tap', function(event) {
				cityPicker3.show(function(items){
					document.getElementById('province_id').value = items[0].value;
					document.getElementById('city_id').value = items[1].value;
					document.getElementById('area_id').value = items[2].value;
					document.getElementById('adress_text').innerText =  items[0].text + " " + items[1].text + " " + items[2].text;
					document.getElementById('adress_text').style.color='#333';
				});
			}, false);
		});
	});
})(mui, document);


$(function(){
	$('.register').click(function(){
		var postData = {};
		postData.account = $('#reg_account').val();
		postData.pwd = $('#reg_pwd').val();
		postData.mername = $('#reg_name').val();
		postData.phone = $('#reg_phone').val();
		postData.province_id = $('#province_id').val();
		postData.city_id = $('#city_id').val();
		postData.area_id = $('#area_id').val();
		postData.invit_code = $('#invit_code').val();
		postData.sms_code = $('#smsCode').val();
		postData.phone_country_type = $('#phone_country_type').val();
		if(typeof($('#spread_code').val())!='undefined' && $('#spread_code').val()!=''){			
			postData.spread_code = $('#spread_code').val();
		}

		if(postData.account.length<6){
			mui.alert('请输入至少 6 个字符的帐号');
		}else if(!/^\w+$/.test(postData.account)){
			mui.alert('帐号只能输入英文和数字和下划线~');
		}else if(postData.pwd.length < 6){
			mui.alert('请输入至少 6 个字符的密码');
		}else if(postData.mername == ''){
			mui.alert('商户名称必填');
		}else if(postData.phone == '' || !/^[0-9]{11}$/.test(postData.phone)){
			mui.alert('请输入正确的手机号码');
		}else if(postData.area_id == ''){
			mui.alert('请选择城市区域');
		}else if(config.store_register_agreement && config.store_register_agreement != '' && !$('#register_agreement').prop('checked')){
			mui.alert('请同意商家注册协议，方可继续注册商家');
		}else{
			
			common.http('Merchantapp&a=mer_reg',postData,function(data){
				
				mui.alert('注册成功！'+(data.type == 2 ? '请耐心等待审核或联系工作人员审核' : ''),'提醒','好的',function(){
				});
				if(common.checkApp()){
					setTimeout(function(){
						if(common.checkAndroidApp()){
							window.pigcmspackapp.closewebview(2);
						}else{
							common.iosFunction('closewebview/2');
						}
					},2000);
				}else{
					setTimeout(function(){
						location.href = 'index.html';
						document.execCommand('Refresh');
					},2000); 
				}
			});
		}	
	});

	$('#register_agreement_btn').click(function(){
		layer.open({
		  type: 1
		  ,title: '商家注册协议' //不显示标题栏
		  ,closeBtn: false
		  ,area: ['70%','90%']
		  ,shade: 0.8
		  ,content: '<div style="padding:20px;height:'+($(window).height()*0.9 - 60)+'px;overflow-y:auto;">'+config.store_register_agreement+'</div>'
		  ,style: 'position:fixed; top:5%; left:5%; width: 90%; height: 90%; border:none;'
		});
	});
	
	mui('.mui-content').on('tap','.sendCode',function(e){
	common.http('Merchantapp&a=sendCode',{type:1,phone:$('#reg_phone').val()}, function(data){
		motify.log('短信发送成功');
		$('.sendCode').data('second',59).html('59 秒').prop('disabled',true);
		var smsTimer = setInterval(function(){
			var second = $('.sendCode').data('second');
			if(second == 1){
				$('.sendCode').html('获取验证码').prop('disabled',false);
				clearInterval(smsTimer);
			}else{
				second--;
				$('.sendCode').data('second',second).html(second + ' 秒');
			}
		},1000);
	});
});
});

