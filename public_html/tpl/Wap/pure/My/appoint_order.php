<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>订单详情</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		<a <if condition='$now_order["appoint_id"]'>href="{pigcms{$now_order.url}"</if>>
			<dl class="list">
				<dd class="dd-padding">
					<div class="more more-weak">
						<div class="dealcard" <if condition='!$now_order["appoint_id"]'>style=" height:1.8rem"</if> >
							<div class="dealcard-img imgbox" style="background:none;"><img src="{pigcms{$now_order.list_pic}" style="width:100%;height: 1.58rem;"/></div>
							<div class="dealcard-block-right">
								<div class="dealcard-brand single-line" style='height:0.58rem'>{pigcms{$now_order.appoint_name}</div>
								<div class="title text-block">
									<if condition='$now_order["payment_status"] eq 1'>定金：￥<if condition="$now_order.product_payment_price gt 0">{pigcms{$now_order.product_payment_price}元<else />{pigcms{$now_order.payment_money}元</if><br/>
									服务类型：
										<if condition='!$now_order["appoint_id"]'>
											<span style="color:green">自营</span>
										<else />
											<php>if($now_order['appoint_type']==0){</php><span>到店</span>
												<php>}else if($now_order['appoint_type']==1){</php><span>上门</span>
											<php>}</php>
										</if>
									
										
								</div>
								<if condition='$now_order["appoint_id"]'>
									<div class="price">
										全价：<if condition='$now_order["is_appoint_price"] eq 1'>
										<span class="strong">
										<?php if($now_order["product_id"] > 0){ ?>
										￥{pigcms{$now_order.product_price}
										<?php }else{?>
										￥{pigcms{$now_order.appoint_price}
										<?php } ?>
										</span>
										<span class="strong-color">
										元<php>if($now_order['extra_price']>0&&$config['open_extra_price']==1){</php>+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}<php>}</php></span><else /><span class="strong">面议</span></if>
									</div>
								</if>
							</div>
						</div>
					</div>
				</dd>
			</dl>
		</a>
        <div class="wrapper-list">
			<dl class="list" style="border-bottom:none;"></dl>
			<dl class="list">
				<dd>
					<dl>
						<dd>
			                <a class="react" <if condition='$now_order["appoint_id"]'>href="{pigcms{:U('Appoint/branch',array('appoint_id'=>$now_order['appoint_id']))}"</if>>
			                    <div class="more more-weak">
			                        <h6>商家信息</h6>
			                        <span class="more-after">查看</span>
			                    </div>
			                </a>
		                </dd>
					</dl>
				</dd>
			</dl>
			
			<if condition='$now_order["type"] neq 1 AND $now_appoint.product_type eq 0'>
			<dl class="list coupons">
				<dd>
					<dl>
						<dt>{pigcms{$config.appoint_alias_name}时间</dt>
						<dd class="dd-padding coupons-code">
							预约时间: {pigcms{$now_order.appoint_date}&nbsp;&nbsp;{pigcms{$now_order.appoint_time}
						</dd>
					</dl>
				</dd>
			</dl>
			</if>
			<dl class="list">
				<dd>
					<dl>
						<dt>{pigcms{$config.appoint_alias_name}详情</dt>
						<ul class="ul">
							<li>订单编号：{pigcms{$now_order.order_id}</li>
							<li>下单时间：{pigcms{$now_order.order_time|date='Y-m-d H:i',###}</li>
							<li>手机号：{pigcms{$now_order.phone}</li>
							<if condition='$now_order["product_name"]'><li>服务名称：{pigcms{$now_order["product_name"]}</li></if>
							<if condition='($now_order["product_payment_price"] neq "0.00") AND ($now_order["type"] lt "2") AND ($now_order["payment_status"])'><li>选择服务定金：¥ {pigcms{$now_order["product_payment_price"]}</li></if>
							<if condition='$now_order["product_price"] != "0.00"'><li>选择服务全价：¥ {pigcms{$now_order["product_price"]}</li></if>
							<if condition='$now_order["product_use_time"]'><li>选择服务时间：{pigcms{$now_order["product_use_time"]}分钟</li></if>
							<if condition="($now_order['product_card_discount'] lt 10) AND ($now_order['product_card_discount'] gt 0)"><li>余额享受会员卡折扣：{pigcms{$now_order['product_card_discount']}折</li></if>
							
							<if condition='$now_order["product_id"] gt 0'>
							
							<if condition="($now_order['product_payment_price'] neq '0.00') AND ($now_order['type'] lt '2') AND ($now_order['payment_status'])"><li>定金支付金额：<?php if($now_order["balance_pay"] > 0){?>{pigcms{$now_order['balance_pay']}<?php }else{?>{pigcms{$now_order['product_payment_price']}<?php } ?>元</li></if>
							
							<li>定金支付方式：<if condition="!empty($now_order['pay_type_txt'])">{pigcms{$now_order['pay_type_txt']}<else />余额支付</if></li>
							<if condition="$now_order['pay_time'] gt 0"><li>定金支付时间：{pigcms{$now_order['pay_time']|date='Y-m-d H:i:s',###}</li></if>
							<elseif condition="($now_order['payment_money'] gt 0) AND ($now_order['payment_status'] eq 1)" />
							<li>定金支付金额：{pigcms{$now_order['payment_money']}元</li>
							<li>定金{pigcms{$config['score_name']}抵扣：{pigcms{$now_order['score_deducte']}元</li>
							<li>定金支付方式：<if condition="!empty($now_order['pay_type_txt'])">{pigcms{$now_order['pay_type_txt']}<else />余额支付</if></li>
							<if condition="$now_order['pay_time'] gt 0"><li>定金支付时间：{pigcms{$now_order['pay_time']|date='Y-m-d H:i:s',###}</li></if>
							</if>
							
							<if condition="$now_order['is_initiative'] eq 1" >
							<if condition="$now_order['user_pay_time'] gt 0"><li>余款支付时间：{pigcms{$now_order['user_pay_time']|date='Y-m-d H:i:s',###}</li></if>
							<li>余款平台余额支付金额：{pigcms{$now_order['product_balance_pay']}元</li>
							
							<if condition="$now_order['product_card_give_money'] neq 0"><li>余款商家会员卡赠送余额：{pigcms{$now_order['product_card_give_money']}元</li></if>
							<if condition="$now_order['product_merchant_balance'] neq 0"><li>余款平台余额抵扣：{pigcms{$now_order['product_merchant_balance']}元</li></if>
							<if condition="$now_order['product_card_price'] gt 0"><li>余款商家优惠券：{pigcms{$now_order['product_card_price']}元</li></if>
							<if condition="$now_order['product_coupon_price'] gt 0"><li>余款平台优惠券：{pigcms{$now_order['product_coupon_price']} 元</li></if>
				
							<if condition="$now_order['product_score_deducte'] gt 0"><li>余款{pigcms{$config.score_name}抵扣：{pigcms{$now_order['product_score_deducte']}元</li></if>
							<li>余款在线支付方式：<if condition="!empty($now_order['product_pay_type_txt'])">{pigcms{$now_order['product_pay_type_txt']}<else />余额支付</if></li>
							<li>余款在线支付金额：{pigcms{$now_order['user_pay_money']}元</li>
							</if>
							<if condition="$now_order['service_status'] eq 0 ">
								<li>消费码：{pigcms{$now_order.appoint_pass}</li>
							</if>
							<li>服务状态：
								<if condition="$now_order['service_status'] eq 0">
									<?php if($now_supply){?>
										<span style="color:red">技师已服务，用户未支付余额</span>
									<?php }else{ ?>
										<span style="color:red">未服务</span>
									<?php } ?>
									
								<elseif condition="$now_order['service_status'] eq 1" />
									<span style="color:green">已服务</span>
                                <elseif condition="$now_order['service_status'] eq 2" />
									<span style="color:green">已评价</span>
								</if>
								
								<if condition='($now_order["is_del"] neq 0) || ($now_order["paid"] eq 3)'>
									<span style="color:red">已取消</span>
								</if>
							</li>
						</ul>
					</dl>
				</dd>
			</dl>
			<dl style="display:block; height:30px"></dl>
			<if condition="$now_order['paid'] eq 2 && $now_order['payment_status'] eq 1">
				<div class="btn-wrapper">
					<span class="order-cancel" style="background-color:#BBB9B5;">已退款</span>
				</div>
			<elseif condition="($now_order['paid'] eq 1) && ($now_order['service_status'] eq 1)" />
				<div class="btn-wrapper">
					<span onclick="window.location.href='{pigcms{:U('My/appoint_feedback',array('order_id'=>$now_order['order_id']))}'"  class="order-cancel">评价</span>
				</div>
			<elseif condition="$now_order['paid'] eq 3" />
				<div class="btn-wrapper">
					<span class="order-cancel">已取消</span>
				</div>
			<elseif condition="($now_order['paid'] eq 0) && ($now_order['payment_status'] eq 1) && ($now_order['is_del'] eq 0)" />
				<div class="btn-wrapper">			
					<span onclick="window.location.href='{pigcms{:U('Pay/check',array('type'=>'appoint','order_id'=>$now_order['order_id']))}'" class="order-pay" style="margin-bottom:15px;">付款</span>
					<span onclick="cancel_order({pigcms{$now_order['order_id']})" class="order-cancel">取消订单</span>
				</div>
			<!--elseif condition="$now_order['payment_status'] eq 0"/-->
			<else />
				<!--if condition='($now_order["is_del"] eq 0) && ($now_order["paid"] eq 0)'-->
				<if condition='$now_order["is_del"] eq 0'>
					<div class="btn-wrapper">
					<!--?php if(($config['appoint_rule']) && (time() > (strtotime($now_order['appoint_date'] .' '. $now_order['appoint_time']) - ($config['appoint_before_cancel_time'] * 60)))) {?-->
						<?php if($config['appoint_rule']) {?>
						<?php if(!$now_order['is_initiative'] && (($now_order['product_price'] - $now_order['product_payment_price'] > 0) ||($now_order['appoint_price'] - $now_order['payment_money'] > 0)) && ($now_order['service_status'] == 0)){?><span  class="order-cancel" style="border:1px solid green" onclick="pay_balance({pigcms{$now_order['order_id']})">支付余款</span>
						<?php if(empty($now_supply)){?><span onclick="chk_cancel_order({pigcms{$now_order['order_id']})" class="order-cancel" style="margin-bottom:15px;">取消订单</span>
						
						<?php }}}else{ ?>
						<span onclick="cancel_order({pigcms{$now_order['order_id']})" class="order-cancel" style="margin-bottom:15px;">取消订单</span>
						<?php } ?> 
					</div>
				</if>
			</if>
		</div>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/jquery.qrcode.min.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>		
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
		<script>
			$(function(){
				$('#see_storestaff_qrcode').click(function(){
					var qrcode_width = $(window).width()*0.6 > 256 ? 256 : $(window).width()*0.6;
					layer.open({
						title:['消费二维码','background-color:#8DCE16;color:#fff;'],
						content:'生成的二维码仅限提供给商家店铺员工扫描验证消费使用！<br/><br/><div id="qrcode"></div>',
						success:function(){
							$('#qrcode').qrcode({
								width:qrcode_width,
								height:qrcode_width,
								text:"{pigcms{$config.site_url}/wap.php?c=Storestaff&a=group_qrcode&order_id={pigcms{$now_order.order_id}&id={pigcms{$now_order.group_pass}"
							});
						}
					});
					$('.layermbox0 .layermchild').css({width:qrcode_width+30+'px','max-width':qrcode_width+30+'px'});
				});
				
				check_update_money();
			});
			
			function check_update_money(){
				$.ajax({
					url: '{pigcms{:U('Appoint/check_update_money')}',
					type: 'POST',
					dataType: 'json',
					data: {money:"{pigcms{$now_order['product_price']}",order_id:'{pigcms{$_GET["order_id"]}'},
					success:function(date){
						if(date.status){
							window.location.href=date.url
						}
					}
				});
				
				s= setTimeout('check_update_money()',1000)
			}
			function cancel_order(order_id){
				layer.open({
				content:'取消后，将无法恢复，是否确认取消？',
				btn: ['确定','取消'],
				yes:function(){
                   var url = "{pigcms{:U('ajax_wap_user_del')}";
					$.post(url,{'order_id':order_id},function(data){
						alert(data.msg);
						if(data.status){
							location.href="{pigcms{:U('appoint_order_list')}";
						}
					},'json')
				}
				});
			}
			
			function chk_cancel_order(order_id){
				var payment_money = "{pigcms{$now_order['payment_money']}";
				var product_payment_price = "{pigcms{$now_order['product_payment_price']}";
				if(product_payment_price != '0.00'){
					var tmp_price = product_payment_price;
				}else{
					var tmp_price = payment_money;
				}
				if(tmp_price != '0.00'){
					var str = '该订单取消后，会扣除定金' + tmp_price + '元，是否确认？';
				}else{
					var str = '确认取消订单？'
				}
				
				layer.open({
				content:str,
				btn: ['确定','取消'],
				yes:function(){
                   var url = "{pigcms{:U(ajax_wap_appoint_del)}";
					$.post(url,{'order_id':order_id},function(data){
						if(typeof(data['status'])!='undefined' && data['status']){
							location.reload();
						}
					},'json');
				}
				});
			}
			
			
			function pay_balance(order_id){
				layer.open({
				content:'确认支付余款？',
				btn: ['确定','取消'],
				yes:function(){
                   var url = "{pigcms{:U(ajax_wap_appoint_pay_balance)}";
					$.post(url,{'order_id':order_id},function(data){
						/* layer.open({content:data.msg,shadeClose:false,btn:['确定'],yes:function(){
							window.location.href = "{pigcms{:U('appoint_order_list')}";
							if(data['status']==1){
								location.href=data['url'];
							}else if(data['status']==2){
								location.reload();
							}
						}}); */
						
						if(data['status']==1){
							location.href=data['url'];
						}
					},'json');
				}
				});
			}
		</script>
{pigcms{$hideScript}
</body>
</html>