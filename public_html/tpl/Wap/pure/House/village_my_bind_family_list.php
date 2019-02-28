<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>绑定家属列表</title>
        </if>
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
        <script type="text/javascript">
        var delUrl = "{pigcms{:U('ajax_village_my_bind_family_del')}";
        </script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
		<style>
			.village_my nav.order_list section p{padding-left:0px; height:20px;}
			.village_my nav.order_list section p span{ width:30%; display:block; float:left; text-align:left}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>绑定家属列表<div id="plus" onclick="location.href='{pigcms{:U('House/village_my_bind_family_add',array('village_id'=>$now_village['village_id']))}'"><img src="{pigcms{$static_path}images/new_my/recharge.png" /></div></header>
    </if>
		<div id="container">
			<div id="scroller" class="village_my">
				<if condition="$family_list">
					<nav class="order_list">
						<volist name="family_list" id="vo">
							<section onClick="my_bind_family_del({pigcms{$vo['pigcms_id']})">
								<p class="money"><span><if condition='$vo["name"]'>{pigcms{$vo.name}<else/>暂无名称</if></span><span>{pigcms{$vo.phone}</span><span style="color:red; float:right">删除</span></p>
							</section>
						</volist>
					</nav>
				<else/>
					<div class="noMoreDiv" style="margin-top:20px;background:#ebebeb;">暂无绑定的家属</div>
				</if>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>