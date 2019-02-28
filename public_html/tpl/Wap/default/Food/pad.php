<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="white">
<meta name="format-detection" content="telephone=no">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}meal/css/base.css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}meal/css/index.css" />
<script type="text/javascript" src="{pigcms{$static_path}meal/js/jquery_min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/jquery.nav.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/fastclick.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/fly.js"></script>
<script type="text/javascript">var store_id = '{pigcms{$store['store_id']}';</script>
<script><if condition="$user_session">var is_login=true;<else/>var is_login=false;var login_url="{pigcms{:U('Login/see_login_qrcode',array('scriptName'=>$_GET['scriptName']))}";</if></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/index.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
</head>
<body>
	<div class="layer"></div>
	<header>
	<div class="header_title">{pigcms{$store['name']}</div>
	</header>
	<div class="shop_list">
		<div class="shop_list_hr"></div>
		<div class="shop_list_content clearfix">
			<div class="shop_menu">
				<ul class="barNavLi">
					<volist name="sortlist" id="so">
					<li><a href="#f{pigcms{$so['sort_id']}">{pigcms{$so['sort_name']}</a></li>
					</volist>
				</ul>
			</div>
			<div class="shop_product">
				<if condition="!empty($meals)">
				<volist name="meals" id="rowset">
					<div class="shop_title">{pigcms{$rowset['sort_name']}</div>
					<ul id="f{pigcms{$rowset['sort_id']}" class="clearfix">
						<volist name="rowset['list']" id="meal">
						<li id="meal_{pigcms{$meal['meal_id']}">
							<a>
								<if condition="!empty($meal['image'])">
								<div class="shop_img">
								<img src="{pigcms{$meal['image']}" />
								</div>
								<else />
								<div class="shop_img shop_img_mark"></div>
								</if>
								<input type="hidden" class="buycar" data-id="{pigcms{$meal['meal_id']}" value="{pigcms{$meal['name']}" data-price="{pigcms{:floatval($meal['price'])}"/>
								<p class="product_name">{pigcms{$meal['name']}</p>
								<p><span class="product_price">￥{pigcms{$meal['price']}</span></p> 
								<b id="food_{pigcms{$meal['meal_id']}">{pigcms{$meal['num']}</b>
							</a>
						</li>
						</volist>
					</ul>
				</volist>
				</if>
			</div>
		</div>
	</div>
	<footer class="clearfix">
	<div class="menu_num">点菜单<i id="show_total_num">0</i></div>
	<if condition="$user_session">
	<div class="menu" style="bottom: 32px">
		<a href="{pigcms{:U('Food/order_list', array('mer_id' => $mer_id, 'store_id' => $store_id, 'meal_type' => 2))}" style="color: #ffffff">我的</a>
	</div>
	<div class="menu_logout">
		<span id="logout">退出</span>
	</div>
	<else />
	<div class="menu">
		<span id="click_login">登录</span>
	</div>
	</if>
	<div class="choose">
		<div class="choose_title clearfix">
			<div class="chw1">菜品名</div>
			<div class="chw2">数量</div>
			<div class="chw3">单价</div>
		</div>
		<div class="choos_base">
		<input type="hidden" id="shop_cart" value="" />
			<ul class="choose_list">
			</ul>
			<div class="choose_bottom clearfix">
				<div class="chw1">合计</div>
				<div class="chw2">
					<b id="total_num">0</b>
				</div>
				<div class="chw3">
					<span id="total_price">0</span>
				</div>
			</div>
			<div class="choose_button">
				<p>
					<span>人数:</span><input type="number" id="num" value="2"/>
				</p>
				<p>
					<span>桌号:</span>
					<select id="tableid" style="border:1px solid #d9d9d9;">
					<volist name="tables" id="table">
					<option value="{pigcms{$table['pigcms_id']}">{pigcms{$table['name']}</option>
					</volist>
					</select>
				</p>
			</div>
			<button class="line" id="online_pay">线上支付</button>
			<if condition="empty($notOffline)">
			<button id="offline_pay">线下支付</button>
			</if>
		</div>
	</div>
	</footer>
</body>
</html>
