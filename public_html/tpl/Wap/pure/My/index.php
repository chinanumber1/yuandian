<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>个人中心</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script>

		<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
    <style>
	    .my-account {
	        color: #333;
	        position: relative;
	        display: block;
	        width:100%;
	        position: relative;
	        height:6rem;
	    }
	    .account-bg{
			position: absolute;
		    top: 0;
		    left: 0;
		    height: 100%;
		    width: 100%;
		    z-index: -1;
	    }
	    .my-account>img {
	        height: 100%;
	        position: absolute;
	        right: 0;
	        top:0;
	        z-index: 0;
	    }
	    .my-account .user-info {
	        z-index: 1;
	        position: absolute;
	        /*top: 1.5em;
	        left: 6em;*/
	        top: 20px;
	        left: 70px;
	        box-sizing: border-box;
	        padding-left: 1.9em;
	        font-size: 13px;
	        color: #666;
	    }
	    .my-account .uname {
	        font-size: 18px;
	        color: #fff;
	        margin-top: .1em;
	        margin-bottom: .2em;
	    }
		.my-account .umoney {
			color: #fff;
	    	margin-bottom: 0.06em;
	    }
	    .my-account .avatar_box{
	    	position: absolute;
	        top: 1em;
	        left: 1em;
	        width: 5em;
	        height: 5em;
	        z-index:1;
			border-radius:100%;
	        border:2px solid #4eccbe;
	        -moz-border-radius:100%;
	        -webkit-border-radius:100%;
	        overflow:hidden;
	    }
	    .my-account .avater {
			width:100%;
			height:100%;
	    }
	    .phone{
			width:85px;
			float:left;
			z-index:100;
	    }
	    .data{
			position: absolute;
			top: 3em;
	        left: 9em;
			padding:2px 2px;
			border:1px solid #f9005e;
			border-radius:15px;
			color:#f9005e;
			width:80px;
			text-align:center;
			margin-top:-7px;
			z-index:100;
	    }
	    .titleImg{
			width:25px;
			height:25px;
			margin-right:10px;
	    }
	    .words{
			text-align:center;
			color:#5f5f5f;
			font-size:12px;
	    }
	    .wh25{
			width:25px;
			height:25px;
	    }
	    .foloow{
			float:left;
			width:25%;
			text-align:center;
			color:#5f5f5f;
			font-size:12px;
	    }
	    .foloow_b{
			border-left:1px solid #e5e5e5;
	    }
	    .padd7{
			padding:7px;
	    }
		#close_eye img{
			width: 16%;
			height: 16%;
		}
	</style>
</head>
<body>
	<div  class="my-account">
		<div class="account-bg <if condition="$now_user.phone">set_up</if>" >
			<img src="{pigcms{$static_path}images/new_my/my_bg.png" alt="" style="width:100%;height:100%;">
		</div>
		<div class="avatar_box <if condition="$now_user.phone">set_up</if>">
			<img class="avater" src="<if condition="$now_user['avatar']">{pigcms{$now_user.avatar}<else/>{pigcms{$static_path}images/new_my/pic-default.png</if>" alt="{pigcms{$now_user.nickname}头像"/>
		</div>
		<div class="user-info">
			<p class="uname <if condition="$now_user.phone">set_up</if>">{pigcms{$now_user.nickname}</p>
			<if condition="$now_user.phone lt 1">
				<a href="{pigcms{:U('bind_user')}">
					<p class="umoney phone" style="color:#f9005e;">未绑定手机号</p>
				</a>
			<else/>
				<p class="umoney phone <if condition="$now_user.phone">set_up</if>">{pigcms{$now_user.phone}</p>
			</if>
			<if condition="isset($config['specificfield'])">
				<a href="{pigcms{:U('inputinfo')}">
					<p class="data <if condition="$now_user.phone">set_up</if>">完善资料 ></p>
				</a>
			</if>
			<if condition="$config['sign_get_score'] gt 0">
				<php>if($today_sing){</php>
				<a href="javascript:void(0)">
					<p class="data" style="border:none;background-color:#ffea37;width:50px;color:#9f0000">已签到</p>
				</a>
				<php>}else{</php>
				<a href="<if condition="$config.wap_sign_url neq ''">javascript:check_http('{pigcms{$config.wap_sign_url}')<else />{pigcms{:U('sign')}</if>">
					<p class="data" style="border:none;background-color:#ffea37;width:50px;color:#9f0000">签到</p>
				</a>
				<php>}</php>
			</if>
		</div>
		<div class="set_up" style="position:absolute;width:50px;height:50px;right:0px;top:10px;z-index:100;"><img style="width:20px;height:20px;margin:15px 5px 0 15px;" src="{pigcms{$static_path}images/new_my/set_up.png" alt=""></div>
	</div>
	<if condition="!empty($scroll_msg)">
		<div style=" height: 16px; margin-bottom: 10px; padding: 10px 15px;background: #ffffff;" class="scroll_msg" >
			<div style="background: url({pigcms{$static_path}images/lbt_03.png) left 1px no-repeat; background-size: 14px; padding-left: 20px;">
				<div class=""  id="scrollText" style="border-left: #cfcfcf 1px solid; padding-left: 8px; font-size: 12px; height: 15px;">
					<marquee  style="line-height: 16px;  white-space: nowrap;    " >
						<volist name="scroll_msg" id="vo">
							<div style="display:inline-block">
								<span style="padding-right:20px;color:#ff2c4d;">
									<a>{pigcms{$vo.content}</a>
								</span>
							</div>
						</volist>
					</marquee>
				</div>
			</div>
		</div>
		<style>
		#scrollText div a{ color: #ff2c4d;}
		</style>
		<link rel="stylesheet" href="{pigcms{$static_public}font-awesome/css/font-awesome.min.css">
	</if>
	<div id="money" style="padding:0 10px 10px;background-color:#fff;">
		<div id ="my_wallet" style="border-bottom:1px solid #e5e5e5;padding-bottom:10px;">
			<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/money.png" />
			<div  style="padding-top:13px;width:60%;">我的钱包</div>
			<if condition="$config.open_score_fenrun eq 0"><img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;" /></if>
		</div>
		<div style="padding:5px 0px;color:#00c4ac;text-align:center;<if condition="$config['open_score_fenrun']">font-size:14px;<else />font-size:20px;</if>display:box;display:-webkit-box;">
			<if condition="$config.open_score_fenrun eq 1">
				<div id="my_fenrun_wallet" style="width:33%;flex: 1;">{pigcms{$now_user.fenrun_money|floatval}<p class="words">分润钱包</p></div>
			</if>
			<div style="width:33%;flex: 1;">
				<div id="close_eye" <if condition="($config['twice_verify'] eq 0) OR $_COOKIE['my_wallet_time'] OR $config['twice_verify_wallet'] eq 0">style="display:none;"</if>>
					<img src="{pigcms{$static_path}images/new_my/close_eye.png" >
				</div>
				<div id="wallet_money" <if condition="$config['twice_verify'] AND empty($_COOKIE['my_wallet_time'])  AND $config['twice_verify_wallet']">style="display:none;"</if>>{pigcms{$now_user.now_money|floatval}</div>
				<if condition="$now_user.frozen_money gt 0 AND $config.open_frozen_money eq 1 AND $now_user.free_time gt $_SERVER['REQUEST_TIME']">
				<p class="words" id="balance_frozen_money"  style="color:red">(冻结：{pigcms{$now_user.frozen_money|floatval})</p></if>
				
				<p class="words" id="balance_money">余额</p>
				
			</div>
			<div id="my_score" style="width:33%;flex: 1;">{pigcms{$now_user.score_count|floatval}<p class="words" id="score">{pigcms{$config.score_name}</p></div>
			<if condition="$now_user.level eq 0">
				<div id="my_level" style="width:33%;flex: 1;color:#e5e5e5;">VIP{pigcms{$now_user.level}<p class="words">等级</p></div>
			<else/>
				<div id="my_level" style="width:33%;flex: 1;">{pigcms{$now_user.lname}<p class="words">等级</p></div>
			</if>
			<div style="clear:both;"></div>
		</div>
		
	</div>
	
	<if condition="$config.open_score_fenrun eq 1 ">
	<dl style="padding:0 10px 10px;background-color:#fff;margin-top:10px;">
		<div style="border-bottom:1px solid #e5e5e5;padding-bottom:10px;">
			<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/yjt.png">
			<div style="padding-top:13px;width:60%;">我的佣金</div>
		</div>
		<dd style="padding:15px 0px 10px 0;text-align:center;display: flex;">
			<a href="{pigcms{:U('Fenrun/user_free_award_list')}" style="text-align: center; color: #5f5f5f; flex: 1; border-right: #f1f1f1 1px solid;">
				<div style="display: inline-block; background: url({pigcms{$static_path}images/grzx_03.png) left center no-repeat; padding-left: 33px; background-size: 22px 24px;">
					<p style="font-size: 20px; color: #ffa400;">{pigcms{$now_user.free_award_money|floatval}</p>
					<p style="font-size: 12px; color: #5f5f5f;">可用佣金</p>
				</div>
			</a>
			<a href="{pigcms{:U('Fenrun/frozen_award_index')}" style="text-align: center; color: #5f5f5f; flex: 1">
				<div style="display: inline-block; background: url({pigcms{$static_path}images/grzx_05.png) left center no-repeat; padding-left: 33px; background-size: 22px 24px;">
					<p style="font-size: 20px; color: #419aff;">{pigcms{$now_user.frozen_award_money|floatval}</p>
					<p style="font-size: 12px; color: #5f5f5f;">冻结佣金</p>
				</div>
			</a>
		</dd>
	</dl>
	</if>
	<dl style="padding:0 0px 10px;background-color:#fff;margin-top:10px;">
		<div style="border-bottom:1px solid #e5e5e5;padding:0 10px 10px;">
			<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/order.png" />
			<div style="padding-top:13px;width:60%;">我的订单</div>
		</div>
		<dd style="padding-bottom:15px;color:#00c4ac;text-align:center;font-size:20px;">
			<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('My/group_order_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/group.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">{pigcms{$config.group_alias_name}订单</p></div>
			<if condition="isset($config['store_open_shop'])"><div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('My/shop_order_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/meal.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">{pigcms{$config.shop_alias_name}订单</p></div></if>
			<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('My/foodshop_order_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/shop.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">{pigcms{$config.meal_alias_name}订单</p></div>
			<if condition="isset($config['appoint_alias_name'])"><div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('My/appoint_order_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/appoint.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">{pigcms{$config.appoint_alias_name}订单</p></div></if>
			<if condition="$config['pay_in_store']">
				<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('My/store_order_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/store.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">到店付订单</p></div>
			</if>
			<if condition="isset($config['gift_alias_name'])">
			<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('My/gift_order_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/gift.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">{pigcms{$config['gift_alias_name']}订单</p></div>
			</if>
			<if condition="$config['mobile_recharge_APIKey'] && $config['mobile_recharge_openid']">
				<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('Third_recharge/mobile_recharge_list')}'"><img class="wh25" src="{pigcms{$static_path}images/phone_ico1.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">话费订单</p></div>
			</if>
			<if condition="$config['open_sub_card'] eq 1">
				<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('Sub_card/sub_card_list')}'"><img class="wh25" src="{pigcms{$static_path}images/sub_card.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">免单订单</p></div>
			</if>
			<if condition="isset($config['wap_home_show_classify'])">
				<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('My/classify_order_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/pay.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">{pigcms{$config.classify_name}订单</p></div>
			</if>
			<if condition="isset($config['service_basic_km'])">
				<div style="float:left;width:25%;padding-top:15px;" onclick="location.href='{pigcms{:U('Service/need_list')}'"><img class="wh25" src="{pigcms{$static_path}images/new_my/service.png" /><p style="text-align:center;color:#5f5f5f;font-size:12px;">服务快派订单</p></div>
			</if>
		</dd>
		<div style="clear:both;"></div>
	</dl>
	<dl style="padding:0 10px 10px;background-color:#fff;margin-top:10px;">
		<div style="border-bottom:1px solid #e5e5e5;padding-bottom:10px;">
			<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/action.png" />
			<div style="padding-top:13px;width:60%;">我的卡券</div>
		</div>
		<dd style="padding:15px 0px 10px 0;text-align:center;font-size:20px;">
				<a href="{pigcms{:U('My/card_list',array('coupon_type'=>'system'))}" style="text-align: center; width: 25%; display: inline-block; float: left;">
					<img src="{pigcms{$static_path}images/new_my/gezxtp_09.png" width=29 height=20>
					<p style="font-size: 14px; color: #5f5f5f;">平台优惠券</p>
					<p style="font-size: 12px; color:#a0a0a0;">共<span style="color: #e94848;">{pigcms{$coupon_number}</span>张</p>
				</a>
				<a href="{pigcms{:U('My/card_list',array('coupon_type'=>'mer'))}" style="text-align: center; width: 25%; display: inline-block; float: left;">
					<img src="{pigcms{$static_path}images/new_my/gezxtp_14.png" width=25 height=20>
					<p style="font-size: 14px; color: #5f5f5f;">商家优惠券</p>
					<p style="font-size: 12px; color:#a0a0a0;">共<span style="color: #e94848;">{pigcms{$mer_number}</span>张</p>
				</a>
				<a href="{pigcms{:U('My/cards')}" style="text-align: center; width: 25%; display: inline-block; float: left;">
					<img src="{pigcms{$static_path}images/new_my/gezxtp_06.png" width=28 height=20>
					<p style="font-size: 14px; color: #5f5f5f;">会员卡</p>
					<p style="font-size: 12px; color:#a0a0a0;">共<span style="color: #e94848;">{pigcms{$card_number}</span>张</p>
				</a>
				<a href="{pigcms{:U('My/join_activity')}" style="text-align: center; width: 25%; display: inline-block; float: left;">
					<img src="{pigcms{$static_path}images/new_my/gezxtp_03.png" width=20 height=20>
					<p style="font-size: 14px; color: #5f5f5f;">参与活动</p>
					<p style="font-size: 12px; color:#a0a0a0;">共<span style="color: #e94848;">{pigcms{$activity_number}</span>张</p>
				</a>

			    <div style="clear:both;"></div>
		</dd>
	</dl>
	<dl style="padding:0 10px 10px;background-color:#fff;margin-top:10px;">
		<div style="border-bottom:1px solid #e5e5e5;padding-bottom:10px;">
			<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/follow.png" />
			<div style="padding-top:13px;width:60%;">收藏关注</div>
		</div>
		<dd style="padding:15px 0px;color:#00c4ac;text-align:center;font-size:20px;">
			<a href="{pigcms{:U('My/follow_merchant')}"><div class="foloow"><div class="padd7">关注商家</div></div></a>
			<a href="{pigcms{:U('My/group_collect')}"><div class="foloow foloow_b"><div class="padd7">{pigcms{$config.group_alias_name}收藏</div></div></a>
			<a href="{pigcms{:U('My/group_store_collect')}"><div class="foloow foloow_b"><div class="padd7">{pigcms{$config.meal_alias_name}收藏</div></div></a>
			<if condition="isset($config['appoint_alias_name'])">
				<a href="{pigcms{:U('My/appoint_collect')}"><div class="foloow foloow_b" style="width:24%;"><div class="padd7">{pigcms{$config.appoint_alias_name}收藏</div></div></a>
			</if>
		</dd>
		<div style="clear:both;"></div>
	</dl>

	<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
		<a href="{pigcms{:U('My/my_spread_code')}" <if condition="$config.open_score_fenrun eq 0">style="display:none"</if>>
			<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
				<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/spread.png" />
				<div style="padding-top:13px;width:60%;">推广有奖</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
			</div>
		</a>
		<if condition="$config.open_distributor eq 1">
			
			<a href="{pigcms{:U('Distributor_agent/agent')}" <>
				<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
					<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/spread.png" />
					<if condition="empty($distributor['agent'])">
					<div style="padding-top:13px;width:110px;float:right;color:#949494;">暂未开通{pigcms{$config.agent_alias_name}</div>
					</if>
					<div style="padding-top:13px;width:60%;">我是{pigcms{$config.agent_alias_name}</div>
					<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
				</div>
			</a>
		</if>
		
		<a href="{pigcms{:U('My/lottery_shop_list')}" <if condition="$config.open_share_lottery eq 0">style="display:none"</if>>
			<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
				<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/lottery.png" />
				<div style="padding-top:13px;width:60%;">分享奖励</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
			</div>
		</a>
		
		<a href="<if condition="$config.open_distributor eq 1 AND empty($distributor['distributor'])">{pigcms{:U('Distributor_agent/index')}<else />{pigcms{:U('My/my_spread')}</if>" <if condition="$config.open_user_spread eq 0">style="display:none"</if>>
			<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
				<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/extension.png" />
				<if condition="$config.open_distributor eq 1 AND empty($distributor['distributor'])">
					<div style="padding-top:13px;width:110px;float:right;color:#949494;">暂未开通{pigcms{$config.distributor_alias_name}</div>
				</if>
				<div style="padding-top:13px;width:60%;">我的推广</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
			</div>
		</a>
	
			
		<a href="{pigcms{:U('SetAccountDeposit/index')}" <if condition="$config.open_account_deposit eq 0">style="display:none"</if>>
			<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
				<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/allinyun.png" />
				<div style="padding-top:13px;width:60%;">存管账户设置</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
			</div>
		</a>
		
		
		
		<a href="{pigcms{:U('House/my_village')}" style="display:none">
			<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
				<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/extension.png" />
				<div style="padding-top:13px;width:60%;">我的小区</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
			</div>
		</a>
		
		
		<if condition="$config['wap_home_show_classify']">
			<a href="{pigcms{:U('Classify/myfabu',array('uid'=>$now_user['uid']))}">
				<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
					<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/release.png" />
					<div style="padding-top:13px;width:60%;">我的发布</div>
					<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
				</div>
			</a>
		</if>


		<if condition="!empty($now_house_worker)">
			<a href="{pigcms{:U('Worker/index')}">
				<div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
					<img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/order.png" />
					<div style="padding-top:13px;width:60%;">社区<if condition='$now_house_worker["type"] eq 0'>客服<else />维修</if></div>
					<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
				</div>
			</a>
		</if>


        <if condition="$config['find_msg']">
            <a href="{pigcms{:U('Discover/my_discover')}">
                <div style="padding-bottom:10px;border-bottom:1px solid #e5e5e5;">
                    <img class="titleImg" style="margin-top:10px;float:left;" src="{pigcms{$static_path}images/new_my/find.png" />
                    <div style="padding-top:13px;width:60%;">我的发现</div>
                    <img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" style="float:right;margin-top:-19px;width:10px;"></img>
                </div>
            </a>
        </if>

		<div style="clear:both;"></div>
	</dl>
		<include file="Public:footer"/>

		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			var site_url = '{pigcms{$config.site_url}';
			var jump_url ;
			var phone="{pigcms{$user_session['phone']}";
			$("#verify_phone").html(phone.substring(0,3)+"****"+phone.substring(8,11));
			if($.cookie('my_wallet_time')){
				var in_time = true;
				$('#close_eye').css('display','none');
				$('#wallet_money').css('display','block');
			}else{
				var in_time = false;
			}
			setInterval(function(){
				if(!$.cookie('my_wallet_time')&&twice_verify_wallet){

					$('#close_eye').css('display','block');
					$('#wallet_money').css('display','none');
				}
			},100);
			$('.set_up').on('click',function(){
				location.href =	"{pigcms{:U('myinfo')}";
			});
			<if condition="$config.open_score_fenrun eq 0">
			$('#my_wallet,#my_score,#my_level,#wallet_money,#balance_money').on('click',function(){
				bio_verify({location:"{pigcms{:U('My/my_money')}",twice:twice_verify,hide:'',visible:'',submit:'',cookie:1});
			});
			<else />
				$('#my_score').on('click',function(){
					bio_verify({location:"{pigcms{:U('My/score_list')}",twice:twice_verify,hide:'',visible:'',submit:'',cookie:1});
				});
				$('#wallet_money').on('click',function(){
					bio_verify({location:"{pigcms{:U('My/money_list')}",twice:twice_verify,hide:'',visible:'',submit:'',cookie:1});
				});
				$('#my_fenrun_wallet').on('click',function(){
					bio_verify({location:"{pigcms{:U('Fenrun/fenrun_money_list')}",twice:twice_verify,hide:'',visible:'',submit:'',cookie:1});
				});
				$('#my_level').on('click',function(){
					bio_verify({location:"{pigcms{:U('My/levelUpdate')}",twice:twice_verify,hide:'',visible:'',submit:'',cookie:1});
				});
			
			</if>
			$('#close_eye').click(function(){
				if(twice_verify_wallet){
					bio_verify({location:"",twice:twice_verify,hide:'#close_eye',visible:'#wallet_money',submit:'',cookie:1});
				}else{
					$('#close_eye').css('display','none');
					$('#wallet_money').css('display','block');
				}
			});

			// $('#wallet_money').click(function(){
				// if(in_time){
					// location.href =	"{pigcms{:U('My/my_money')}";
				// }else{
					// $('#close_eye').css('display','block');
					// $('#wallet_money').css('display','none');
					// bio_verify({location:"",twice:twice_verify,hide:'#close_eye',visible:'#wallet_money',submit:'',cookie:1});
				// }
			// });
			<if condition="$config['twice_verify']">var twice_verify = true;<else />var twice_verify = false;</if>
			<if condition="$config['twice_verify_wallet']">var twice_verify_wallet = true;<else />var twice_verify_wallet = false;</if>
			<if condition="$_SESSION['user']['verify_end_time']">var verify_end_time = {pigcms{$_SESSION['user']['verify_end_time']};</if>

			//$('.scroll_msg').show();
			
			function check_http(url){
				url = url.substr(0,4).toLowerCase() == "http" ? url : "http://" + url;
				window.location.href= url;
			}
		</script>
		<!--<script src="{pigcms{$static_path}js/bioAuth.js"></script>-->
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
		{pigcms{$BioAuthticMethod}
		{pigcms{$shareScript}
		<script type="text/javascript">
		if(typeof wx != "undefined"){
			wx.ready(function(){
				if(window.__wxjs_environment === 'miniprogram'){
					wx.miniProgram.switchTab({url: '/pages/my/index'});
				}
			});
		}
		</script>
	</body>
</html>