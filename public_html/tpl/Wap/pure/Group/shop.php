<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>店铺详情</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/shop.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/shop.js?211" charset="utf-8"></script>
	</head>
	<body>
		<div class="pageSliderHide" style="display:none;"></div>
		<section class="shopDetail">
			<div class="imgInfo">
				<div class="dealcard-img imgbox" data-pics="<volist name="now_store['all_pic']" id="vo">{pigcms{$vo}<if condition="count($now_store['all_pic']) gt $i">,</if></volist>">
					<img src="{pigcms{$now_store.all_pic.0}" alt="{pigcms{$now_store.name}">
				</div>
				<div class="dealcard-block-right">
					<div class="brand">{pigcms{$now_store.name}</div>
					<div class="rateInfo">
						<if condition="$store_score">
							<div class="starIconBg"><div class="starIcon" style="width:{pigcms{$store_score['score_all']/$store_score['reply_count']*20}%;"></div></div><div class="starText">{pigcms{:number_format($store_score['score_all']/$store_score['reply_count'],1)}</div>
						<else/>
							<span style="color:#999">暂无评分</span>
						</if>
					</div>
					<a href="{pigcms{:U('Index/index',array('token'=>$now_store['mer_id']))}" class="btn">商家微官网</a>
					<if condition="$config['pay_in_store']">
						<a href="{pigcms{:U('My/pay',array('store_id' => $now_store['store_id']))}" class="btn">{pigcms{$config.cash_alias_name}</a>
					</if>
				</div>
			</div>
			<div class="locationInfo link-url" data-url="{pigcms{:U('Group/addressinfo',array('store_id'=>$now_store['store_id']))}">
				<div class="txt">{pigcms{$now_store.area_name}{pigcms{$now_store.adress}</div>
				<div class="phone" data-phone="{pigcms{$now_store.phone}"></div>
			</div>
		</section>
		<if condition="$store_group_list">
			<section class="storeProList introList">
				<div class="titleDiv"><div class="title">本店{pigcms{$config.group_alias_name}({pigcms{:count($store_group_list)})</div></div>
				<dl class="likeBox dealcard">
					<volist name="store_group_list" id="vo">
						<dd class="link-url" data-url="{pigcms{$vo.url}">
							<php> if($vo['pin_num'] > 0){ </php><div class="pin_style"></div> <php> }  </php>
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.group_name}"/>
							</div>
							<div class="dealcard-block-right">
								<div class="title">{pigcms{$vo.group_name}</div>
								<div class="price">
									<strong>{pigcms{$vo['price']}</strong><span class="strong-color">元</span><div class="line_m">{pigcms{$vo.old_price}元</div><if condition="$vo['wx_cheap']"><span class="tag">微信再减{pigcms{$vo.wx_cheap}元</span></if><span class="line-right">{pigcms{$vo['sale_txt']}</span>
								</div>
							</div>
						</dd>
					</volist>
					
				</ul>
			</section>
		</if>
		<if condition="$index_sort_group_list && $merchant_link_showOther">
			<section class="sysProList introList">
				<div class="titleDiv"><div class="title">为您推荐</div></div>
				<dl class="likeBox dealcard">
					<volist name="index_sort_group_list" id="vo">
						<dd class="link-url" data-url="{pigcms{$vo.url}">
							<php> if($vo['pin_num'] > 0){ </php><div class="pin_style"></div> <php> }  </php>
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.group_name}"/>
							</div>
							<div class="dealcard-block-right">
								<div class="brand">{pigcms{$vo.s_name}<if condition="$vo['range']"><span class="location-right">{pigcms{$vo.range}</span></if></div>
								<div class="title">{pigcms{$vo.intro}</div>
								<div class="price">
									<strong>{pigcms{$vo.price}</strong><span class="strong-color">元</span><div class="line_m">{pigcms{$vo.old_price}元</div> <if condition="$vo['wx_cheap']"><span class="tag">微信再减{pigcms{$vo.wx_cheap}元</span></if><span class="line-right">{pigcms{$vo['sale_txt']}</span>
								</div>
							</div>
						</dd>
					</volist>
				</dl>
			</section>
		</if>
		<style type="text/css">
		.line_m{text-decoration:line-through;display: inline;color: #ccc}
		</style>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<script type="text/javascript">
		window.shareData = {
			"moduleName":"Group",
			"moduleID":"0",
			"imgUrl": "{pigcms{$now_store.all_pic.0}",
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Group/shop', array('store_id' => $now_store['store_id']))}",
			"tTitle": "{pigcms{$now_store.name}",
			"tContent": "<php>$txt_info_arr = explode(PHP_EOL,$now_store['txt_info']);echo ($txt_info_arr[0]);</php>"
		};
		</script>
		{pigcms{$shareScript}
		<include file="kefu" />
	</body>
</html>