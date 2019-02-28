<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
		<table>
			<tr>
				<th width="15%">订单编号</th>
				<td width="35%">{pigcms{$now_order.real_orderid}</td>
				<th width="15%">{pigcms{$config.group_alias_name}商品</th>
				<td width="35%"><a href="{pigcms{$now_order.url}" target="_blank" title="查看商品详情">{pigcms{$now_order.s_name}</a></td>
			</tr>
			<if condition="$now_order.orderid neq 0">
			<tr>
				<th colspan="1" >流水号</th>
				<td colspan="3"><if condition="$now_order['pay_type'] neq 'baidu'">group_</if>{pigcms{$now_order.orderid}</td>
			</tr>
			</if>
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息</b></td>
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
						<if condition="$now_order['pay_type'] eq 'offline' AND empty($now_order['third_id'])" >
							<font color="red">线下支付　未付款</font>
						<elseif condition="$now_order['status'] eq '0'" />
							<font color="green">已付款</font>&nbsp;
							<if condition="$now_order['tuan_type'] neq '2'">
							<php>if($now_order['tuan_type'] != 2){</php>
								<font color="red">未消费</font>
							<php>}else{</php>
								<php>if($now_order['express_id'] != ''){</php>
									<font color="red">已发货</font>
								<php>}else{</php>
									<font color="red">未发货</font>
								<php>}</php>
							<php>}</php>
						<elseif condition="$now_order['status'] eq '1'"/>
							<php>if($now_order['tuan_type'] != 2){</php>
								<font color="green">已消费</font>
							<php>}else{</php>
								<php>if($vo['is_pick_in_store']){</php>
									<font color="green">已取货</font>
								<php>}else{</php>
									<font color="green">已发货</font>
								<php>}</php>
							<php>}</php>&nbsp;
							<font color="red">待评价</font>
						<elseif condition="$now_order['status'] eq 3"/>
							<font color="red">已退款</font>
						<elseif condition="$now_order['status'] eq 4"/>
							<font color="red">用户已取消</font>
						<else/>
							<font color="green">已完成</font>
						</if>
					<else/>
						<if condition="$now_order['status'] eq 4">
							<font color="red">已取消</font>
						<else />
							<font color="red">未付款</font>
						</if>
					</if>
				</td>
			</tr>
			<tr>
				<th width="15%">数量</th>
				<td width="35%">{pigcms{$now_order.num}</td>
				<th width="15%">总价</th>
				<td width="35%">￥ {pigcms{$now_order['total_money']} 元<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if></td>
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
			<if condition="$now_order.pay_type eq 'offline' AND empty($now_order['third_id'])">
		
			<tr>
				<th width="15%">线下还需支付</th>
				<td width="85%" colspan="3"><font color="red">￥ {pigcms{$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font></td>
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
						<th width="15%">收货地址</th>
						<td width="85%" colspan="3">{pigcms{$now_order.adress}</td>
					</tr>
					<php>if(empty($now_order['store_id'])&&empty($_GET['from'])){</php>
					<php>if(empty($pin_info) || $pin_info['status']==1 || $pin_info['status']==3){</php>
						<tr>
							<th width="15%">将订单归属于店铺：</th>
							<td width="85%" colspan="3">
								<select id="order_store_id">
									<volist name="group_store_list" id="vo">
										<option value="{pigcms{$vo.store_id}">{pigcms{$vo.name}</option>
									</volist>
								</select>
								<button id="store_id_btn">修改</button>
							</td>
						</tr>
						
						<php>}else{</php>
						<tr>
							<th width="15%">收货地址</th>
							<td width="85%" colspan="3" style="color:red">还未成团不能分配店铺</td>
						</tr>
						<php>}</php>
					<php>}</php>
				</if>
				<tr>
					<th width="15%">余额支付金额</th>
					<td width="35%">￥{pigcms{$now_order.balance_pay}</td>
					<th width="15%">在线支付金额</th>
					<td width="35%">￥{pigcms{$now_order.payment_money}</td>
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
					<th width="15%">{pigcms{$config.score_name}抵扣金额</th>
					<td width="35%">￥{pigcms{$now_order.score_deducte} 元</td>
					<th width="15%">{pigcms{$config.score_name}使用数量</th>
					<td width="35%">{pigcms{$now_order.score_used_count}</td>
				</tr>
				<if condition="$now_order['paid'] eq '1' AND $_GET['from'] neq 'bill'">
					<tr>
						<td colspan="4" style="padding-left:5px;color:black;"><b>额外信息：</b></td>
					</tr>
					<tr>
						<th width="15%">订单标记</th>
						<td width="85%" colspan="3"><input type="text" class="input" id="merchant_remark" value="{pigcms{$now_order.merchant_remark}" style="width:400px;"/> <button id="merchant_remark_btn">修改</button></td>
					</tr>
				</if>
				<if condition="$pass_array">
					<tr>
						<th width="15%">消费密码</th>
						<th width="85%" colspan="3">
							<volist name="pass_array" id="vo">
							{pigcms{$i}. {pigcms{$vo.group_pass}<br>
							</volist>
						</th>
					</tr>	
				<elseif condition="$now_order['group_pass']"   />
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
						<th width="85%" colspan="3">
							<volist name="trade_hotel_info['price_list_txt']" id="vo">
								{pigcms{$vo.day}：{pigcms{$vo.price} 元 * {pigcms{$trade_hotel_info.num}<br/>
							</volist>
						</th>
					</tr>
				</if>
			</if>
		</table>
		<script type="text/javascript">
			$(function(){
				$('#merchant_remark_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						$('#merchant_remark_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				$('#store_id_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/order_store_id',array('order_id'=>$now_order['order_id']))}",{store_id:$('#order_store_id').val()},function(result){
						$('#store_id_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
			});
		</script>
	</body>
</html>