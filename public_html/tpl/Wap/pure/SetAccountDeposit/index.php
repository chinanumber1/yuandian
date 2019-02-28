<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>存管账户设置</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<link href="{pigcms{$static_path}scenic/css/css_whir.css" rel="stylesheet"/>
		<link rel="stylesheet" href="{pigcms{$static_public}font-awesome/css/font-awesome.min.css">
		<script src="{pigcms{$static_path}scenic/js/jquery-1.8.3.min.js"></script>
    <style>
	    .my-account {
	        color: #333;
	        position: relative;
	        display: block;
	        width:100%;
	        position: relative;
	        height:11rem;
	    }
	    .account-bg{
			position: absolute;
		    top: 0;
		    left: 0;
		    height: 100%;
		    width: 100%;
		    z-index: -1;
			background-color: #f32516;
	    }
	    .my-account>img {
	        height: 100%;
	        position: absolute;
	        right: 0;
	        top:0;
	        z-index: 0;
	    }
	    .titleImg{
			width:30px;
			height:25px;
			margin-right:10px;
			margin-top:10px;
			float:left;
	    }
	    .wh25{
			width:25px;
			height:25px;
	    }
	    .title{
			padding:0 10px;
			background-color:#fff;
			margin-top:10px;
			margin-bottom:10px;
	    }
	    .title_img{
			float:right;
			margin-top:-19px;
			width:10px;
	    }
	    .pb10{
			padding-bottom:10px;
	    }
		.reason{
			font-size:16px;
			display: inline-block;
			font: normal normal normal 14px/1 FontAwesome;
			font-size: inherit;
			text-rendering: auto;
			-webkit-font-smoothing: antialiased;
		}
		.reason:before{
			content: "\f29c";
		}
		.public{ height: 44px; line-height: 44px; background: #f32516; color: #fff; position: fixed; width: 100%; top: 0px; left: 0px; z-index: 880; }
		.public .content{ text-align: center;font-size: 16px;   }
		.public .return{ position: absolute; width: 50px; height: 100%; left: 0px; top: 0px; }
		.public .return:after{ display: block;content: "";border-top: 2px solid #fff;border-left: 2px solid #fff;width: 10px;height: 10px;-webkit-transform: rotate(-45deg);background-color: transparent;position: absolute; left: 16px;top: 16px; }
		.deposit_status{
			font-size:12px;
			margin-left: 34px;
		}
		.Cen_tx{
			    position: absolute;
    text-align: center;
    width: 100%;
    top: 70%;
    margin-top: -60px;
		}
		
		.Cen_tx img{
			    width: 60px;
    height: 60px;
    border-radius: 100%;
    border: #fff 3px solid;
		}
	</style>
</head>
<body>
	<section class="public pageSliderHide">
		<div class="return link-url" data-url-type="openLeftWindow" data-url="back"></div>
		<div class="content">存管账户设置</div>
	</section>
	<div class="my-account">
		<div class="account-bg">
			
		</div>
		<div style="text-align:center;padding-top:40px;color:#fff;">
		<div class="Cen_tx">
					<img src="{pigcms{$user_session['avatar']}">
				
				</div>
			
		</div>

		<!--<img style="position:absolute;top:9.8em;right:6.5em;width:25px;height:25px;margin-right:5px;" src="{pigcms{$static_path}images/new_my/withdrawals.png" />
		<div style="position:absolute;top:10em;right:4.5em;color:#fff;">提现</div>-->
	</div>
	<if condition="$deposit.bizUserId neq ''">
	
		<dl  class="title">
			<div class="pb10">
				<div style="padding-top:12px;width:95%;">云商通ID  <span class="deposit_status">{pigcms{$deposit.bizUserId}</span> <span class="deposit_status"> <if condition="$deposit.signStatus eq 0"><a href="{pigcms{:U('signConnect')}">去签约</a><else />已签约</if></span></div>
				
			</div>
			<div style="clear:both;"></div>
		</dl>
	
	</if>
	<dl id="bindphone" class="title">
		<div class="pb10">
			
			<div style="padding-top:12px;width:95%;">绑定手机  <span class="deposit_status"><if condition="$deposit.phone eq 0">去设置<else />{pigcms{$deposit.phone}</if></span></div>
			
			
			<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
		</div>
		<div style="clear:both;"></div>
	</dl>
	<dl id="setRealName" class="title">
		<div class="pb10">
			
			<div style="padding-top:12px;width:95%;">实名认证 <span class="deposit_status"><if condition="$deposit.identityNo eq ''">去认证<else />已认证</if></span></div>
			<if condition="$deposit.identityNo eq ''">
			<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
			</if>
		</div>
		<div style="clear:both;"></div>
	</dl>
	<dl id="setPwd" class="title">
		<div class="pb10">
			
			<div style="padding-top:12px;width:95%;">交易密码 <span class="deposit_status"><if condition="$deposit.setPwd eq 0">去设置<else />已设置 去修改</if></span></div>
			<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
		</div>
		<div style="clear:both;"></div>
	</dl>
	
	<dl id="bankList" class="title">
		<div class="pb10">
			
			<div style="padding-top:12px;width:95%;">银行卡管理 <span class="deposit_status"><if condition="$deposit.bind_bank_list eq ''">去绑定<else />已绑定</if></span></div>
			<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
		</div>
		<div style="clear:both;"></div>
	</dl>
	
	
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script src="{pigcms{$static_path}js/deposit.js"></script>
		<script>
			$('#bindphone').on('click',function(){
				location.href =	"<if condition="$deposit.bind_phone_status eq 1 ">{pigcms{:U('editphone')}<else />{pigcms{:U('bindphone')}</if>";
			});
			
			$('#setRealName').on('click',function(){
				location.href =	"<if condition="$deposit.identityNo eq ''">{pigcms{:U('verify_real_name')}<else />javascript:void(0)</if>";
			});
			
			$('#setPwd').on('click',function(){
				location.href =	"{pigcms{:U('setPwd')}";
			});
			$('#bankList').on('click',function(){
				location.href =	"<if condition="$deposit.bind_bank_list neq ''">{pigcms{:U('add_bind_card')}<else />{pigcms{:U('apply_bind_card')}</if>";
			});
			
			$('.frozen_money').on('click',function(){
				layer.open({
					content: '冻结理由：{pigcms{$now_user.frozen_reason}<br>冻结日期：{pigcms{$now_user.frozen_time|date='Y-m-d',###} 至 {pigcms{$now_user.free_time|date='Y-m-d',###}'
					,btn: ['确定']
				  });
			});
			
		</script>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
		{pigcms{$shareScript}
		<if condition="$_SESSION['source'] eq 1">
			<include file="Public/scenic_footer"/>
		<else/>
			</body>
			</html>
		</if>