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
		<style>
			.shopDetail .dealcard-img{
				height: 64px;
			}
			.shopDetail .dealcard-block-right{
				margin-bottom:15px;
			}
		</style>
	</head>
	<body>
		<div class="pageSliderHide" style="display:none;"></div>
		<section class="shopDetail">
			<div class="imgInfo">		
				<if condition='$now_store["isverify"]'>
					<img src="./static/images/kd_rec.png" style="width:41px;height:15px;position: absolute;z-index: 15;margin:2px 0 0 0">
				</if>				
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
				</div>
			</div>
			<div class="locationInfo link-url" data-url="{pigcms{:U('Group/addressinfo',array('store_id'=>$now_store['store_id']))}">
				<div class="txt">{pigcms{$now_store.area_name}{pigcms{$now_store.adress}</div>
				<div class="phone" data-phone="{pigcms{$now_store.phone}"></div>
			</div>
            <if condition="$is_wap">
			<div class="linkInfo link-url" data-url="{pigcms{:U('Index/index',array('token'=>$now_store['mer_id']))}">
				<div class="icon web">网</div>
				<div class="txt">商家微官网</div>
			</div>
            </if>
			<if condition="$now_store['have_shop'] eq 1">
				<php>$nows_shop_store = M('Merchant_store_shop')->where(array('store_id'=>$now_store['store_id']))->find();</php>
				<div class="linkInfo link-url" data-url="<if condition="$nows_shop_store.is_mult_class eq 1">{pigcms{:U('Shop/classic_shop')}&shop_id={pigcms{$now_store['store_id']} <else />{pigcms{:U('Shop/index')}#shop-{pigcms{$now_store['store_id']}</if>">
					<div class="icon" style="background:#F72530;">{pigcms{$config.shop_alias_name}</div>
					<div class="txt">{pigcms{$config.shop_alias_name}</div>
				</div>
			</if>
			<if condition="$config['pay_in_store']">
				<div class="linkInfo link-url" data-url="{pigcms{:U('My/pay',array('store_id' => $now_store['store_id']))}">
					<div class="icon fu">付</div>
					<div class="txt">{pigcms{$config.cash_alias_name}</div>
				</div>
			</if>
			<if condition="$now_store['have_meal'] eq 1">
				<div class="linkInfo link-url" data-url="{pigcms{$now_store['wap_url']}">
					<div class="icon">{pigcms{$config.meal_alias_name}</div>
					<div class="txt">{pigcms{$config.meal_alias_name}</div>
				</div>
			</if>
		</section>
		<if condition="$store_group_list">
			<section class="storeProList introList">
				<div class="titleDiv"><div class="title">本店{pigcms{$config.group_alias_name}</div></div>
				<ul class="goodList">
					<volist name="store_group_list" id="vo">
						<li class="link-url" data-url="{pigcms{$vo.url}" <if condition="$i gt 2">style="display:none;"</if>>
							<php> if($vo['pin_num'] > 0){ </php><div class="pin_style"></div> <php> }  </php>
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.group_name}"/>
							</div>
							<div class="dealcard-block-right">
								<div class="title">{pigcms{$vo.group_name}</div>
								<div class="price">
									<strong>{pigcms{$vo['price']}</strong><span class="strong-color">元</span><if condition="$vo['wx_cheap']"><span class="tag">微信再减{pigcms{$vo.wx_cheap}元</span></if><span class="line-right">已售{pigcms{$vo['sale_count']+$vo['virtual_num']}</span>
								</div>
							</div>
						</li>
					</volist>
					<if condition="count($store_group_list) gt 2"><li class="more">全部展开</li></if>
				</ul>
			</section>
		</if>
		<if condition="$now_appoint">
			<section class="storeProList introList">
				<div class="titleDiv"><div class="title">本店预约</div></div>
				<ul class="goodList">
					<volist name="now_appoint" id="vo">
						<li  class="link-url" data-url="{pigcms{$vo.url}" <if condition="$i gt 2">style="display:none;"</if>>
							<div class="dealcard-img imgbox">
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.appoint_name}"/>
							</div>
							<div class="dealcard-block-right">
								<div class="title">{pigcms{$vo.appoint_name}</div>
								<div class="price">
									<if condition='$vo["is_appoint_price"] neq 0'><strong>{pigcms{$vo['appoint_price']}</strong><span class="strong-color">元</span><else /><strong>面议</strong></if><span class="line-right">已预约{pigcms{$vo['appoint_sum']}</span>
								</div>
							</div>
						</li>
					</volist>
					<if condition="count($now_appoint) gt 2"><li class="more">全部展开</li></if>
				</ul>
			</section>
		</if>
		<script type="text/javascript">
		window.shareData = {
					"moduleName":"Merchant",
					"moduleID":"0",
					"imgUrl": "{pigcms{$now_store.all_pic.0}",
					"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Merchant/shop', array('store_id' => $now_store['store_id']))}",
					"tTitle": "{pigcms{$now_store.name}",
					"tContent": ""
		};
		</script>
		{pigcms{$shareScript}
		<include file="kefu" />
	</body>
</html>