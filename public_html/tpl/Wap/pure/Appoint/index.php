<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$config.appoint_site_name}
		
		</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index.css?216"/>
		
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script>
		<!--script type="text/javascript">
		var app_version = '{pigcms{$_REQUEST["app_version"]}';
		</script-->
		<script type="text/javascript" src="{pigcms{$static_path}js/appoint_home.js?210" charset="utf-8"></script>
		<style>
			.hasManyCity #searchBox{margin-right:40px;}
			body{max-width:100%;}
			.recommend{height:144px;border-bottom:none;}
			.activity{height:152px;}
			.activity .headBox,.hotCategory .headBox{color: #666;height: 38px;line-height: 38px;padding-left: 8px;font-size: 14px;border-bottom: 1px solid #edebeb;font-weight:bold;}
			.activity .headBox .more,.hotCategory .headBox .more{float:right;font-weight: normal;color: #999;margin-right: 10px;}
			.activity .headBox .more span,.hotCategory .headBox .more span{margin-right:2px;}
			.activity .activityBox .swiper-wrapper{height:100px;}
			.activity .activityBox .swiper-slide a .desc{padding-top:10px;}
			.activity .activityBox .swiper-slide{width:65%;}
			.activity .activityBox .swiper-slide a .icon{width:80px;height:80px;}
			.activity .activityBox .swiper-slide a{padding-left: 105px;}
			.hotCategory{border-top: 1px solid #edebeb;border-bottom: 1px solid #edebeb;background-color: white;margin-bottom: 10px;}
			.hotCategory .dealcard{    display: -webkit-box;display: box;}
			.hotCategory dd{-webkit-box-sizing: border-box;box-sizing: border-box;-webkit-box-flex: 1;box-flex: 1;-webkit-box-sizing: border-box;    text-align: center;width:33.3%}
			.hotCategory dd .box{padding:0px 4px;}
			/*.hotCategory dd .imgBox{height:80px;}*/
			.hotCategory dd .imgBox img{width:100%;height: 70px;}
			.hotCategory dd .catName{margin-top:6px;}
		</style>
	</head>
	<body>
	
		<header <if condition="$config['many_city']">class="hasManyCity"</if>>
			<if condition="$config['many_city']">
				<div id="cityBtn" class="link-url" data-url="{pigcms{:U('Changecity/index')}&appoint=1">{pigcms{$config.now_select_city.area_name}</div>
			</if>
			<!--div id="locaitonBtn" class="link-url" data-url="{pigcms{:U('Merchant/around')}"></div-->
			<div id="searchBox">
				<a href="{pigcms{:U('Search/index')}&type=appoint">
					<i class="icon-search"></i>
					<span>请输入您想找的内容</span>
				</a>
			</div>
			<div id="qrcodeBtn"></div>
		</header>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<div id="container">
			<div id="scroller">
				<div id="pullDown">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
				<if condition="$wap_index_top_adver">
					<section class="banner">
						<div class="swiper-container swiper-container1">
							<div class="swiper-wrapper">
								<volist name="wap_index_top_adver" id="vo">
									<div class="swiper-slide">
										<a href="{pigcms{$vo.url}">
											<img src="{pigcms{$vo.pic}"/>
										</a>
									</div>
								</volist>
							</div>
							<div class="swiper-pagination swiper-pagination1"></div>
						</div>
					</section>
				</if>
				<if condition="$wap_index_slider">
					<section class="slider">
						<div class="swiper-container swiper-container2" style="height:168px;">
							<div class="swiper-wrapper">
								<volist name="wap_index_slider" id="vo">
									<div class="swiper-slide">
										<ul class="icon-list">
											<volist name="vo" id="voo">
												<li class="icon">
													<a href="{pigcms{$voo.url}">
														<span class="icon-circle">
															<img src="{pigcms{$voo.pic}">
														</span>
														<span class="icon-desc">{pigcms{$voo.name}</span>
													</a>
												</li>
											</volist>
										</ul>
									</div>
								</volist>
							</div>
							<div class="swiper-pagination swiper-pagination2"></div>
						</div>
						<if condition="$news_list">
							<div class="platformNews clearfix link-url" data-url="{pigcms{:U('Systemnews/index')}">
								<div class="left ico"></div>
								<div class="left list">
									<ul>
										<volist name="news_list" id="vo">
											<li class="num-{pigcms{$i}" <if condition="$i gt 2">style="display:none;"</if>>[{pigcms{$vo.name}] {pigcms{$vo.title}</li>
										</volist>
									</ul>
								</div>
							</div>
						</if>
					</section>
				</if>
				<if condition="$coupon_list">
					<section class="activity">
						<div class="headBox link-url" data-url="{pigcms{:U('Systemcoupon/index')}">优惠券<div class="more"><span>更多</span>&gt;</div></div>
						<div class="activityBox">
							<div class="swiper-container swiper-container4">
								<div class="swiper-wrapper">
									<volist name="coupon_list" id="vo">
										<div class="swiper-slide">
											<a href="{pigcms{:U('Systemcoupon/index')}">
												<label>
													<span class="title">领取</span>
													<span class="number">{pigcms{$vo.had_pull}</span>
												</label>
												<div class="icon">
													<img src="{pigcms{$vo.img}" alt="{pigcms{$vo.name}"/>
												</div>
												<div class="desc">
													<div class="name">{pigcms{$vo.name}</div>
													<div class="price">
														<strong class="yuan">剩{pigcms{$vo['num']-$vo['had_pull']}个</strong>
													</div>
												</div>
											</a>
										</div>
									</volist>
								</div>
							</div>
						</div>
					</section>
				</if>
				<if condition="$wap_index_center_adver">
					<section class="recommend" <if condition="!$wap_index_center_adver">style="height:85px;"</if>>
						<div class="recommendBox">
							<div class="recommendLeft link-url" data-url="{pigcms{$wap_index_center_adver.2.url}">
								<img src="{pigcms{$wap_index_center_adver.2.pic}" alt="{pigcms{$wap_index_center_adver.2.name}"/>
							</div>
							<div class="recommendRight">
								<div class="recommendRightTop link-url" data-url="{pigcms{$wap_index_center_adver.1.url}">
									<img src="{pigcms{$wap_index_center_adver.1.pic}" alt="{pigcms{$wap_index_center_adver.1.name}"/>
								</div>
								<div class="recommendRightBottom link-url" data-url="{pigcms{$wap_index_center_adver.0.url}">
									<img src="{pigcms{$wap_index_center_adver.0.pic}" alt="{pigcms{$wap_index_center_adver.0.name}"/>
								</div>
							</div>
						</div>				
					</section>
				</if>
				<volist name="all_category_list" id="vo">
					<section class="hotCategory clearfix">
						<div class="headBox link-url" data-url="{pigcms{:U('Appoint/category',array('cat_id'=>$vo['cat_id']))}">{pigcms{$vo.cat_name}<div class="more"><span>更多</span>&gt;</div></div>
						<dl class="likeBox dealcard">
							<volist name="vo['category_list']" id="voo" offset="0" length="3">
								<dd class="link-url" data-url="{pigcms{:U('Appoint/two_category',array('cat_id'=>$voo['cat_id']))}">
									<div class="box">
										<div class="imgBox">
											<img src="{pigcms{$voo.cat_big_pic}"/>
										</div>
										<div class="catName">{pigcms{$voo.cat_name}</div>
									</div>
								</dd>
							</volist>
						</dl>
					</section>
				</volist>
				<div id="pullUp" style="bottom:-60px;">
					<img src="{pigcms{$config.appoint_site_logo}" style="width:130px;height:40px;margin-top:10px"/>
				</div>
			</div>
		</div>
		<include file="footer"/>
		<!--script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script-->
		{pigcms{$shareScript}
		{pigcms{$coupon_html}
		<script>
			$('.hotCategory dd .imgBox img').each(function(index,val){
				var width=$(val).width();
				$(val).css('height',width/1.8);
			})	
			
		</script>
	</body>
</html>