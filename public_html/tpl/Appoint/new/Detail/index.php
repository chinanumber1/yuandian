<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>【{pigcms{$now_group.merchant_name}预约】{pigcms{$now_group.appoint_name}预约 - {pigcms{$config.site_name}</title>
		<meta name="keywords" content="{pigcms{$now_group.merchant_name},{pigcms{$now_group.appoint_name},{pigcms{$config.site_name}" />
		<meta name="description" content="{pigcms{$now_group.appoint_content}" />
		<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
		<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/shopping.css"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/table.css"/>
		 	<script type="text/javascript">
			var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
			</script>
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script>var site_url = "{pigcms{$config.site_url}";var store_long="{pigcms{$now_group.store_list.0.long}";var store_lat="{pigcms{$now_group.store_list.0.lat}";var get_reply_url="{pigcms{:U('Index/Reply/ajax_get_list',array('order_type'=>0,'parent_id'=>$now_group['group_id'],'store_count'=>count($now_group['store_list'])))}";var collect_url="{pigcms{:U('Index/Collect/collect')}";var login_url="{pigcms{:U('Index/Login/frame_login')}";save_history("{pigcms{$now_group.appoint_name}","{pigcms{$now_group.all_pic.0.m_image}","{pigcms{$now_group.url}","","预约");</script>
		
		<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
		<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>

		<script src="{pigcms{$static_path}js/group_detail.js"></script>
	<style type="text/css">
	#mpackageslist li{
    border: 2px solid #ddd;
    color: #666;
    display: inline-block;
    line-height: 27px;
    margin: 0 5px 5px 0;
    max-width: 230px;
    overflow: hidden;
    padding-left: 8px;
    padding-right: 8px;
    position: relative;
    text-decoration: none;
    text-overflow: ellipsis;
    white-space: nowrap;
   }
	#mpackageslist li a{ color: #666;}
	#mpackageslist li a:hover{ color: #fe5842;}
	#mpackageslist .current{  border: 2px solid #fe5842}
	#mpackageslist .current a{ color: #fe5842;}
	#mpackageslist ul{left: 15px;margin-left: 0px;position: relative; width: 85%;}
</style>
	</head>
	<body>
		<include file="Public:header_top"/>
		<div class="body"> 
			<article>
				<div class="menu cf">
					<div class="menu_left hide">
						<div class="menu_left_top"><img src="{pigcms{$static_path}images/o2o1_27.png" /></div>
						<div class="list">
							<ul>
								<volist name="all_category_list" id="vo" key="k">
									<li>
										<div class="li_top cf">
											<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
											<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
										</div>
										<if condition="$vo['cat_count'] gt 1">
											<div class="li_bottom">
												<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
													<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
												</volist>
											</div>
										</if>
									</li>
								</volist>
							</ul>
						</div>
					</div>
					<div class="menu_right cf">
						<div class="menu_right_top">
							<ul>
								<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
									<li class="ctur">
										<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
									</li>
								</pigcms:slider>
							</ul>
						</div>
					</div>
				</div>
			</article>
			<article class="product cf">
				<div class="navBreadCrumb cf">
					<ul class="cf">
						<li><a href="{pigcms{$config.site_url}">网站首页</a></li>
						<li><span>»</span></li>
						<li><a href="{pigcms{$f_category.url}">{pigcms{$f_category.cat_name}</a></li>
						<li><span>»</span></li>
						<li><a class="link--black__green" href="{pigcms{$s_category.url}">{pigcms{$s_category.cat_name}</a></li>
						<if condition="$now_group['store_list'][0]['area']">
							<li><span>»</span></li>
							<li><a href="{pigcms{$now_group.store_list.0.area.url}">{pigcms{$now_group.store_list.0.area.area_name}</a></li>
							<li><span>»</span></li>
							<li><a href="{pigcms{$now_group.store_list.0.circle.url}">{pigcms{$now_group.store_list.0.circle.area_name}</a></li>
						</if>
						<li><span>»</span></li>
						<li>{pigcms{$now_group.merchant_name}</li>
					</ul>
				</div>
				<div class="product_top_line">
					<div class="product_name"><span>【{pigcms{$now_group.merchant_name}】</span>{pigcms{$now_group.appoint_name}</div>
					<div class="product_dec">{pigcms{$now_group.appoint_content}</div>
				</div>
				<div class="product_table cf">
					<div class="product_img cf"> 
						<div id="slider">
							<div class="show-box">
								<ul>
									<li><img src="{pigcms{$now_group.all_pic.0.m_image}"/></li>
								</ul>
							</div>
							<div class="minImgs">
								<div class="min-box">
									<ul class="min-box-list" style="margin:0px auto;">
										<volist name="now_group['all_pic']" id="vo">
											<li class="<if condition='$i eq 1'>cur</if>">
												<div><img src="{pigcms{$vo.m_image}"/></div>
											</li>
										</volist>
									</ul>
								</div>							  
							</div>
						</div>
					</div>
					<div class="product_list cf">
						<div class="product_list_top cf">
							<div class="product_info">
								<div class="product_info_list">
									<ul>
										<li class="cf">
											<div class="product_info_list_left">定金：</div>
											<div class="priduct_price"><if condition="$now_group['payment_status'] eq 1">¥<strong>{pigcms{$now_group.payment_money}</strong><else/>无需定金</if></div>
										</li>
										<li class="cf">
											<div class="product_info_list_left">已预约：</div>
											<div class="priduct_sale">{pigcms{$now_group['appoint_sum']}</div>
										</li>
										<li class="cf">
											<div class="product_info_list_left">商家：</div>
											<div class="priduct_shop"><a href="{pigcms{$config.site_url}/merindex/{pigcms{$now_group.mer_id}.html" target="_blank">{pigcms{$now_group.merchant_name}</a>&nbsp;&nbsp;&nbsp;<span>|</span>&nbsp;&nbsp;&nbsp;<a class="see_anchor" data-anchor="business-info" href="javascript:void(0);">查看地址/电话</a></div>
										</li>
										<li class="cf">
											<div class="product_info_list_left">有效期：</div>
											<div class="priduct_data">截止到{pigcms{$now_group.end_time|date='Y.m.d',###} </div>
										</li>
										<li>
											<div class="product_info_list_left">可选服务：</div>
											<div class="bigclass">
												<div class="priduct_data">
												<?php if($appoint_product_list): ?>
												<?php foreach($appoint_product_list as $val): ?>
													<div class="class2">
														<span style="margin-right:10px;"><?php echo $val['name']; ?></span>
														<span>￥<?php echo $val['price']; ?></span>
													</div>
												<?php endforeach; ?>
												<?php else : ?>
													<div class="class2">
														<span style="margin-right:10px;">该预约没有可选服务</span>
													</div>
												<?php endif; ?>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="product_info_right">
								<div class="product_info_right_img"><img src="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'appoint','id'=>$now_group['appoint_id']))}"/></div>
								<p>微信扫一扫轻松预约</p>
							</div>
						</div>
						<div class="product_list_bottom">
							<form action="{pigcms{$now_group.buy_url}" method="get">
								<div class="but cf">
									<button class="info_but" type="submit">立即预约</button>
									<a class="info_shop_but" href="{pigcms{$config.site_url}/merindex/{pigcms{$now_group.mer_id}.html" target="_blank">商家店铺</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</article>
		</div>
		<div class="detail_content cf">
			<div class="content_left">
				<div class="content_navbar" id="J-content-navbar">
					<ul class="cf">
						<li class="current"><a href="#business-info">商家位置</a></li>
						<li><a href="#anchor-detail">预约详情</a></li>
						<li><a href="#anchor-bizinfo">商家介绍</a></li>
					</ul>
					<div id="J-nav-buy" class="buy-group J-hub">
						<a rel="nofollow" class="J-buy btn-hot buy" href="{pigcms{$now_group.buy_url}">立即预约</a>
					</div>
				</div>
				<section class="address cf" id="business-info">
					<div class="section_title cf">
						<div class="section_txt">商家位置</div>
						<div class="section_border"></div>
					</div>
					<div class="map">
						<div class="map_map">
							<div class="map_map_img">
								<div id="map-canvas" map_point="{pigcms{$now_group.store_list.0.long},{pigcms{$now_group.store_list.0.lat}" store_name="{pigcms{$now_group.store_list.0.name}" store_adress="{pigcms{$now_group.store_list.0.area_name}{pigcms{$now_group.store_list.0.adress}" store_phone="{pigcms{$now_group.store_list.0.phone}" frame_url="{pigcms{:U('Map/frame_map')}"></div>
								<div class="map_icon J-view-full"><img src="{pigcms{$static_path}images/xiangqing_31.png"/></div>
							</div>
						</div>
						<div class="map_txt">
							<volist name="now_group['store_list']" id="vo">
								<div class="biz-info <if condition="$i eq 1">biz-info--open biz-info--first</if> <if condition="count($now_group['store_list']) eq 1">biz-info--only</if>">
									<div class="biz-info__title">
										<div class="shop_name">{pigcms{$vo.name}</div>
										<i class="F-glob F-glob-caret-down-thin down-arrow"></i>
									</div>
									<div class="biz-info__content">
										<div class="shop_add"><span>地址：</span>{pigcms{$vo.area_name}{pigcms{$vo.adress}</div>
										<div class="shop_map"><a class="view-map" href="javascript:void(0)" map_point="{pigcms{$vo.long},{pigcms{$vo.lat}"  store_name="{pigcms{$vo.name}" store_adress="{pigcms{$vo.area_name}{pigcms{$vo.adress}" store_phone="{pigcms{$vo.phone}" frame_url="{pigcms{:U('group/Map/frame_map')}">查看地图</a>&nbsp;&nbsp;&nbsp;<a class="search-path" href="javascript:void(0)" shop_name="{pigcms{$vo.adress}">公交/驾车去这里</a></div>
										<div class="shop_ip"><span>电话：</span>{pigcms{$vo.phone}</div>
									</div>
								</div>
							</volist>
						</div>
					</div>
				</section>
				
				<section class="package cf" id="anchor-detail">
					<div class="section_title cf">
						<div class="section_txt">预约详情</div>
						<div class="section_border"></div>
					</div>
					<style>
						.BMap_cpyCtrl{display:none;}
						.group_content{padding-top:20px;font-size:14px;  color: #666;}
						.group_content table { width:100%!important; margin-top:0px; border:none; color:#222;  border-collapse: collapse;border-spacing: 0; }
						.group_content table .name { width:auto; text-align:left; border-left:none; }
						.group_content table .price { width:15%; text-align:center; }
						.group_content table .amount { width:15%; text-align:center; }
						.group_content table .subtotal { width:15%; text-align:right; border-right:none; font-family: arial, sans-serif; }
						.group_content table caption, .group_content table th, .group_content table td { padding:8px 10px; background:#FFF; border:1px solid #E8E8E8; border-top:none; word-break:break-all; word-wrap:break-word; }
						.group_content table caption { background:#F0F0F0; }
						.group_content table caption .title, .group_content table .subline .title { font-weight:bold; }
						.group_content table th { color:#333; background:#F0F0F0; font-weight:bold; border-left-style:none; border-right-style:none;}
						.group_content table td { color:#666; /*border-left-style:none; border-right-style:none;*/ border-bottom-style:dotted; }
						.group_content table .subline { background:#fff; text-align:center; border-left:none; border-right:none; }
						.group_content table .subline-left { width:22%; text-align:left;border-right: 1px #e8e8e8 dotted; }
						.group_content p{  margin: 10px 0;font: 14px/24px helvetica neue,helvetica,arial,simsun,"微软雅黑",Hiragino Sans GB,sans-serif;color: #666;}
						.deal-menu-summary { padding:0 10px 10px; text-align:right; border-bottom:1px #e8e8e8 solid; }
						.deal-menu-summary .worth { display:inline-block; min-width:10px; _width:10px; padding-right:20px; text-align:left; word-break:normal; word-wrap:normal; font-weight:bold; }
						.deal-menu-summary .price { color:#ea4f01; padding-right:0; }
						.group_content ul.list{margin:10px 0 15px;padding-left:18px;}
						.group_content ul.list li {list-style-position: outside;list-style-type: disc;margin-bottom: 5px;}
					</style>
					<div class="group_content">{pigcms{$now_group.appoint_pic_content}</div>
				</section>
				<section class="introduce cf" id="anchor-bizinfo">
					<div class="section_title cf">
						<div class="section_txt"><a name="anchor-bizinfo">商家介绍</a></div>
						<div class="section_border"></div>
					</div>
					<div class="introduce_title">{pigcms{$now_group.merchant_name}</div>
					<div class="introduce_txt">{pigcms{$now_group.txt_info}</div>
					<div class="introduce_img">
						<volist name="now_group['merchant_pic']" id="vo">
							<img src="{pigcms{$vo}" alt="{pigcms{$now_group.merchant_name}" class="standard-image"/>
						</volist>
					</div>
				</section>
				<section class="shop_bottom">
					<ul>
						<li>
							<div class="shop_bottom_list">已预约</div>
							<div class="shop_bottom_txt">{pigcms{$now_group['appoint_sum']}</div>
						</li>
						<li>
							<div class="shop_bottom_list">定金</div>
							<div class="shop_bottom_txt"><if condition="$now_group['payment_status'] eq 1">¥{pigcms{$now_group.payment_money}<else/>无需定金</if></div>
						</li>
						</if>
						<li style="float:right">
							<a class="shop_bottom_but" href="{pigcms{$now_group.buy_url}">预约</a>
						</li>
					</ul>
				</section>
			</div>
			<if condition="$category_hot_group_list">
				<div class="content_right">
					<div class="activity">
						<div class="activity_title">看了本预约的人还看了</div>
						<div class="content_right_list">
							<ul>
								<volist name="category_hot_group_list" id="vo">
									<li>
										<a href="{pigcms{$vo.url}" target="_blank">
											<div class="category_list_img">
												<img src="{pigcms{$vo.list_pic}" title="【{pigcms{$vo.merchant_name}】{pigcms{$vo.appoint_name}"/>
												
											</div>
											<div class="datal cf">
												<div class="category_list_title">【{pigcms{$vo.merchant_name}】{pigcms{$vo.appoint_name}</div>
												<if condition="$now_group['payment_status'] eq 1">
												<div class="deal-tile__detail cf"><span id="price"><span style="color:black;margin:0;">定金：</span>¥<strong>{pigcms{$vo.payment_money}</strong></span></div>
												</if>
												<div class="extra-inner cf">
													<div class="sales">已预约<strong class="num">{pigcms{$vo['appoint_sum']}</strong></div>
												</div>
											</div>
										</a>
									</li>
								</volist>
							</ul>
						</div>
					</div>
				</div>
			</if>
		</div>
		<include file="Public:footer"/>
	</body>
</html>
