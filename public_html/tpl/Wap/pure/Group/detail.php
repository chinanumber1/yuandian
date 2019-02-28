<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.group_alias_name}详情</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/detail.css?213"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?216" charset="utf-8"></script>
		<script type="text/javascript"><if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/detail.js?216" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
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
		
  		.collection{ position: absolute; width: 35px; height: 35px; border-radius: 100%; background: url({pigcms{$static_path}images/3.png) center no-repeat; z-index: 9999; top:10px; right: 10px; }
		.collection:after{ display: block;  content: ''; width: 16px; height: 16px; background: url({pigcms{$static_path}images/2.png) center no-repeat; background-size: 16px; position: absolute; ); top: 50%; left: 50%;  margin: -8px; }
		.collection.on:after{ background: url({pigcms{$static_path}images/1.png) center no-repeat; background-size: 16px; }
		.discount{
			border: 1px solid #eda4a4;
			color: #cc0000;
			background-color: #fae6e6 !important;
		}
		.tag{
			border: 1px solid #fbd3a4;
			color: #f8a100;
			background-color:#fef3e6;
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
		    })
		  })
		</script>

	</head>
	<body>
		<div id="container">
			<div id="scroller" style="padding-bottom:50px">
				<div id="pullDown" style="background-color:#06c1ae;color:white;">
					<span class="pullDownLabel" style="padding-left:0px;"><i class="yesLightIcon" style="margin-right:10px;vertical-align:middle;"></i>{pigcms{$config.wechat_name} 精心为您优选</span>
				</div>
				<section class="imgBox">
					<img src="{pigcms{$now_group.all_pic.0.m_image}" class="view_album" data-pics="<volist name="now_group['all_pic']" id="vo">{pigcms{$vo.m_image}<if condition="count($now_group['all_pic']) gt $i">,</if></volist>"/>
					<div class="imgCon">
						<div class="title">{pigcms{$now_group.group_name}</div>
						<div class="desc">{pigcms{$now_group.s_name}</div>
					</div>
					<div class="back"></div>
					<div class="collection <if condition="$is_collect">on</if>"></div>
				</section>
				
				<section class="buyBox">
					<div class="priceDiv">
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
					</div>
					<if condition="$now_group['wx_cheap'] OR ($now_group['discount'] gt 0 AND $now_group['vip_discount_type'] gt 0)">
                        <if condition="$is_app_browser">
                        <div class="cheapDiv">优惠 <span class="tag">APP购买再减{pigcms{$now_group.wx_cheap}元</span></div>
                        <else/>
						<div class="cheapDiv">优惠 <php>if($now_group['discount'] > 0 && $now_group['vip_discount_type'] > 0){</php><span class="tag discount">{pigcms{$now_group['discount']|floatval}折</span><php>}</php>
						
						<php>if($now_group['wx_cheap']){</php><span class="tag">微信购买再减{pigcms{$now_group.wx_cheap}元</span><php>}</php></div>
                        </if>
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
					
					<if condition="$now_group.group_share_num gt 0">
						<div class="cheapDiv">组团 <span class="tag" style="border:0px">您需要购买或者邀请好友购买{pigcms{$now_group.group_share_num}份才能成团</span></div>
					<elseif condition="$now_group.open_now_num gt 0" />
						<div class="cheapDiv">拼团 <span class="tag" style="border:0px">还差{pigcms{$now_group.open_now_num}份成团</span></div>
					<elseif condition="$now_group.open_num gt 0" />
						<div class="cheapDiv">拼团 <span class="tag" style="border:0px">还差{pigcms{$now_group.open_num}份成团</span></div>
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
				<div id="pullUp" style="bottom:-60px;">
					<img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
				</div>
			</div>
		</div>
		<php>if($now_group['pin_num']==0){</php>
		<div class="positionDiv">
			<div class="left"><div class="back"></div></div>
			<if condition="$now_group['tuan_type'] neq 2">
				<div class="center">{pigcms{$now_group.merchant_name}</div>
			<else/>
				<div class="center">{pigcms{$now_group.s_name}</div>
			</if>
			<if condition="$now_group['end_time'] gt $_SERVER['REQUEST_TIME'] AND $now_group['begin_time'] lt $_SERVER['REQUEST_TIME'] AND $now_group['type'] eq 1">
				<div class="right">
					<a class="btn buy-btn btn-large btn-strong" href="{pigcms{:U('Group/buy',array('group_id'=>$now_group['group_id']))}">购买</a>
				</div>
			</if>
		</div>
					<php>}</php>
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
	</body>
</html>