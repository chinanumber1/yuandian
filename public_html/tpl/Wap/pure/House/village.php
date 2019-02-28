<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>{pigcms{$now_village.village_name}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.lazyload.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/layer.m.js" charset="utf-8"></script>
		<link href="{pigcms{$static_path}css/layer.css" type="text/css" rel="styleSheet" id="layermcss">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/houseBase.css">
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js" charset="utf-8"></script>
		<script type="text/javascript">
			var village_list_url = "{pigcms{:U('House/village_list',array('choose'=>1))}";
			var ajax_url_root = "{pigcms{$config.site_url}/wap.php?c=House&a=";
			var cat_url = type_url = 'all', deliverName = "{pigcms{$config['deliver_name']}";
			var sort_url = 'juli';
			var village_id = "{pigcms{$_GET['village_id']}";
			var user_long = '{pigcms{$now_village["long"]}',
			user_lat = '{pigcms{$now_village["lat"]}'
			$.cookie('visit_village_id','{pigcms{$now_village.village_id}')
		</script>
	</head>

	<body style="zoom: 1; min-height: 736px; overflow-y: auto;">
		<div id="pageList" class="pageDiv nowPage" style="padding-bottom: 56px; min-height: 736px; display: block;">

			<if condition='$slider_list'>
				<section id="listHeader" class="roundBg">
					<div id="listBackBtn" class="listBackBtn hide">
						<div></div>
					</div>
					<div id="locationBtn" class="page-link" data-url="{pigcms{:U('House/village_list',array('choose'=>1))}" data-url-type="openRightFloatWindow">
						<span class="location"></span>
						<span id="locationText">{pigcms{$now_village.village_name}</span>
						<span class="go"></span>
					</div>
				</section>
				<section class="banner">
					<div class="swiper-container swiper-container1">
						<div class="swiper-wrapper">
							<volist name="slider_list" id="vo">
								<div class="swiper-slide">
									<a href="{pigcms{:wapLbsTranform($vo['url'],array('title'=>$vo['name']))}">
										<img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic}" />
									</a>
								</div>
							</volist>
						</div>
						<div class="swiper-pagination swiper-pagination1"></div>
					</div>
				</section>
				
				<else />
				<header class="pageSliderHide">
					<div id="backBtn" onclick="location.href='{pigcms{:U('House/village_list',array('choose'=>1))}'"></div>{pigcms{$now_village.village_name}</header>
			</if>

                    
                        <if condition="$wap_dis">
				<section class="banner">
					<div class="swiper-container swiper-container1">
						<div class="swiper-wrapper">
							<volist name="wap_dis" id="vo">
								<div class="swiper-slide">

                                        <video width="100%" id="videoID" autoplay="" controls="controls" webkit-playsinline="playsinline" preload="auto" x5-video-player-fullscreen="true" playsinline="true" x5-playsinline="playsinline">
                                            <source src="{pigcms{$vo.video}" type="video/mp4">

                                        </video>

								
								</div>
							</volist>
						</div>
						<div class="swiper-pagination swiper-pagination1"></div>
					</div>
				</section>

                            <else />
                            <volist name="wap_dis" id="vo">
                                <div class="swiper-slide">
                                    <a href="{pigcms{$vo.url}">
                                        <img src="{pigcms{$vo.pic}"/>
                                    </a>
                                </div>
                            </volist>

			</if>




                    
			<if condition='!$now_village["has_index_nav"]'>
				<section class="slider">
					<div class="swiper-container" style="height: 168px; cursor: -webkit-grab;">
						<div class="swiper-wrapper">

							<div class="swiper-slide swiper-slide-visible swiper-slide-active">
								<ul class="icon-list">
									<li class="icon">
										<a href="{pigcms{:U('village_manager_list',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_2.png">
														</span>
											<span class="icon-desc">小区管家</span>
										</a>
									</li>
									<li class="icon">
										<a href="{pigcms{:U('House/village_my_pay',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_5.png">
														</span>
											<span class="icon-desc">生活缴费</span>
										</a>
									</li>
									<li class="icon">
										<a href="{pigcms{:U('Ride/ride_list',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_4.png">
														</span>
											<span class="icon-desc">社区用车</span>
										</a>
									</li>
									<li class="icon">
										<a href="{pigcms{:U('Library/express_service_list',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_1.png">
														</span>
											<span class="icon-desc">快递代收</span>
										</a>
									</li>
									<li class="icon">
										<a href="{pigcms{:U('House/village_activitylist',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_3.png">
														</span>
											<span class="icon-desc">社区活动</span>
										</a>
									</li>
									<li class="icon">
										<a href="{pigcms{:U('House/village_grouplist',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_7.png">
														</span>
											<span class="icon-desc"><if condition="$config['shop_alias_name']">{pigcms{$config.group_alias_name}<else />周边团购</if></span>
										</a>
									</li>
									<li class="icon">
										<a href="{pigcms{:U('shop#cat-all',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_6.png">
														</span>
											<span class="icon-desc"><if condition="$config['shop_alias_name']">{pigcms{$config.shop_alias_name}<else />周边快店</if></span>
										</a>
									</li>
									<li class="icon">
										<a href="{pigcms{:U('village_more_list',array('village_id'=>$_GET['village_id']))}">
											<span class="icon-circle">
															<img src="{pigcms{$static_path}img/house_index_8.png">
														</span>
											<span class="icon-desc">更多</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					
					
				</section>
				
				
				
				<else />
				<if condition='$index_service_cat_list'>
					<section class="slider">
						<div class="swiper-container swiper-container2" style=" max-height:190px;">
							<div class="swiper-wrapper">
								<volist name="index_service_cat_list" id="vo">
									<div class="swiper-slide" >
										<ul class="icon-list" >
											<volist name="vo" id="voo">
												<li class="icon">
													<a href="{pigcms{$voo.url}">
														<span class="icon-circle">
															<img src="{pigcms{$config.site_url}/upload/service/{pigcms{$voo.img}">
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
					</section>
				</if>
                
			</if>
            <if condition="$news_list">
					<section class="slider">
							<div class="platformNews clearfix link-url" data-url="{pigcms{:U('village_newslist',array('village_id'=>$_GET['village_id']))}">
								<volist name="news_list" id="vo">
									<p><span class="green">公告</span>{pigcms{$vo.title|msubstr=###,0,30}<span class="right">{pigcms{$vo.add_time|date='m-d H:i',###}</span></p>
								</volist>
								<p class="more">查看更多></p>
							</div>
					</section>	
				</if>

			<if condition="$activity_list['list']">
				<section class="TripList_5">
					<div class="swiper-container swiper-container3">
						<div class="swiper-wrapper">
							<volist name="activity_list['list']" id="vo">
								<div class="swiper-slide">
									<a href="{pigcms{:U('House/village_activity',array('village_id'=>$now_village['village_id'],'id'=>$vo['id']))}" class="Cardis">
										<p class="Cardis_p1"><img src="{pigcms{$config.site_url}/upload/activity/{pigcms{$vo.pic}" height="150px"/><span style="position:absolute; right:20px; bottom:70px; color:#fff; background:#000;opacity: 0.3; border-radius:100px; padding:0 5px; font-size:12px; line-height: 30px;height: 30px;"><if condition='$vo["apply_end_time"]+86400 gt time()'>截至 {pigcms{$vo.apply_end_time|date="Y-m-d",###}<else />&nbsp;&nbsp;已截止&nbsp;&nbsp;</if></span></p>
										<p class="Cardis_p2">{pigcms{$vo.title|strip_tags}</p>
									</a>
								</div>
							</volist>
						</div>
						<div class="swiper-pagination swiper-pagination3"></div>
						<div class="swiper-pagination more" onclick="location.href='{pigcms{:U('village_activitylist',array('village_id'=>$_GET['village_id']))}'">查看更多></div>
					</div>
				</section>
			</if>
			<include file="House:footer" />
			<if condition="$has_index_store">

			<div id="storeList">
				<header>
					<a href="{pigcms{:U('shop#cat-all',array('village_id'=>$_GET['village_id']))}"><i class="fa fa-shopping-cart pull-left funFont"></i><span class="pull-left">小区商家</span><i class="fa fa-angle-right pull-right"></i></a>
				</header>
				<dl class="dealcard"></dl>
				<div id="storeListLoadTip">正在加载中...</div>
			</div>
            </if>
		</div>
		<div id="pageShopSearch" class="pageDiv">
			<div id="pageShopSearchHeader" class="searchHeader">
				<div id="pageShopSearchBackBtn" class="searhBackBtn"></div>
				<div id="pageShopSearchBox" class="searchBox">
					<div class="searchIco"></div>
					<input type="text" id="pageShopSearchTxt" class="searchTxt" placeholder="请输入店铺名称" autocomplete="off" />
					<div class="delIco" id="pageShopSearchDel">
						<div></div>
					</div>
				</div>
				<div id="pageShopSearchBtn" class="searchBtn">搜索</div>
			</div>

		</div>
        <!--<div class='back'>返回</div>-->
		<script type="text/javascript" src="{pigcms{$static_path}js/houseBase.js" charset="utf-8"></script>

		<script id="listShopTpl" type="text/html">
			{{# for(var i = 0, len = d.length; i
			< len; i++){ }} <dd class="page-link" data-url="" data-url-type="openRightFloatWindow" {{# if(d[i].is_close){ }}style="opacity:0.6;" {{# } }}>
				<div class="dealcard-img imgbox" onclick="location.href='/wap.php?c=Shop&a=classic_shop&shop_id={{ d[i].id }}'" >
					<img src="{{ d[i].image }}" alt="{{ d[i].name }}"> {{# if(d[i].is_close){ }}
					<div class="closeTip">休息中</div>{{# } }}
				</div>
				<div class="dealcard-block-right" onclick="location.href='/wap.php?c=Shop&a=classic_shop&shop_id={{ d[i].id }}'" >
					<div class="brand">{{ d[i].name }}<em class="location-right">{{# if(user_long != '0'){ }}{{ d[i].range }}{{# } }}</em></div>
					<div class="title {{# if(!d[i].delivery){ }}pick{{# } }}">
						<span class="star"><i class="full"></i><i class="full"></i><i class="full"></i><i class="half"></i><i></i></span><span>月售{{ d[i].month_sale_count }}单</span> {{# if(d[i].delivery){ }}
						<em class="location-right">{{ d[i].delivery_time }}分钟</em> {{# }else{ }}
						<em class="location-right">门店自提</em> {{# } }}
					</div>
					{{# if(d[i].delivery){ }}
					<div class="price">
						<span>起送价 ￥{{ d[i].delivery_price }}</span><span class="delivery">配送费 ￥{{ d[i].delivery_money }}</span> {{# if(d[i].delivery_system){ }}
						<em class="location-right">{{ deliverName }} </em> {{# }else{ }}
						<em class="location-right">商家配送</em> {{# } }}
					</div>
					{{# } }}
				</div>
				{{# if(d[i].coupon_count > 0){ }}
				<div class="coupon {{# if(d[i].coupon_count > 2){ }}hasMore{{# } }}">
					<ul>
						{{# var tmpCouponList = parseCoupon(d[i].coupon_list,'array'); }} {{# if(tmpCouponList['invoice']){ }}
						<li><em class="merchant_invoice"></em>{{ tmpCouponList['invoice'] }}</li>
						{{# } }} {{# if(tmpCouponList['discount']){ }}
						<li><em class="merchant_discount"></em>{{ tmpCouponList['discount'] }}</li>
						{{# } }} {{# if(tmpCouponList['minus']){ }}
						<li><em class="merchant_minus"></em>{{ tmpCouponList['minus'] }}</li>
						{{# } }} {{# if(tmpCouponList['newuser']){ }}
						<li><em class="newuser"></em>{{ tmpCouponList['newuser'] }}</li>
						{{# } }} {{# if(tmpCouponList['delivery']){ }}
						<li><em class="delivery"></em>{{ tmpCouponList['delivery'] }}</li>
						{{# } }} {{# if(tmpCouponList['system_minus']){ }}
						<li><em class="system_minus"></em>{{ tmpCouponList['system_minus'] }}</li>
						{{# } }} {{# if(tmpCouponList['system_newuser']){ }}
						<li><em class="system_newuser"></em>{{ tmpCouponList['system_newuser'] }}</li>
						{{# } }}
					</ul>
					{{# if(d[i].coupon_count > 2){ }}
					<div class="more">{{ d[i].coupon_count }}个活动</div>
					{{# } }}
				</div>
				{{# } }}
				</dd>
				{{# } }}
		</script>
		<script type="text/javascript">
			var userOpenid = "{pigcms{$user_session.openid}";
			window.shareData = {  
				"moduleName":"Village",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}",
				"tTitle": "{pigcms{$now_village.village_name}",
				"tContent": "欢迎您进入{pigcms{$now_village.village_name}"
			};
		</script>
		<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
		<!--script type="text/javascript">
			$(".back").on("click".function(){
				wx.miniProgram.navigateTo({url: '/pages/shop/index'})
			})
		</script-->
		
		<!--wx.miniProgram.navigateTo({url: '/path/to/page'})-->
		{pigcms{$shareScript}
		{pigcms{$coupon_html}
	</body>
</html>