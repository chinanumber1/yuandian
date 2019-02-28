<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<title>小猪{pigcms{$config.meal_alias_name}{pigcms{$config.group_alias_name}系统 - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			.plus{
				padding: 3px 5px;
			}
			
			.reduce{
				padding: 3px 5px;
			}
		</style>
	</head>
	<body>
		<table>
			<tr>
				<th width="15%">订单编号</th>
				<td colspan="3" width="85%">{pigcms{$now_order.real_orderid}</td>
			</tr>
			<if condition="$now_order.orderid neq 0">
				<tr>
					<th width="15%">订单流水号</th>
					<td colspan="3" width="85%">{pigcms{$now_order.orderid}</td>
				</tr>
			</if>
			<tr>
				<th width="15%">{pigcms{$config.group_alias_name}商品</th>
				<td colspan="3" width="85%"><a href="{pigcms{$now_order.url}" target="_blank" title="查看商品详情">{pigcms{$now_order.s_name}</a></td>
			</tr>
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息：</b></td>
			</tr>
			<tr>
				<th width="15%">订单类型</th>
				<td width="35%"><if condition="$now_order['tuan_type'] eq '0'">{pigcms{$config.group_alias_name}券<elseif condition="$now_order['tuan_type'] eq '1'"/>代金券<else/>实物</if></td>
				<th width="15%">订单状态</th>
				<td width="35%">
				
				<if condition="$now_order['status'] eq 7">
				      <font color="red">退款失败，原因：{pigcms{$now_order['refund_detail']}</font>
					<elseif condition="$now_order['status'] eq 3"/>
					<font color="red">已取消</font>
					<elseif condition="$now_order['paid'] eq '1'" />
						<if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline' AND $now_order['status'] eq 0">
							<font color="red">线下未付款</font>
						<elseif condition="$now_order['status'] eq '0'"/>
							<font color="green">已付款</font>
							<if condition="$now_order['tuan_type'] neq '2'">
							<php>if($now_order['tuan_type'] != 2){</php>
								<font color="red">未消费</font>
							<php>}else{</php>
								<php>if($now_order['is_pick_in_store']){</php>
									<font color="red">未取货</font>
								<php>}else{</php>
									<php>if($now_order['express_id'] != ''){</php>
										<font color="red">已发货</font>
									<php>}else{</php>
										<font color="red">未发货</font>
									<php>}</php>
								<php>}</php>
							<php>}</php>
						<elseif condition="$now_order['status'] eq '1'"/>
						
							<php>if($now_order['tuan_type'] != 2){</php>
								<font color="green">已消费</font>
							<php>}else{</php>
								<php>if($now_order['is_pick_in_store']){</php>
									<font color="green">已取货</font>
								<php>}else{</php>
									<font color="green">已发货</font>
								<php>}</php>
							<php>}</php>
							<font color="red">待评价</font>
					
						<else/>
							<font color="green">已完成</font>
						</if>
					<else/>
						<font color="red">未付款</font>
					</if>
				</td>
			</tr>
			<tr>
				<th width="15%">数量</th>
				<td width="35%">{pigcms{$now_order.num}</td>
				<th width="15%">总价</th>
				<td width="35%">￥ {pigcms{$now_order.total_money}</td>
			</tr>
			<if condition="$now_order.express_fee gt 0">
			<tr>
				<th width="15%">运费</th>
				<td width="35%">{pigcms{$now_order.express_fee}</td>
				<th width="15%"></th>
				<td width="35%"></td>
			</tr>
			</if>
			<tr>
				<th width="15%">下单时间</th>
				<td width="35%">{pigcms{$now_order.add_time|date='Y-m-d H:i:s',###}</td>
				<if condition="$now_order['paid']">
					<th width="15%">付款时间</th>
					<td width="35%"><if condition="$now_order.pay_type neq 'offline'">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</if></td>
				<else/>
					<th width="15%"></th>
					<td width="35%"></td>
				</if>
				<if condition="$now_order.pay_type eq 'offline' AND empty($now_order['third_id']) AND $now_order.status neq 3">			
					<tr>
						<th width="15%">线下还需支付</th>
						<td width="85%" colspan="3"><font color="red">￥ {pigcms{$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']} 元</font></td>
					</tr>
				</if>
			</tr>
			<tr>
				<th width="15%">买家留言</th>
				<td width="85%" colspan="3">{pigcms{$now_order.delivery_comment}</td>
			</tr>
			<tr>
				<th width="15%">支付方式</th>
				<td width="85%" colspan="3">{pigcms{$now_order.paytypestr}</td>
			</tr>
			<if condition="!empty($now_order['use_time'])">		
				<tr>
					<th width="15%"><if condition="$now_order['tuan_type'] neq 2">消费<else/>发货</if>时间</th>
					<td width="35%">{pigcms{$now_order.use_time|date='Y-m-d H:i:s',###}</td>
					<th width="15%">操作店员：</th>
					<td width="35%">{pigcms{$now_order.last_staff}</td>
				</tr>
			</if>
			<if condition="$now_order['paid'] eq '1'">
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>用户信息：</b></td>
				</tr>
				<tr>
					<th width="15%">用户ID</th>
					<td width="35%">{pigcms{$now_order.uid}</td>
					<th width="15%">用户名</th>
					<td width="35%">{pigcms{$now_order.nickname}</td>
				</tr>
				<tr>
					<th width="15%">订单手机号</th>
					<td width="35%">{pigcms{$now_order.phone}</td>
					<th width="15%">用户手机号</th>
					<td width="35%">{pigcms{$now_order.user_phone}</td>
				</tr>
				<if condition="$now_order['tuan_type'] eq 2">
					<tr>
						<td colspan="4" style="padding-left:5px;color:black;"><b>配送信息：</b></td>
					</tr>
					<tr>
						<th width="15%">收货人</th>
						<td width="35%">{pigcms{$now_order.contact_name}</td>
						<th width="15%">联系电话</th>
						<td width="35%">{pigcms{$now_order.phone}</td>
					</tr>
					<tr>
						<th width="15%">配送要求</th>
						<td width="35%">
							<switch name="now_order['delivery_type']">
								<case value="1">工作日、双休日与假日均可送货</case>
								<case value="2">只工作日送货</case>
								<case value="3">只双休日、假日送货</case>
								<case value="4">白天没人，其它时间送货</case>
							</switch>
						</td>
						<th width="15%">邮编</th>
						<td width="35%">{pigcms{$now_order.zipcode}</td>
					</tr>
					<tr>
						<th width="15%"><?php if(!$now_order['is_pick_in_store']){?>收货地址<?php }else{?>自提地址<?php }?></th>
						<td width="85%" colspan="3">{pigcms{$now_order.adress}</td>
					</tr>
					<?php if(!$now_order['is_pick_in_store']){?>
						<?php if(empty($pin_info) || $pin_info['status']==1||$pin_info['status']==3){?>
					
						<tr>
							<th width="15%">快递信息</th>
							<td width="85%" colspan="3"><select id="express_type"><volist name="express_list" id="vo"><option value="{pigcms{$vo.id}"<if condition="$now_order['express_type'] eq $vo['id']">selected=selected</if>>{pigcms{$vo.name}</option></volist></select> <input type="text" class="input" id="express_id" value="{pigcms{$now_order.express_id}" style="width:140px;"/> <button id="express_id_btn">填写</button></td>
						</tr>
							<?php }else{?>
							<tr>
								<th width="15%">快递信息</th>
								<td width="85%" colspan="3" style="color:red">还未成团不能填写快递信息</td>
							</tr>
							
							<?php }?>
							
							<?php }else{?>
						<tr>
							<th width="15%">自取信息</th>
							<td width="85%" colspan="3"><font color="blue">用户到店自取</font><?php if($now_order['status']!=1){?><button id="pickup">用户取货确认</button><?php }?></td>
						</tr>	
							
					<?php }?>
				</if>
				<tr>
					<th width="15%">余额支付金额</th>
					<td width="35%">{pigcms{$now_order.balance_pay}</td>
					<th width="15%">在线支付金额</th>
					<td width="35%">{pigcms{$now_order.payment_money}</td>
				</tr>
			
				<tr>
					<th width="15%">使用商家会员卡余额</th>
					<td width="85%" colspan="3">折扣：{pigcms{$now_order['card_discount']}折， 余额：￥{pigcms{$now_order['merchant_balance']} ，赠送余额：￥{pigcms{$now_order['card_give_money']}</td>
				</tr>
				
				<tr>
					<th width="15%">微信优惠</th>
					<td width="85%" colspan="3">￥{pigcms{$now_order['wx_cheap']}</td>
				</tr>
				<tr>
				
						<th width="15%">平台优惠券</th>
						<td width="35%">￥{pigcms{$now_order.coupon_price|floatval} </td>
					
				
						<th width="15%">商家优惠券</th>
						<td width="35%">￥{pigcms{$now_order.card_price|floatval}</td>
				
				</tr>
				<tr>
					<th width="15%">{pigcms{$config['score_name']}使用数量</th>
					<td width="35%">{pigcms{$now_order.score_used_count}</td>
					<th width="15%">{pigcms{$config['score_name']}抵扣金额</th>
					<td width="35%">￥ {pigcms{$now_order.score_deducte} 元</td>
				</tr>
				<if condition="$pass_array">
					<tr>
						<th width="15%">消费密码</th>
						<th width="85%" colspan="3">
							<volist name="pass_array" id="vo">
							{pigcms{$i}. {pigcms{$vo.group_pass} <if condition="vo.status eq 1">已消费<else />未消费</if><br>
							</volist>
						</th>
					</tr>	
				<elseif condition="$now_order['group_pass']"  />
					<tr>
						<th width="15%">消费密码</th>
						<th width="85%" colspan="3">
						{pigcms{$now_order.group_pass}
						</th>
					</tr>	
				</if>
				<if condition="$trade_hotel_info">
					<tr>
						<td colspan="4" style="padding-left:5px;color:black;"><b>酒店订单详情：</b></td>
					</tr>
					<tr>
						<th width="15%">房间类型</th>
						<td width="85%" colspan="3">{pigcms{$trade_hotel_info.cat_pname} ({pigcms{$trade_hotel_info.cat_name})</td>
					</tr>
					<tr>
						<th width="15%">入住时间</th>
						<td width="35%">{pigcms{$trade_hotel_info.dep_time_txt}</td>
						<th width="15%">离店时间</th>
						<td width="35%">{pigcms{$trade_hotel_info.end_time_txt}</td>
					</tr>
					<tr>
						<th width="15%">房间数</th>
						<td width="35%">{pigcms{$trade_hotel_info.num}</td>
						<th width="15%">入住天数</th>
						<td width="35%">{pigcms{$trade_hotel_info['end_time']-$trade_hotel_info['dep_time']}天</td>
					</tr>
					<tr>
						<th width="15%">价格清单</th>
						<th width="70%" colspan="2">
							<form id="hotel" onsubmit="return false;">
						
							<volist name="trade_hotel_info['price_list_txt']" id="vo">
								{pigcms{$vo.day}：{pigcms{$vo.price} 元 * {pigcms{$trade_hotel_info.num} 
							
								&nbsp;&nbsp;可退{pigcms{$trade_hotel_info['num']-$now_order['refund_detail']['detail'][$vo['day_key']]['num']}间
								<button class="reduce" data-id="{pigcms{$i}">-</button>
								<input type="text" class="refund_num" id="refund_num_{pigcms{$i}" name="refund_num[{pigcms{$trade_hotel_info['price_tmp'][$key]}]" value="0"  size="1" style="margin-left:15px">
								<button class="plus" data-id="{pigcms{$i}" data-max_num="{pigcms{$trade_hotel_info['num']-$now_order['refund_detail']['detail'][$vo['day_key']]['num']}">+</button><br/>
							</volist>
							<input type="hidden" name="order_id" value="{pigcms{$now_order.order_id}">
							</form>
						</th>
						<th width="15%">
						
						<if condition="$now_order['refund_detail']['refund_time']">
							已退款积分：{pigcms{$now_order['refund_detail']['score']|round=###,2} 个<br>
							已退款商家余额：{pigcms{$now_order['refund_detail']['card_give_money']+$now_order['refund_detail']['detail']['merchant_balance']|round=###,2} 元<br>
							
							已退款平台余额：{pigcms{$now_order['refund_detail']['balance_pay']|round=###,2} 元<br>
							已退款在线支付：{pigcms{$now_order['refund_detail']['payment_money']|round=###,2} 元<br>
						</if>
						
						<span class="refund_div" style="display:none">退款天数<em class="refund_days" style="font-size:14px;color:orange;letter-spacing: 5px;">0</em>天，退款房间数<em class="refund_rooms" style="font-size:14px;color:orange;letter-spacing: 5px;">0</em>间<button id="refund">退款</button></span></th>
					</tr>
				</if>
				<if condition="$now_order['paid'] eq '1'">
					<tr>
						<td colspan="4" style="padding-left:5px;color:black;"><b>额外信息：</b></td>
					</tr>
					<tr>
						<th width="15%">订单标记</th>
						<td width="85%" colspan="3"><input type="text" class="input" id="merchant_remark" value="{pigcms{$now_order.merchant_remark}" style="width:400px;"/> <button id="merchant_remark_btn">修改</button></td>
					</tr>
				</if>
			</if>
		</table>
		<script type="text/javascript">
				<if condition="$now_order['paid'] eq 1 && $now_order['status'] eq 0">var fahuo=1;<else/>var fahuo=0;</if>
		</script>
		<script type="text/javascript">
			$(function(){
				$('#express_id_btn').click(function(){
					if(fahuo == 1){
						if(confirm("您确定要提交快递信息吗？提交后订单状态会修改为已发货。")){
							express_post();
						}
					}else{
						express_post();
					}
				});
				
				$('.plus').click(function(){
					var id  = $(this).data('id')
					var refund_num = $('#refund_num_'+id).val();
					refund_num = Number(refund_num)
					
					if(refund_num+1<=$(this).data('max_num')){
						 $('#refund_num_'+id).val(refund_num+1)
					}
					refund_show()
				});
				
				
				
				$('.reduce').click(function(){
					var id  = $(this).data('id')
					var refund_num = $('#refund_num_'+id).val();
					refund_num = Number(refund_num)
			
					if(refund_num-1>=0){
						 $('#refund_num_'+id).val(refund_num-1)
					}
					refund_show()
				});
				
				$('#refund').click(function(){
					console.log($('.refund_num').serialize())
					console.log($('.refund_num'))
					var verify_btn = $('#refund');
					verify_btn.html('退款中..');
					layer.confirm('是否退款?', {
						  btn: ['是','否'] //按钮
						}, function(){
							$.post('{pigcms{:U("hotel_refund")}',$('#hotel').serialize(),function(result){
								if(result.status == 1){
									window.location.href = window.location.href;
								}else{
									//verify_btn.html('验证消费');
									layer.msg('退款成功', {icon: 1});
								}
							});
						}, function(){
							verify_btn.html('退款');
							layer.msg('已取消退款', {icon: 2});
					});
					return false;
				});
				
				$('#pickup').click(function(){
			
					if(confirm("您确定用户已经到店取货了吗？请确保用户信息，支付信息正确，提交后订单状态会修改为已自取。")){
						$.post("{pigcms{:U('Store/group_pick',array('order_id'=>$now_order['order_id']))}",function(result){
							$('#merchant_remark_btn').html('提交中...').prop('disabled',false);
							alert(result.info);
							window.location.href = window.location.href;
							$('#pickup').attr('disabled','true');
						});
					}
					
				});
				$('#merchant_remark_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Store/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						$('#merchant_remark_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				
			});
			
			function express_post(){
				$('#express_id_btn').html('提交中...').prop('disabled',true);
				$.post("{pigcms{:U('Store/group_express',array('order_id'=>$now_order['order_id']))}",{express_type:$('#express_type').val(),express_id:$('#express_id').val()},function(result){
					if(result.status == 1){
						fahuo=0;
						window.location.href = window.location.href;
					}
					$('#express_id_btn').html('填写').prop('disabled',false);
					alert(result.info);
				});
			}
			
			function refund_show(){
				var refund_days = 0;
				var refund_rooms = 0;
				$.each($('.refund_num'),function(i,val){
					if($(val).val()>0){
						refund_days++;
					}
					refund_rooms+=Number($(val).val())
				})
				
				if(refund_days>0 && refund_rooms>0){
					$('.refund_div').show()
				}
			
				$('.refund_days').html(refund_days)
				$('.refund_rooms').html(refund_rooms)
			}
		</script>
	</body>
</html>