<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>我的钱包</title>
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
	</style>
</head>
<body>
	<div class="my-account">
		<div class="account-bg">
			<img src="{pigcms{$static_path}images/new_my/my_money.png" style="width:100%;height:100%;">
		</div>
		<div style="text-align:center;padding-top:40px;color:#fff;">
			<div style="font-size:24px;"><span style="font-size:16px;"></span>{pigcms{$now_user.now_money_two}<if condition="$now_user.frozen_money gt 0 AND $config.open_frozen_money eq 1 AND $now_user.free_time gt $_SERVER['REQUEST_TIME']"><span class="frozen_money" style="font-size:12px;margin-left:5px;">(冻结{pigcms{$now_user.frozen_money|floatval}元)<i class="reason" style="margin-left:5px;"></i></span></if></div>
			<div style="text-align:center;">我的余额</div>
			
		</div>
		<if condition="$config.open_user_recharge eq 1">
		<div id="recharge" style="position:absolute;top:9.9em;left:4.5em;width:30%;height:35px;">
			<img class="wh25" style="margin-right:10px;float:left;" src="{pigcms{$static_path}images/new_my/recharge.png" />
			<div style="color:#fff;float:left;margin-top:3px;">充值</div>
		</div>
		</if>
		<if condition="$config.company_pay_open eq 1">
		<div id="withdraw" style="position:absolute;top:9.9em;right:1.5em;width:30%;height:35px;">
			<img class="wh25" style="margin-right:10px;float:left;" src="{pigcms{$static_path}images/new_my/withdrawals.png" />
			<div style="color:#fff;float:left;margin-top:3px;">提现</div>
		</div>
		</if>
		<!--<img style="position:absolute;top:9.8em;right:6.5em;width:25px;height:25px;margin-right:5px;" src="{pigcms{$static_path}images/new_my/withdrawals.png" />
		<div style="position:absolute;top:10em;right:4.5em;color:#fff;">提现</div>-->
	</div>
	<dl id="transaction" class="title">
		<div class="pb10">
			<img class="titleImg" src="{pigcms{$static_path}images/new_my/transaction.png" />
			<div style="padding-top:12px;width:95%;">余额记录</div>
			<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
		</div>
		<div style="clear:both;"></div>
	</dl>
	<if condition="$_SESSION['source'] neq 1">
	<dl id="integral" class="title">
		<div class="pb10">
			<img class="titleImg" src="{pigcms{$static_path}images/new_my/integral.png" />
			
			<div style="padding-top:12px;width:95%;">{pigcms{$config.score_name}记录</div>
			<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
		</div>
		<div style="clear:both;"></div>
	</dl>
		<dl id="levelUpdate" class="title" <php>if($config['level_onoff']==0){ echo 'style="display:none"'; }</php>>
			<div class="pb10">
				<img class="titleImg" src="{pigcms{$static_path}images/new_my/grade.png" />
				<div style="padding-top:12px;width:80%;">等级管理
					<if condition="$now_user.level eq 0">
					<div style="position:absolute;color:#fff;background-color:#e5e5e5;padding:0px 8px;right:1.8em;top:301px;border-radius:10px;">VIP<span style="font-size:17px;">{pigcms{$now_user.level}</span></div>
					<else/>
					<div style="position:absolute;color:#fff;background-color:#ffb80f;padding:0px 8px;right:1.8em;top:301px;border-radius:10px;"><span style="font-size:17px;">{pigcms{$now_user.lname}</span></div>
					</if>
				</div>
				<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
			</div>
			<div style="clear:both;"></div>
		</dl>
	<if condition="$config['score_recharge'] AND $config.open_extra_price eq 0 AND $config.open_score_fenrun eq 0">
	<dl id="score_recharge" class="title">
		<div class="pb10">
			<img class="titleImg" src="{pigcms{$static_path}images/new_my/exchange.png" />
			<div style="padding-top:12px;width:95%;">{pigcms{$config.score_name}换余额<span style="float:right;color:#ee4236;">{pigcms{$now_user.score_count|floatval}</span></div>
			<img class="title_img" src="{pigcms{$static_path}images/new_my/tubiao2_11.png"></img>
		</div>
		<div style="clear:both;"></div>
	</dl>
	</if>
	</if>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			$('#recharge').on('click',function(){
				location.href =	"{pigcms{:U('My/recharge')}";
			});
			$('#withdraw').on('click',function(){
				location.href =	"{pigcms{:U('Fenrun/withdraw')}";
			});
			$('#transaction').on('click',function(){
				location.href =	"{pigcms{:U('My/transaction')}";
			});
			$('#integral').on('click',function(){
				location.href =	"{pigcms{:U('My/integral')}";
			});
			$('#levelUpdate').on('click',function(){
				location.href =	"{pigcms{:U('My/levelUpdate')}";
			});
			$('#score_recharge').on('click',function(){
				location.href =	"{pigcms{:U('My/score_recharge')}";
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