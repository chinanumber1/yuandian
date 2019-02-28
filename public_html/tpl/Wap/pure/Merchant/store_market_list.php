<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.store_alias_name}列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?222">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var location_url = "{pigcms{:U('Merchant/store_market_list_ajax')}";
			var market_id="<if condition="!empty($market_id)">{pigcms{$market_id}<else/>-1</if>";
			var now_cat_url="<if condition="!empty($now_cat_url)">{pigcms{$now_cat_url}<else/>-1</if>";
			var now_area_url="<if condition="!empty($now_area_url) && $all_area_list">{pigcms{$now_area_url}<else/>-1</if>";
			var now_sort_id="<if condition="!empty($now_sort_array)">{pigcms{$now_sort_array.sort_id}<else/>defaults</if>";
			<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/dropdown.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/store_list.js?210" charset="utf-8"></script>
	</head>
	<body>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				<script id="groupListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d.store_list[i].url }}">
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.store_list[i].list_pic) }}" alt="{{ d.store_list[i].store_name }}"/>
							</div>
							<div class="dealcard-block-right" style="font-family:'Microsoft YaHei' !important;">
								<div class="brand" style="padding:0px 8px 0px 1px;font-weight:bolder;float:left;">{{ d.store_list[i].store_name }}</div>
								<div class="brand" style="float:right;">
									{{# if(d.store_list[i].have_group == 1){ }}
										<i class="text-icon order-jiudian order-icon" style="background-color:#EAAD0D;width:20px;height:22px;font-size:14px;">{{ d.img.group_alias_name }}</i>
									{{# } }}
									{{# if(d.store_list[i].have_meal == 1){ }}
										<i class="text-icon order-jiudian order-icon" style="width:20px;height:22px;font-size:14px;">{{ d.img.meal_alias_name }}</i>
									{{# } }}
									{{# if(d.store_list[i].now_appoint){ }}
										<i class="text-icon order-jiudian order-icon" style="background-color:#0092DE;width:20px;height:22px;font-size:14px;">{{ d.img.appoint_alias_name }}</i>
									{{# } }}
								</div>
								<div style="clear:both"></div>
								<div class="price" style="margin-bottom:5px;font-size:14px;">商家有<span style="color: #fe5842;font-size: 18px;">{{ d.store_list[i].fans_count }}</span>个粉丝</div>
								<div class="rateInfo" style="margin-top:3px;float:left;">
								{{# if(d.store_list[i].pingjun){ }}
									<div class="starIconBg">
										<div class="starIcon" style="width:{{ d.store_list[i].xing }}%"></div>
									</div>
									<div class="starText">{{ d.store_list[i].pingjun }}</div>
								{{# }else{ }}
									<span style="color:#999">暂无评分</span>
								{{# } }}
								</div>
								<div class="rateInfo" style="float:right;margin-top:4px;">
									<span style="color:#909090;float:right;font-size:12px">{{ d.store_list[i].range_txt }}</span>
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
				<section class="storeListBox listBox">
					<dl></dl>
					<div class="shade hide"></div>
					<div class="no-deals hide">暂无店铺</div>
				</section>
				<div id="pullUp">
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		window.shareData = {
					"moduleName":"Merchant",
					"moduleID":"0",
					"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
					"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Merchant/store_list')}",
					"tTitle": "{pigcms{$config.store_alias_name}列表",
					"tContent": "{pigcms{$config.site_name}"
		};
		</script>
		{pigcms{$shareScript}
	</body>
</html>