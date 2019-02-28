<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>{pigcms{$now_village.village_name}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?212"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/villagephone.js?210" charset="utf-8"></script>
		<style>
			#container{top:130px;border-top: 1px solid #edebeb;}
		</style>
	</head>
	<body>
		<if condition="!$is_app_browser">
			<header class="pageSliderHide">常用电话</header>
		</if>
		<section class="bigBtn phone pageSliderHide" data-phonetip="物业服务中心" data-phone="{pigcms{$now_village.property_phone}">
			拨打物业服务中心电话
		</section>
		<div id="container">
			<div id="scroller">
				<volist name="phone_list" id="vo">
					<section class="villageBox phoneBox">
						<div class="headBox">{pigcms{$vo.cat_name}</div>
						<dl>
							<volist name="vo['phone_list']" id="voo">
								<dd class="phone" data-phonetip="{pigcms{$voo.name}" data-phone="{pigcms{$voo.phone}">
									<div>{pigcms{$voo.name}</div>
									<span>{pigcms{$voo.phone}</span>
								</dd>
							</volist>
						</dl>
					</section>
				</volist>
			</div>
		</div>
		<include file="House:footer"/>
		{pigcms{$shareScript}
	</body>
</html>