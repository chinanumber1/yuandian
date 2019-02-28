<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<if condition="$is_app_browser && $app_browser_type eq 'android'">
			<title>{pigcms{$_GET['title']}</title>
		<else/>
			<title>LBS位置信息</title>
		</if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?240"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?240" charset="utf-8"></script>
		<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0"></script>
		<script type="text/javascript">
			var backUrl = "{pigcms{:U('Home/index')}";
			var go_title = "{pigcms{$_GET['title']}";
			var go_long = "{pigcms{$_GET['long']}";
			var go_lat = "{pigcms{$_GET['lat']}";
			var go_address = "{pigcms{$_GET['address']}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/lbsshow.js?240" charset="utf-8"></script>

		<if condition="!$_GET['pic']">
			<style>
			.serviceListBox .block-right{ margin-left:0}
			</style>
		</if>
	</head>
	<body>
		<div id="container">
			<div id="scroller" style="min-height:666666px;">
				<section class="serviceListBox listBox">
					<dl>
						<dd>
							<if condition="$_GET['pic']">
								<div class="imgbox">
									<img src="{pigcms{$_GET['pic']}" alt="{pigcms{$_GET['title']}"/>
								</div>
							</if>
							<div class="block-right">
								<div class="brand">{pigcms{$_GET['title']}</div>
								<div class="desc">
									<span class="line-desc"><if condition="$_GET['phone']"><span class="phone" data-phone="{pigcms{$_GET['phone']}">拨打电话</span></if></span>
									<if condition='!empty($_GET["village_id"])'>
										<if condition="$is_app_browser && $app_browser_type eq 'android'">
											<span class="line-right"><a href="javascript:void(0)" id="map_event" class="goHere"><span class="see_nav">查看路线</span></a></span>
											<else />
											<span class="line-right"><a href="{pigcms{:U('route',array('title'=>$_GET['title'],'village_id'=>$_GET['village_id'],'long'=>$_GET['long'],'lat'=>$_GET['lat']))}" class="goHere"><span class="see_nav">查看路线</span></a></span>
											</if>
									<else />
									<span class="line-right"><a id="map_event" href="javascript:void(0)" data-href="{pigcms{:U('route',array('long'=>$_GET['long'],'lat'=>$_GET['lat'],'title'=>$_GET['title']))}" class="goHere"><span class="see_nav">查看路线</span></a></span>
									</if>

								</div>
							</div>
						</dd>
					</dl>
				</section>
				<section  id="biz-map"></section>
			</div>
		</div>

		<php>$no_footer=true;</php>
		<include file="House:footer"/>
		<script type="text/javascript">
		$('#map_event').click(function(){
            <if condition="$app_browser_type eq 'android'">
                window.lifepasslogin.startToNavigation(go_long,go_lat,go_title);
            </if>
		});

        </script>
		{pigcms{$shareScript}
	</body>
</html>