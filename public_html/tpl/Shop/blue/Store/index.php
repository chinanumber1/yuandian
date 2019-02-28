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
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script type="text/javascript">var  ajax_list = "{pigcms{:U('Store/ajax_list')}", deliverName = "{pigcms{$config['deliver_name']}";</script>
	<script src="{pigcms{$static_path}js/shop_store_list.js"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<!--[if lte IE 9]>
	<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
	<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
	<![endif]-->
    <script type="text/javascript">var deliverName = '{pigcms{$config['deliver_name']}';</script>
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
		<div class="shopto_end">
			<div class="w1200 clr">
				<div class="fl img">
					<a href="/"><img src="{pigcms{$config.site_logo}" width=163 height=51></a>
				</div>
				<div class="link fl">
					<a href="/shop.html" class="on">首页</a><span>|</span><a href="{pigcms{:UU('User/Index/index')}">我的订单</a>
				</div>
				<div class="fr">
					<input type="text" placeholder="搜索店铺" id="keyword" value="">
					<button id="search" style="cursor: pointer;">搜索</button>
				</div>
			</div>
		</div>
	</section>
	<section class="fication" <if condition="$keyword">style="display:none"</if>>
		<div class="w1200 clr">
			<div class="fication_n clr">
				<div class="fication_top fl">店铺分类：</div>
				<div class="fication_end">
					<ul class="clr">
						<volist name="category_list" id="rowset">
						<li <if condition="$rowset['cat_id'] eq $cat_fid">class="on"</if> data-cat_url="{pigcms{$rowset['cat_url']}" data-cat_id="{pigcms{$rowset['cat_id']}">
							<a href="/shop/{pigcms{$rowset['cat_url']}/{pigcms{$sort_url}/{pigcms{$type_url}"><span>{pigcms{$rowset['cat_name']}</span></a>
						</li>
						</volist>
					</ul>
					<volist name="category_list" id="rowset">
					<if condition="$rowset['son_list']">
					<div class="fication_list fication_list_{pigcms{$rowset['cat_id']}" <if condition="$rowset['cat_id'] eq $cat_fid">style="display:block"</if>>
						<dl class="clr">
							<dd><a href="/shop/{pigcms{$rowset['cat_url']}/{pigcms{$sort_url}/{pigcms{$type_url}" <if condition="0 eq $cat_id AND $rowset['cat_id'] eq $cat_fid">class="on"</if> data-cat_url="{pigcms{$rowset['cat_url']}">全部</a></dd>
							<volist name="rowset['son_list']" id="row">
								<dd><a href="/shop/{pigcms{$row['cat_url']}/{pigcms{$sort_url}/{pigcms{$type_url}" <if condition="$row['cat_id'] eq $cat_id">class="on"</if> data-cat_url="{pigcms{$row['cat_url']}">{pigcms{$row['cat_name']}</a></dd>
							</volist>
						</dl>
					</div>
					</if>
					</volist>
				</div>
			</div>
		</div>
	</section>
	<section class="Shoplist">
		<div class="w1200">
			<div class="search-tip" <if condition="empty($keyword)">style="display:none"</if>>
				<p>找到<span class="keyword">“{pigcms{$keyword}”</span>相关{pigcms{$config.shop_alias_name}<span class="count"></span>个</p>
			</div>
			<div class="Shoplist_top clr" <if condition="$keyword">style="display:none"</if>>
				<div class="fl sort">
					<a href="/shop/{pigcms{$cat_url}/juli/{pigcms{$type_url}" <if condition="$sort_url eq 'juli'">class="on"</if> data-sort_url="juli">默认排序</a>
					<a href="/shop/{pigcms{$cat_url}/sale_count/{pigcms{$type_url}" <if condition="$sort_url eq 'sale_count'">class="on"</if> data-sort_url="sale_count">销量<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/send_time/{pigcms{$type_url}" <if condition="$sort_url eq 'send_time'">class="on"</if> data-sort_url="send_time">配送时间<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/basic_price/{pigcms{$type_url}" <if condition="$sort_url eq 'basic_price'">class="on"</if> data-sort_url="basic_price">起送价<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/score_mean/{pigcms{$type_url}" <if condition="$sort_url eq 'score_mean'">class="on"</if> data-sort_url="score_mean">评分<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/create_time/{pigcms{$type_url}" <if condition="$sort_url eq 'create_time'">class="on"</if> data-sort_url="create_time">最新发布<i></i></a>
				</div>
				<div class="fr deliver">
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/-1" style="padding:0px;background:white;"><span <if condition="$type_url eq -1">class="on"</if> data-type="-1">全部</span></a>
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/0" style="padding:0px;background:white;"><span <if condition="$type_url eq 0">class="on"</if> data-type="0">配送</span></a>
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/2" style="padding:0px;background:white;"><span <if condition="$type_url eq 2">class="on"</if> data-type="2">自提</span></a>
					<!-- span <if condition="$type_url eq 3">class="on"</if> data-type="3">{pigcms{$config['deliver_name']}/自提</span>
					<span <if condition="$type_url eq 4">class="on"</if> data-type="4">商家配送/自提</span -->
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/5" style="padding:0px;background:white;"><span <if condition="$type_url eq 5">class="on"</if> data-type="5">快递配送</span></a>
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/1" style="padding:0px;background:white;"><span <if condition="$type_url eq 1">class="on"</if> data-type="1">{pigcms{$config['deliver_name']}</span></a>
				</div>
			</div> 
			<div class="Shoplist_end">
				<ul class="clr navBox_list">
					<volist name="store_list" id="vo">
					<li>
						<a href="{pigcms{$vo['detail_url']}">
							<div class="fix">
							<div class="img">
								<img src="{pigcms{$vo['image']}" onerror="javascript:this.src='/upload/store/000/003/903/58be25bc41b3e238.png';" width=222 height=148>
								<div class="imgewm">
									<if condition="$i lt 8">
										<img src="{pigcms{$vo['qrcode_url']}" width="78" height="78"/>
									<else/>
										<img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$vo['qrcode_url']}" width="78" height="78"/>
									</if>
									<p>微信扫码 手机查看</p>
								</div>
							</div>
							<div class="text">
								<dl>
									<dd class="clr top">
										<h2 class="fl">{pigcms{$vo['name']}</h2>
										<span class="fr">{pigcms{$vo['range']}</span>
									</dd>
									<dd class="clr middle">
										<div class="fl">
											<div class="atar_Show">
												<p></p>
											</div>
											<span class="Fraction"><i>{pigcms{$vo['star']}</i>分</span>
				  						</div>
										<span class="fr">已售{pigcms{$vo['month_sale_count']}单</span>
				 					</dd>
									<if condition="$vo['delivery']">
									<dd class="clr end">
										<span class="r5">起送:￥<i>{pigcms{$vo['delivery_price']}</i></span>
										<span class="r5">配送费:￥<i>{pigcms{$vo['delivery_money']}</i></span>
										<span class="fr">{pigcms{$vo['delivery_time']}分钟</span>
									</dd>
									<else />
									<dd class="clr end">
										<span class="r5">人均消费:￥<i>{pigcms{$vo['mean_money']}</i></span>
									</dd>
									</if>
								</dl>
							</div>
							<div class="list">
								<dl class="clr">
									<php>$tmp_num=0;</php>
									<if condition="$vo['isverify'] gt 0">
										<dd class="fl zheng">证</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['system_newuser'])">
									<dd class="fl platform">首</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['system_minus'])">
									<dd class="fl reduce">减</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['delivery'])">
									<dd class="fl red">惠</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['discount'])">
									<dd class="fl zhe">折</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['newuser'])">
									<dd class="fl business">首</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['minus']) AND $tmp_num lt 6">
									<dd class="fl ticket">减</dd>
                                        <php>$tmp_num++;</php>
									</if>
                                    <if condition="isset($vo['coupon_list']['isDiscountGoods']) AND $vo['coupon_list']['isDiscountGoods'] == 1 AND $tmp_num lt 6">
                                    <dd class="fl red">部</dd>
                                    <php>$tmp_num++;</php>
                                    </if>
                                    <if condition="isset($vo['coupon_list']['isdiscountsort']) AND $vo['coupon_list']['isdiscountsort'] == 1 AND $tmp_num lt 6">
                                    <dd class="fl zhe">分</dd>
                                    <php>$tmp_num++;</php>
                                    </if>
				
									<if condition="$vo['delivery'] eq 0">
										<dd class="fr express">门店自提</dd>
									<elseif condition="$vo['delivery_system']" />
										<dd class="fr platform">{pigcms{$config['deliver_name']}</dd>
									<elseif condition="$vo['deliver_type'] eq 5" />
										<dd class="fr Since">快递配送</dd>
									<else />
										<dd class="fr business">商家配送</dd>
									</if>
								</dl>
							</div>
							</div>
							<div class="position">
								<h2 class="h2top">{pigcms{$vo['name']}</h2>
								<div class="activity">
									<dl>
				
										<if condition="isset($vo['system_newuser_text'])">
										<dd>
											<span class="fl platform">首</span>
											<div class="a_text">{pigcms{$vo['system_newuser_text']}</div>
										</dd>
										</if>
										<if condition="isset($vo['system_minus_text'])">
										<dd>
											<span class="fl reduce">减</span>
											<div class="a_text">{pigcms{$vo['system_minus_text']}</div>
										</dd>
										</if>
										<if condition="isset($vo['delivery_text'])">
										<dd>
											<span class="fl red">惠</span>
											<div class="a_text">{pigcms{$vo['delivery_text']}</div>
										</dd>
										</if>
										
										<if condition="isset($vo['coupon_list']['discount'])">
										<dd>
											<span class="fl zhe">折</span>
											<div class="a_text">店内全场{pigcms{$vo['coupon_list']['discount']}折</div>
										</dd>
										</if>
										<if condition="isset($vo['newuser_text'])">
										<dd>
											<span class="fl red">首</span>
											<div class="a_text">{pigcms{$vo['newuser_text']}</div>
										</dd>
										</if>
										<if condition="isset($vo['minus_text'])">
										<dd>
											<span class="fl ticket">减</span>
											<div class="a_text">{pigcms{$vo['minus_text']}</div>
										</dd>
										</if>
                                        <if condition="isset($vo['coupon_list']['isDiscountGoods']) AND $vo['coupon_list']['isDiscountGoods'] eq 1">
                                        <dd>
                                            <span class="fl ticket">限</span>
                                            <div class="a_text">店内有部分商品限时优惠</div>
                                        </dd>
                                        </if>
									</dl>
								</div>
				 				<div class="notice">
									<h2>商家公告</h2>{pigcms{$vo['store_notice']}
								</div>
							</div> 
						</a>
					</li>
					</volist>
				</ul>
			</div>
			<if condition="$next_page">
			<a href="javascript:void(0)" class="Load" data-page="2">点击加载更多商家...</a>
			</if>
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
					<img src="{pigcms{$config.wechat_qrcode}" width=122 height=122>
				</div>
			</li>
			<li class="Return"></li>
		</ul>
	</section>
<script id="storeListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
	<li>
		<a href="{{ d.store_list[i].detail_url }}">
			<div class="fix">
			<div class="img">
				<img src="{{ d.store_list[i].image }}" onerror="javascript:this.src='/upload/store/000/003/903/58be25bc41b3e238.png';" width=222 height=148>
				<div class="imgewm">
					{{# if(i < 8){ }}
						<img src="{{ d.store_list[i].qrcode_url }}" width="78" height="78"/>
					{{# }else{ }}
						<img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{{ d.store_list[i].qrcode_url }}" width="78" height="78"/>
					{{# } }}
					<p>微信扫码 手机查看</p>
				</div>
			</div>
			<div class="text">
				<dl>
					<dd class="clr top">
						<h2 class="fl">{{ d.store_list[i].name }}</h2>
						<span class="fr">{{ d.store_list[i].range }}</span>
					</dd>
					<dd class="clr middle">
						<div class="fl">
							<div class="atar_Show">
								<p></p>
							</div>
							<span class="Fraction"><i>{{ d.store_list[i].star }}</i>分</span>
  						</div>
						<span class="fr">已售{{ d.store_list[i].month_sale_count }}单</span>
 					</dd>
					{{# if(d.store_list[i].delivery){ }}
					<dd class="clr end">
						<span class="r5">起送:￥<i>{{ d.store_list[i].delivery_price }}</i></span>
						<span class="r5">配送费:￥<i>{{ d.store_list[i].delivery_money }}</i></span>
						<span class="fr">{{ d.store_list[i].delivery_time }}分钟</span>
					</dd>
					{{# }else{ }}
					<dd class="clr end">
						<span class="r5">人均消费:￥<i>{{ d.store_list[i].mean_money }}</i></span>
					</dd>
					{{# } }}
				</dl>
			</div>
			<div class="list">
				<dl class="clr">
				{{# var tmp_num=0 }}
					{{# if(d.store_list[i].isverify > 0){ }}
						<dd class="fl zheng">证</dd>
						{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.system_newuser != undefined){ }}
					<dd class="fl platform">首</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.system_minus != undefined){ }}
					<dd class="fl reduce">减</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.delivery != undefined){ }}
					<dd class="fl red">惠</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.discount != undefined){ }}
					<dd class="fl zhe">折</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.newuser != undefined){ }}
					<dd class="fl business">首</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.isDiscountGoods != 0 && tmp_num<6){ }}
					<dd class="fl ticket">限</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.minus != undefined&&tmp_num<6){ }}
					<dd class="fl ticket">减</dd>
					{{# } }}
						
					{{# if(!d.store_list[i].delivery){ }}
					<dd class="fr express">门店自提</dd>
					{{# } }}


					{{# if(d.store_list[i].delivery){ }}
						{{# if(d.store_list[i].delivery_system){ }}
							<dd class="fr platform">{{ deliverName }}</dd>
						{{# }else{ }}
							{{# if(d.store_list[i].deliver_type == 5){ }}
								<dd class="fr Since">快递配送</dd>
							{{# }else{ }}
								<dd class="fr business">商家配送</dd>
							{{# } }}
						{{# } }}
					{{# } }}
				</dl>
			</div>
			</div>
			<div class="position">
				<h2 class="h2top">{{ d.store_list[i].name }}</h2>
				<div class="activity">
					<dl>
						{{# var tmpCouponList = parseCoupon(d.store_list[i].coupon_list,'array');  }}

						{{# if(tmpCouponList['system_newuser']){ }}
						<dd>
							<span class="fl platform">首</span>
							<div class="a_text">{{ tmpCouponList['system_newuser'] }}</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['system_minus']){ }}
						<dd>
							<span class="fl reduce">减</span>
							<div class="a_text">{{ tmpCouponList['system_minus'] }}</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['delivery']){ }}
						<dd>
							<span class="fl red">惠</span>
							<div class="a_text">{{ tmpCouponList['delivery'] }}</div>
						</dd>
						{{# } }}
						{{# if(d.store_list[i].coupon_list.discount != undefined){ }}
						<dd>
							<span class="fl zhe">折</span>
							<div class="a_text">店内全场{{ d.store_list[i].coupon_list.discount }}折</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['newuser']){ }}
						<dd>
							<span class="fl red">首</span>
							<div class="a_text">{{ tmpCouponList['newuser'] }}</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['minus']){ }}
						<dd>
							<span class="fl ticket">减</span>
							<div class="a_text">{{ tmpCouponList['minus'] }}</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['isDiscountGoods']){ }}
						<dd>
							<span class="fl ticket">限</span>
							<div class="a_text">{{ tmpCouponList['isDiscountGoods'] }}</div>
						</dd>
						{{# } }}
					</dl>
				</div>
 				<div class="notice">
					<h2>商家公告</h2>{{ d.store_list[i].store_notice }}
				</div>  
			</div> 
		</a>
	</li>
{{# } }}
</script>
</body>
</html>
