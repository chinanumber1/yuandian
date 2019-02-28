<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>店铺列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/merchant.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/branch.js?210" charset="utf-8"></script>
	</head>
	<body>
		<section class="headerBar pageSliderHide"><div class="total">以下{pigcms{:count($now_group['store_list'])}店通用</div><div class="rightRange link-url" data-url="{pigcms{:U('Group/map',array('group_id'=>$now_group['group_id']))}"></div></section>
		<div id="container">
			<div id="scroller">
				<section class="storeBox">
					<dl class="storeList">
						<volist name="now_group['store_list']" id="vo">
							<dd class="link-url" data-url="{pigcms{:U('Group/shop',array('store_id'=>$vo['store_id']))}">
								<div class="name">{pigcms{$vo.name}</div>
								<div class="address">{pigcms{$vo.area_name}{pigcms{$vo.adress}</div>
								<if condition="$vo['range']"><div class="position"><div class="range">{pigcms{$vo.range}</div><if condition="$i eq 1"><div class="desc">离我最近</div></if></div></if>
								<div class="phone" data-phone="{pigcms{$vo.phone}"></div>
							</dd>
						</volist>
					</dl>
				</section>
			</div>
		</div>
	</body>
</html>