<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>在线报修列表</title>
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
		<if condition="!$is_app_browser">
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
		<else />
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my_2.js?210" charset="utf-8"></script>
		</if>
		<style>
			.village_my nav.order_list section p{padding-left:0px;}
			.ko{
				z-index: 100;
				position: fixed;
				bottom: 0;
				width: 100%;
				text-align: center;
				font-size: 16px;
				color: #fff;
				background: #06c1ae;
				line-height: 16px;
				padding: 20px 0;
			}
			

			header #backBtn2 {
			    position: absolute;
			    width: 50px;
			    height: 100%;
			    top: 0;
			    left: 0;
			}
			
			header #backBtn2:after {
			    display: block;
			    content: "";
			    border-top: 2px solid white;
			    border-left: 2px solid white;
			    width: 12px;
			    height: 12px;
			    -webkit-transform: rotate(315deg);
			    background-color: transparent;
			    position: absolute;
			    top: 19px;
			    left: 19px;
			}

		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn2" onclick="location.href='{pigcms{:U('House/village_my',array('village_id'=>$now_village['village_id']))}'"></div>在线报修列表<div id="plus" onclick="location.href='{pigcms{:U('House/village_my_repair',array('village_id'=>$now_village['village_id']))}'"><img src="{pigcms{$static_path}images/new_my/recharge.png" /></div></header>
    </if>
		<div id="container">
			<div id="scroller" class="village_my">
				<if condition="$repair_list">
					<nav class="order_list">
						<volist name="repair_list" id="vo">
							<section class="link-url" data-url="{pigcms{:U('House/village_my_repair_detail',array('village_id'=>$vo['village_id'],'id'=>$vo['pigcms_id']))}">
								<p>{pigcms{$vo.content|msubstr=###,0,20}</p>
								<p class="money">
									<if condition='$vo["status"] eq 0'>
										<font color="red">未受理</font>
									<elseif condition='$vo["status"] eq 1' />
										<font color="green">物业已受理</font>
									<elseif condition='$vo["status"] eq 2' />
										<font color="green">维修员已受理</font>
									<elseif condition='$vo["status"] eq 3' />
										<font color="green">维修员已处理</font>
									<elseif condition='$vo["status"] eq 4' />
										<font color="green">已评价</font>
									</if>
								<em>{pigcms{$vo.time|date='Y-m-d H:i',###}</em></p>
							</section>
						</volist>
					</nav>
				<else/>
					<div class="noMoreDiv" style="margin-top:20px;background:#ebebeb;">您还没有使用在线报修功能</div>
				</if>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
			</div>
		</div>
		<if condition="$is_app_browser">
			<p class="ko" id="top-plus" onclick="location.href='{pigcms{:U('House/village_my_repair',array('village_id'=>$now_village['village_id']))}'">添加报修</p>
		</if>
		{pigcms{$shareScript}
	</body>
</html>