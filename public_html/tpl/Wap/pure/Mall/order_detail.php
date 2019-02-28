<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">

<script type="text/javascript" src="{pigcms{$static_path}shop/js/jquery1.8.3.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/dialog.js"></script>

<title>{pigcms{$config.shop_alias_name}订单详情</title>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta content="telephone=no, address=no" name="format-detection">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="format-detection" content="telephone=no"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/main.css" media="all">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/style_dd39d16.css">
<!-- <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/orderhistory_c6670c7.css"> -->
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/order_4bc7e9e.css">
<style>
#dingcai_adress_info{
border-top: 1px solid #ddd8ce;
border-bottom: 1px solid #ddd8ce;
position: relative;
}
#dingcai_adress_info:after{
position: absolute;
right: 8px;
top: 50%;
display: block;
content: '';
width: 13px;
height: 13px;
border-left: 3px solid #999;
border-bottom: 3px solid #999;
-webkit-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
-moz-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
-ms-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
}


#enter_im_div {
  bottom: 121px;
  z-index: 11;
  display: none;
  position: fixed;
  width: 100%;
  max-width: 640px;
  height: 1px;
}
#enter_im {
  width: 94px;
  margin-left: 110px;
  position: relative;
  left: -100px;
  display: block;
}
a {
  color: #323232;
  outline-style: none;
  text-decoration: none;
}
#to_user_list {
  height: 30px;
  padding: 7px 6px 8px 8px;
  background-color: #00bc06;
  border-radius: 25px;
  /* box-shadow: 0 0 2px 0 rgba(0,0,0,.4); */
}
#to_user_list_icon_div {
  width: 20px;
  height: 16px;
  background-color: #fff;
  border-radius: 10px;
}

.rel {
  position: relative;
}
.left {
  float: left;
}
.to_user_list_icon_em_a {
  left: 4px;
}
#to_user_list_icon_em_num {
  background-color: #f00;
}
#to_user_list_icon_em_num {
  width: 14px;
  height: 14px;
  border-radius: 7px;
  text-align: center;
  font-size: 12px;
  line-height: 14px;
  color: #fff;
  top: -14px;
  left: 68px;
}
.hide {
  display: none;
}
.abs {
  position: absolute;
}
.to_user_list_icon_em_a, .to_user_list_icon_em_b, .to_user_list_icon_em_c {
  width: 2px;
  height: 2px;
  border-radius: 1px;
  top: 7px;
  background-color: #00ba0a;
}
.to_user_list_icon_em_a {
  left: 4px;
}
.to_user_list_icon_em_b {
  left: 9px;
}
.to_user_list_icon_em_c {
  right: 4px;
}
.to_user_list_icon_em_d {
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 4px;
  top: 14px;
  left: 6px;
  border-color: #fff transparent transparent transparent;
}
#to_user_list_txt {
  color: #fff;
  font-size: 13px;
  line-height: 16px;
  padding: 1px 3px 0 5px;
}
</style>
</head>
<body onselectstart="return true;" ondragstart="return false;">
<div class="container">
    <div id="fis_elm__2">
        <div id="common-widget-nav" class="common-widget-nav ">
            <div class="left-slogan"> <a class="left-arrow icon-arrow-left2" id="goBackUrl" href="javascript:history.go(-1);"></a> </div>
            <div class="center-title"> <a href="javascript:void(0)">{pigcms{$store['name']}</a> </div>
            <div class="right-slogan "> <a class="tel-btn" href="tel:{pigcms{$store['phone']}"><i class="icon-phone"></i></a> </div>
        </div>
    </div>
    <div id="fis_elm__3">
        <div id="common-widget-tab" class="common-widget-tab">
            <ul class="order-tab">
                <li><a href="{pigcms{:U('Mall/status', array('order_id' => $order['order_id']))}">订单状态</a></li>
                <li class="active"><a href="javaScript:void(0);">订单详情</a></li>
            </ul>
        </div>
    </div>
	<section>
		<!--ul class="my_order">
			<li>
				<a href="?c=Shop#shop-{pigcms{$store['store_id']}">
					<div>
						<div class="ico_status {pigcms{$order['css']}"><i></i>{pigcms{$order['show_status']}</div>
					</div>
					<div>
						<h3 class="highlight">{pigcms{$store['name']}</h3>
						<p>{pigcms{$order['num']}份/￥{pigcms{$order['price']|floatval}</p>
						<div>{pigcms{$order['date']}</div>
					</div>
					<div class="w14"><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul-->
		<table class="my_menu_list">
			<thead>
				<tr>
					<th>商品列表</th>
					<th>{pigcms{$order['num']}份</th>
					<th><strong class="highlight">￥{pigcms{$order['goods_price']|floatval}</strong></th>
				</tr>
			</thead>
			<tbody>
				<volist name="order['info']" id="info">
				<tr>
					<td>{pigcms{$info['name']}<if condition="$info['spec']">({pigcms{$info['spec']})</if></td>
					<td>X{pigcms{$info['num']}</td>
					<td>￥{pigcms{$info['price']|floatval}</td>
				</tr>
				</volist>
			</tbody>
		</table>

		<ul class="box">
			<li>客户姓名：{pigcms{$order['username']}</li>
			<li>客户手机：{pigcms{$order['userphone']}</li>
			<if condition="$order['is_pick_in_store'] eq 2">
			<li>自提地址：{pigcms{$order['address']}</li>
			<else />
			<li>客户地址：{pigcms{$order['address']}</li>
			</if>
			<li>配送方式：{pigcms{$order['deliver_str']}</li>
			<if condition="$order['is_pick_in_store'] eq 3 AND $order['express_id']">
			<li>快递公司：{pigcms{$order['express_name']}</li>
			<li>快递单号：{pigcms{$order['express_number']} &nbsp;<a href="http://m.kuaidi100.com/index_all.html?type={pigcms{$order.express_code}&postid={pigcms{$order.express_number}&callbackurl=<?php echo 'http://'.urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>" target="_blank" style="color:#1B9C46;">查看物流信息</a></li>
			</if>
			<!--li>配送状态：{pigcms{$order['deliver_status_str']}</li-->
		</ul>
		<ul class="box">
			<li>订单编号：{pigcms{$order['real_orderid']} </li>
			<li>下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </li>
			<if condition="$order['pay_time']">
			<li>支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </li>
			</if>
			<if condition="$order['expect_use_time']">
			<li>到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</li>
			</if>
			<li>商品总价：￥{pigcms{$order['goods_price']|floatval} 元</li>
			<li>配送费用：￥{pigcms{$order['freight_charge']|floatval} 元</li>
			<if condition="$order['packing_charge'] gt 0">
			<li>{pigcms{$store['pack_alias']|default='打包费'}：￥{pigcms{$order['packing_charge']|floatval} 元</li>
			</if>
			<li>订单总价：￥{pigcms{$order['total_price']|floatval} 元</li>
			<if condition="$order['merchant_reduce'] gt 0">
			<li>店铺优惠：￥{pigcms{$order['merchant_reduce']|floatval} 元</li>
			</if>
			<if condition="$order['balance_reduce'] gt 0">
			<li>平台优惠：￥{pigcms{$order['balance_reduce']|floatval} 元</li>
			</if>
			<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
			<li>会员卡：{pigcms{$order['card_discount']|floatval} 折优惠</li>
			</if>
			<li>实付金额：￥{pigcms{$order['price']|floatval} 元</li>
		</ul>
		<ul class="box">
			<if condition="$order['score_used_count']">
			<li>使用{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </li>
			<li>{pigcms{$config.score_name}抵现：￥{pigcms{$order['score_deducte']|floatval} 元</li>
			</if>
			
			<if condition="$order['card_give_money'] gt 0">
			<li>会员卡余额：￥{pigcms{$order['card_give_money']|floatval} 元</li>
			</if>
			
			<if condition="$order['merchant_balance'] gt 0">
			<li>商家余额：￥{pigcms{$order['merchant_balance']|floatval} 元</li>
			</if>
			<if condition="$order['balance_pay'] gt 0">
			<li>平台余额：￥{pigcms{$order['balance_pay']|floatval} 元</li>
			</if>
			<if condition="$order['payment_money'] gt 0">
			<li>在线支付：￥{pigcms{$order['payment_money']|floatval} 元</li>
			</if>
			
			<if condition="$order['card_id']">
			<li>店铺优惠券金额：￥{pigcms{$order['card_price']} 元</li>
			</if>
			<if condition="$order['coupon_id']">
			<li>平台优惠券金额：￥{pigcms{$order['coupon_price']} 元</li>
			</if>
			<if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])">
			<li>线下需支付：￥{pigcms{$order['offline_price']|floatval}元</li>
			</if>
		</ul>
		<ul class="box">
			<li>支付状态：{pigcms{$order['pay_status']}</li>
			<li>支付方式：{pigcms{$order['pay_type_str']}</li>
			<li>订单状态：{pigcms{$order['status_str']}</li>
			<if condition="($order['paid'] && $order['status'] eq 0) OR ($order['is_pick_in_store'] eq 2 AND $order['paid'] AND !in_array($order['status'], array(2,3,4,5)))">
			<li>消费二维码：<span id="see_storestaff_qrcode" style="color:#FF658E;">查看二维码</span></li>
			</if>
		</ul>
		<ul class="box">
			<if condition="$order['cue_field']">
			<volist name="order['cue_field']" id="cue">
			<li>{pigcms{$cue['title']}：{pigcms{$cue['txt']}</li>
			</volist>
			</if>
			<li>备注</li>
			<li>{pigcms{$order['desc']|default="无"}</li>
		</ul>
	</section>
	<if condition="$order['status'] lt 3 OR ($order['paid'] eq 1 AND $order['status'] eq 5)">
	<footer class="order_fixed">
		<div class="fixed">
			<if condition="$order['paid'] eq 0">
				<div style="float: left">
					<a href="{pigcms{:U('Pay/check',array('order_id' => $order['order_id'], 'type'=>'shop'))}" class="comm_btn" style="background-color: #5fb038;">支付订单</a>
				</div>
			</if>
			<div style="float: right">
				<if condition="$order['paid'] eq 0">
				<a class="comm_btn" href="{pigcms{:U('Shop/orderdel', array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id'], 'order_id' => $order['order_id']))}">取消订单</a> 
				<elseif condition="$order['paid'] eq 1 AND $order['status'] lt 2" />
				<a class="comm_btn" href="{pigcms{:U('My/shop_order_refund', array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id'], 'order_id' => $order['order_id']))}">取消订单</a> 
				<elseif condition="$order['paid'] eq 1 AND $order['status'] eq 5" />
				<a class="comm_btn" href="{pigcms{:U('My/shop_order_refund', array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id'], 'order_id' => $order['order_id']))}">取消订单</a> 
				</if>
			</div>
			<if condition="$order['status'] eq 2">
				<div style="float: right">
					<a href="{pigcms{:U('My/shop_feedback',array('order_id' => $order['order_id']))}" class="comm_btn">去评价</a>
				</div>	
			</if>
		</div>
	</footer>
	</if>
</div>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript">
var order_id = {pigcms{$order['order_id']};
var status = {pigcms{$order['status']};
var order_status = {pigcms{$order['order_status']};
$(document).ready(function(){
	setInterval("ostatus()", 5000);//1000为1秒钟
});

function ostatus(){
	$.get('wap.php?c=Shop&a=orderstatus', {'order_id':order_id}, function(response){
		if (response.error_code == false) {
			var data = response.data;
			if (status < data.status) {
				if (data.status == 1) {
					order_status = data.order_status;
					status = data.status;
					tips('店员已经接单了！');
				}
			} else if (order_status < data.order_status) {
				if (data.order_status == 2) {
					order_status = data.order_status;
					status = data.status;
					tips('配送员已接单！');
				} else if (data.order_status == 3) {
					order_status = data.order_status;
					status = data.status;
					tips('配送员已经取货！');
				} else if (data.order_status == 4) {
					order_status = data.order_status;
					status = data.status;
					tips('配送员已经将您的货物送达！');
				} else if (data.order_status == 5) {
					order_status = data.order_status;
					status = data.status;
					tips('您已经确认收货！');
				}
			}
		}
	}, 'json');
}


function tips(msg){
	layer.open({
		title: [
		        '状态修改提示',
		        'background-color:#8DCE16; color:#fff;'
		    ],
	    content: msg,
	    btn: ['确认'],
	    shadeClose: false,
	    yes: function(){
	        location.href='wap.php?c=Shop&a=status&order_id=' + order_id;
	    }
	});
}

if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
	var reg = /versioncode=(\d+),/;
	var arr = reg.exec(navigator.userAgent.toLowerCase());
	if(arr == null){
		
	}else{
		var version = parseInt(arr[1]);
		if(version >= 50){
			if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
				$('#goBackUrl').click(function(){
					$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
					return false;
				});
			}else{
				$('#goBackUrl').click(function(){
					window.lifepasslogin.webViewGoBack();
					return false;
				});
			}
		}
	}
}
</script>
<include file="kefu" />
{pigcms{$hideScript}
</body>
</html>