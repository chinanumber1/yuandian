<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>分类</title>
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
		<!--script type="text/javascript">
		var app_version = '{pigcms{$_REQUEST["app_version"]}';
		</script-->
		<script type="text/javascript" src="{pigcms{$static_path}js/appoint_category.js?210" charset="utf-8"></script>
		<style>
			body{max-width:100%;}
			.hasManyCity #searchBox{margin-right:40px;}
			.leftBar{float:left;width:76px;background-color:#fff;}
			.leftBar .scrollerBox{width:76px;}
			.leftBar li{height:50px;line-height: 50px;padding: 0 10px;overflow: hidden;text-align:center;}
			.leftBar li.cur{color:#06c1ae;position:relative;border-left:3px solid #06c1ae;background-color:#F6F6F7;padding:0 8px;}
			.rightBar{float:left;}
			.rightBar dl{padding:0 8px;margin-top:8px;display:none;}
			.rightBar dl dd{width:50%;float:left;margin-bottom:10px; }
			.rightBar dl dd .remind{ position: absolute; width: 24px; height: 25px; right: 0px;  top: -5px;  }
			.rightBar dl dd .remind.platform{background: url({pigcms{$static_path}images/yyt1.png) center no-repeat; background-size: 24px 25px;}
			.rightBar dl dd .remind.third{background: url({pigcms{$static_path}images/yyt2.png) center no-repeat; background-size: 24px 25px;}
			.rightBar dl dd .box{margin-right:4px;text-align:center;    background-color: white; position: relative;}
			.rightBar dl dd:nth-of-type(even) .box{margin-right:0px;margin-left:4px;}
			.rightBar dl dd .box .imgBox{width:100%;}
			.rightBar dl dd .box .imgBox img{width:100%;height:100%;}
			.rightBar dl dd .box .catName{padding:6px 0;}
		</style>
	</head>
	<body>
		<header <if condition="$config['many_city']">class="hasManyCity"</if>>
			<if condition="$config['many_city']">
				<div id="cityBtn" class="link-url" data-url="{pigcms{:U('Changecity/index')}">{pigcms{$config.now_select_city.area_name}</div>
			</if>
			<!--div id="locaitonBtn" class="link-url" data-url="{pigcms{:U('Merchant/around')}"></div-->
			<div id="searchBox">
				<a href="{pigcms{:U('Search/index')}">
					<i class="icon-search"></i>
					<span>请输入您想找的内容</span>
				</a>
			</div>
			<div id="qrcodeBtn"></div>
		</header>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<div style="height:10px;background-color:white;border-bottom:1px solid #f1f1f1;"></div>
		<div id="container" style="display:none;" class="pageSliderHide">
			<div class="leftBar">
				<ul class="scrollerBox">
					<volist name="all_category_list" id="vo">
						<li data-catid="{pigcms{$vo.cat_id}" <if condition="$vo['cat_id'] eq $now_cat_id">class="cur"</if>>{pigcms{$vo.cat_name}</li>
					</volist>
				</ul>
			</div>
			<div class="rightBar">
				<div class="scrollerBox">
					<volist name="all_category_list" id="vo">
						<dl id="right_{pigcms{$vo.cat_id}" class="clearfix">
							<volist name="vo['category_list']" id="voo">
								<dd class="link-url" data-url="{pigcms{:U('Appoint/two_category',array('cat_id'=>$voo['cat_id']))}">
									<div class="box">
										<div class="imgBox">
											<img data-src="{pigcms{$voo.cat_big_pic}"/>
										</div>
										<div class="catName">{pigcms{$voo.cat_name}</div>

										<!-- 新加 -->
										<if condition="$voo.is_autotrophic eq 1">
										<div class="remind third"></div>
										<elseif condition="$voo.is_autotrophic eq 2"/>
										<div class="remind  platform"></div>
										</if>
										<!-- /新加 -->
										
									</div>
								</dd>
							</volist>
						</dl>
					</volist>
				</div>
			</div>
		</div>
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