<include file="header" />
<script type="text/javascript" src="{pigcms{$static_path}takeout/js/scroller.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}takeout/js/menu.js"></script>
<style type="text/css">
#mymenu_lists .dztj_c{display:none}
#mymenu_lists .nodztj_c{display:block}
.menu_wrap .left {
    width: 25%;
    float: left;
    overflow: auto;
}
.menu_wrap .right {
    width: 75%;
    float: left;
    overflow: auto;
}
.menu_wrap .left .content ul li {
    line-height: 20px;
    text-align: center;
    font-size: 14px;
    padding: 10px 0;
}
.content a.on {
    border-color: #ea3f25;
    background-color: #ea3f25;
    color: #fff;
}
.content a {
    position: static;
    width: 100%;
    line-height: 18px;
    border-width: 0 0 1px;
    text-align: center;
    -webkit-tap-highlight-color: transparent;
    display: block;
    height: inherit;
    padding: 10px 0;
}
</style>
<body onselectstart="return true;" ondragstart="return false;">
<div class="container">
	<header class="nav menu">
		<div>
			<a href="javascript:;" class="on">商品列表</a>
			<a href="{pigcms{:U('Takeout/shop', array('mer_id' => $mer_id, 'store_id' => $store_id))}">门店详情</a>
		</div>
	</header>
	<form name="cart_form" action="{pigcms{:U('Takeout/sureOrder', array('mer_id' => $mer_id, 'store_id' => $store_id))}" method="post">
	<section class="menu_wrap" id="menuWrap">
	<div class="left" id="menuNav">
		<div class="content " id="sideNav">
			<ul id="typeList"><!--class="on"-->
				<volist name="sortlist" id="so">
				<li><a href="javascript:void(0);" title="{pigcms{$so['sort_id']}">{pigcms{$so['sort_name']}</a></li>
				</volist>
			</ul>
		</div>
	</div>
	 <div class="menu_container right" id="mymenu_lists">
	 <if condition="!empty($meals)">
		<volist name="meals" id="ditem">
			<div class="menu_tt nodztj_c">
			<h2>{pigcms{$ditem['sort_name']}</h2>
			</div>
			<ul class="menu_list nodztj_c">
			<volist name="ditem['list']" id="dditem">
			<li>
				<div>
					<if condition="!empty($dditem['image'])">
					<img src="{pigcms{$dditem['image']}" alt="" url="{pigcms{$dditem['image']}">
					</if>
				</div>
				<div>
					<h3>{pigcms{$dditem['name']}</h3>
					<p class="salenum">已售<span class="sale_num">{pigcms{$dditem['sell_count']}</span><span class="sale_unit"><if condition="!empty($dditem['unit'])">{pigcms{$dditem['unit']}<else/>份</if></span>
					<div class="info">{pigcms{$dditem['des']|htmlspecialchars_decode=ENT_QUOTES}</div>
				</div>
				<div class="price_wrap">
					<strong>￥<span class="unit_price">{pigcms{:floatval($dditem['price'])}</span></strong>
					<div class="fr" max="{pigcms{$dditem['max']}">
						<a href="javascript:void(0);" class="btn add" data-num="{pigcms{$dditem['num']}"></a>
					</div>
					<input autocomplete="off" class="number" type="hidden" name="dish[{pigcms{$dditem['meal_id']}]" value="">
				</div>
			</li>
			</volist>
			</ul>
		</volist>

	 </if>
	</div>
	</section>
	<footer class="shopping_cart">
		<div class="fixed">
			<div class="cart_bg">
			<span class="cart_num" id="cartNum"></span></div>
			<div>￥<span id="totalPrice">0</span></div>
			<div><span class="comm_btn disabled">还差 <span id="sendCondition"><if condition="$store['basic_price'] gt 0 AND $store['delivery_fee_valid'] eq 0">{pigcms{$store['basic_price']}<else/>0</if></span> 起送</span>
			<a id="settlement" href="javascript:document.cart_form.submit();" class="comm_btn" style="display: none;">去结算</a></div>
			<if condition="$store['delivery_fee'] gt 0">
				<if condition="$store['reach_delivery_fee_type'] eq 0">
				<p style="padding:10px;"><span>温馨提示：</span>商家设定了外送的费用{pigcms{:floatval($store['delivery_fee'])}元，订单金额超过{pigcms{:floatval($store['basic_price'])}元的将不收取外送费用</p>
				<elseif condition="$store['reach_delivery_fee_type'] eq 1" />
				<p style="padding:10px;"><span>温馨提示：</span>商家设定了外送的费用{pigcms{:floatval($store['delivery_fee'])}元</p>
				<elseif condition="$store['reach_delivery_fee_type'] eq 2" />
				<p style="padding:10px;"><span>温馨提示：</span>商家设定了外送的费用{pigcms{:floatval($store['delivery_fee'])}元，订单金额超过{pigcms{:floatval($store['no_delivery_fee_value'])}元的将不收取外送费用</p>
				</if>
			</if>
		</div>
	</footer>
	</form>

	<div class="menu_detail" id="menuDetail">
		<img style="display: none;">
		<div class="nopic"></div>
		<!--a href="javascript:void(0);" class="comm_btn" id="detailBtn">来一份</a-->
		
		<dl>
			<dt>商品名称：</dt>
			<dd class="name highlight"></dd>
		</dl>
		<div class="showfixd">
		<div class="btndiv1"><span><a class="btn del active"></a><span class="num">1</span></span><a class="btn add active" id="detailBtn" max="93"></a></div>
		<dl>
			<dt>价格：</dt>
			<dd class="highlight">￥<span class="price"></span></dd>
		</dl>
		</div>
		<p class="sale_desc">月售<span class="sale_num"></span>份</p>
		<dl>
			<dt>介绍：</dt>
			<dd class="info"></dd>
		</dl>
	</div>

</div>
<include file="kefu" />
<script type="text/javascript">
window.shareData = {  
            "moduleName":"Takeout",
            "moduleID":"0",
            "imgUrl": "{pigcms{$store.image}", 
            "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Takeout/menu',array('mer_id' => $mer_id, 'store_id' => $store_id))}",
            "tTitle": "{pigcms{$store.name}",
            "tContent": "{pigcms{$store.txt_info}"
};
</script>
{pigcms{$shareScript}
</body>
</html>