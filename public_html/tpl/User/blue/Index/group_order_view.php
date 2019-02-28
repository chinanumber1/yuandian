<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{$config.group_alias_name}订单详情 | {pigcms{$config.site_name}</title>
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
<style>
.share-user ul{
	clear: both;
    height: 104px;
}
.share-user li{
	height: 100px;
    width: 80px;
    margin-bottom: 5px;
    margin-left: 5px;
	float:left;
	
}
.share-user span{
	display:block;
	text-align:left;
}

.img_list{
	width:60px;
	height:60px;
	border-radius:50%; 
	overflow:hidden;
	margin-left: 19px;
    margin-top: 5px;
}
</style>
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
						<h2>订单详情<span class="op-area"><a href="{pigcms{:U('Index/index')}">返回订单列表</a></span></h2>
						<dl class="info-section primary-info J-primary-info">
							<dt>
								<span class="info-section--title">当前订单状态：</span>
								<em class="info-section--text">
								<if condition="empty($now_order['paid'])">
									未付款
								<elseif condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'"/>
									未消费 (<font color="red">线下未付款</font>)
								<elseif condition="empty($now_order['status'])"/>
									
									<if condition="$now_order['tuan_type'] neq 2">
										未消费
									<else/>
										<php> 
										if($now_order['express_id']!=''){
											echo '已发货';
										}else{
											echo '未发货';
										}
										</php>
										
									</if>
								<elseif condition="$now_order['status'] == '1'"/>
									<if condition="$now_order['tuan_type'] neq 2">
										已使用
									<else/>
										已发货
									</if>
								<elseif condition="$now_order['status'] == '2'"/>
									已完成
								</if>
								</em>
								<div style="float:right;"><a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>2000000000+$now_order['order_id']))}">查看微信二维码</a></div>
							</dt>
							
							<dd class="last">
							
								<if condition="$now_order['status'] eq '3' OR $now_order['status'] eq 4" >
									<div class="operation">
									    <a class="btn btn-mini">已取消并退款</a>
									</div>
								<elseif condition="$now_order['status'] eq 6" />
									<div class="operation">
									    <a class="btn btn-mini">已退款：<font color="red">{pigcms{$now_order['refund_total']}</font>元<if condition="$now_order['refund_fee']">(手续费：{pigcms{$now_order['refund_fee']})</if></a>
									</div>
								
								<elseif condition="($now_order['status'] eq '0') AND ($now_order['paid'] eq '1' ) AND $now_order['is_head'] eq 0 AND $now_order['pin_fid'] eq 0 AND $vo['express_id'] eq '' " />
									<div class="operation">
										<a class="btn btn-mini" href="javascript:void(0)" onclick="order_cancel({pigcms{$now_order['order_id']})">取消订单</a>
									</div>
								<elseif condition="empty($now_order['paid']) || $now_order['status'] eq '1'" />
									<if condition="empty($now_order['paid'])"><p>请及时付款，不然就被抢光啦！</p></if>
									<div class="operation">
										<if condition="empty($now_order['paid'])">
											<a class="btn btn-mini" href="{pigcms{:U('Index/Pay/check',array('type'=>'group','order_id'=>$now_order['order_id']))}">付款</a>&nbsp;&nbsp;&nbsp;
											<a class="inline-link J-order-cancel" href="{pigcms{:U('Index/group_order_del',array('order_id'=>$now_order['order_id']))}">删除</a>
										<elseif condition="$now_order['status'] eq '1'"/>
											<a class="btn btn-mini" href="{pigcms{:U('Rates/index')}">评价</a>
										</if>
									</div>
								</if>
							</dd>
						</dl>
						<dl class="bunch-section J-coupon">
							<if condition=" $now_order.paid eq 1 AND $now_order['status'] neq 3 AND $now_order['is_share_group']!=2">
							<dd class="bunch-section__content">
								<div class="coupon-field">
									<input type="index" name="share_url" style="width:70%;" value="http://{pigcms{$_SERVER['HTTP_HOST']}/group/{pigcms{$now_group['group_id']}.html?fid={pigcms{$is_shared.res.fid}"/> 
									<p>复制链接分享给朋友，立即成团</p>
								</div>
							</dd>
							</if>
							<if condition="($now_group['group_share_num'] neq 0) AND ($group_share_num lt $now_group['group_share_num']) AND ($now_order['is_share_group']!=2)">
							<dd class="bunch-section__content">
								<div class="coupon-field">
									<p class="coupon-field__tip">参团份数还未达到 {pigcms{$now_group['group_share_num']} 份的份数要求</p>
									<ul>
										<li class="invalid" id="share_num">还差 {pigcms{$now_group['group_share_num']-$group_share_num} 份就可以成团<php>if($now_order['status']!=3){</php>&nbsp;<strong id="countdown">&nbsp;&nbsp;</strong>秒后刷新数据<php>}</php></li>
									</ul>
								</div>
							</dd>
							<elseif condition="$now_order['paid'] && $now_order['tuan_type'] neq 2 && $now_order['status'] neq 3" />
								<dt class="bunch-section__label">{pigcms{$config.group_alias_name}券</dt>
								<php>if($now_order['is_share_group']==2||$now_group['open_num']<=$now_group['sale_count']||$now_group['open_num']==0||($now_group['open_now_num']<=$now_group['sale_count']&&$now_group['open_now_num']!=0)){</php>
								<dd class="bunch-section__content">
									<div class="coupon-field">
										<p class="coupon-field__tip">小提示：记下或拍下{pigcms{$config.group_alias_name}券密码向商家出示即可消费</p>
										<ul>
										<php>if($now_order['pass_array']){</php>
											<volist name="pass_array" id="vv">
												<li class="invalid">
													{pigcms{$config.group_alias_name}券密码： <php>if($vv['status'] == 2){</php><font color="red">无法查看</font><php>}else{</php>{pigcms{$vv.group_pass}<php>}</php> <b style="color:black;"><php>if($vv['status']==0){</php>未消费<php>}elseif($vv['status']==1){</php>已消费<php>}elseif($vv['status']==3){</php><font color="red">还需支付：{pigcms{$vv['need_pay']} 元</font><php>}elseif($vv['status']==2){</php><font color="red">已退款</font><php>}</php></b>
												<li>
											</volist>
											<php>}else{</php>
												<li class="invalid">{pigcms{$config.group_alias_name}券密码：<b style="color:black;">{pigcms{$now_order.group_pass_txt}</b><span><if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'">未消费 (<font color="red">线下未付款</font>)<elseif condition="empty($now_order['status'])"/>未消费<elseif condition="$now_order['status'] == '1'"/>已使用<elseif condition="$now_order['status'] == '2'"/>已完成</if></span></li>
											<php>}</php>
										</ul>
									</div>
								</dd>
								<php>}elseif($now_group['open_now_num']>0){</php>
								<dd class="bunch-section__content">
									<div class="coupon-field">
										<p class="coupon-field__tip">参团份数还未达到 {pigcms{$now_group['open_now_num']} 份的份数要求</p>
										<ul>
											<li class="invalid">还差 {pigcms{$now_group['open_now_num']-$now_group['sale_count']} 份就可以成团</li>
										</ul>
									</div>
								</dd>
								
								<php>}else{</php>
								<dd class="bunch-section__content">
									<div class="coupon-field">
										<p class="coupon-field__tip">参团份数还未达到 {pigcms{$now_group['open_num']} 份的份数要求</p>
										<ul>
											<li class="invalid">还差 {pigcms{$now_group['open_num']-$now_group['sale_count']} 份就可以成团</li>
										</ul>
									</div>
								</dd>
								<php>}</php>
								
							</if>
							<php>if($share_user){</php>
								<dt class="bunch-section__label">购买用户：</dt>	
								<dd class="bunch-section__content share-user">
									<ul  class="coupon-field" id="user-list">
										<volist name="share_user" id="vo">
											<li id="uid-{pigcms{$vo.uid}" data-id="{pigcms{$vo.uid}">
												<if condition="$key lt 10">
												<img class="img_list" src="{pigcms{$vo.img}" />
												<span >{pigcms{$vo.name}</span>
												<else />
												<span >......</span>
												</if>
											</li>
										</volist>
									</ul>
								</dd>
								
									
								<php>}</php>
							<dt class="bunch-section__label">订单信息</dt>
							<dd class="bunch-section__content">
								<ul class="flow-list">
									<li>订单编号：{pigcms{$now_order.real_orderid}</li>
									<li>下单时间：{pigcms{$now_order.add_time|date='Y-m-d H:i',###}</li>
									<if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'">
										<li></li>
										<li></li>
										<li></li>
										<li style="margin-top:30px;width:auto;"><b>线下需向商家付金额：</b>总金额 ￥{pigcms{$now_order['total_money']} - <if condition="$now_order['wx_cheap'] neq '0.00'">微信优惠 ￥{pigcms{$now_order['wx_cheap']} - </if> 商家会员卡余额支付 ￥{pigcms{:floatval($now_order['merchant_balance'])} - 平台余额支付 ￥{pigcms{:floatval($now_order['balance_pay'])} - 平台{pigcms{$config.score_name}支付 ￥{pigcms{:floatval($now_order['score_deducte'])}<if condition="$now_order['card_id']"> - 商家优惠券 ￥{pigcms{$now_order['coupon_price']}<elseif condition="$now_order['coupon_id']"> - 平台优惠券 ￥{pigcms{$now_order['coupon_price']}</if> = <font color="red">￥{pigcms{$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font></li>
									<elseif condition="($now_order['pay_type'] eq 'offline' AND $now_order['paid'] AND !empty($now_order['third_id'])) OR ($now_order['pay_type'] neq 'offline' AND $now_order['paid'])"/>
										<li>付款方式：{pigcms{$now_order.pay_type_txt}</li>
										<li>付款时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i',###}</li>
										<if condition="!empty($now_order['use_time'])">
											<li>消费时间：{pigcms{$now_order.use_time|date='Y-m-d H:i',###}</li>
										</if>
										<li style="margin:30px 0;width:auto;"><b>支付详情：</b>在线支付金额 ￥{pigcms{$now_order['payment_money']}  商家会员卡余额支付 ￥{pigcms{:floatval($now_order['merchant_balance'])}  平台余额支付 ￥{pigcms{:floatval($now_order['balance_pay'])} <if condition="$now_order['score_deducte'] gt 0">{pigcms{$config.score_name}抵扣金额 ￥{pigcms{$now_order.score_deducte} {pigcms{$config.score_name}使用数量 {pigcms{$now_order.score_used_count|floatval}</if>
										</li>
									</if>
								</ul>
							</dd>
							<if condition="$now_order['tuan_type'] eq 2">
							
							
								<dt class="bunch-section__label"><if condition="$now_order.is_pick_in_store">自取信息<else />快递信息</if></dt>
								<dd class="bunch-section__content">
									<div class="coupon-field">
										<ul>
											<if condition="$now_order.is_pick_in_store">
											<li class="invalid">取货地址：{pigcms{$now_order.adress}</li>
											<li class="invalid"><php>if($now_order['status']==2){</php>已自提<php>}else{</php>未取货<php>}</php></li>
											<else />
											<li class="invalid">收货地址：{pigcms{$now_order.contact_name}，{pigcms{$now_order.adress}，{pigcms{$now_order.zipcode}，{pigcms{$now_order.phone}</li>
											<li class="invalid"><php>if($now_order['express_id']){</php>快递单号：{pigcms{$now_order.express_id}<php>}else{</php>未发货<php>}</php></li>
											</if>
										</ul>
									</div>
								</dd>
								
							</if>
							<dt class="bunch-section__label">{pigcms{$config.group_alias_name}信息</dt>
							<dd class="bunch-section__content">
								<table cellspacing="0" cellpadding="0" border="0" class="info-table">
									<tbody>
										<tr>
											<th class="left" width="100">{pigcms{$config.group_alias_name}项目</th>
											<th width="50">单价</th>
											<th width="10"></th>
											<th width="30">数量</th>
											<th width="10"></th>
											<th width="54">支付金额</th>
										</tr>
										<tr>
											<td class="left">
												<a class="deal-title" href="{pigcms{$now_order.url}" target="_blank">{pigcms{$now_order.s_name}</a>
											</td>
											<td><span class="money">¥</span>{pigcms{$now_order.price}<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if></td>
											<td>x</td>
											<td>{pigcms{$now_order.num}</td>
											<td>=</td>
											<td class="total"><span class="money">¥</span>{pigcms{$now_order.total_money}<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order['extra_price']*$now_order['num']}{pigcms{$config.extra_price_alias_name}</if></td>
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
		var flag=false;
			
			<if condition="$now_group.group_share_num gt 0 AND $now_order['is_share_group'] neq 2 AND $now_order['status']!='3'">
				var last_num = {pigcms{$now_group['group_share_num']-$group_share_num};
				var group_share_num = {pigcms{$now_group['group_share_num']};
				
				function ajax_get_num(){
					$.post('{pigcms{:U('Index/ajax_group_share_num')}', {order_id:{pigcms{$now_order.order_id},uid:{pigcms{$now_order.uid}}, function(data, textStatus, xhr) {
						data = JSON.parse(data);
						if(data.error_code){
							alert(data.msg);
						}else{
							if(data.num!=last_num){
								last_num = data.num;
								flag = true;
							}else {
								flag = false;
							}
							var num = group_share_num-data.num;
							if(num<=0){
								document.location.reload();
							}
							if(flag)
								ajax_get_user();
							$('#share_num').html('还差 '+num+' 份就可以成团&nbsp;<strong id="countdown">&nbsp;&nbsp;</strong>秒后刷新数据');
						}
					});
				}
				
				function ajax_get_user(){
					var uids='';
					$.each($('#user-list li'), function(index, val) {
						uids += $(this).attr('data-id')+',';
					});
					$.post('{pigcms{:U('Index/ajax_group_user')}', {order_id:{pigcms{$now_order.order_id},uid:{pigcms{$now_order.uid},uids:uids}, function(data, textStatus, xhr) {
						data = JSON.parse(data);
						if(data.error_code){
							alert(data.msg);
						}else{
							var user_arr = data.res.user_arr;
							if(user_arr){
								$.each(user_arr, function(index, val) {
									if($('#uid-'+val.uid).length==0){
										$('#user-list').append('<li id="uid-'+val.uid+'" data-id="'+val.uid+'"><img class="img_list" src="'+val.img+'" /><span >'+val.name+'</span></li>');
									}
									
								});
							}
							
							if(data.res.not_in){
								$.each(data.res.not_in, function(index, val) {
									$('#uid-'+val).remove();
								});	
							}
							flag=false;
						}
					});
				}
				var start = 5;
				var step = -1;
				function count(){
					document.getElementById("countdown").innerHTML = start;
					start += step;
					if(start ==0){
						start =5;
						ajax_get_num();
						
					}
					setTimeout("count()",1000);
				}
				window.onload = count;
			</if>
			
	
	
		function is_share_group(){
			$.post('{pigcms{:U('Index/is_group_share')}', {order_id:{pigcms{$now_order.order_id},uid:{pigcms{$now_order.uid},order_id:{pigcms{$now_order.order_id}}, function(data, textStatus, xhr) {});
		}
		
		$(document).ready(function(){
			$('input[name="share_url"]').select();
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
		
		
		function order_cancel(order_id){
			if(confirm('确认取消订单？')){
				var cancelUrl = "{pigcms{:U('group_order_check_refund')}"
				cancelUrl += '&order_id='+order_id;
				location.href=cancelUrl;
			}
		}
	</script>
</body>
</html>
