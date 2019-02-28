<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>推荐{pigcms{$config.appoint_alias_name}</title>
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
			var location_url = "{pigcms{:U('House/village_appointlist',array('village_id'=>$now_village['village_id']))}",totalPage = {pigcms{$totalPage};var backUrl = "{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_grouplist.js?213" charset="utf-8"></script>
		<style>
			body{background-color:#f4f4f4;}
			.appoint{border:none;}
			.dealcard{padding:0px;}
			.dealcard dd{padding:8px;}
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
        <header class="pageSliderHide"><div id="backBtn2" onclick="history.go(-1);"></div>推荐{pigcms{$config.appoint_alias_name}</header>
    </if>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新页面</span>
				</div>
				<section class="appoint">
					<dl class="likeBox dealcard" id="listDom">
					</dl>
				</section>
				<div id="pullUp" style="display:none;">
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
				<script id="BoxTpl" type="text/html">
					{{# for(var i = 0, len = d.length; i < len; i++){ }}
						<dd class="link-url" data-url="{pigcms{$config.site_url}/wap.php?c=Appoint&a=detail&appoint_id={{ d[i].appoint_id }}">
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d[i].list_pic) }}" alt="{{d[i].appoint_name }}"/>
							</div>
							<div class="dealcard-block-right">									
								<div class="brand">{{d[i].appoint_name }} {{# if(d[i].juli){ }}<span class="location-right">{{d[i].juli }}</span>{{# } }}</div>	
								<div class="title" style="font-size:14px;margin:4px 0;">{{# if(d[i].payment_money){ }}定金:￥{{d[i].payment_money }}{{# }else{ }}无需定金{{# } }}|{{d[i].appoint_content }}</div>
								<div class="price">
									{{# if(d[i].appoint_type == 1){ }}<span class="imgLabel shangmen"></span>{{# }else{ }}<span class="imgLabel daodian"></span>{{# } }}
									{{# if(d[i].appoint_sum ){ }}<span class="line-right">已预约{{d[i].appoint_sum }}</span>{{# } }}
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>