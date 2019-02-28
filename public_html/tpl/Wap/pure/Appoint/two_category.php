<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$cat_info['cat_name']}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index.css?216"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script>
		<script type="text/javascript">var backUrl = "{pigcms{:U('Appoint/category')}";</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/appoint_two_category.js?212" charset="utf-8"></script>
		<style>
			body{width:100%;max-width:100%;}
			header{color: white;line-height: 50px;text-align: center;display:block;font-size:18px;}
			.headBox {
				color: #666;
				height: 48px;
				line-height: 48px;
				padding-left: 8px;
				font-size: 14px;
				border-bottom: 1px solid #edebeb;
				font-weight: bold;
				background: #f5f5f5;
				background: -moz-linear-gradient(top,rgba(249,249,249,.98),rgba(245,245,245,.98) 100%);
				background: -webkit-gradient(linear,0 0,0 100%,from(rgba(249,249,249,.98)),to(rgba(245,245,245,.98)));
			}
			.swiper-container, .swiper-wrapper, .swiper-slide {
				width: 100%;
				height: 100%;
			}
			.swiper-container {
				margin: 0 auto;
				position: relative;
				overflow: hidden;
				-webkit-backface-visibility: hidden;
				-moz-backface-visibility: hidden;
				-ms-backface-visibility: hidden;
				-o-backface-visibility: hidden;
				backface-visibility: hidden;
				z-index: 1;
			}
			.swiper-wrapper {
				position: relative;
				width: 100%;
				z-index: 1;
				-webkit-transform-style: preserve-3d;
				-moz-transform-style: preserve-3d;
				-ms-transform-style: preserve-3d;
				transform-style: preserve-3d;
				-webkit-transition-property: -webkit-transform;
				-moz-transition-property: -moz-transform;
				-o-transition-property: -o-transform;
				-ms-transition-property: -ms-transform;
				transition-property: transform;
				-webkit-box-sizing: content-box;
				-moz-box-sizing: content-box;
				box-sizing: content-box;
			}
			.headBox li {
				float: left;
				text-align: center;
				white-space: nowrap;
				padding: 0 10px;
				min-width: 58px;
				font-weight: normal;
				width: auto;
				height: auto;
			}
			.headBox li.on {
				color: #e34a4a;
				border-bottom: 2px solid #e34a4a;
				line-height: 46px;
			}
			#scroller section{
				margin:0px 8px;
				padding:8px 12px;
				margin-top:12px;
				margin-bottom:4px;
				background-color:white;
			}
			.title {
				font-size: 20px;
				width: 100%;
			}
			.title i {
				width: 5px;
				height: 20px;
				background: #e34a4a;
				float: left;
				margin-right: 10px;
				margin-top: 5px;
				display: inline;
			}
			.content-list {
				font-size: 14px;
				
			}
			.content-list p{
				margin:8px 0;
			}
			.content-list img {
				max-width: 100%;
				width: 100%;
				margin: 8px 0;
			}
			.m-simpleFooter {
				position: fixed;
				z-index: 2;
				left: 0;
				right: 0;
				border: 1px solid #D4D4D4;
				background: #fff;
				padding: 11px 20px;
				bottom: 0;
				border-width: 1px 0;
				height: 43px;
			}
			.m-simpleFooter-text {
				text-align: center;
			}
			.w-button,.w-button:visited,.w-button:hover {
				text-align: center;
				white-space: nowrap;
				font-size: 16px;
				display: inline-block;
				vertical-align: middle;
				color: #fff;
				border-width: 0;
				border-style: solid;
				width: 100%;
				text-align: center;
				height: 40px;
				line-height: 40px;
				border-radius: 3px;
				cursor: pointer;
				text-decoration: none!important;
				outline: none;
					background: #06c1ae;
				border-color: #b6243d;
				
			}
			.content-list table td{
				border: 1px solid #ccc;
				line-height:2;
			}
			
			.cond_top{ text-align: center; display: table; background: url({pigcms{$static_path}images/xqt_02.jpg) center no-repeat; width: 100%; background-size:cover }
			.cond_index{display: table-cell; background: url({pigcms{$static_path}images/fenleit.png) center no-repeat;  width: 190px; height: 121px;vertical-align:middle; position: relative; z-index: 99; background-size: 70% 80% }
			.cond_index span{padding-top: 20px; font-size: 33px; color: #fff; display: inline-block;}
			.after{ position: absolute; z-index: 11;  content: ''; display: block; width: 100%; height: 100%; background: rgba(0,0,0,0.2); left: 0px; top: 50px; }
			.ul{ padding: 10px; padding-bottom: 65px; }
			.ul li{ border: #e9ebee 1px solid; margin-bottom: 10px; }
			.ul li .h22{ padding: 0px; margin: 0px; font-size: 14px; font-weight: normal; color: #10131c; line-height: 40px; background: #e9ebee; padding: 0 10px; }
			.ul div img{max-width:100%;}
		</style>
	</head>
	<body>
		<if condition="!$is_app_browser">
			<header class="pageSliderHide"><div id="backBtn"></div>{pigcms{$cat_info['cat_name']}</header>
		</if>

		

		
		<if condition='$cat_info["wap_content"]'>
		<!-- <div class="headBox newsheader pageSliderHide">
			<div class="swiper-container swiper-container1">
				<ul class="swiper-wrapper">
				<volist name='cat_info["wap_title"]' id='vo'>
					<li class="swiper-slide <if condition='$key eq 0'>on</if>" data-id="{pigcms{$i}">{pigcms{$vo}</li>
				</volist>
				</ul>
			</div>
		</div> -->
		<if condition="!$is_app_browser">
			<div class="pageSliderHide cond_top" >
				<div class="cond_index" >
					<span >{pigcms{$cat_info['cat_name']}</span>
				</div>
			</div>
			<div class="after pageSliderHide"></div>
		</if>
		<div class="ul pageSliderHide">
			<ul>
				<volist name='cat_info["wap_content"]' id='vo'>
					<li>
						<h2 class="h22">{pigcms{$cat_info["wap_title"][$key]}</h2>
						<div style="padding: 10px; background: #fff;">
							{pigcms{$vo|html_entity_decode}
						</div>
					</li>
				</volist>
			</ul>
		</div>
		<!-- <div id="container" class="pageSliderHide" style="display:none;">
			<div id="scroller">
				<volist name='cat_info["wap_content"]' id='vo'>
					<section id="section{pigcms{$i}">
						<div class="title">
							<i></i>{pigcms{$cat_info["wap_title"][$key]}
						</div>
						<div class="content-list">
							{pigcms{$vo|html_entity_decode}
						</div>
					</section>
				</volist>	
			</div>
		</div> -->
		<else />
		<div id="scroller">
			<section id="section1">
				<div class="content-list" style="text-align:center">
					暂无信息
				</div>
			</section>
		</div>
		</if>
		<div class="m-simpleFooter m-detail-buy pageSliderHide">
			<div class="m-simpleFooter-text pageSliderHide">
				<!-- 平台自营品类 -->
				<if condition='$cat_info["is_autotrophic"] eq 1'><a id="quickBuy" class="w-button w-button-main" <if condition='$cat_info["wap_content"]'>href="{pigcms{:U('platform_order',array('cat_id'=>$cat_info['cat_id'],'cat_fid'=>$cat_info['cat_fid']))}"<else />href="javascript:void(0)" style="background:#E7E7EB; color:#a5a6aa"</if>>立即预约</a></if>
				<!--第三方入驻-->
				<if condition='$cat_info["is_autotrophic"] eq 2'><a id="quickBuy" class="w-button w-button-main phone" data-phonetitle="下单电话" data-phone="{pigcms{$cat_info.outsourced_phone}">立即预约</a></if>
			</div>
		</div>
		<php>$no_footer_appoint = true;</php>
		<include file="footer"/>
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
	</body>
</html>

<script type="text/javascript">

	$(".cond_top,.after").css("height",$(".cond_top").width()*0.4375);

</script>