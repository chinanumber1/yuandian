<!DOCTYPE html>
<html style="">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>餐饮点餐</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}foodshop/css/search.css?t=1" />
<script src="{pigcms{:C('JQUERY_FILE_190')}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/foodshopmenupic.js" charset="utf-8"></script>
<script>var store_id = '{pigcms{$store["store_id"]}', all_goods = '{pigcms{$all_goods}', cookie_index = 'foodshop_cart_{pigcms{$store["store_id"]}_order_{pigcms{$order["order_id"]}';
var  open_extra_price = Number('{pigcms{$config.open_extra_price}');
</script>
<script type="text/javascript">
	!function(e,t){function n(){var n=l.getBoundingClientRect().width;t=t||540,n>t&&(n=t);var i=100*n/e;r.innerHTML="html{font-size:"+i+"px;}"}var i,d=document,o=window,l=d.documentElement,r=document.createElement("style");if(l.firstElementChild)l.firstElementChild.appendChild(r);else{var a=d.createElement("div");a.appendChild(r),d.write(a.innerHTML),a=null}n(),o.addEventListener("resize",function(){clearTimeout(i),i=setTimeout(n,300)},!1),o.addEventListener("pageshow",function(e){e.persisted&&(clearTimeout(i),i=setTimeout(n,300))},!1),"complete"===d.readyState?d.body.style.fontSize="16px":d.addEventListener("DOMContentLoaded",function(e){d.body.style.fontSize="16px"},!1)}(640,640);
</script>
<style type="text/css">
.motifyShade{
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    bottom:0;
    padding: 0;
    z-index: 998;
    width: 100%;
}
.motify {
    display: none;
    position: fixed;
    top: 35%;
    left: 50%;
    width: 260px;
    padding: 0;
    margin: 0 0 0 -130px;
    z-index: 999;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    font-size: 14px;
    line-height: 1.5em;
    border-radius: 6px;
    -webkit-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
    box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
}
.motify .motify-inner {
    padding: 10px 10px;
    text-align: center;
    word-wrap: break-word;
}
</style>
</head>
<body>
    <div class="contanir" <if condition="$store['background']">style="background: url({pigcms{$store['background']}) center no-repeat;background-size: cover;"</if>>
        <div class="bg_mask">
			<header>
				<a href="{pigcms{:U('Foodshop/menu', array('order_id' => $order['real_orderid'], 'store_id' => $order['store_id']))}"></a>
				<div class="search">
					<i></i>
                    <form action="{pigcms{:U('Foodshop/searchGoods', array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))}" method="post">
                    <input type="hidden" name="store_id" value="{pigcms{$order['store_id']}">
                    <input type="hidden" name="order_id" value="{pigcms{$order['real_orderid']}">
					<input type="text" name="keyword" id="search" value="{pigcms{$keyword}" placeholder="搜索菜品"/>
                    </form>
				</div>
			</header>
            <div class="all_lists">
                <if condition="!empty($hot_list)">
                <div class="list_item">
                    <p>{pigcms{$store['hot_alias_name']|default='推荐榜'}</p>
                    <volist name="hot_list" id="hot">
                    <php> if ($hot['show_type']) { </php>
                        <a href="{pigcms{:U('Foodshop/goods_detail', array('goods_id' => $hot['goods_id'], 'order_id' => $order['real_orderid']))}" class="big_items">
                            <img src="{pigcms{$hot['pic_arr'][0]['url']['image']}" id="goodsImage_{pigcms{$hot['goods_id']}"/>
                            <php>if (!empty($hot['label'])) {</php>
                            <span class="tuijian">
                                <b>{pigcms{$hot['label']}</b>
                            </span>
                            <php> } </php>
                            <i class="paihang" id="goods_{pigcms{$hot['goods_id']}" style="display: none"></i>
                            <ul class="food_name">
                                <li>{pigcms{$hot['name']}</li>
                                <li>
                                    <span>￥</span>
                                    <b>{pigcms{$hot['price']|floatval}/{pigcms{$hot['unit']}</b>
                                </li>
                            </ul>
                        </a>
                    <php> } else { </php>
                    <a href="{pigcms{:U('Foodshop/goods_detail', array('goods_id' => $hot['goods_id'], 'order_id' => $order['real_orderid']))}" class="items_img">
                        <dl>
                            <php>if (!empty($hot['label'])) {</php>
                            <span class="small_style">
                                <b>{pigcms{$hot['label']}</b>
                            </span>
                            <php> } </php>
                            <dt>
                                <img src="{pigcms{$hot['pic_arr'][0]['url']['image']}" id="goodsImage_{pigcms{$hot['goods_id']}"/>
                            </dt>
                            <dd class="name_color">{pigcms{$hot['name']}</dd>
                            <dd>￥{pigcms{$hot['price']|floatval}/{pigcms{$hot['unit']}</dd>
                        </dl>
                        <i class="paihang" id="goods_{pigcms{$hot['goods_id']}" style="display: none"></i>
                    </a>
                    <php>}</php>
                    </volist>
                </div>
                </if>
                
                <volist name="goods_list" id="rowset">
                <div id="d{pigcms{$rowset['sort_id']}" class="list_item">
                    <p>{pigcms{$rowset['sort_name']}</p>
                        <volist name="rowset['goods_list']" id="row">
                        <php> if ($row['show_type']) { </php>
                            <a href="{pigcms{:U('Foodshop/goods_detail', array('goods_id' => $row['goods_id'], 'order_id' => $order['real_orderid']))}" class="big_items">
                                <img src="{pigcms{$row['pic_arr'][0]['url']['image']}" id="goodsImage_{pigcms{$row['goods_id']}"/>
                                <php>if (!empty($row['label'])) {</php>
                                <span class="tuijian">
                                    <b>{pigcms{$row['label']}</b>
                                </span>
                                <php> } </php>
                                <i class="paihang" id="goods_{pigcms{$row['goods_id']}" style="display: none"></i>
                                <ul class="food_name">
                                    <li>{pigcms{$row['name']}</li>
                                    <li>
                                        <span>￥</span>
                                        <b>{pigcms{$row['price']|floatval}/{pigcms{$row['unit']}</b>
                                    </li>
                                </ul>
                            </a>
                        <php> } else { </php>
                        <a href="{pigcms{:U('Foodshop/goods_detail', array('goods_id' => $row['goods_id'], 'order_id' => $order['real_orderid']))}" class="items_img">
                            <dl>
                                <php>if (!empty($row['label'])) {</php>
                                <span class="small_style">
                                    <b>{pigcms{$row['label']}</b>
                                </span>
                                <php> } </php>
                                <dt>
                                    <img src="{pigcms{$row['pic_arr'][0]['url']['image']}" id="goodsImage_{pigcms{$row['goods_id']}"/>
                                </dt>
                                <dd class="name_color">{pigcms{$row['name']}</dd>
                                <dd>￥ {pigcms{$row['price']|floatval}/{pigcms{$row['unit']}</dd>
                            </dl>
                            <i class="paihang" id="goods_{pigcms{$row['goods_id']}" style="display: none"></i>
                        </a>
                        <php>}</php>
                        </volist>
                </div>
                </volist>
            </div>
            <if condition="empty($hot_list) AND empty($goods_list)">
            <p class="no_dishes">暂无此商品信息，换其他商品试试看吧！</p>
            </if>
        </div>
    </div>
</body>
</html>