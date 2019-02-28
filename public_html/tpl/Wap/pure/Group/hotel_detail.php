<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>酒店详情</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/detail.css?213"/>
		  <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/trade_hotel.css?22" rel="stylesheet"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?216" charset="utf-8"></script>
		<script type="text/javascript"><if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/detail.js?216" charset="utf-8"></script>
		
		<style type="text/css">
		.clock {
		    position: absolute;
		    display: inline-block;
		    right: 10px;
		    border: 1px solid #DADADA;
		    border-radius: 5px;
		    padding: 1px 3px;
		    top: 25px;
		    background: url(../tpl/Wap/pure/static/img/index/clock.png) no-repeat;
		    background-size: 14px;
		    background-position: 4px 4px;
		    padding-left: 22px;
		}
		.clock .timerBox {
		    letter-spacing: 0.5px;
		}
		.clock .timer {
		    color: #FF658D;
		}
		#mpackageslist li {
			width: 35%;
			text-align:center;
			border-radius: 4px;
			border: 1px solid #ddd;
			color: #666;
			display: inline-block;
			line-height: 27px;
			margin: 0 5px 5px 10px;
			max-width: 230px;
			overflow: hidden;
			padding-left: 8px;
			padding-right: 8px;
			position: relative;
			text-decoration: none;
			text-overflow: ellipsis;
			white-space: nowrap;
			list-style-type: none;
		}
		#mpackageslist .current {
			border: 2px solid #fe5842;
		}
		#mpackageslist .current a {
			color: #fe5842;
		}
		.packetDiv{
			padding: 10px 0 0px 12px;
			color: #999;
			border-bottom: 1px solid #f1f1f1;
		}
		.product_info_list_left {
			float: left;
			text-align: right;
			color: #999;
		}
		
  		.collection{ position: absolute; width: 35px; height: 35px; border-radius: 100%; background: url({pigcms{$static_path}images/3.png) center no-repeat; z-index: 999; top:10px; right: 10px; }
		.collection:after{ display: block;  content: ''; width: 16px; height: 16px; background: url({pigcms{$static_path}images/2.png) center no-repeat; background-size: 16px; position: absolute; ); top: 50%; left: 50%;  margin: -8px; }
		.collection.on:after{ background: url({pigcms{$static_path}images/1.png) center no-repeat; background-size: 16px; }
		.goodList li.more {
		    border-bottom: none;
		    height: 26px;
		    line-height: 13px;
		    text-align: center;
		    color: #999;
		}
		.goodList li.more:after {
		    content: "";
		    display: inline-block;
		    margin-left: 6px;
		    width: 8px;
		    height: 8px;
		    border: 1px solid #999;
		    border-width: 0 1px 1px 0;
		    border-top-width: 0px;
		    border-right-width: 1px;
		    border-bottom-width: 1px;
		    border-left-width: 0px;
		    -webkit-transform: rotate(45deg);
		    margin-top: -6px;
		    vertical-align: top;
		}
		#pullUp {
		    /* height: 50px; */
		    /* line-height: 50px; */
		    text-align: center;
		     bottom: 0px; 
		    width: 100%;
		    position: absolute;
		}
		#container {
		    position: absolute;
		    z-index: 1;
		    top: 0px;
		 
		    left: 0;
		    width: 100%;
		    overflow: hidden;
		}
	
		#close_yui:hover{
			opacity: .3;
			color: #0062cc;
		}
		#close_yui:before{
			display: block;
			content: "";
			border-top: 2px solid #0851f5;
			border-left: 2px solid #0851f5;
			width: 10px;
			height: 10px;
			-webkit-transform: rotate(315deg);
			background-color: transparent;
			position: absolute;
			top: 13px;
			left: 19px;
		}
		.mui-title{
			right: 40px;
			left: 40px;
			display: inline-block;
			overflow: hidden;
			width: auto;
			margin: 0;
			text-overflow: ellipsis;
			font-size: 17px;
			font-weight: 500;
			line-height: 44px;
			position: absolute;
			display: block;
			/* width: 100%; */
			margin: 0 -10px;
			padding: 0;
			text-align: center;
			white-space: nowrap;
			color: #000;
		}
		</style>
		
		<script type="text/javascript">
		 $(function(){

		    $(".collection").click(function(){
		    	var uid = "{pigcms{$user_session['uid']}";
			  	if(!uid){
		  			layer.open({content:'请先登录再进行收藏！',btn: ['确定'],end:function(){location.href="{pigcms{:U('Login/index')}";}});
		  			return false;
			  	}

	      	if($(this).hasClass("on")){
		        $(this).removeClass("on");
				$.post('{pigcms{:U('My/ajax_group_collect')}', {id: {pigcms{$_GET['group_id']},type:'group_detail',action:'del'}, function(data, textStatus, xhr) {
					layer.open({
					    content: data.info
					    ,skin: 'msg'
					    ,anim: 'up'
					    ,time: 2 //2秒后自动关闭
				  	});
				});
		   
		      }else{
		        $(this).addClass("on");
				$.post('{pigcms{:U('My/ajax_group_collect')}', {id: {pigcms{$_GET['group_id']},type:'group_detail',action:'add'}, function(data, textStatus, xhr) {
					layer.open({
					    content: data.info
					    ,skin: 'msg'
					    ,anim: 'up'
					    ,time: 2 //2秒后自动关闭
				  	});
				});
		   
		      }
		    });
		  });
		</script>

	</head>
	<body>
		<div id="container" >
			<div id="scroller" style="padding-bottom:50px">
				<div id="pullDown" style="background-color:#06c1ae;color:white;">
					<span class="pullDownLabel" style="padding-left:0px;"><i class="yesLightIcon" style="margin-right:10px;vertical-align:middle;"></i>{pigcms{$config.wechat_name} 精心为您优选</span>
				</div>
				<section class="imgBox">
					<img src="{pigcms{$now_group.all_pic.0.m_image}" class="view_album" data-pics="<volist name="now_group['all_pic']" id="vo">{pigcms{$vo.m_image}<if condition="count($now_group['all_pic']) gt $i">,</if></volist>"/>
					<div class="imgCon">
						<div class="title">{pigcms{$now_group.group_name}</div>
						<div class="desc">{pigcms{$now_group.intro}</div>
					</div>
					<div class="back"></div>
					<div class="collection <if condition="$is_collect">on</if>"></div>
				</section>
				
				<section class="buyBox">
					<!--div class="priceDiv">
						<span class="price">￥<strong>{pigcms{$now_group['price']}<if condition="$now_group.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$now_group.extra_pay_price|floatval}{pigcms{$config.extra_price_alias_name}</if></strong><span class="old">￥<del>{pigcms{$now_group.old_price}</del></span></span>
						<if condition="$now_group['begin_time'] gt $_SERVER['REQUEST_TIME']">
							<span class="clock"><span class="time_d">{pigcms{$time_array['d']}</span>天 <span class="timerBox"><span class="timer time_h">{pigcms{$time_array['h']}</span>:<span class="timer time_m">{pigcms{$time_array['m']}</span>:<span class="timer time_s">{pigcms{$time_array['s']}</span></span></span>
						<elseif condition="$now_group['end_time'] gt $_SERVER['REQUEST_TIME'] AND $now_group['begin_time'] lt $_SERVER['REQUEST_TIME'] AND $now_group['type'] eq 1" />
							<if condition='$now_group["is_appoint_bind"]'>
								<a class="btn buy-btn btn-large btn-strong" href="{pigcms{:U('Appoint/detail',array('appoint_id'=>$now_group['appoint_id']))}">立即预约</a>
							<elseif condition="$now_group['trade_type'] eq 'hotel'"/>
								<a class="btn buy-btn btn-large btn-strong" href="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id']))}">立即预订</a>
							<else />
								<a class="btn buy-btn btn-large btn-strong" href="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id']))}">立即购买</a>
							</if>
						</if>
					</div-->
					<if condition="$now_group['wx_cheap']">
                        <if condition="$is_app_browser">
                        <div class="cheapDiv">优惠 <span class="tag">APP购买再减{pigcms{$now_group.wx_cheap}元</span></div>
                        <else/>
						<div class="cheapDiv">优惠 <span class="tag">微信购买再减{pigcms{$now_group.wx_cheap}元</span></div>
                        </if>

					</if>
					

					<if condition="isset($mpackages) AND !empty($mpackages)">
					<div class="packetDiv" id="mpackageslist">
						<table style="width:100%">
						<tr>
						<td width="15%" valign="top">套餐：</td>
						<td>
						 <ul style="width: 90%;">
						   <volist name="mpackages" id="vv">
							  <a href="{pigcms{:U('Group/detail',array('group_id'=>$key))}"><li <if condition="$key eq $now_group['group_id']"> class="current"</if> >{pigcms{$vv}</li></a>
						   </volist>
						</ul>
						</td>
						</tr>
						</table>
					</div>
					</if>
					<if condition="empty($user_session) && $config['user_score_max_use']">
						 <div class="cheapDiv link-url" data-url="{pigcms{:U('Login/index')}">{pigcms{$config.score_name}抵现 <span class="tag">请先登录查看可抵现金额</span></div>
					<elseif condition="$user_coupon_use['score']"/>
                        <div class="cheapDiv">{pigcms{$config.score_name}抵现 <span class="tag">本单最高可用{pigcms{$user_coupon_use.score}{pigcms{$config.score_name}抵{pigcms{$user_coupon_use.score_money}元</span></div>
					</if>
					
					<if condition="$merchant_card_spread_info && $merchant_card_spread_info['card_discount']">
						<if condition="empty($user_session)">
							<div class="cheapDiv link-url" data-url="{pigcms{:U('Login/index')}">领取商家会员卡结算 <span style="color:red;">{pigcms{$merchant_card_spread_info.card_discount}折</span> 优惠 <span class="tag">请先登录</span></div>
						<elseif condition="!$merchant_card_spread_info['get_card']"/>
							<div class="cheapDiv link-url" data-url="{pigcms{$merchant_card_spread_info.url}">领取商家会员卡结算 <span style="color:red;">{pigcms{$merchant_card_spread_info.card_discount}折</span> 优惠 <span class="tag">点击领取</span></div>
						<else/>
							<div class="cheapDiv link-url" data-url="{pigcms{$merchant_card_spread_info.url}">领取商家会员卡结算 <span style="color:red;">{pigcms{$merchant_card_spread_info.card_discount}折</span> 优惠 <span class="tag">已经领取</span></div>
						</if>
					</if>
					
					<if condition="$now_group['no_refund'] eq 1">
						<div class="saleDiv">该{pigcms{$config.group_alias_name}为抢购商品，支付后不可退款
						<if condition="$now_group.sale_txt neq ''"><span class="sale" ><i class="yesIcon"></i>{pigcms{$now_group['sale_txt']}</span></if>
						</div>
					</if>
					<if condition="$now_group['trade_type']!='hotel' AND $now_group['no_refund'] eq 0">
					<div class="saleDiv">
						<span><i class="yesLightIcon"></i>随时退</span>
						<span><i class="yesLightIcon"></i>过期退</span>
						<if condition="$now_group.sale_txt neq ''"><span class="sale"><i class="yesIcon"></i>{pigcms{$now_group['sale_txt']}</span></if>
					</div>
					</if>
				</section>
					
				<if condition="!empty($reply_list)">
					<section class="scoreBox link-url" data-url="{pigcms{:U('Group/feedback',array('group_id'=>$now_group['group_id']))}">
						<div class="rateInfo">
							<div class="starIconBg"><div class="starIcon" style="width:{pigcms{$now_group['score_mean']*20}%;"></div></div>
							<div class="starText">{pigcms{$now_group.score_mean}</div>
							<div class="right">{pigcms{$now_group.reply_count} 人评价</div>
						</div>
					</section>
				</if>
				
				<php>if($now_group['store_list']){ </php>
				<section class="storeBox">
					<dl class="storeList">
						<volist name="now_group['store_list']" id="vo" offset="0" length="2">
							<dd class="link-url" data-url="<if condition="$config.open_extra_price eq 1">{pigcms{:U('Shop/merchant_shop',array('store_id'=>$vo['store_id']))}<else />{pigcms{:U('Group/shop',array('store_id'=>$vo['store_id']))}</if>">
								<div class="name">{pigcms{$vo.name}</div>
								<div class="address">{pigcms{$vo.area_name}{pigcms{$vo.adress}</div>
								<if condition="$vo['range']"><div class="position"><div class="range">{pigcms{$vo.range}</div><if condition="$i eq 1"><div class="desc">离我最近</div></if></div></if>
								<div class="phone" data-phone="{pigcms{$vo.phone}"></div>
							</dd>
						</volist>
					</dl>
					<if condition="count($now_group['store_list']) gt 2">
						<div class="more link-url" data-url="{pigcms{:U('Group/branch',array('group_id'=>$now_group['group_id']))}">全部{pigcms{:count($now_group['store_list'])}家分店</div>
					</if>
				</section>
					<php>}</php>
				<if condition="$now_group['trade_type'] eq 'hotel'">
				<form id="buy-form" action="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id']))}" method="POST" class="wrapper-list" autocomplete="off">
					<div id="hotel-info-box">
						<div class="hotel-info detail-date datefixed">
							<div class="getin_room_a">
								<span>入住</span>
								<span class="ol_night">共{pigcms{$trade_hotel['days']}晚</span>
								<span>离店</span>
							</div>
							<div class="getin_room_b">
								<span><em class="indate" data-date="{pigcms{$trade_hotel.time_dep_time}">{pigcms{$trade_hotel.show_dep_time}</em><em class="startweek"></em></span>
								<span class="getin_fen">|</span>
								<span><em class="outdate" data-date="{pigcms{$trade_hotel.time_end_time}">{pigcms{$trade_hotel.show_end_time}</em><em class="endweek"></em></span>
							</div>
						</div>
						<div class="detail-main type">
							<ul></ul>
						</div>
						<input type="hidden" name="dep-time" id="dep-time" value="{pigcms{$trade_hotel.dep_time}"/>
						<input type="hidden" name="end-time" id="end-time" value="{pigcms{$trade_hotel.end_time}"/>
						<input type="hidden" name="cat-id" id="cat-id" value=""/>
					</div>
				
					
				</form>
				</if>
				<if condition="$now_group['cue_arr']">
					<section class="term introList">
						<div class="titleDiv"><div class="title">购买须知</div></div>
						<div class="content">
							<ul>
								<volist name="now_group['cue_arr']" id="vo">
									<if condition="$vo['value']">
										<li><b>{pigcms{$vo.key}：</b>{pigcms{$vo.value|nl2br=###}</li>
									</if>
								</volist>
							</ul>
						</div>
					</section>
				</if>
				
				<section class="detail introList">
					<div class="titleDiv"><div class="title">本单详情</div></div>
					<div class="content">{pigcms{$now_group.content}</div>
				</section>
				
				<if condition="!empty($reply_list)">
					<section class="comment introList">
						<div class="titleDiv"><div class="title">评价<div class="rateInfo"><div class="starIconBg"><div class="starIcon" style="width:{pigcms{$now_group['score_mean']*20}%;"></div></div><div class="starText">{pigcms{$now_group.score_mean}</div></div><div class="right">{pigcms{$now_group.reply_count} 人评论</div></div></div>
						<dl>
							<volist name="reply_list" id="vo">
								<dd>
									<div class="titleBar">
										<div class="nickname">{pigcms{$vo.nickname}</div><div class="dateline">{pigcms{$vo.add_time}</div><div class="rateInfo"><div class="starIconBg"><div class="starIcon" style="width:{pigcms{$vo['score']*20}%;"></div></div></div>
									</div>
									<div class="replyCon">
										<div class="textDiv">
											<div class="text">{pigcms{$vo.comment}</div>
										</div>
										<if condition="$vo['pics']">
											<ul class="imgList" data-pics="<volist name="vo['pics']" id="voo">{pigcms{$voo.m_image}<if condition="count($vo['pics']) gt $i">,</if></volist>">
												<volist name="vo['pics']" id="voo">
													<li><img src="{pigcms{$voo.s_image}"/></li>
												</volist>
											</ul>
										</if>
										<if condition="$vo['merchant_reply_content']">
										<div class="textDiv">
											<div class="text" style=" font-size: 12px;color: #C6895A;">商家回复：{pigcms{$vo.merchant_reply_content}</div>
										</div>
										</if>
									</div>
								</dd>
							</volist>
						</dl>
						<if condition="$now_group['reply_count'] gt 3">
							<div class="more link-url" data-url="{pigcms{:U('Group/feedback',array('group_id'=>$now_group['group_id']))}">查看全部 {pigcms{$now_group.reply_count} 条评价</div>
						</if>
					</section>
				</if>
				<if condition="$merchant_group_list">
					<section class="storeProList introList">
						<div class="titleDiv"><div class="title">商家其他{pigcms{$config.group_alias_name}</div></div>
						<ul class="goodList">
							<volist name="merchant_group_list" id="vo">
								<li class="link-url" data-url="{pigcms{$vo.url}" <if condition="$i gt 2">style="display:none;"</if>>
									<div class="dealcard-img imgbox">
										<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/>
									</div>
									<div class="dealcard-block-right">
										<div class="title">{pigcms{$vo.group_name}</div>
										<div class="price">
											<strong>{pigcms{$vo['price']}</strong><span class="strong-color">元<if condition="$vo.extra_pay_price neq ''">{pigcms{$vo.extra_pay_price}</if></span><if condition="$vo['wx_cheap']"><span class="tag"><?php if($is_app_browser && in_array($app_browser_type,array('android','ios'))){ ?>APP<?php }else{ ?>微信<?php } ?>再减{pigcms{$vo.wx_cheap}元</span></if><span class="line-right">{pigcms{$vo['sale_txt']}</span>
										</div>
									</div>
								</li>
							</volist>
							<if condition="count($merchant_group_list) gt 2"><li class="more">其他{pigcms{:count($merchant_group_list)-2}个{pigcms{$config.group_alias_name}</li></if>
						</ul>
					</section>
				</if>
				<if condition="$category_group_list && $merchant_link_showOther">
					<section class="sysProList introList">
						<div class="titleDiv"><div class="title">看了本{pigcms{$config.group_alias_name}的用户还看了</div></div>
						<dl class="likeBox dealcard">
							<volist name="category_group_list" id="vo">
								<dd class="link-url" data-url="{pigcms{$vo.url}">
									<div class="dealcard-img imgbox">
										<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={pigcms{:urlencode($vo['list_pic'])}" alt="{pigcms{$vo.name}"/>
									</div>
									<div class="dealcard-block-right">
										<div class="brand">{pigcms{$vo.s_name}<if condition="$vo['range_txt']"><span class="location-right">{pigcms{$vo.range_txt}米</span></if></div>
										<div class="title">[{pigcms{$vo.prefix_title}]{pigcms{$vo.intro}</div>
										<div class="price">
											<strong>{pigcms{$vo['price']}</strong><span class="strong-color">元<if condition="$vo.extra_pay_price neq ''">{pigcms{$vo.extra_pay_price}</if></span><if condition="$vo['wx_cheap']"><span class="tag"><?php if($is_app_browser && in_array($app_browser_type,array('android','ios'))){ ?>APP<?php }else{ ?>微信<?php } ?>再减{pigcms{$vo.wx_cheap}元</span></if><span class="line-right">{pigcms{$vo['sale_txt']}</span>
										</div>
									</div>
								</dd>
							</volist>
						</dl>
					</section>
				</if>
				<!-- <div id="pullUp">
					<img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
				</div> -->
			</div>
		</div>
		<div id="J_Calendar" class="calendar" style="display:none;">
			<header class="mui-bar mui-bar-nav">
				<a id="close_yui" class=" mui-icon mui-icon-left-nav mui-pull-left"></a>
				<h1 class="mui-title">日期选择</h1>
			</header>
			<ul class="calendar-title bar" style="top:36px">
				<li>周日</li>
				<li>周一</li>
				<li>周二</li>
				<li>周三</li>
				<li>周四</li>
				<li>周五</li>
				<li>周六</li>
			</ul>
		</div>
		<php>$no_footer=true;</php>
		<include file="Public:footer"/>
		<script type="text/javascript">
			var show_detail=false;
			$(function(){
				$('#group_rule').click(function(){
					if(show_detail){
						$('.group_rule').removeClass('more');
						show_detail = false;
					}else{
						$('.group_rule').addClass('more');
						show_detail = true;
					}
				});
				
				<if condition="$start_num gt 0 AND empty($start_head)">
					$('.star_group').css('height','230px');
				</if>
			});
			
			 <if condition="$group_share_info">
				var h={pigcms{$effective_time.h};
				var m={pigcms{$effective_time.m};
				var s={pigcms{$effective_time.s};
				time=setInterval("run()",1000);
				
				function run(){
					--s;
					if(s<0){
						--m;
						s=59;
					}
					if(m<0){
						--h;
						m=59
					}
					if(h<0){
						s=0;
						m=0;
					}
					$('.timeDown').html(h+":"+m+":"+s);
				}
			</if>
			 
			<if condition="$now_group['begin_time'] gt $_SERVER['REQUEST_TIME']">
			var h = {pigcms{$time_array['h']};
			var m = {pigcms{$time_array['m']};
			var s = {pigcms{$time_array['s']};
			function run_time(){
				--s;
				if(s<0){
					--m;
					s=59;
				}
				if(m<0){
					--h;
					m=59
				}
				if(h<0){
					s=0;
					m=0;
				   window.location.reload();
				}
				$('.time_h').html(h);
				$('.time_m').html(m);
				$('.time_s').html(s);
			}
			time=setInterval("run_time()",1000);
		</if>
			
			window.shareData={
				"moduleName":"Group",
				"moduleID":"0",
				"imgUrl": "<if condition="$now_group['all_pic'][0]['m_image']">{pigcms{$now_group.all_pic.0.m_image}<else/>{pigcms{$config['wechat_share_img']}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Group/detail', array('group_id' => $now_group['group_id']))}",
				"tTitle": "{pigcms{$now_group.price}元 【{pigcms{$now_group.s_name}】",
				"tContent": "{pigcms{$now_group['intro']|msubstr=0,40}"
			};
		</script>
		{pigcms{$shareScript}
		<include file="kefu" />
        <if condition="$is_app_browser">
            <script type="text/javascript">
                window.lifepasslogin.shareLifePass("{pigcms{$now_group.price}元 【{pigcms{$now_group.s_name}】","{pigcms{$now_group.intro}","<if condition="$now_group['all_pic'][0]['m_image']">{pigcms{$now_group.all_pic.0.m_image}<else/>{pigcms{$config['wechat_share_img']}</if>","{pigcms{$config.site_url}{pigcms{:U('Group/detail', array('group_id' => $now_group['group_id']))}");
            </script>
        </if>
		
		
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script src="https://cdn.bootcss.com/yui/3.18.1/yui/yui.js"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
	
	<script>
		FastClick.attach(document.body);
		var price = 0;
		var wx_cheap = {pigcms{$now_group['wx_cheap']*100};
		var finalprice = {pigcms{$finalprice};
		var discount_price_all  =0;
		var discount_room  = 0;
		
		var hotel_content = '{pigcms{:json_encode($hotel_list)}';
		var initialize_data = $.parseJSON(hotel_content.replace(/\r\n/g,"<BR>").replace(/\n/g,"<BR>").replace(/\t/g,""));
		//console.log(initialize_data)
		var week = ["日","一","二","三","四","五","六"];
		var oCal;
		YUI({
			modules: {
				'price-calendar': {
					fullpath: '{pigcms{$static_public}trip-calendar/price-calendar.js',
					type    : 'js',
					requires: ['price-calendar-css']
				},
				'price-calendar-css': {
					fullpath: '{pigcms{$static_public}trip-calendar/price-calendar.css',
					type    : 'css'
				}
			}
		}).use('price-calendar', function(Y) {
			
			/**
			 * 非弹出式日历实例
			 * 直接将日历插入到页面指定容器内
			 */
			oCal = new Y.PriceCalendar({
				container   : '#J_Calendar' //非弹出式日历时指定的容器（必选）
				// ,selectedDate: new Date       //指定日历选择的日期
				,count		: 3
				,afterDays	: 180
				<?php if($trade_hotel['time_dep_time']){ ?>
				,depDate	: '{pigcms{$trade_hotel.time_dep_time}'
				,endDate	: '{pigcms{$trade_hotel.time_end_time}'
				<?php } ?>
			});
			$('.price-calendar-bounding-box table td').click(function(){
				if($(this).hasClass('disabled')){
					return false;
				}else{
					if(($('.dep-date').size() > 0 && $('.end-date').size() > 0) || ($('.dep-date').size() == 0 && $('.end-date').size() == 0)){
						$('.dep-date').find('.mark').empty();
						$('.dep-date').removeClass('dep-date');
						$('.end-date').find('.mark').empty();
						$('.end-date').removeClass('end-date');
						oCal.set('endDate','');
						
						$('.selected-range').removeClass('selected-range');
						
						oCal.set('depDate',$(this).data('date'));
						$(this).addClass('dep-date').find('.mark').html('入住');
					}else if(oCal.get('depDate')){
						var nowTmpdate = $(this).data('date').replace(/-/g,'');
						var prevTmpdate = oCal.get('depDate').replace(/-/g,'');
					
						if(nowTmpdate < prevTmpdate){
							$('.dep-date').find('.mark').empty();
							$('.dep-date').removeClass('dep-date');
							oCal.set('depDate',$(this).data('date'));
							$(this).addClass('dep-date').find('.mark').html('入住');
						}else{
							var tmp_dep_data = $(this).attr('class');
							if(tmp_dep_data=='dep-date'){
								alert('不能选同一天'); 
							}else{
								oCal.set('endDate',$(this).data('date'));
								
								var depTmpdate = parseInt(oCal.get('depDate').replace(/-/g,''));
								var endTmpdate = parseInt(oCal.get('endDate').replace(/-/g,''));
								$(this).addClass('end-date').find('.mark').html('离店');
								for(var i = depTmpdate+1;i<endTmpdate;i++){
									var tmpI = i.toString();
									var tmpDate = tmpI.substr(0,4)+'-'+tmpI.substr(4,2)+'-'+tmpI.substr(6,2);
									$('td[data-date="'+tmpDate+'"]').addClass('selected-range');
								}
								setTimeout(function(){
									changeTime();
								},300);
							}
						}
					}
				}
			});
			$('#hotel-info-box .startweek').html('周'+week[oCal._toDate($('#hotel-info-box .indate').data('date')).getDay()]);
			$('#hotel-info-box .endweek').html('周'+week[oCal._toDate($('#hotel-info-box .outdate').data('date')).getDay()]);
			$('.detail-date').click(function(){
				$('#hotel-info-box').hide();
				$('#J_Calendar').show();
			});
			$(document).on("click",".rooms .rigit_activebg",function(){
			//$('.rooms .rigit_activebg').live('click',function(){
				var rooms = $(this).closest('.rooms');
				if(rooms.hasClass('on')){
					rooms.removeClass('on');
					rooms.find('.info-list').removeAttr('style');
				}else{
					rooms.addClass('on');
				}
				return false;
			});
			$(document).on("click","#close_yui",function(){
				$('#hotel-info-box').show();
				$('#J_Calendar').hide();
			});
			$(document).on("click",".rooms .roomdetail .btn2",function(){
				var layerTip = layer.open({type: 2});
				var that = $(this);
				var book_day_ = parseInt(that.data('book_day'));
				var dep_time  = $('#dep-time').val()+"";
				var end_time  = $('#end-time').val()+"";
			
				ah = dep_time.substring(0,4);
				am = dep_time.substring(4,6);
				as = dep_time.substring(6,8);
				oDate1  =  new  Date(ah  +  '-'  +  am  +  '-'  +  as) 
				
				ah = end_time.substring(0,4);
			
				am = end_time.substring(4,6);
		
			
				as = end_time.substring(6,8);
			
				oDate2  =  new  Date(ah  +  '-'  +  am  +  '-'  +  as) 
				iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)  
			
				if(iDays>book_day_&&book_day_!=0){
					layer.open({
						content: '最多可预订'+book_day_+'天'
						,btn:['关闭']
					});
					layer.close(layerTip);
					return false;
				}
				
				
				//window.location.href="{pigcms{:U('Group/buy',array('group_id'=>$_GET['group_id']))}#buy";
				$.post("{pigcms{:U('ajax_get_trade_hotel_price')}",{group_id:{pigcms{$now_group.group_id},cat_id:$(this).data('cat_id'),dep_time:$('#dep-time').val(),end_time:$('#end-time').val()},function(result){
					result = $.parseJSON(result);
					console.log(result)
					if(result.err_code){
						layer.open({
							content: result.err_msg
							,btn: ['关闭']
						});
					}
					layer.close(layerTip);
					var detailVal = {
						'cat_pname' : that.closest('.rooms').find('.picroom-info .room').html()
						,'cat_name' : that.closest('.roomdetail').find('.left .bra').html()
						,'cat_id' : that.data('cat_id')
						,'dep_date' : $('#hotel-info-box .indate').data('date')
						,'end_date' : $('#hotel-info-box .outdate').data('date')
						,'price' : result.price
						,'stock' : result.stock
						,'discount_room' : result.discount_room
						,'stock_list' : result.stock_list
					};
					$.cookie('cat_id',that.data('cat_id'));
					$.cookie('dep_time',$('#dep-time').val());
					$.cookie('end_time',$('#end-time').val());
					$.cookie('cat_pname',that.closest('.rooms').find('.picroom-info .room').html());
					$.cookie('cat_name',that.closest('.roomdetail').find('.left .bra').html());
					$.cookie('dep_date',$('#hotel-info-box .indate').data('date'));
					$.cookie('end_date', $('#hotel-info-box .outdate').data('date'));
					$.cookie('allready_buy', 1);
			
				
					
					window.location.href="{pigcms{:U('Group/buy',array('group_id'=>$_GET['group_id']))}#buy";
					//showDetail(detailVal);
				});
				return false;
			});
			$(document).on("click",".rooms .rpDetail",function(){
			//$('.rooms .rpDetail').live('click',function(){
				$('body').append('<div class="mask-layer"></div>');
				var cat_id_click = 0;
				
				var this_cat_id = $(this).data('cat_id') 
				$.each(initialize_data,function(index,i){
					if(i.cat_id==this_cat_id){
						cat_id_click = index;
					}
				})
				
				laytpl($('#listTypePopTpl').html()).render(initialize_data[cat_id_click], function(html){
					$('body').append(html);
					$('.hpic_show').height($(window).width()*0.92*450/760);
					var productSwiper = $('.hpic_show').swiper({
						pagination:'.swiper-pagination',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						simulateTouch:false
					});
				});
				$('.mask-layer,.roomTypeInfo .htclose').one('click',function(){
					//$('body').css('overflow',' auto'); 

					$('.roomTypeInfo').remove();
					$('.mask-layer').remove();
				});
				
				$('.roomTypeInfo .bottom_btn span').click(function(){
					$('.room-cat-'+$(this).data('cat_id')).addClass('on');
					$('.roomTypeInfo .htclose').trigger('click');
				});
				//$('body').css('overflow',' hidden'); 

				
			});
			$(document).on("click",".roomdetail .left",function(){
			//$('.roomdetail .left').live('click',function(){
				$('body').append('<div class="mask-layer"></div>');
				var cat_id = $(this).data('cat_id');
				var tmp_data;
				for(var i in initialize_data){
					if(initialize_data[i].son_list){						
						for(var k in initialize_data[i].son_list){
							if(initialize_data[i].son_list[k].cat_id==cat_id){
								tmp_data = initialize_data[i].son_list[k];
							}
						}
					}
				}
			
				laytpl($('#listsonTypl').html()).render(tmp_data, function(html){
					
					$('body').append(html);
					var productSwiper = $('.hpic_show').swiper({
						pagination:'.swiper-pagination',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						simulateTouch:false
					});
				});
				$('.mask-layer,.roomTypeInfo .htclose').one('click',function(){
					$('body').css('overflow','auto'); 
					$('.roomTypeInfo').remove();
					$('.mask-layer').remove();
				});
				
				$('.roomTypeInfo .bottom_btn span').click(function(){
					$('.room-cat-'+$(this).data('cat_id')).addClass('on');
					$('.roomTypeInfo .htclose').trigger('click');
				});
	
				$('#soninfo').height($('#son_content').height()*3); 
				$('body').css('overflow',' hidden'); 

			
				
			});
			
			laytpl($('#listHotelTpl').html()).render(initialize_data, function(html){
				console.log(initialize_data);
				$('.detail-main ul').html(html);
				myScroll.refresh();
			});
			
			$(window).bind('hashchange',function(){
				if(location.hash == '' || location.hash == '#'){
					$('#buy_form_box').hide();
					$('#hotel-info-box').show();
				}
			});
		});
		function showDetail(detailVal){
			$('#buy_form_box .type.pname').html(detailVal.cat_pname);
			$('#buy_form_box .type.name').html(detailVal.cat_name);
			$('#cat-id').val(detailVal.cat_id);
			var book_day = get_day($('.dep-date').data('date'),$('.end-date').data('date'));
			//计算入住离店时间
			var tmpInFormatDate = $('.dep-date').data('date').replace(/-/g,'');

			var tmpOutFormatDate = $('.end-date').data('date').replace(/-/g,'');

			var tmpInFormatDateWeek = '周'+week[oCal._toDate(detailVal.dep_date).getDay()];
			var tmpOutFormatDateWeek = '周'+week[oCal._toDate(detailVal.end_date).getDay()];
			
			$('#buy_form_box .p-info .date').html(tmpInFormatDate.substr(4,2)+'月'+tmpInFormatDate.substr(6,2)+'日('+tmpInFormatDateWeek+') — '+tmpOutFormatDate.substr(4,2)+'月'+tmpOutFormatDate.substr(6,2)+'('+tmpOutFormatDateWeek+') 共'+(book_day)+'晚');
			
			$('#buy_form_box .J_total-price').html(detailVal.price+'元');
			discount_price_all = 0;
			if(Number(detailVal.discount_room)>0){
				$('#discount_room .J_discount-room').html(detailVal.discount_room+'间及以上');
				$('#discount_room .J_discount-price').html(detailVal.discount_price);
				$('#discount_room_price').show();
				discount_room = Number(detailVal.discount_room);
				$.each(detailVal.stock_list,function(index,val){
					if(val.discount_price>0){
						discount_price_all += Number(val.discount_price);
					}else{
						discount_price_all += Number(val.price);
					}
				});
			}
			$('input[name="quantity"]').val(1);
			$('.J_campaign-value').hide();
			if(Number(detailVal.discount_room)>0 && $('input[name="quantity"]').val()>detailVal.discount_room){
				$('.J_total-price').html(discount_price_all+'元');
			}
			if(discount_price_all>0  && $('input[name="quantity"]').val()>detailVal.discount_room ){
				$('.J_campaign-value').html(detailVal.price+'元');
				$('.J_campaign-value').show(); 
				
			}
			//$('.J_total-price').html(discount_price_all+'元');
			$("input[name='quantity']").attr('max',detailVal.stock);
			$('button.plus').prop('disabled',false);
			price = finalprice >0 ? finalprice * 100 : detailVal.price*100;
			
			var priceHtml = '';

			for(var i in detailVal.stock_list){
				var tmpFormatDate = detailVal.stock_list[i].day.replace(/-/g,'');
				var discount_price_txt='';
				if (detailVal.stock_list[i].discount_price>0){
					discount_price_txt= '<div class="discount_price">(优惠价格 '+detailVal.stock_list[i].discount_price+' 元)</div>';
				}
				priceHtml+= '<dd class="dd-padding"><div class="left">'+tmpFormatDate.substr(4,2)+'月'+tmpFormatDate.substr(6,2)+'日：</div><div class="right">'+detailVal.stock_list[i].price+'元'+discount_price_txt+'</div></dd>';
			}
			$('.price-list').html(priceHtml);
			
			$('#hotel-info-box').hide();
			$('#buy_form_box').show();
			  
			location.hash = 'buy';
			if(discount_price_all>0){
				$('.discount_price').show();
			}
			
			// console.log(detailVal);
		}
		function get_day(dep_time,end_time){
			aDate  = dep_time.split("-")  
		   oDate1  =  new  Date(aDate[0]  +  '-'  +  aDate[1]  +  '-'  +  aDate[2]) 
		   aDate  =  end_time.split("-")  
		   oDate2  =  new  Date(aDate[0]  +  '-'  +  aDate[1]  +  '-'  +  aDate[2])  
		   iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)  
		  return iDays;
		}
		function changeTime(){
			 window.scrollTo( 0, 0 );
			$('#hotel-info-box .indate').data('date',$('.dep-date').data('date'));
			$('#hotel-info-box .outdate').data('date',$('.end-date').data('date'));
			
			var tmpInFormatDate = $('.dep-date').data('date').replace(/-/g,'');
			$('#hotel-info-box .indate').html(tmpInFormatDate.substr(4,2)+'-'+tmpInFormatDate.substr(6,2));
			$('#dep-time').val(tmpInFormatDate);
			
			var tmpOutFormatDate = $('.end-date').data('date').replace(/-/g,'');
			$('#hotel-info-box .outdate').html(tmpOutFormatDate.substr(4,2)+'-'+tmpOutFormatDate.substr(6,2));
			$('#end-time').val(tmpOutFormatDate);
			
			$('#hotel-info-box .startweek').html('周'+week[oCal._toDate($('#hotel-info-box .indate').data('date')).getDay()]);
			$('#hotel-info-box .endweek').html('周'+week[oCal._toDate($('#hotel-info-box .outdate').data('date')).getDay()]);
			aDate  =  $('.dep-date').data('date').split("-")  
			oDate1  =  new Date( aDate[0]+  '-'  + aDate[1]  +  '-'  +  aDate[2]  ) 
		
			aDate  =  $('.end-date').data('date').split("-")  
			oDate2  =  new Date(aDate[0]+  '-'  + aDate[1]  +  '-'  +  aDate[2] )  
			iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)  
		 
			$('#hotel-info-box .ol_night').html('共'+(iDays)+'晚');
			
			
			$('#J_Calendar').hide();
			$('#hotel-info-box').show();
			var layerTip = layer.open({type: 2});
			$.post("{pigcms{:U('ajax_get_trade_hotel_stock')}",{group_id:{pigcms{$now_group.group_id},dep_time:$('#dep-time').val(),end_time:$('#end-time').val()},function(result){
				initialize_data = $.parseJSON(result);
				console.log(initialize_data);
				laytpl($('#listHotelTpl').html()).render(initialize_data, function(html){
					layer.close(layerTip);
					$('.detail-main ul').html(html);
					myScroll.refresh();
				});
			});
		}
	</script>
	<if condition="$merchant_card_spread_info && $merchant_card_spread_info['card_discount'] && !$merchant_card_spread_info['get_card']">
		<script>
			$.post("{pigcms{:U('Home/get_merchant_card_spread_info')}",{mer_id:{pigcms{$now_group.mer_id}},function(result){
				if(!result.info.get_card){
					layer.open({
						content: '领取商家会员卡结算 <span style="color:red;">'+result.info.card_discount+'折</span> 优惠'
						,btn: ['领取', '不要']
						,yes: function(index){
							layer.close(index);
							location.href = result.info.url;
						}
					});
				}else{
					location.reload();
				}
			});
		</script>
	</if>
	<script id="listHotelTpl" type="text/html">
		{{# var ii = 0; for(var i in d){ii++; }}
			<li class="rooms room-cat-{{ d[i].cat_id }} {{# if(!d[i].has_room && typeof(d[i].has_room)!='undefined'){ }}no{{# }else if(ii == 1){ }}on{{# } }} ">
				<div class="wrap">
					<div class="left rpDetail" data-cat_id="{{ d[i].cat_id }}">
						<div class="pic tjclick">
							<img src="{{ d[i].cat_pic_list[0].s_image }}"/>
						</div>
						<div class="picroom-info">
							<div class="room">{{ d[i].cat_name }}</div>
							<div class="room-info"><span>{{ d[i].room_size }}</span><span>{{ d[i].bed_info }}</span></div>
						</div>
					</div>
					<div class="right">
						<div class="price">
							{{# if(d[i].min_price){ }}
								<span>￥</span><span class="num">{{ d[i].min_price }}</span>起
							{{# }else{ }}
								<span>暂无售价</span>
							{{# } }}
						</div>
						<div class="icon"></div>
					</div>
					<div class="de-btn"><i></i></div>
					<div class="rigit_activebg"></div>
				</div>
				<div class="info-list" {{# if(i == 0){ }}style="display:block;"{{# } }}>
					<ul>
						{{#
						if(d[i].son_list){
							for(var j = 0, len = d[i].son_list.length; j < len; j++){
								var voo = d[i].son_list[j];
						}}
							<li class="roomdetail">
								<div class="left" data-cat_id="{{ voo.cat_id}}">
									<div class="bra clearfix">{{ voo.cat_name }}</div>
									<div class="xstm">
						<span class="f_c49f">{{ voo.refund_txt }}</span>
									</div>
								</div>
								<div class="value">
									<div class="price">
										{{# if(voo.price_txt){ }}
											￥<span>{{ voo.price_txt }}</span><br>
											{{# if(voo.discount_room>0 && voo.discount_price_txt>0 ){ }} <span style="font-size: 12px;color: #ccc;">满{{ voo.discount_room }}间享￥{{ voo.discount_price_txt }}单价</span> {{# } }}
										{{# }else{ }}
											暂无售价
										{{# } }}
									</div>
								</div>
								<div class="book">
									{{# if(voo.stock_num && voo.price_txt){ }}
										<div class="btn2 btn2_center" data-cat_id="{{ voo.cat_id }}" data-book_day="{{ voo.book_day }}"><span>订</span></div>
									{{# }else if(!voo.price_txt){ }}
										<div class="btn3 btn2_center">无</div>									
									{{# }else{ }}
										<div class="btn3 btn2_center">满</div>
									{{# } }}
								</div>
							</li>
						{{# 
							}
						}
						}}
					</ul>
				</div>
			</li>
		{{# } }}
	</script>
	<script id="listTypePopTpl" type="text/html">
		<div class="type-pop-box roomTypeInfo newdetailhsize plugin-inited box-active plugin-show" style="position:fixed;">
			<div class="toptitle">
				<p><span class="htitle">{{ d.cat_name }}</span></p>
				<div class="htclose"><i class="cancel-icon"></i></div>
			</div>
			<div class="wrap page-content">
				<div class="swiper-container hpic_show">
					<div class="swiper-wrapper">
						{{# for(var i in d.cat_pic_list){ }}
							<div class="swiper-slide"><img src="{{d.cat_pic_list[i].m_image}}"/></div>
						{{# } }}
					</div>
					<div class="swiper-pagination"></div>
				</div>
				<div class="type-list">
					<p class="faclist">
						<span><i class="detail_fac_v0"></i>早餐：{{ d.breakfast_info }}</span>
						<span><i class="detail_fac_v1"></i>窗户：{{ d.window_info }}</span>
						<span><i class="detail_fac_v2"></i>楼层：{{ d.floor_info }}</span>
						<span><i class="detail_fac_v3"></i>面积：{{ d.room_size }}</span>
						<span><i class="detail_fac_v4"></i>床型：{{ d.bed_info }}</span>
						<span><i class="detail_fac_v6"></i>网络：{{ d.network_info }}</span>
					</p>
					<p class="tip"></p>
				</div>
				{{# if(d.cat_info){ }}
				<div class="discount u-bt discountRoomInfo">
					<p class="clearfix">
						<span class="dct_tit">其他信息：</span>
						<span class="dct_txt">{{ d.cat_info }}</span>
					</p>
				</div>
				{{# } }}
			</div>
			<div class="bottom_btn"><span data-cat_id="{{ d.cat_id }}" style="font-size:14px">关闭</span></div>
		</div>
	</script>
	
	<script id="listsonTypl" type="text/html">
		<div class="type-pop-box roomTypeInfo newdetailhsize plugin-inited box-active plugin-show"  id="soninfo" style="position:fixed;">
			<div class="toptitle">
				<p><span class="htitle">{{ d.cat_name }}</span></p>
				<div class="htclose"><i class="cancel-icon"></i></div>
			</div>
			<div class="wrap page-content">
				
				
				<div class="discount u-bt discountRoomInfo" id="son_content">
					
					<p class="clearfix">
						<span class="dct_tit">价格：</span>
						<span class="dct_txt">{{# if(d.price_txt){ }}￥ {{ d.price_txt }} {{# }else{ }}暂无售价 {{# } }}</span>
					</p>
					
					<p class="clearfix" style="margin-top:10px">
						<span class="dct_tit">支持发票：</span>
						<span class="dct_txt">{{# if(d.has_receipt==1){ }} 支持 {{# }else{ }} 不支持{{# } }}</span>
					</p>
					<p class="clearfix" style="margin-top:10px"> 
						<span class="dct_tit">是否任意退：</span>
						<span class="dct_txt">{{# if(d.has_refund==0){ }} 任意退 {{# }else if(d.has_refund == 1){ }} 不可取消 {{# }else if(d.has_refund==2){  }} 入住{{ d.refund_hour }}小时前可退 {{# } }}</span>
					</p>
					{{# if(d.cat_info!=''){ }}
					<p class="clearfix" style="margin-top:10px"> 
						<span class="dct_tit">其他信息：</span>
						<span class="dct_txt">{{ d.cat_info }}</span>
					</p>
					{{# } }}
					
				</div>
				
			</div>
		<div class="bottom_btn"><span data-cat_id="{{ d.cat_id }}" style="font-size:14px">关闭</span></div>
		</div>
		
	</script>
	</body>
</html>