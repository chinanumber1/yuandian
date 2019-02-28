<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>会员中心</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <style>
	    .my-account {
	        color: #333;
	        position: relative;
/*	        border-bottom: 1px solid #C0BBB2;*/
	        display: block;
	        height: 4rem;
	        position: relative;
	    }
	    .my-account>img {
	        height: 100%;
	        position: absolute;
	        right: 0;
	        top:0;
	        z-index: 0;
	    }
	    .my-account .user-info {
	        z-index: 1;
	        position: relative;
	        height: 100%;
	        padding: .28rem .2rem;
	        box-sizing: border-box;
	        padding-left: 1.9rem;
	        font-size: .24rem;
	        color: #666;
	    }
	    .my-account .uname {
	        font-size: .4rem;
	        color: #fff;
	        margin-top: .1rem;
	        margin-bottom: .2rem;
	    }
		.my-account .umoney {
			color: #fff;
	    	margin-bottom: 0.06rem;
	    }
	    .my-account .avater {
	        position: absolute;
	        top: .2rem;
	        left: .3rem;
	        width: 1.4rem;
	        height: 1.4rem;
	        border-radius: 50%;
	    }
	    .react {
	        padding: .28rem 0rem;
	    }
	    .phone{
			width:85px;
			float:left;
			z-index:100;
	    }
	    .data{
			float:left;
			padding:2px 2px;
			border:1px solid #f9005e;
			border-radius:15px;
			color:#f9005e;
			width:80px;
			text-align:center;
			margin-top:-7px;
			z-index:100;
	    }
	    .titleImg{
			width:25px;
			height:25px;
			margin-right:10px;
			margin-top:-2px;
	    }
	    .words{
			text-align:center;
			color:#5f5f5f;
			font-size:12px;
			margin-top:5px;
	    }
	    .wh25{
			width:25px;
			height:25px;
	    }
	    .foloow{
			float:left;
			width:25%;
			text-align:center;
			color:#5f5f5f;
			font-size:12px;
	    }
	    .foloow_b{
			border-left:1px solid #e5e5e5;
	    }
	    .padd7{
			padding:7px;
	    }
	</style>
</head>
<body>
	<div id="tips" class="tips"></div>
	<div class="my-account" style="color:#fff;">
		<div class="account-bg" style="
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: -1;"><img src="http://www.group.com/tpl/Wap/default/static/images/new_my/my_money.png" alt="" style="width:100%;height: 100%;"></div>
		<div class="" style="text-align:center;padding-top:40px;">
			<div style="font-size:24px;"><span style="font-size:16px;"><b>￥</b></span>{pigcms{$now_user.now_money}</div>
			<div style="text-align:center;margin-top:10px;">我的余额</div>
		</div>
	</div>
	<div style="margin-top:10px;"></div>
	<dl class="list" style="margin-top:0rem;border:0px;padding:0 10px;">
		<dd>
			<a class="react" style="padding:.28rem .0rem;" href="{pigcms{:U('Classify/myCenter')}">
				<div class="more more-weak">
					<img class="titleImg" src="{pigcms{$static_path}images/new_my/transaction.png" />交易记录<span class="more-after"></span>
				</div>
			</a>
		</dd>
	</dl>
	<div style="margin-top:10px;"></div>
	<dl class="list" style="margin-top:0rem;border:0px;padding:0 10px;">
		<dd>
			<a class="react" style="padding:.28rem .0rem;" href="{pigcms{:U('Classify/myCenter')}">
				<div class="more more-weak">
					<img class="titleImg" src="{pigcms{$static_path}images/new_my/grade.png" />等级管理<span class="more-after"></span>
				</div>
			</a>
		</dd>
	</dl>
	<div style="margin-top:10px;"></div>
	<dl class="list" style="margin-top:0rem;border:0px;padding:0 10px;">
		<dd>
			<a class="react" style="padding:.28rem .0rem;" href="{pigcms{:U('Classify/myCenter')}">
				<div class="more more-weak">
					<img class="titleImg" src="{pigcms{$static_path}images/new_my/integral.png" />{pigcms{$config['score_name']}兑换余额<span class="more-after"></span>
				</div>
			</a>
		</dd>
	</dl>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common_wap.js"></script>
{pigcms{$hideScript}
</body>
</html>