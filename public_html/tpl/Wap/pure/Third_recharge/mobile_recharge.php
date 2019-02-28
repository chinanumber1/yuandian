<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
     
        <title>话费充值</title>
        
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<style>
			.phone_ico{
				    background-image: url({pigcms{$static_path}/images/phone_ico.png);
			}
			.un_recharge{
				background-color: #ccc;
			}
			.price-list {
				list-style: none;
				padding-left: 20px;
			}
			
			.price-list li {
				float: left;
				-webkit-box-sizing: border-box;
				width: 31.3%;
				padding: 6px;
				
			}
			
			.price-list li a {
				position: relative;
				height: 36px;
				line-height: 36px;
				text-align: center;
				border: 1px solid #06c1ae;
				border-radius: 5px;
			
				color: #06c1ae;
				display: block;
				text-decoration: none;
			}
			.ft{
				margin-top:15px;
			}
			.on{
				border: 1px solid #ccc;
			}
			.price-list li.active a {
				color: #fff;
				background-color: #06c1ae;
				/*border-color: #FF5000;*/
			}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>话费充值</header>
    </if>
		<div id="container">
			<div id="scroller">
				
				<section class="query-container">
					<div class="query_div phone_ico"></div>
				
					
						<div class="area_input" style="margin-top:15px;">
							<input type="tel" class="recharge_txt" id="phone" name="phone" placeholder="手机号码" value="{pigcms{$user_session['phone']}" />
							<span class="nametip"></span>
						</div>
						<div class="ft" style="padding-left: 22px;    margin-top: 20px;color:#6B6B6B">
							<span>面值选择</span>
						</div>
						<div class="money_list ">
							<ul class="price-list">
								<volist name="price_arr" id="vo">
									<li data-price="{pigcms{$vo}"><a class="price">{pigcms{$vo}元</a></li>
								</volist>
							</ul>
						</div>
					<div class="area_btn un_recharge " style="width:80%;    margin-top: 120px;position: absolute;margin-left: 8%;"><input type="button" id="recharge_btn" value="充值" disabled /></div>
				</section>
			
			</div>
		</div>
	<script>
			var phone,error_msg ,flag=false;
		$(function(){
			if($('#phone').val()!=''){
				phone = $('#phone').val();
				ajax_phone($('#phone').val())
			}
			
			$('#phone').blur(function(){
				phone = $(this).val();
				ajax_phone(phone)
				
			});
			
			$("#phone").bind('input propertychange',function(){
				//if($(this).val().length==11){
					ajax_phone($(this).val())
			//	}
			})
			
			$('#recharge_btn').click(function(){
				var money = $('.price-list .active').data('price');
				if(typeof(money)=='undefined'){
					layer.open({
						content: '没有选择充值金额'
						,btn: ['我知道了']
					});
				}
				if(flag){
					window.location.href = '{pigcms{:U('mobile_recharge_pay')}&phone='+phone+'&money='+money;
				}else{
					layer.open({
						content: error_msg
						,btn: ['我知道了']
					});
				}
			});
			
			$('.price-list li').click(function(){
				$(this).addClass('active').siblings('li').removeClass('active');
				nowMoney = $(this).data('price');
				ajax_phone($('#phone').val())
				//$('#otherPrice').val('');
				//giveCount();
			});
			$('#backBtn').click(function(){
				window.location.href="{pigcms{$config.site_url}/wap.php";
			})
			
		});
		
		function ajax_phone(phone){
			if(typeof($('.price-list .active').data('price'))!='undefined'){
				money = $('.price-list .active').data('price');
			}else{
				money =10;
			}
			if(phone.length==11){
				
			
			 $.post('{pigcms{:U('ajax_get_phone')}', {phone:phone,money:money}, function(data, textStatus, xhr) {
					if(!data.error_code){
						flag = true;
						$('.nametip').html(data.result.game_area)
						if($('.price-list .active').length<1){
							$('.area_btn').addClass('un_recharge');
							$('#recharge_btn').attr('disabled','disabled');
						}else{
							$('.area_btn').removeClass('un_recharge');
							$('#recharge_btn').removeAttr('disabled');
						}
						// $('#recharge_btn').after('('+data.result.inprice+'元)')
						
					}else{
						error_msg = data.reason;
						flag =false;
						layer.open({
							content: data.reason
							,btn: ['我知道了']
							 ,yes: function(index){
				
							  $('.area_btn').addClass('un_recharge');
							$('#recharge_btn').attr('disabled','disabled');
							  layer.close(index);
							}
						});
						
					}
				 },'json');
			 }else{
				 $('.nametip').html('')
			   $('.area_btn').addClass('un_recharge');
				$('#recharge_btn').attr('disabled','disabled');
			 }
		}
		window.shareData = {
			"moduleName":"Store",
			"moduleID":"0",
			"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Third_recharge/mobile_recharge')}",
			"tTitle": "在线话费充值",
			"tContent": "{pigcms{$config.site_name}"
		};
	</script>
		{pigcms{$shareScript}
	</body>
</html>