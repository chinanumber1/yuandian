<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
<section class="Cart CartReserve">
	<div class="CartReserve_top"><span>已预订菜品</span></div>
	<div class="Cart_list">
		<ul>
			<volist name="goods_list" id="goods">
			<li class="clr">
				<div class="Clist_left">
					<h2>{pigcms{$goods['name']}</h2>
					<if condition="$goods['spec']">
					<span>({pigcms{$goods['spec']})</span>
					</if>
				</div>
				<div class="Clist_right">
					<div class="MenuPrice">
						<i>￥</i>{pigcms{$goods['price']|floatval}
					</div>
					<div class="Addsub">
						<!--a href="javascript:void(0)" class="jian"></a-->
						<input type="text" value="{pigcms{$goods['num']}" readOnly="true" class="num">
						<!--a href="javascript:void(0)" class="jia"></a-->
					</div>
				</div>
			</li>
			</volist>
		</ul>
	</div>
	<div class="Serving">
		<dl>
			<dt>总计：{pigcms{$price|floatval}元</dt>
			<dd class="vegetables clr">
				<a href="{pigcms{:U('Foodshop/menu', array('order_id' => $order['order_id'], 'store_id' => $order['store_id']))}" class="add">加菜</a>
				<a href="#" class="notice">通知上菜</a>
			</dd>
		</dl>
	</div>
</section>
<if condition="$order['book_time']">
<section class="Sudetails margin80">
	<ul>
		<li class="Su_zh">
			<dl>
				<dd>{pigcms{$order['book_time_show']}</dd>
				<dd>{pigcms{$order['book_num']}人 | {pigcms{$order['table_type_name']}  <span class="Su_sit">已付定金:￥{pigcms{$order['book_price']|floatval}</span></dd>
				<dd>{pigcms{$order['name']} <if condition="$order['sex'] eq 1">先生<else />女士</if> {pigcms{$order['phone']}</dd>
			</dl>
		</li>
		<li class="Su_bots">{pigcms{$order['note']}</li>
	</ul>
</section>
</if>
<div class="Total clr">
	<div class="Total_left">总计<span>￥<i>{pigcms{$price|floatval}</i></span></div>
	<a href="#" class="Check">去买单</a>
</div>
</body>
</html>