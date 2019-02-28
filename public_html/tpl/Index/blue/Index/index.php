<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<if condition="$config['site_favicon']">
			<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
		</if>
		<title>{pigcms{$config.seo_title}</title>
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
		<meta name="description" content="{pigcms{$config.seo_description}" />
		<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
		<link href="{pigcms{$static_path}css/new.css" type="text/css"  rel="stylesheet" />
		<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/ydyfx.css"/>
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script src="{pigcms{$static_path}js/jquery.nav.js"></script>
		<script src="{pigcms{$static_path}js/navfix.js"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}js/index.js"></script>
		<script src="{pigcms{$static_path}js/index.activity.js"></script>
		<if condition="$config['wap_redirect']">
			<script>
				if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){
					<if condition="$config['wap_redirect'] eq 1">
						window.location.href = './wap.php';
					<else/>
						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){
							window.location.href = './wap.php';
						}
					</if>
				}

			</script>
		</if>
		<!--[if IE 6]>
		<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
		<script type="text/javascript">
		   /* EXAMPLE */
		   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

		   /* string argument can be any CSS selector */
		   /* .png_bg example is unnecessary */
		   /* change it to what suits you! */
		</script>
		<script type="text/javascript">DD_belatedPNG.fix('*');</script>
		<style type="text/css">
			body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
			.category_list li:hover .bmbox {filter:alpha(opacity=50);}
			.gd_box{display:none;}
		</style>
		<![endif]-->
	</head>
	<body>
		<pigcms:adver cat_key="index_top_fold" limit="1" var_name="index_top_fold">
			<div class="index_top_fold_box" style="background:url({pigcms{$vo.pic}) no-repeat center top {pigcms{$vo.bg_color};">
				<a href="{pigcms{$vo.url}" target="_blank" class="link"></a>
			</div>
		</pigcms:adver>
		<include file="Public:header_top"/>
		<div class="containr" style="position: relative; top:20px;">
			<div class="body" style="width:100%;">
				<div class="gd_box" style="top:1540px;">
					<div id="gd_box">
						<div id="gd_box1">
							<div id="nav">
								<ul>
									<php>$autoI = 0;</php>
									<volist name="index_group_list" id="vo">
										<if condition="!empty($vo['group_list']) && count($vo['group_list']) egt 4">
											<li <if condition="$i eq 1">class="current"</if>>
												<a class="f{pigcms{$i}" onClick="scrollToId('#f{pigcms{$i}');"><img src="{pigcms{$vo.cat_pic}" />
													<div class="scroll_{pigcms{$autoI%7+1}">{pigcms{$vo.cat_name}</div>
												</a>
											</li>
											<php>$autoI++;</php>
										</if>
									</volist>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div style=" width: 780px; height:50px; overflow: hidden; margin: 0 auto; position: absolute; top:50px; z-index: 9999; line-height: 50px;left: 50%;margin-left: -390px;">
				<if condition="!empty($scroll_msg)">
					<div  class="scroll_msg" style="">
						<div style="">
							<div class=""  id="scrollText" style="">
								<marquee  scrollamount="5" onmouseover = this.stop()  onmouseout=this.start() style="height:50px;margin: 0px; padding: 0px;background: rgba(255,255,255,0.9);" >
								<volist name="scroll_msg" id="vo">
									<div style="display:inline-block">
										<span style="padding-right:30px;">
											<i style="background:url({pigcms{$static_path}images/lbt_03.png) left center no-repeat; background-size: 15px; width: 20px; height: 20px; display:block; float: left; margin-top: 15px;"></i>
											<a style="color: #ff2c4d;;">{pigcms{$vo.content}</a>
										</span>
									</div>
								</volist>
								</marquee>
							</div>
						</div>
					</div>


					<style>
					#scrollText div a{ color: #a94442;}
					</style>
					<link rel="stylesheet" href="{pigcms{$static_public}font-awesome/css/font-awesome.min.css">
					</if>
				</div>
				<!---menu left--->
				<div class="menu cf" style="position: relative;width: 1200px;">
					<div class="menu_left" style="margin-top:0px">
						<img class="category" src="{pigcms{$static_path}images/category.png" /><div class="menu_left_top">全部分类</div>
						<div class="list">
							<ul>
								<volist name="all_category_list" id="vo" key="k">
									<li>
										<div class="li_top cf">
											<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
											<div class="li_txt"><a href="{pigcms{$vo.url}" title="{pigcms{$vo.cat_name}" target="_blank">{pigcms{$vo.cat_name|mb_substr=0,6,'utf-8'}</a></div>
										</div>
										<if condition="$vo['cat_count'] gt 1">
											<div class="rightIco"></div>
											<div class="li_bottom">
												<volist name="vo['category_list']" id="voo" offset="0" length="2" key="j">
													<span><a href="{pigcms{$voo.url}" title="{pigcms{$voo.cat_name}" target="_blank">{pigcms{$voo.cat_name|mb_substr=0,4,'utf-8'}</a></span>
												</volist>
											</div>
											<div class="list_txt">
												<p><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name|mb_substr=0,4,'utf-8'}</a></p>
												<volist name="vo['category_list']" id="voo" key="j">
													<a class="<if condition="$voo['is_hot']">bribe</if>" href="{pigcms{$voo.url}" title="{pigcms{$voo.cat_name}" target="_blank">{pigcms{$voo.cat_name|mb_substr=0,4,'utf-8'}</a>
												</volist>
											</div>
										</if>
									</li>
								</volist>
							</ul>
						</div>
					</div>
					<!---menu right--->
					<div class="menu_right cf" style="margin-top:0px">
						<div class="menu_right_top">
							<ul>
								<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
									<li class="ctur">
										<a href="{pigcms{$vo.url}" <if condition="$i eq 1">class="select"</if>>{pigcms{$vo.name}</a>
									</li>
								</pigcms:slider>
							</ul>
						</div>
					</div>
				</div>
				<div class="menu_main">
				<div style=" position: relative;width: 980px;padding-left:220px">
					<div class="menu cf" >
						<div class="menu_right cf" style="margin-top:0px">
							<div class="menu_right_bottom cf">
								<div class="left cf">
									<div class="activityDiv">
											<ul>
												<pigcms:adver cat_key="index_today_fav" limit="6" var_name="index_today_fav">
													<li <if condition="$i eq 1">class="mt-slider-current-trigger"</if> data-color="{pigcms{$vo.bg_color}" data-name="{pigcms{$vo.name}" data-subname="{pigcms{$vo.sub_name}">
														<a href="{pigcms{$vo.url}" target="_blank">
															<img src="{pigcms{$vo.pic}" alt="{pigcms{$vo.name}"/>
														</a>
													</li>
												</pigcms:adver>
											</ul>
											<div class="activityDesc">
												<h1></h1>
												<div class="activityInfo"></div>
												<pigcms:adver cat_key="index_today_fav" limit="6" var_name="index_today_fav">
												<a id="point{pigcms{$i}" href="javascript:void(0);" <if condition="$i neq 1">class="un_select"</if> ></a>
												</pigcms:adver>
											</div>

										<div class="pre-next">
											<a href="javascript:;" hidefocus="true" class="mt-slider-previous "></a>
											<a href="javascript:;" hidefocus="true" class="mt-slider-next "></a>
										</div>
									</div>

								</div>
								<div class="right cf" style="background-color:#fff;<if condition='$now_activity'>border-right:1px solid #dfdfdf;</if>">
									<div class="systemNews">
										<img src="{pigcms{$static_path}images/systemnews.png"><div class="title">平台快报<div class="more"><a href="{pigcms{$config.site_url}/news/" target="_blank">更多></a></div></div>
										<div class="newslist cf">
											<ul>
												<pigcms:system_news limit="8" var_name="system_newss">
													<li><a href="{pigcms{$config.site_url}/news/{pigcms{$vo.id}.html" target="_blank"><span>[{pigcms{$vo.name}]</span>{pigcms{$vo.title}</a></li>
												</pigcms:system_news>
											</ul>
										</div>
									</div>
									<div class="systemQrocde">
										<!--<div class="title">微信专享价 省更多</div>-->
										<div class="qrcodeDiv">
											<img src="{pigcms{$config.wechat_qrcode}"/>
										</div>
										<div class="s_title">微信扫描二维码 关注我们</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				<div style=" position: relative;width: 1200px;">
					<div class="menu cf" >
						<div class="menu_right cf" style="margin-top:0px;width:100%">

							<div class="menu_right_bottom cf" style="width:100%">
								<div class="left cf" style="width: 100%;height: 194px;">
									<div class="mainbav clearfix">
										<div class="main_list cf hot" >
											<div class="mainbav_left clearfix">
												<div class="mainbav_txt group">热门{pigcms{$config.group_alias_name}</div>
											</div>
											<div class="mainbav_list">
												<volist name="hot_group_category" id="vo">
													<span><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.cat_name}</a></span>
												</volist>
												<!--<div class="more"></div>-->
											</div>
										</div>
										<div class="main_list cf allarea" >
											<div class="mainbav_left clearfix">
												<div class="mainbav_txt area">全部区域</div>
											</div>
											<div class="mainbav_list">
												<volist name="all_area_list" id="vo">
													<span><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.area_name}</a></span>
												</volist>
												<!--<div class="more"></div>-->
											</div>
										</div>
										<div class="main_list cf circle hotcircle" >
											<div class="mainbav_left clearfix">
												<div class="mainbav_txt circle">热门商圈</div>
											</div>
											<div class="mainbav_list">
												<volist name="hot_circle_list" id="vo">
													<span><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.area_name}</a></span>
												</volist>
												<!--<div class="more"></div>-->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<pigcms:near_shop limit="10"/>
				<if condition="$near_shop_list">
					<pigcms:adver cat_key="index_meal_top" limit="4" var_name="index_meal_top">
						<if condition="$i eq 1"><ul class="index_adver_ul clearfix"></if>
							<li <if condition="$i eq 4">class="li_4"</if>>
								<a href="{pigcms{$vo.url}" target="_blank" class="link" title="{pigcms{$vo.name}">
									<img src="{pigcms{$vo.pic}"/>
								</a>
							</li>
						<if condition="$i eq 4 || $i eq count($index_meal_top)"></ul></if>
					</pigcms:adver>
					<div class="nearby cf indexMeal">
						<div class="indexMealTitle clearfix">
							<h1><if condition="$is_near_shop">附近{pigcms{$config.shop_alias_name}<else/>推荐{pigcms{$config.shop_alias_name}</if></h1>
						</div>
						<div class="nearby_list clearfix">
							<ul>
								<volist name="near_shop_list" id="vo">
									<li <if condition="$i gt 5">style="border-top:0px;"</if>>
										<div class="box">
											<div class="nearby_list_img">
												<a href="{pigcms{$vo.url}" target="_blank">

													<img class="meal_img lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$vo.image}" title="【{pigcms{$vo.area_name}】{pigcms{$vo.name}"/>
													<div class="bmbox">
														<div class="bmbox_title"> 微信扫码 手机查看</div>
														<div class="bmbox_list">
															<div class="bmbox_list_img"><img class="qrcode_img lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'shop','id'=>$vo['store_id']))}" /></div>
														</div>
														<!--div class="bmbox_tip">微信扫码 手机查看</div-->
													</div>
													<div class="name" style="height:42px;"><if condition="$vo.isverify"><span class="zheng">证</span></if>【{pigcms{$vo.area_name}】{pigcms{$vo.name}</div>
													<if condition="$vo['state']"><!--div class="name_info"><b>营业中</b></div--></if>
													<div class="extro">
														<div class="info">
															<div class="join"><if condition="$vo['range']">距离您 <span>{pigcms{$vo.range} </span><else/>粉丝 <span>{pigcms{$vo.fans_count}</span></if></div>
														</div>
														<if condition="$vo['sale_count']">
															<div class="info mealSales">
																<div class="join">已售 <span>{pigcms{$vo.sale_count}</span></div>
															</div>
														</if>
													</div>
												</a>
											</div>
										</div>
									</li>
								</volist>
							</ul>
						</div>
						<!--if condition="empty($is_near_shop)">
							<section class="nearby_box">
								<div class="nearby_box_txt"><img src="{pigcms{$static_path}images/tankuang_10.png"/></div>
								<button class="nearby_box_but"><span>选取</span></button>
								<div class="nearby_box_close"></div>
							</section>
						</if-->
					</div>
				</if>
				<div class="socll" style="width:100%;z-index:99">
					<php>$autoI=0;</php>
					<volist name="index_group_list" id="cat_vo">
						<if condition="!empty($cat_vo['group_list']) && count($cat_vo['group_list']) egt 4">
							<pigcms:adver cat_key="cat_{$cat_vo['cat_id']}_top" limit="4" var_name="cat_top" key="m">
								<if condition="$m eq 1"><ul class="index_adver_ul clearfix"></if>
									<li <if condition="$m eq 4">class="li_4"</if>>
										<a href="{pigcms{$vo.url}" target="_blank" class="link" title="{pigcms{$vo.name}">
											<img src="{pigcms{$vo.pic}"/>
										</a>
									</li>
								<if condition="$m eq 4 || $m eq count($cat_top)"></ul></if>
							</pigcms:adver>
							<div class="category cf sa" id="f{pigcms{$i}">
								<div class="category_top cf">
									<div class="category_top_left">
										<ul>
											<li id="category_main_{pigcms{$autoI%7+1}">
												<div class="category_main_icon"><if condition="$cat_vo['cat_pic']"><img src="{pigcms{$cat_vo.cat_pic}" style="width:22px;"/></if></div>
												<div class="category_main_txt">{pigcms{$cat_vo.cat_name}</div>
											</li>
										</ul>
									</div>
									<div class="category_top_right">
										<ul>
											<if condition="count($cat_vo['category_list']) gt 1">
												<volist name="cat_vo['category_list']" id="voo" offset="0" length="6" key="j">
													<li><a target="_blank" href="{pigcms{$voo.url}" class="link">{pigcms{$voo.cat_name}</a></li>
												</volist>
											</if>
											<li><a target="_blank" href="{pigcms{$cat_vo.url}" class="link all">全部></a></li>
										</ul>
									</div>
								</div>
								<div class="category_list cf">
									<ul class="cf">
										<volist name="cat_vo['group_list']" id="voo" offset="0" length="8" key="k">
											<li class="<if condition='$k gt 4'>btp0</if> li <if condition="$k%4 eq 0 || $k eq count($cat_vo['group_list'])">last--even</if>">
												<div class="category_list_img">
													<a href="{pigcms{$voo.url}" target="_blank" class="imgBox">
														<img alt="{pigcms{$voo.s_name}" class="deal_img lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$voo.list_pic}"/>
														<div class="bmbox">
															<div class="bmbox_title"> 该商家有<span> {pigcms{$voo.fans_count} </span>个粉丝</div>
															<div class="bmbox_list">
																<div class="bmbox_list_img"><img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'group','id'=>$voo['group_id']))}" /></div>
																<div class="bmbox_list_li">
																	<ul class="cf">
																		<li class="open_windows" data-url="{pigcms{$config.site_url}/merindex/{pigcms{$voo.mer_id}.html">商家</li>
																		<li class="open_windows" data-url="{pigcms{$config.site_url}/meractivity/{pigcms{$voo.mer_id}.html">{pigcms{$config.group_alias_name}</li>
																		<li class="open_windows" data-url="{pigcms{$config.site_url}/mergoods/{pigcms{$voo.mer_id}.html">{pigcms{$config.shop_alias_name}</li>
																		<li class="open_windows" data-url="{pigcms{$config.site_url}/mermap/{pigcms{$voo.mer_id}.html">地图</li>
																	</ul>
																</div>
															</div>
															<div class="bmbox_tip">微信扫码 更多优惠</div>
														</div>
													</a>
													<div class="datal">
														<a href="{pigcms{$voo.url}" target="_blank">
															<div class="category_list_title">{pigcms{$voo.group_name}</div>
															<div class="category_list_description">【{pigcms{$voo.prefix_title}】{pigcms{$voo.merchant_name}</div>
														</a>
														<div class="deal-tile__detail cf">
															<span class="price">&yen;<strong>{pigcms{$voo.price}<if condition="$voo.extra_pay_price  neq ''">{pigcms{$voo.extra_pay_price}</if></strong> </span>
															<span>门店价 &yen;{pigcms{$voo.old_price}<if condition="$voo.extra_pay_price  neq ''">{pigcms{$voo.extra_pay_price}</if></span>
															<if condition="$voo['wx_cheap']">
																<div class="cheap">微信购买立减￥{pigcms{$voo.wx_cheap}</div>
															</if>
														</div>
													</div>
													<div class="extra-inner cf">
														<div class="sales">{pigcms{$voo['sale_txt']}</div>
														<div class="noreviews">
															<if condition="$voo['reply_count']">
																<a href="{pigcms{$voo.url}#anchor-reviews" target="_blank">
																	<div class="icon"><span style="width:{pigcms{$voo['score_mean']/5*100}%;" class="rate-stars"></span></div>
																	<span>{pigcms{$voo.reply_count}次评价</span>
																</a>
															<else/>
																<span>暂无评价</span>
															</if>
														</div >
													</div>
												</div>
											</li>
										</volist>
									</ul>
								</div>
								<div class="category_more cf">
									<a href="{pigcms{$cat_vo.url}" target="_blank">
									查看全部 <span>{pigcms{$cat_vo.cat_name}</span> {pigcms{$config.group_alias_name} >
									</a>
								</div>
							</div>
							<php>$autoI++;</php>
						</if>
					</volist>
				</div>
			</div>
		</div>
		<!--友情链接-->
		<if condition="!empty($flink_list)">
			<style type="text/css">.component-holy-reco {clear: both; margin: 0 auto;width: 1210px; position: relative;bottom: -98px;}.holy-reco{width:100%;margin:0 auto;padding-bottom:20px;_display:none}.holy-reco .tab-item {
			color: #666;}.holy-reco__content{border:1px solid #E8E8E8;padding:10px;background:#FFF}.holy-reco__content a{display:inline-block;color:#666;font-size:12px;padding:0 5px;line-height:16px;white-space:nowrap;width:85px;overflow:hidden;text-overflow:ellipsis}.nav-tabs--small .current {background: #ededed none repeat scroll 0 0;width:80px;text-align:center;padding:0 6px;float:left;cursor:pointer;}</style>
			<div class="component-holy-reco">
				<div class="J-holy-reco holy-reco">
					<div>
						<ul class="ccf cf nav-tabs--small">
							<li class="J-holy-reco__label current"><a href="javascript:void(0)" class="tab-item">友情链接</a></li>
						</ul>
					</div>
					<div class="J-holy-reco__content holy-reco__content">
						<volist name="flink_list" id="vo">
							<a href="{pigcms{$vo.url}" title="{pigcms{$vo.info}" target="_blank">{pigcms{$vo.name}</a>
						</volist>
					</div>
				</div>
			</div>
		</if>
		<!--友情链接--end-->
		<include file="Public:footer"/>
	</body>
</html>
