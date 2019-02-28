<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>快店订单详情 | {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  shop_alias_name = "{pigcms{$config.shop_alias_name}";
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
<body>
 <include file="Public:header_top"/>
 <div class="body pg-buy-process"> 
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top">全部分类</div>
					<div class="list">
						<ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul>
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<include file="Public:scroll_msg"/>	
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<div id="content">
					<div class="mainbox mine">
						<h2>订单详情<span class="op-area"><a href="{pigcms{:U('Index/shop_list')}">返回订单列表</a></span></h2>
						<dl class="info-section primary-info J-primary-info">
							<dt>
								<span class="info-section--title">当前订单状态：</span>
								<em class="info-section--text">
								<if condition="$now_order['status'] eq 4">
									已取消并退款
								<elseif condition="$now_order['status'] eq 5" />
									已取消
								<elseif condition="empty($now_order['paid'])" />
									未付款
								<elseif condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'"/>
									未消费 (<font color="red">线下未付款</font>)
								
								<elseif condition="$now_order['status'] == 2"/>
									已使用
                                <elseif condition="$now_order['status'] == 1 AND $now_order['is_pick_in_store'] == 3"/>
	                                                                                     已发货
                                <elseif condition="$now_order['status'] == 1 AND $now_order['is_pick_in_store'] != 3"/>
                                                                                         已接单
								<elseif condition="$now_order['status'] == 3"/>
									已完成
								</if></em>
								<div style="float:right;"><a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>3500000000+$now_order['order_id']))}">查看微信二维码</a></div>
							</dt>
							<dd class="last">
							  <if condition="$now_order['status'] eq 4">
									<div class="operation">
									    <a class="btn btn-mini">已取消并退款</a>
									</div>
								<elseif condition="($now_order['status'] eq '0') AND ($now_order['paid'] eq '1')" />
									<div class="operation">
										<a class="btn btn-mini" href="javascript:void(0)" onclick="shop_order_cancel({pigcms{$now_order['order_id']})">取消订单</a>
									</div>
                                <elseif condition="$now_order['status'] == 1 AND $now_order['is_pick_in_store'] == 3" />
                                    <div class="operation">
                                        <a class="btn btn-mini" href="javascript:void(0)" onclick="shop_order_finish({pigcms{$now_order['order_id']})">确认收货</a>
                                    </div>
								<elseif condition="empty($now_order['paid']) || $now_order['status'] eq 2" />
									<div class="operation">
										<if condition="empty($now_order['paid']) AND ($now_order['status'] eq 0)">
											<a class="btn btn-mini" href="{pigcms{:U('Index/Pay/check',array('type'=>'shop','order_id'=>$now_order['order_id']))}">付款</a>
											<a class="inline-link J-order-cancel" href="{pigcms{:U('Index/shop_order_del',array('order_id'=>$now_order['order_id']))}">删除</a>
										
										<elseif condition="$now_order['status'] eq 2"/>
											<a class="btn btn-mini" href="{pigcms{:U('Rates/shop')}">评价</a>
										</if>
									</div>
								</if>
							</dd>
						</dl>
						<dl class="bunch-section J-coupon">
							<if condition="false && $now_order['paid'] && $now_order['status'] lt 4">
								<dt class="bunch-section__label">{pigcms{$config.shop_alias_name}券</dt>
								<dd class="bunch-section__content">
									<div class="coupon-field">
										<p class="coupon-field__tip">小提示：记下或拍下{pigcms{$config.shop_alias_name}券密码向商家出示即可消费</p>
										<ul>
											<li class="invalid">{pigcms{$config.shop_alias_name}券密码：<b style="color:black;">{pigcms{$now_order.shop_pass_txt}</b><span>
											<if condition="$now_order['status'] lt 2">未消费<elseif condition="$now_order['status'] eq 2"/>已使用<elseif condition="$now_order['status'] eq 3"/>已完成</if></span></li>
										</ul>
									</div>
								</dd>
							</if>
							<dt class="bunch-section__label">订单信息</dt>
							<dd class="bunch-section__content">
								<ul class="flow-list">
									<li>订单编号：{pigcms{$now_order.real_orderid}</li>
									<li>下单时间：{pigcms{$now_order.create_time|date='Y-m-d H:i:s',###}</li>
									<if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'">
										<li></li>
										<li></li>
										<li></li>
										<li style="margin:30px 0;width:auto;"><b>线下需向商家付金额：</b>总金额 ￥{pigcms{$now_order['total_price']} - 商家会员卡余额支付 ￥{pigcms{:floatval($now_order['merchant_balance'])} - 平台余额支付 ￥{pigcms{:floatval($now_order['balance_pay'])} - 平台{pigcms{$config.score_name}支付 ￥{pigcms{:floatval($now_order['score_deducte'])}<if condition="$now_order['card_id']"> - 商家优惠券 ￥{pigcms{$now_order['coupon_price']}<elseif condition="$now_order['coupon_id']"> - 平台优惠券 ￥{pigcms{$now_order['coupon_price']}</if> = <font color="red">￥{pigcms{$now_order['total_price']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font></li>
									<elseif condition="$now_order['paid']"/>
										<li>付款方式：{pigcms{$now_order.pay_type_str}</li>
										<li>付款时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</li>
									</if>
									
									<if condition="!empty($now_order['use_time'])">
										<li>消费时间：{pigcms{$now_order.use_time|date='Y-m-d H:i',###}</li>
									</if>
								</ul>
								<ul class="flow-list">
									<li>商品总价：￥{pigcms{$now_order.goods_price|floatval} 元</li>
								<if condition="$now_order['is_pick_in_store'] neq 2">
									<li>配送费用：￥{pigcms{$now_order['freight_charge']|floatval} 元</li>
								</if>
                                <if condition="$now_order['packing_charge'] gt 0">
                                    <li>打包费用：￥{pigcms{$now_order['packing_charge']|floatval} 元</li>
                                </if>
									<li>订单总价：￥{pigcms{$now_order['total_price']|floatval} 元</li>
								
								<if condition="$now_order['merchant_reduce'] gt 0">
									<li>店铺优惠：￥{pigcms{$now_order['merchant_reduce']|floatval} 元</li>
								</if>
								
								
								<if condition="$now_order['balance_reduce'] gt 0">
									<li>平台优惠：￥{pigcms{$now_order['balance_reduce']|floatval} 元</li>
								</if>
									<li>实付金额：￥{pigcms{$now_order['price']|floatval} 元</li>
								</ul>
								<ul class="flow-list">
								<if condition="$now_order['score_used_count']">
								<li>使用{pigcms{$config.score_name}：{pigcms{$now_order['score_used_count']|floatval} </li>
								<li>{pigcms{$config.score_name}抵现：￥{pigcms{$now_order['score_deducte']|floatval} 元</li>
								</if>
			
								<if condition="$now_order['merchant_balance']">
								<li>商家余额：￥{pigcms{$now_order['merchant_balance']|floatval} 元</li>
								</if>
								<if condition="$now_order['balance_pay']">
								<li>平台余额：￥{pigcms{$now_order['balance_pay']|floatval} 元</li>
								</if>
								<if condition="$now_order['card_give_money']">
								<li>会员卡赠送余额：￥{pigcms{$now_order['card_give_money']|floatval} 元</li>
								</if>
								<if condition="$now_order['payment_money']">
								<li>在线支付：￥{pigcms{$now_order['payment_money']|floatval} 元</li>
								</if>
								
								<if condition="$now_order['card_id']">
								<li>店铺优惠券金额：￥{pigcms{$now_order['card_price']|floatval} 元</li>
								</if>
								<if condition="$now_order['coupon_id']">
								<li>平台优惠券金额：￥{pigcms{$now_order['coupon_price']|floatval} 元</li>
								</if>
								<if condition="$now_order['pay_type'] eq 'offline' AND empty($now_order['third_id'])">
								<li>线下需支付：￥{pigcms{$now_order['price']-$now_order['card_price']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['payment_money']-$now_order['score_deducte']-$now_order['coupon_price']|floatval}元</li>
								</if>
			
								</ul>
								<if condition="$now_order['is_pick_in_store'] eq 2">
									<ul>
										<li class="invalid">提货信息：{pigcms{$now_order.address}</li>
									</ul>
								<else />
									<ul>
										<li class="invalid">送货信息：{pigcms{$now_order.username}，{pigcms{$now_order.userphone}，{pigcms{$now_order.address}</li>
									</ul>
								</if>
							</dd>
							
							<dt class="bunch-section__label">{pigcms{$config.shop_alias_name}信息</dt>
							<dd class="bunch-section__content">
								<table cellspacing="0" cellpadding="0" border="0" class="info-table">
									<tbody>
										<tr>
											<th class="left" width="100">商品名称</th>
											<th width="50">单价</th>
											<th width="10"></th>
											<th width="30">数量</th>
											<th width="10"></th>
											<th width="54">支付金额</th>
										</tr>
										<volist name="now_order['info']" id="v">
										<tr>
											<td class="left">{pigcms{$v.name}</td>
											<td><span class="money">¥</span>{pigcms{$v.price}<if condition="$config.open_extra_price eq 1 AND $v.extra_price gt 0">+{pigcms{$v.extra_price}{pigcms{$config.extra_price_alias_name}</if></td>
											<td>x</td>
											<td>{pigcms{$v.num}</td>
											<td>=</td>
											<td class="total"><span class="money">¥</span>{pigcms{$v['price'] * $v['num']}<if condition="$config.open_extra_price eq 1 AND $v.extra_price gt 0">+{pigcms{$v['extra_price']*$v['num']}{pigcms{$config.extra_price_alias_name}</if></td>
										</tr>
										</volist>
									</tbody>
								</table>
							</dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
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
		});
		
		function shop_order_cancel(order_id){
			if(confirm('确认取消该订单？')){
				var cancelUrl = "{pigcms{:U('shop_order_check_refund')}";
				cancelUrl += "&order_id="+order_id;
				location.href = cancelUrl;
			}
		}
        
        function shop_order_finish(order_id){
            if(confirm('您确定已经收到货物了？')){
                var cancelUrl = "{pigcms{:U('finishOrder')}";
                cancelUrl += "&order_id="+order_id;
                location.href = cancelUrl;
            }
        }
	</script>
</body>
</html>
