<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>我的推广码</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/my_spread_code.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/my_spread_code.js?211" charset="utf-8"></script>
	

	
	</head>
	
	 <body style="background: #ec4729">
        <section class="recom">
            <div class="recom_top">
                <div class="h2">我的邀请码</div>
                <div class="con">
                    <p class="zm">{pigcms{$spread_code}</p>
					<if condition="$spread_qrcode">
                    <img src="{pigcms{$spread_qrcode}">
                    <p class="tx">长按此图识别二维码</p>
					</if>
                </div>
            </div>
            <div class="recom_end">
                <div class="h2">使用方法</div>
                <div class="con">
                    <div class="list_con">
                        <div class=con_h2>一、分享邀请码</div>
                        <div class="p">您可以将邀请码发送给您的好友，您的好友注册为（仅限app，pc端，手机浏览器端）平台会员或成为平台审核通过的商家，则您可获得一定的推广佣金。</div>
                    </div>
					<if condition="$spread_qrcode">
                    <div class="list_con">
                        <div class=con_h2>二、分享二维码</div>
                        <div class="p">您可以将二维码分享给您的好友，您的好友使用微信（仅限微信）扫描分享的二维码注册成功之后，则您可获得一定的推广佣金</div>
                    </div>
					</if>
                </div>
            </div>
            
            
        </section>

		{pigcms{$shareScript}
    </body>



</html>