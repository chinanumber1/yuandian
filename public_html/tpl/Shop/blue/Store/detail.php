<!doctype html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<if condition="$config['site_favicon']">
		<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
	</if>
	<title>{pigcms{$config.shop_alias_name}_{pigcms{$now_city.area_name}_{pigcms{$config.seo_title}</title>
	<if condition="$now_area">
		<meta name="keywords" content="{pigcms{$now_area.area_name},{pigcms{$now_circle.area_name},{pigcms{$config.seo_keywords}" />
	<else />
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
	</if>
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<meta charset="utf-8">
	<link href="{pigcms{$static_path}css/shop_pc.css" rel="stylesheet"/>
    <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
        <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}"</script>
        <else />
	<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2&s=1"></script>
    </if>
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script src="{pigcms{$static_public}js/layer/layer.js"></script>
	<script src="{pigcms{$static_path}js/requestAnimationFrame.js"></script>
	<script src="{pigcms{$static_path}js/fly.js"></script>
	<script type="text/javascript">var store_id = '{pigcms{$store['id']}',store_theme = '{pigcms{$store['store_theme']}',isClose = '{pigcms{$store['is_close']}', is_pick = '{pigcms{$store['pick']}', delivery_price = '{pigcms{$store['delivery_price']|floatval}', pack_alias = '{pigcms{$store["pack_alias"]}', store_long = '{pigcms{$store.long}',store_lat = '{pigcms{$store.lat}',static_path = "{pigcms{$static_path}", ajax_goods="{pigcms{:U('Store/ajax_goods')}", cookie_index = 'foodshop_cart_{pigcms{$store["id"]}', cart_url = "/shop/order/{pigcms{$store['id']}.html",ExtraPirceName = "{pigcms{$config.extra_price_alias_name}",open_extra_price = Number("{pigcms{$config.open_extra_price}");</script>
	<script src="{pigcms{$static_path}js/shop_menu.js?t=111"></script>
	<!--[if lte IE 9]>
	<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
	<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
	<![endif]-->
</head>
<body style="background: #f5f5f5;">
	<section class="shoptop">
		<div class="shopto_top">
			<div class="w1200 clr">
				<div class="fl clr">
					<span class="fl">{pigcms{$shop_select_address}</span>
					<a href="/shop/change.html" class="fl">[切换地址]</a> 
				</div>
				<if condition="empty($user_session)">
					<div class="fr">
						<span><a href="{pigcms{:UU('Index/Login/index')}">登录</a> | <a href="{pigcms{:UU('Index/Login/reg')}">注册</a></span>
					</div>
				<else />
					<div class="fr">
						<span><a href="{pigcms{:UU('User/Index/index')}">{pigcms{$user_session.nickname}</a> | <a href="{pigcms{:UU('Index/Login/logout')}">退出</a></span>
					</div>
				</if>
			</div>
		</div>
		<div class="shopto_end shopto_end2">
			<div class="w1200 clr">
				<div class="fl img">
					<a href="/"><img src="{pigcms{$config.site_logo}" width="163" height="51"></a>
				</div>
				<div class="link fl">
					<a href="/shop.html" class="on">首页</a><span>|</span><a href="{pigcms{:UU('User/Index/index')}">我的订单</a>
				</div>
				<div class="fr">
					<input type="text" placeholder="搜索美食" id="keyword" value="{pigcms{$keyword}">
					<button id="search" style="cursor: pointer;">搜索</button>
				</div>
			</div>
		</div>
	</section>
	<section class="details">
		<div class="w1200 clr">
			<div class="fl parent">
				<div class="img fl">
					<img src="{pigcms{$store['image']}">
				</div>
				<div class="pl15 clr">
					<div class="title clr">
						<h2>{pigcms{$store['name']}</h2>
						<if condition="$store['is_close']">
							<span class="no">未营业</span>
						<else />
							<span class="yes">营业中</span>
						</if>
						<if condition="$store['isverify']">
							<img src="../static/images/sjxq_rec.png" style="float: right;margin-left: 5px;" >
						</if>
					</div>
					<div class="score clr">
						<div class="fl">
							<div class="atar_Show">
								<p></p>
							</div>
							<span class="Fraction"><i>{pigcms{$store['star']}</i>分</span>
						</div>
						<span class="fl">月售{pigcms{$store['month_sale_count']}单</span>
					</div>
					<div class="time">接单时间：{pigcms{$store['time']}</div>
                    <if condition="!empty($store['close_reason']) AND $store['is_close']">
					<div class="radio">{pigcms{$store['close_reason']}</div>
                    </if>
				</div>
				<div class="trans">
					<div class="trans_n">
						<ul>
							<li>
								<span class="fl">店铺地址：</span>
								<div class="p62">{pigcms{$store['adress']}</div> 
							</li>
							<li>
								<span class="fl">店铺电话：</span>
								<div class="p62">{pigcms{$store['phone']}</div> 
							</li>
							<li>
								<span class="fl">配送服务：</span>
								<div class="p62">{pigcms{$store['deliver_name']}</div> 
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="fr give">
				<ul class="clr">
					<li>
						<h2>￥{pigcms{$store['delivery_price']|floatval}</h2>
						<p>起送价</p>
					</li>
					<li>
						<h2>￥{pigcms{$store['delivery_money']|floatval}</h2>
						<p>配送费</p>
					</li>
					<li>
						<h2>{pigcms{$store['delivery_time']}分钟</h2>
						<p>送达时间</p>
					</li>
				</ul>
			</div>
		</div>
	</section>
	
	<section class="variety">
		<div class="w1200 clr">
			<div class="fl vleft">
				<if condition="$keyword">
					<div class="search-tip">
						<p>找到<span class="keyword">“{pigcms{$keyword}”</span>相关餐饮<span class="count">{pigcms{$count}</span>个</p>
					</div>
					<else />
					<div class="vlefttop">
						<div class="clr change">
							<a href="/shop/{pigcms{$store['id']}.html" class="on">商品</a>
							<a href="/shop/comment/{pigcms{$store['id']}.html">评价</a>
							<if condition="$config['store_shop_auth']">
								<a href="/shop/auth/{pigcms{$store['id']}.html">资质证照</a>
							</if>
						</div>
						<div class="Selling Shoplist_top clr">
							<div class="fr">
								<a href="/shop/{pigcms{$store['id']}.html" <if condition="$sort eq 0">class="on"</if>>默认排序</a>
								<a href="/shop/{pigcms{$store['id']}.html?sort=1" <if condition="$sort eq 1">class="on"</if>>销量<i></i></a>
								<a href="/shop/{pigcms{$store['id']}.html?sort=2" <if condition="$sort eq 2">class="on"</if>>价格<i></i></a>
							</div>
						</div>
					</div>
					<if condition="$sort eq 0">
						<div class="vleftend clr">
							<volist name="product_list" id="row">
								<a href="javascript:void(0)" <if condition="$key eq 0">class="on"</if> data-cat_id="{pigcms{$row['cat_id']}">{pigcms{$row['cat_name']} </a>
							</volist>
						</div>
					</if>
				</if>
				<div class="varietylist">
					<volist name="product_list" id="rowset">
					<div class="slist varietylist-{pigcms{$rowset['cat_id']}" data-cat_id="{pigcms{$rowset['cat_id']}">
						<if condition="$rowset['cat_id']">
						<div class="Selling clr">
							<div class="fl">{pigcms{$rowset['cat_name']} <if condition="$rowset['sort_discount']"><span style="font-size:14px;color:red">{pigcms{$rowset['sort_discount']}折</span></if></div> 
						</div>
						</if>
						<div class="Sell_list">
							<ul class="clr">
								<volist name="rowset['product_list']" id="vo">
									<li>
										<a href="javascript:void(0)" style="cursor: default;">
											<div class="img" data-goods_id="{pigcms{$vo['product_id']}" data-index="{pigcms{$vo['product_id']}" data-has_format="{pigcms{$vo['has_format']}" style="cursor: pointer;">
												<if condition="$store['store_theme']">
													<img src="{pigcms{$vo['product_image']}" style="height:190px;width:190px;"/>
												<else />
													<img src="{pigcms{$vo['product_image']}" style="padding:18px 0;height:105px;width:190px;"/>
												</if>
											</div>
											<div class="text">
												<dl>
													<dd class="clr top">
														<h2>{pigcms{$vo['product_name']}</h2>
													</dd>
													<dd class="clr middle">
														<div class="fl" style="width: 140px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">已售{pigcms{$vo['product_sale']}{pigcms{$vo['unit']}  <if condition="$vo['stock'] neq -1 AND $vo['stock'] lt 10">库存{pigcms{$vo['stock']}{pigcms{$vo['unit']}</if></div>
														<div class="fr bs-up">(<i>{pigcms{$vo['product_reply']}</i>)</div>
													</dd>
													<dd class="clr end">
														<div class="fl" <if condition="$vo['is_seckill_price'] eq 1 ">style="line-height:18px"</if>>
															<if condition="$vo['is_seckill_price'] eq 1">
															<span><i class="imit_i">限时价:</i>￥{pigcms{$vo['product_price']|floatval}<if condition="$vo.extra_pay_price gt 0 AND $config.open_extra_price eq 1 AND $vo.has_format eq false">+{pigcms{$vo.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></span> 
															<br>
															<del style="ne-height: 16px;float: left;margin-left: 0px;">原价￥{pigcms{$vo['o_price']|floatval}</del>
															<else />
															<span>￥{pigcms{$vo['product_price']|floatval}<if condition="$vo.extra_pay_price gt 0 AND $config.open_extra_price eq 1 AND $vo.has_format eq false">+{pigcms{$vo.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if>
															</span>
															</if>
                                                            <if condition="$vo['min_num'] gt 1">
                                                            <span style="font-size: 12px; color:#999">{pigcms{$vo['min_num']}{pigcms{$vo['unit']}起购</span>
                                                            </if>
														</div>
														<div style="cursor: pointer;" class="fr click" data-has_format="{pigcms{$vo['has_format']}" data-index="{pigcms{$vo['product_id']}" data-goods_id="{pigcms{$vo['product_id']}" data-price="{pigcms{$vo['product_price']|floatval}" data-packing_charge="{pigcms{$vo['packing_charge']|floatval}" data-stock="{pigcms{$vo['stock']}" data-name="{pigcms{$vo['product_name']}" data-extra_pay_price="{pigcms{$vo.extra_pay_price}" data-max_num="{pigcms{$vo.max_num }" data-min_num="{pigcms{$vo.min_num }" data-unit="{pigcms{$vo.unit }" data-limit_type="{pigcms{$vo.limit_type}" data-o_price="{pigcms{$vo.o_price}" data-is_seckill="{pigcms{$vo.is_seckill_price|intval}"></div>
													</dd>
												</dl>
											</div>
										</a>
										<if condition="$vo['is_seckill_price'] eq 1">
										<div class="imit">限时优惠<php>if ($vo['max_num'] > 0) { echo ',限' . $vo['max_num'] . $vo['unit'] . '优惠';}</php></div>
                                        <elseif condition="$vo['max_num'] gt 0" />
                                        <div class="imit">限购{pigcms{$vo['max_num']}{pigcms{$vo['unit']}</div>
										</if>
									</li>
								</volist>
							</ul>
						</div> 
					</div>
					</volist>
				</div>
			</div>
			
			<div class="fr vright">
				<div class="vright_top">
					<h2>商家公告</h2>
					<div class="text">{pigcms{$store['store_notice']}</div> 
				</div>
				<div class="vright_middle">
					<div class="activity">
						<dl>
							<if condition="isset($store['coupon_list']['system_newuser']) AND $store['coupon_list']['system_newuser']">
								<dd>
									<span class="fl platform">首</span>
									<div class="a_text">平台首单
										<volist name="store['coupon_list']['system_newuser']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['system_minus']) AND $store['coupon_list']['system_minus']">
								<dd>
									<span class="fl reduce">减</span>
									<div class="a_text">平台
										<volist name="store['coupon_list']['system_minus']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['delivery']) AND $store['coupon_list']['delivery']">
								<dd>
									<span class="fl red">惠</span>
									<div class="a_text">配送费
									<volist name="store['coupon_list']['delivery']" id="vo">
										满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
									</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['discount']) AND $store['coupon_list']['discount']">
								<dd>
									<span class="fl zhe">折</span>
									<div class="a_text">店内全场{pigcms{$store['coupon_list']['discount']}折</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['newuser']) AND $store['coupon_list']['newuser']">
								<dd>
									<span class="fl business">首</span>
									<div class="a_text">店铺首单
										<volist name="store['coupon_list']['newuser']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['minus']) AND $store['coupon_list']['minus']">
								<dd>
									<span class="fl ticket">减</span>
									<div class="a_text">店铺
										<volist name="store['coupon_list']['minus']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
						</dl>
					</div>
				</div>
				<div class="vright_end" id="biz-map">
				</div>
			</div>
		</div>
	</section>
	
	<include file="Public:footer"/>
	<!-- 导航 -->
	<section class="scan">
		<ul>
			<li class="code">
				<div class="display">
					<h2>扫描二维码</h2>
					<p>关注微信 下单优惠更多</p>
					<img src="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'shop','id'=>$store['id']))}" width=122 height=122>
				</div>
			</li>
			<li class="Return"></li>
		</ul>
	</section>
	
	<!-- 弹窗 -->
	<div class="Popup">
		<h2 class="title">商品介绍</h2>
		<div class="Popup_n clr"></div>
		<a href="javascript:void(0)" class="gb"></a>
	</div>
	<div class="mask"></div>
	<!-- 弹窗 -->
	
	<!-- 购物车 -->
	<div class="car">
		<div class="cartop clr">
			<span class="fl">购物车</span>
			<a href="javascript:void(0)" class="fr empty">清空</a>
		</div>
		<div class="carmiddle clr"><ul></ul></div>
		<div class="carend">
			<div class="fl carleft">
				<span class="mark"></span>
				<div class="common clr" style="display: none;">
					<span class="fl">共</span>
					<span class="fr">￥<i id="total_price">35</i></span>
				</div>
				<i class="amount" style="display: none;"></i>
			</div>
			<if condition="$store['pick']">
				<div class="tencer">购物车是空的</div>
			<else />
				<div class="tencer">{pigcms{$store['delivery_price']|floatval}元起送</div>
			</if>
			<form action="/shop/order/{pigcms{$store['id']}.html" method="post" id="post_cart">
				<input type="hidden" name="foodshop_cart" id="foodshop_cart"/>
			</form>
		</div>
	</div>
	<!-- 购物车 -->
</body>
</html>