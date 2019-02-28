<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta name="format-detection" content="telephone=no"/>
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mall.css"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/swiper.min.css"/>
<!-- <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript">var noAnimate = true;</script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script>var ajax_url = "{pigcms{:U('Mall/ajax_goods', array('store_id' => $store['id']))}", shopReplyUrl = "{pigcms{$config.site_url}/index.php?g=Index&c=Reply&a=ajax_get_list&order_type=3&parent_id={pigcms{$store['id']}";
var open_extra_price =Number("{pigcms{$config.open_extra_price}");
var extra_price_name ="{pigcms{$config.extra_price_alias_name}";</script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
<script type="text/javascript" src="{pigcms{$static_path}js/mallstore.js" charset="utf-8"></script>
</head>
<body>
	<section class="Florist home" style="background: url(<if condition="$store['background']">{pigcms{$store['background']}<else />{pigcms{$static_path}images/ff_02.jpg</if>) center no-repeat; background-size: 100%;">
		<div class="Florist_top clr">
			<div class="fl">
				<img src="{pigcms{$store['image']}" width=49 height=49>
			</div>
			<div class="p53">
				<h2>
					<a href="javascript:void(0)" class="clr purl" >
						<span class="fl title">{pigcms{$store['name']}</span>
						<div class="fl">
							<span class="fl markjt"></span>
						</div>
					</a>
				</h2>
				<p>{pigcms{$store['store_notice']}</p>
			</div>
			<div class="all">
				<h3>{pigcms{$goods_count}</h3>
				<p>全部商品</p>
			</div>
		</div>
		<a href="{pigcms{:U('Mall/search', array('store_id' => $store['id']))}" class="Icon"></a>
	</section>
	<section class="menus">
		<div class="switch">
			<ul class="clr">
				<li class="icons on"  data-type="goods">全部商品</li>
				<li class="icons" data-type="reply">评价</li>
				<li class="icons"  data-type="detail">商家详情</li>
				<li class="slider"></li>
			</ul>
			<input type="hidden" id="isLoadReply" value="0"/>
		</div>
		<div class="hand"> 
			<div class="hand_list">
				<ul class="clr">
					<li class="sort <if condition="$now_sort['sort_id']">on</if>">
						<span data-sort_id="{pigcms{$now_sort['sort_id']}">{pigcms{$now_sort['sort_name']}</span>
						<div class="halist_n">
							<dl>
								<dd data-sort_id="0"><div class="agio_top">全部</div></dd>
								<volist name="sort_list" id="sort">
								<dd data-sort_id="{pigcms{$sort['sort_id']}">
									<div class="agio_top">{pigcms{$sort['sort_name']}</div>
									<if condition="$sort['sort_discount']">
									<div class="agio_end">
										<div class="disink clr">
											<em class="agio fl">{pigcms{$sort['sort_discount']}折</em>
											<em class="fl">折扣不同享</em>
										</div>
									</div>
									</if>
								</dd>
								</volist>
							</dl>
						</div>
					</li>
					<li class="sorts on" data-sort="1">
						<span>销量</span>
					</li>
					<li class="sorts" data-sort="2">
						<span>价格</span>
					</li>
					<!--li class="sorts" data-sort="3">
						<span>评分</span>
					</li-->
				</ul>
			</div>
			<div class="bd_a clr">
<!-- 				<a href="http://www.group.com/wap.php?g=Wap&c=Mall&a=detail&goods_id=132"> -->
<!-- 					<div class="bd_img"> -->
<!-- 						<img src="images/user_avatar.jpg" width=100% > -->
<!-- 					</div> -->
<!-- 					<div class="bd_text"> -->
<!-- 						<h2>芒果拿破仑</h2> -->
<!-- 						<div class="Price clr"> -->
<!-- 							<div class="fl"> -->
<!-- 								<span>￥<i>38</i></span> -->
<!-- 								<del>原价58</del> -->
<!-- 							</div> -->
<!-- 							<div class="fr">月售2586单</div> -->
<!-- 						</div> -->
<!--  					</div> -->
<!-- 					<div class="discount">限时优惠</div>    -->
<!-- 				</a> -->
			</div>
		</div>
		
		<div class="hand" style="display: none;">
			<div class="hand_discuss">
				<ul class="clr">
					<li class="on" data-tab="">
						<h2>全部评价</h2>
						<p>0</p>
					</li>
					<li data-tab="good">
						<h2>满意</h2>
						<p>0</p>
					</li>
					<li data-tab="wrong">
						<h2>不满意</h2>
						<p>0</p>
					</li>
				</ul>
			</div>
			
			<section class="evaluate evaluates">
				<dl>
<!-- 					<dd> -->
<!-- 						<div class="title clr"> -->
<!-- 							<h2 class="fl clr"> -->
<!-- 								<img src="images/user_avatar.jpg" width=20 height=20 class="fl"> -->
<!-- 								<span class="fl">Miss 胡</span> -->
<!-- 							</h2> -->
<!-- 							<div class="atar_Show fr"> -->
<!-- 								<p tip="3.5"></p> -->
<!-- 							</div> -->
<!-- 						</div> -->
<!-- 						<div class="content">送货很快，很好看。包装的很好看，很喜欢，下次还会再来</div> -->
<!-- 						<div class="attr">规格属性：中盒；19朵玫瑰</div> -->
<!-- 						<div class="attr clr"> -->
<!-- 							<span>购买日期：2016-10-19</span> -->
<!-- 						</div> -->
<!-- 						<div class="date"> -->
<!-- 							<div class="data_n"> -->
<!-- 								<div class="data_top clr"> -->
<!-- 									<h2 class="fl">商家回复</h2> -->
<!-- 									<span class="fr">2016-10-19 18:20</span> -->
<!-- 								</div> -->
<!-- 								<p>非常感谢您对我们的肯定，真诚期待您的下次合作，我们会做的更好！</p> -->
<!-- 							</div> -->
<!-- 						</div> -->
<!-- 					</dd> -->
<!-- 					<dd> -->
<!-- 						<div class="title clr"> -->
<!-- 							<h2 class="fl clr"> -->
<!-- 								<img src="images/user_avatar.jpg" width=20 height=20 class="fl"> -->
<!-- 								<span class="fl">Miss 胡</span> -->
<!-- 							</h2> -->
<!-- 							<div class="atar_Show fr"> -->
<!-- 								<p tip="3.5"></p> -->
<!-- 							</div> -->
<!-- 						</div> -->
<!-- 						<div class="content">送货很快，很好看。包装的很好看，很喜欢，下次还会再来</div> -->
<!-- 						<div class="attr">规格属性：中盒；19朵玫瑰</div> -->
<!-- 						<div class="attr clr"> -->
<!-- 							<span>购买日期：2016-10-19</span> -->
<!-- 						</div> -->
<!-- 					</dd> -->
				</dl>
			</section> 
		</div>

		<div class="hand" style="display: none;">
			<div class="hand_dz">
				<ul>
					<li class="photo more">
						<a href="javascript:void(0);">
							<span></span>
							<div class="p25 phone" data-phone="{pigcms{$store['phone']}">店铺电话：{pigcms{$store['phone']}</div>
						</a>
					</li>
					<li class="dp more">
						<a href="{pigcms{:U('Mall/addressinfo', array('store_id' => $store['id']))}">
							<span></span>
							<div class="p25">店铺地址：{pigcms{$store['adress']}</div>
						</a>
					</li>
					<li class="time">
						<span></span>
						<div class="p25">营业时间：{pigcms{$store['time']}</div>
					</li>
					<!--li class="ps">
						<span></span>
						<if condition="$store['delivery']">
							<if condition="$store['delivery_system']">
							<div class="p25">配送服务：由 平台 提供配送</div>
							<else />
							<div class="p25">配送服务：由 店铺 提供配送</div>
							</if>
						<else />
						<div class="p25">配送服务：本店铺仅支持门店自提</div>
						</if>
					</li-->
					<li class="gog">
						<span></span>
						<div class="p25">店铺公告：{pigcms{$store['store_notice']}</div>
					</li>
				</ul>
			</div>
			<if condition="$store['coupon_list']">
			<div class="hand_dz">
				<ul>
					<if condition="isset($store['coupon_list']['invoice']) AND $store['coupon_list']['invoice']">
					<li class="cc">
						<span>
							<em>票</em>
						</span>
						<div class="p25">满{pigcms{$store['coupon_list']['invoice']}元支持开发票，请在下单时填写发票抬头</div>
					</li>
					</if>
					<if condition="isset($store['coupon_list']['discount']) AND $store['coupon_list']['discount']">
					<li class="aa">
						<span>
							<em>折</em>
						</span>
						<div class="p25">店内全场{pigcms{$store['coupon_list']['discount']}折</div>
					</li>
					</if>
					<if condition="$store['txt_discount']['sys_newuser']">
					<li class="ff">
						<span>
							<em>首</em>
						</span>
						<div class="p25">平台首单{pigcms{$store['txt_discount']['sys_newuser']}</div>
					</li>
					</if>
					<if condition="$store['txt_discount']['sys_minus']">
					<li class="cc">
						<span>
							<em>减</em>
						</span>
						<div class="p25">平台满减{pigcms{$store['txt_discount']['sys_minus']}</div>
					</li>
					</if>
					<if condition="$store['txt_discount']['mer_newuser']">
					<li class="ff">
						<span>
							<em>首</em>
						</span>
						<div class="p25">店铺首单{pigcms{$store['txt_discount']['mer_newuser']}</div>
					</li>
					</if>
					<if condition="$store['txt_discount']['mer_minus']">
					<li class="cc">
						<span>
							<em>减</em>
						</span>
						<div class="p25">店铺满减{pigcms{$store['txt_discount']['mer_minus']}</div>
					</li>
					</if>
				</ul>
			</div>
			</if>
		</div>
	</section>
	<div class="mask"></div>
</body>
<script id="goodsListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.goods_list.length; i < len; i++){ }}
	<a href="{{ d.goods_list[i].url }}">
		<div class="bd_img">
			<img src="{{ d.goods_list[i].image }}" width="100%"/>
		</div>
		<div class="bd_text">
			<h2>{{ d.goods_list[i].name }}</h2>
			<div class="Price clr">
				<div class="fl">
					<span>￥<i>{{ d.goods_list[i].price }}{{# if(open_extra_price==1&&d.goods_list[i].extra_pay_price>0){ }}+{{ d.goods_list[i].extra_pay_price }}{{ extra_price_name }}{{# } }}</i></span>
					{{# if (d.goods_list[i].is_seckill_price){}}
					<del>原价{{ d.goods_list[i].old_price }}</del>
					{{# } }}
				</div>
				{{# if (d.goods_list[i].sell_count > 0){}}
				<div class="fr">已售{{ d.goods_list[i].sell_count }}单</div>
				{{# } else if(d.goods_list[i].is_new == 1) { }}
				<div class="fr">新品上架</div>
				{{# } }}
			</div>
		</div>
		{{# if (d.goods_list[i].is_seckill_price){}}
		<div class="discount">限时优惠</div>
		{{# } }}
	</a>
{{# } }}
</script>
<script id="shopReplyTpl" type="text/html">
{{# for(var i = 0, len = d.length; i < len; i++){ }}
<dd>
	<div class="title clr">
		<h2 class="fl clr">
			<img src="{{# if(d[i].avatar!= ''){}}{{ d[i].avatar }}{{# }else{ }}/static/images/portrait.jpg{{# } }}" width=20 height=20 class="fl">
			<span class="fl">{{ d[i].nickname }}</span>
		</h2>
		<div class="atar_Show fr">
			<p tip="{{ d[i].score }}"></p>
		</div>
	</div>
	<div class="content">{{ d[i].comment }}</div>
	{{# if(d[i].goods){ }}
		{{# var tmpGoods = d[i].goods; }}
		<div class="attr">点赞商品：
		{{# for(var k in tmpGoods){ }}
			{{ tmpGoods[k] }} 
		{{# } }}
		</div>
	{{# } }}
	<div class="attr clr">
		<span>发表日期：{{ d[i].add_time_hi }}</span>
	</div>
	{{# if(d[i].merchant_reply_time != '0'){ }}
	<div class="date">
		<div class="data_n">
			<div class="data_top clr">
				<h2 class="fl">商家回复</h2>
				<span class="fr">{{ d[i].merchant_reply_time_hi }}</span>
			</div>
			<p>{{ d[i].merchant_reply_content }}</p>
		</div>
	</div>
	{{# } }}
</dd>
{{# } }}
</script>
<script type="text/javascript">
window.shareData = {
			"moduleName":"Mall",
			"moduleID":"0",
			"imgUrl": "{pigcms{$store['image']}",
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Mall/store', array('store_id' => $store['id']))}",
			"tTitle": "{pigcms{$store['name']}",
			"tContent": "{pigcms{$store['name']}"
};
</script>
{pigcms{$shareScript}
<if condition="$is_app_browser">
<script type="text/javascript">
    window.lifepasslogin.shareLifePass("{pigcms{$store['name']}", "{pigcms{$store['name']}", "{pigcms{$store['image']}", "{pigcms{$config.site_url}{pigcms{:U('Mall/store', array('store_id' => $store['id']))}");
</script>
</if>
</html>