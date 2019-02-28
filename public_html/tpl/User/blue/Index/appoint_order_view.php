<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>预约订单详情 | {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_path}js/category.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/meal_order_detail.css" />
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   /* EXAMPLE */
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

   /* string argument can be any CSS selector */
   /* .png_bg example is unnecessary */
   /* change it to what suits you! */
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
		body{behavior:url("{pigcms{$static_path}css/csshover.htc"); 
		}
		.category_list li:hover .bmbox {
filter:alpha(opacity=50);
	 
			}
  .gd_box{	display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
</head>
<body id="order-detail">
	<div id="doc" class="bg-for-new-index">
		<header id="site-mast" class="site-mast">
			<include file="Public:header_top"/>
		</header>
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<div id="content">
					<div class="mainbox mine">
						<h2>订单详情<span class="op-area"><a href="{pigcms{:U('Index/appoint_order')}">返回订单列表</a></span></h2>
						<dl class="info-section primary-info J-primary-info">
							<dt>
								<span class="info-section--title">当前订单状态：</span>
								<em class="info-section--text">
									<if condition="$now_order['service_status'] == '0'">未服务
									<elseif condition="$now_order['service_status'] == '1'"/>已服务</if>
								</em>
								<!-- <div style="float:right;"><a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>2000000000+$now_order['order_id']))}">查看微信二维码</a></div> -->
							</dt>
							<dd class="last">
								<div class="operation">
									<if condition="$now_order['paid'] == '0' && $now_order['is_del'] eq 0">
										<a class="btn btn-mini" href="{pigcms{:U('Index/Pay/check',array('type'=>'appoint','order_id'=>$now_order['order_id']))}">付款</a>&nbsp;&nbsp;&nbsp;
										<a class="inline-link J-order-cancel" href="{pigcms{:U('Index/appoint_order_del',array('order_id'=>$now_order['order_id']))}">删除</a>
									<elseif condition="$now_order['service_status'] == '1'"/>
										<a class="btn btn-mini" href="{pigcms{:U('Rates/appoint')}">评价</a>
									</if>
								</div>
							</dd>
						</dl>
						<dl class="bunch-section J-coupon">
							<if condition="$now_order['paid'] && $now_order['tuan_type'] neq 2 && $now_order['status'] neq 3">
								<dt class="bunch-section__label">预约码</dt>
								<dd class="bunch-section__content">
									<div class="coupon-field">
										<p class="coupon-field__tip">小提示：记下或拍下预约码向商家出示即可消费</p>
										<ul>
											<li class="invalid">预约码：<b style="color:black;">{pigcms{$now_order.appoint_pass}</b>
												<span>
													<if condition="$now_order['paid'] == '0'">未支付
													<elseif condition="$now_order['paid'] == '1'"/>已支付
													<elseif condition="$now_order['paid'] == '2'"/>已退款
													</if>
												</span>
											</li>
										</ul>
									</div>
								</dd>
							</if>
							<dt class="bunch-section__label">订单信息</dt>
							<dd class="bunch-section__content">
							
								
								<ul class="flow-list">
									<li>订单编号：{pigcms{$now_order.order_id}</li>
									<li>下单时间：{pigcms{$now_order.order_time|date='Y-m-d H:i',###}</li>
									<if condition="!empty($now_order['last_time'])">
										<li>验证时间：{pigcms{$now_order.last_time|date='Y-m-d H:i',###}</li>
									</if>
									
									<if condition='$now_order["is_del"]'>
										<li>
											<switch name='now_order["is_del"]'>
												<case value="1">已取消【用户】【PC端】</case>
												<case value="2">已取消【平台】</case>
												<case value="3">已取消【商家】</case>
												<case value="4">已取消【店员】</case>
												<case value="5">已取消【用户】【WAP端】</case>
											</switch>
										</li>
										<if condition='$now_order["del_time"]'><li>取消时间：{pigcms{$now_order.del_time|date='Y-m-d H:i',###}</li></if>
									</if>
									
									<if condition="($now_order['pay_type'] eq 'offline' AND $now_order['paid'] AND !empty($now_order['third_id'])) OR ($now_order['pay_type'] neq 'offline' AND $now_order['paid'])">
										<li>付款方式：{pigcms{$now_order.pay_type_txt}</li>
										<if condition='$now_order["pay_time"]'><li>付款时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i',###}</li></if>
										<li style="margin:30px 0;width:auto;"><b>支付详情：</b><br />&nbsp;&nbsp;&nbsp;&nbsp;使用商户余额：{pigcms{$now_order['merchant_balance']}  
										
										&nbsp;&nbsp;&nbsp;&nbsp;在线支付金额：
										<if condition='$now_order["is_initiative"] AND $now_order["service_status"]'>
											{pigcms{$now_order['balance_pay'] + $now_order['product_balance_pay']}
										<elseif condition='$now_order["is_initiative"] AND !$now_order["service_status"]' />
											{pigcms{$now_order['product_balance_pay']}
										<else />
											{pigcms{$now_order.balance_pay}
										</if><br />
										
										&nbsp;&nbsp;&nbsp;&nbsp;定金积分抵现金额：{pigcms{$now_order.score_deducte}
										&nbsp;&nbsp;&nbsp;&nbsp;商品规格余额商家赠送余额：{pigcms{$now_order.product_card_give_money}
										
										 <if condition='($now_order["product_id"]) AND ($now_order["paid"] == 1) AND ($now_order["pay_time"] gt 0)'>
												&nbsp;&nbsp;&nbsp;&nbsp;商品规格实际支付定金：{pigcms{$now_order['product_payment_price']}
												&nbsp;&nbsp;&nbsp;&nbsp;商品规格实际支付定金时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}
										</if><br />
										
										
										<if condition='($now_order["service_status"] gt 0) AND $now_order["is_initiative"]'>
											&nbsp;&nbsp;&nbsp;&nbsp;商品规格实际支付余额：{pigcms{$now_order['user_pay_money'] + $now_order['product_balance_pay']}
											&nbsp;&nbsp;&nbsp;&nbsp;商品规格实际支付余额时间：{pigcms{$now_order.user_pay_time|date='Y-m-d H:i:s',###}
											&nbsp;&nbsp;&nbsp;&nbsp;商品规格实际支付积分金额：{pigcms{$now_order['product_score_deducte']}
											&nbsp;&nbsp;&nbsp;&nbsp;商品规格实际支付平台优惠券金额：{pigcms{$now_order['product_coupon_price']}
											&nbsp;&nbsp;&nbsp;&nbsp;商品规格实际支付商家优惠券金额：{pigcms{$now_order['product_card_price']}
										</if>
										</li>
									</if>
								</ul>
								
								<if condition='$now_order["product_detail"]'>
									<ul class="flow-list">
										<li><b>选择服务详情：</b>选择服务名称：{pigcms{$now_order["product_detail"]['name']}</li>
										<li>选择服务价格：￥{pigcms{$now_order["product_detail"]['price']}</li>
									</ul>
								</if>
								
							</dd>
							<dt class="bunch-section__label">预约信息</dt>
							<dd class="bunch-section__content">
								<table cellspacing="0" cellpadding="0" border="0" class="info-table">
									<tbody>
										<tr>
											<th class="left" width="100">预约服务</th>
											<th width="50">定金</th>
											<th width="10"></th>
											<th width="30">下单时间</th>
											<th width="10"></th>
											<th width="54">预约时间</th>
										</tr>
										<tr>
											<td class="left">
												<a class="deal-title" href="{pigcms{$now_order.url}" target="_blank">{pigcms{$now_order.appoint_name}</a>
											</td>
											<td><span class="money">¥</span>{pigcms{$now_order.payment_money}</td>
											<td></td>
											<td>{pigcms{$now_order.order_time|date='Y-m-d H:i',###}</td> 
											<td></td>
											<td class="total"><if condition='$now_order["appoint_date"] AND $now_order["appoint_time"] AND ($now_order["appoint_date"] neq $now_order["appoint_time"])'><?php echo date('Y-m-d', strtotime($now_order['appoint_date'])); ?>&nbsp;&nbsp;&nbsp;{pigcms{$now_order.appoint_time}<else />{pigcms{$now_order.appoint_time} </if></td>
												
										</tr>
									</tbody>
								</table>
							</dd>
						</dl>
					</div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>	
	<include file="Public:footer"/>
	<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.see_tmp_qrcode').click(function(){
				var qrcode_href = $(this).attr('href');
				art.dialog.open(qrcode_href+"&"+Math.random(),{
					init: function(){
						var iframe = this.iframe.contentWindow;
						window.top.art.dialog.data('login_iframe_handle',iframe);
					},
					id: 'login_handle',
					title:'请使用微信扫描二维码',
					padding: 0,
					width: 430,
					height: 433,
					lock: true,
					resize: false,
					background:'black',
					button: null,
					fixed: false,
					close: null,
					left: '50%',
					top: '38.2%',
					opacity:'0.4'
				});
				return false;
			});
			$('.J-order-cancel').click(function(){
				if(!confirm('确定删除订单？删除后本订单将从订单列表消失，且不能恢复。')){
					return false;
				}
			});
		});
	</script>
</body>
</html>
