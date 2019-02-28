<include file="Food:header" />
<script type="text/javascript" src="{pigcms{$static_path}meal/js/nav.js"></script>
<link href="{pigcms{$static_path}meal/css/nav.css" rel="stylesheet">
<body onselectstart="return true;" ondragstart="return false;">
	<div data-role="container" class="container orderList">
		<section data-role="body">
		<div>
		<a href="{pigcms{:U('Food/order_list', array('mer_id' => $mer_id, 'store_id' => $store_id, 'meal_type' => 0))}" class="clear" style="<if condition="$meal_type eq 0">background: #B2B2B2;<else/>background: #ececec;</if>color: #ef7f2c;display: block; float: left; margin-right: 10px; margin-top: 2px; width: 77px; height: 30px; text-align: center; font-size: 14px; line-height: 30px;">快店订单</a>
		<a href="{pigcms{:U('Food/order_list', array('mer_id' => $mer_id, 'store_id' => $store_id, 'meal_type' => 1))}" class="clear" style="<if condition="$meal_type eq 1">background: #B2B2B2;<else/>background: #ececec;</if>color: #ef7f2c;display: block; float: left; margin-right: 10px; margin-top: 2px; width: 77px; height: 30px; text-align: center; font-size: 14px; line-height: 30px;">外卖订单</a>
		<if condition="$meal_type eq 2">
		<a href="{pigcms{:U('Food/pad', array('mer_id' => $mer_id, 'store_id' => $store_id))}" class="clear" style="background: #ececec;color: #ef7f2c;display: block; float: right; margin-right: 10px; margin-top: 2px; width: 77px; height: 30px; text-align: center; font-size: 14px; line-height: 30px;">返回</a>
		</if>
		</div>
		<ul class="orderlist">
		   <if condition="!empty($orderList)">
		   <volist name="orderList" id="order">
			<li>
				<if condition="$meal_type eq 1">
				<a href="{pigcms{:U('Takeout/order_detail', array('mer_id' => $mer_id, 'store_id' => $store_id, 'order_id' => $order['order_id']))}" class="info">
				<else />
				<a href="{pigcms{:U('Food/order_detail', array('mer_id' => $mer_id, 'store_id' => $store_id, 'order_id' => $order['order_id']))}" class="info">
				</if>
				
					<span class="sawtooth {pigcms{$order['css']}">{pigcms{$order['show_status']}</span>
					<label>
						<span class="name">{pigcms{$order['s_name']}</span>
						<span class="time">{pigcms{$order['otimestr']}</span>
					</label>
				</a>
				<if condition="$order['topay']">
				<a href="<if condition="$order['meal_type'] eq 2">{pigcms{:U('Pay/check', array('order_id' => $order['order_id'], 'type'=>'foodPad'))}<else />{pigcms{:U('Pay/check', array('order_id' => $order['order_id'], 'type'=>'food'))}</if>" class="btn" style="margin-right: 15px;  border-radius: 5px;">去付款</a>
				<else />
				<a><span class="icon_right"><span class="right_adron"></span></span></a>
				</if>
				<!---<if condition="isset($order['jiaxcai']) AND $order['jiaxcai']">
				  <a href="{pigcms{:U('Repast/dishMenu', array('token'=>$token, 'cid'=>$order['cid'],'orid'=>$order['oid'], 'wecha_id'=>$wecha_id))}" class="btn" style="margin-right: 100px;">去加菜</a>
				</if>-->
			</li>
			</volist>
			</if>
			</ul>
		</section>
		<if condition="$meal_type eq 0">
		<footer data-role="footer">
			<nav class="nav">
				<ul class="box">
					<li>
						<a href="{pigcms{:U('Index/index', array('mer_id' => $mer_id, 'store_id' => $store_id))}">
							<span class="home">&nbsp;</span>
							<label>首页</label>				
						</a>
					</li>
					<li >
						<a href="{pigcms{:U('Food/index', array('mer_id' => $mer_id, 'store_id' => $store_id))}">
							<span class="order">&nbsp;</span>
							<label>在线购买</label>				
						</a>
					</li>
					<li>
						<a href="{pigcms{:U('Food/sureorder', array('mer_id' => $mer_id, 'store_id' => $store_id, 'is_reserve' => 1))}">
							<span class="book">&nbsp;</span>
							<label>预约位子</label>				
						</a>
					</li>
					<li class="on">
						<a href="{pigcms{:U('Food/order_list', array('mer_id' => $mer_id, 'store_id' => $store_id))}">
							<span class="my">&nbsp;</span>
							<label>我的订单</label>
						</a>
					</li>
				</ul>
			</nav>
		</footer>
		</if>
</div>
<if condition="$meal_type eq 0">
<include file="kefu" />
{pigcms{$hideScript}
</if>
</body>
</html>