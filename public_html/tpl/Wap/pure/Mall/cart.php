<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta name="format-detection" content="telephone=no"/>
<meta charset="utf-8">
<title>购物车</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mall.css"/>
<!-- <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript">var save_url = "{pigcms{:U('Mall/confirm_order')}";
var open_extra_price =Number("{pigcms{$config.open_extra_price}");
var extra_price_name ="{pigcms{$config.extra_price_alias_name}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/mallbuycart.js?t=1"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
<style>
.plus em, .piton span{
cursor: pointer;
}
</style>
</head>
<body>
	<if condition="$product_list">
	<section class="endcar">
		<ul>
			
			<volist name="product_list" id="sto" key="index">
			<php>if ($index == 1) {$store_id = $sto['store_id'];}</php>
			<li>
				<dl>
					<dt>
						<div class="fl piton">
							<span data-store_id="{pigcms{$sto['store_id']}" <if condition="$index eq 1">class="on"</if>></span>
						</div>
						<div class="p30">
							<a href="{pigcms{:U('Mall/store', array('store_id' => $sto['store_id']))}">{pigcms{$sto['name']}</a>
						</div>
					</dt>
					<volist name="sto['goods_list']" id="goods">
					<dd id="{pigcms{$goods['index_key']}" class="goods">
						<div class="fl piton">
							<span data-index_key="{pigcms{$goods['index_key']}" data-store_id="{pigcms{$sto['store_id']}" <if condition="$index eq 1">class="on"</if>></span>
						</div>
						<div class="p30 clr">
							<a href="{pigcms{:U('Mall/detail', array('goods_id' => $goods['goods_id']))}">
								<div class="img fl">
									<img src="{pigcms{$goods['image']}" width="92" height="92">
								</div>
								<div class="p102">
									<div class="conceal">{pigcms{$goods['name']}</div>
									<div class="cost clr">
										<span class="fl pricet">￥{pigcms{$goods['price']|floatval}<if condition="$config.open_extra_price eq 1 AND $goods.extra_pay_price gt 0">+{pigcms{$goods.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></span> 
									</div>
								</div>
							</a>
							<div class="plus clr fr">
								<em href="javascript:void(0)" class="jian" data-goods_id="{pigcms{$goods['goods_id']}" data-price="{pigcms{$goods['price']}" data-stock="{pigcms{$goods['stock_num']}" data-index_key="{pigcms{$goods['index_key']}" data-store_id="{pigcms{$sto['store_id']}" data-extra_pay_price="{pigcms{$goods['extra_pay_price']}">-</em>
								<input type="text" value="{pigcms{$goods['num']}" readonly="readonly">
								<em href="javascript:void(0)" class="jia" data-goods_id="{pigcms{$goods['goods_id']}" data-price="{pigcms{$goods['price']}" data-stock="{pigcms{$goods['stock_num']}" data-index_key="{pigcms{$goods['index_key']}" data-store_id="{pigcms{$sto['store_id']}" data-extra_pay_price="{pigcms{$goods['extra_pay_price']}">+</em>
							</div>
						</div>
					</dd>
					</volist>
					<dd>
						<div class="total clr">
							<div class="fr total_top">小计：<span id="this_total_{pigcms{$sto['store_id']}"></span></div>
							<!--div class="fr total_end">价格明细</div-->
						</div> 
					</dd>
				</dl>
			</li>
			</volist>
		</ul>
	</section>
	
	<section class="carbot clr" <if condition="$store_id">style="bottom:0px"</if>>
		<div class="carbot_n clr">
			<div class="fl carbot_left clr">
				<!--div class="fl whole"><span></span>全选</div-->
				<div class="fl close">合计:￥0</div>
			</div>
			<div class="fr carbot_right clr">
				<div class="fr close" data-store_id="{pigcms{$store_id}">去结算</div>
				<div class="fr whole"></div> 
			</div>
		</div>
	</section>
	
	<!-- 购物单 -->
	<section class="carxq">
		<h2>优惠详情</h2>
		<div class="carxq_ul">
			<ul>
				<li class="clr">
					<span class="fl">商品总价</span>
					<span class="fr red">￥576</span>
				</li>
				<li class="clr">
					<span class="fl">店铺折扣</span>
					<span class="fr">9折</span>
				</li>
				<li class="clr">
					<span class="fl">折扣后商品总价</span>
					<span class="fr red">￥576</span>
				</li>
				<li class="clr">
					<span class="fl">包装经费</span>
					<span class="fr red">￥576</span>
				</li>
				<li class="clr">
					<span class="fl">平台满减优惠</span>
					<span class="fr red">满￥10,减￥1元</span>
				</li>
			</ul>
		</div>
		<div class="meter">
			小计：<span>￥570</span>
		</div>
		<a href="javascript:void(0)" class="shut">关闭</a>
	</section>
	<div class="mask"></div>
	</if>
	<div class="stroll" <if condition="$product_list">style="display:none"</if>>
		<div class="stroll_n">
			<img src="{pigcms{$static_path}images/car.png" width="100" height="100">
			<p class="p1">购物车快饿瘪了！</p>
			<p class="p2">主人给我挑点东西吃吧！</p>
			<if condition="$store_id">
				<a href="{pigcms{:U('Mall/store',array('store_id'=>$store_id))}">逛一逛</a>
			<else/>
				<a href="{pigcms{:U('Mall/index')}">逛一逛</a>
			</if>
		</div>
	</div>
	<!-- 底部 -->
	<if condition="empty($store_id)">
	<include file="footer"/>
	</if>
</body>
</html>