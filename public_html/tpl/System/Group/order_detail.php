<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
	<style>
		.frame_form td{line-height:24px;}
	</style>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
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
			<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息</b></td>
		</tr>
		<tr>
			<th width="15%">订单类型</th>
			<td width="35%"><if condition="$now_order['tuan_type'] eq '0'">{pigcms{$config.group_alias_name}券<elseif condition="$now_order['tuan_type'] eq '1'"/>代金券<else/>实物</if></td>
			<th width="15%">订单状态</th>
			<td width="35%">
				<if condition="$now_order['paid']">
					<if condition="$now_order['pay_type'] eq 'offline' AND empty($now_order['third_id'])" >
						<font color="red">线下支付&nbsp;未付款</font>
					<elseif condition="$now_order['status'] eq 0" />
						<font color="green">已付款</font>&nbsp;
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

					<elseif condition="$now_order['status'] eq 7"/>
				      <font color="red">退款失败，原因：{pigcms{$now_order['refund_detail']}</font>
					<elseif condition="$now_order['status'] eq 1"/>
					
						<php>if($now_order['tuan_type'] != 2){</php>
							<font color="green">已消费</font>
						<php>}else{</php>
							<php>if($now_order['is_pick_in_store']){</php>
									<font color="green">已取货</font>
								<php>}else{</php>
									<font color="green">已发货</font>
								<php>}</php>
						<php>}</php>&nbsp;
						<font color="red">待评价</font>
					<elseif condition="$now_order['status'] eq 2"/>
						<font color="green">已完成</font>
					<elseif condition="$now_order['status'] eq 3"/>
						<font color="red">已退款</font>
					<elseif condition="$now_order['status'] eq 4"/>
						<font color="red">已取消</font>
					</if>
				<else/>
					<if condition="$now_order['status'] eq 4">
						<font color="red">已取消</font>
					<else />
						<font color="red">未付款</font>
					</if>
				</if>
				<if condition="$now_order['status'] eq 0 AND $now_order['paid'] eq 1"><a href="javascript:void(0)" onclick="refund_confirm();"><font color="blue">更改状态为已退款</font></a></if>
				
				<if condition=" $now_order['status'] eq 0 AND $now_order['paid'] eq 0"><a href="javascript:void(0)" onclick="change_status();"><font color="blue">更改状态为已取消</font></a></if>
			</td>
		</tr>
		<tr>
			<th width="15%">数量</th>
			<td width="35%">{pigcms{$now_order.num}</td>
			<th width="15%">总价</th>
			<td width="35%">￥ {pigcms{$now_order.total_money}<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if></td>
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
				<td width="35%">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</td>
			<else/>
				<th width="15%"></th>
				<td width="35%"></td>
			</if>
		</tr>
		<tr>
			<th width="15%">消费密码</th>
			<td width="35%"><if condition="$now_order['group_pass']">{pigcms{$now_order.group_pass_txt}</if></td>
			<th width="15%">验证店员</th>
			<td width="35%">
				<if condition="$now_order['store_id']">
					<if condition="$now_order['store_name']">{pigcms{$now_order.store_name}<else/>店铺不存在</if>
					 (<if condition="$now_order['last_staff']">{pigcms{$now_order.last_staff}<else/>尚未验证</if>)
				<else/>
					尚未验证
				</if>
			</td>
		</tr>
		<tr>
			<th width="15%">买家留言</th>
			<td width="85%" colspan="3">{pigcms{$now_order.delivery_comment}</td>
		</tr>
		<if condition="$now_order['status'] eq 1">		
			<tr>
				<th width="15%"><if condition="$now_order['tuan_type'] neq 2">消费<else/>发货</if>时间</th>
				<td width="85%" colspan="3">{pigcms{$now_order.use_time|date='Y-m-d H:i:s',###}</td>
			</tr>
		</if>
		
		<tr>
			<td colspan="4" style="padding-left:5px;color:black;"><b>用户信息：</b></td>
		</tr>
		<tr>
			<th width="15%">用户ID</th>
			<td width="35%">{pigcms{$now_order.uid}</td>
			<th width="15%">用户昵称</th>
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
		</if>
		<if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'">
			<tr>
				<th width="15%">线下支付</th>
				<th width="85%" colspan="3">总金额￥{pigcms{$now_order['total_money']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;平台余额支付{pigcms{$now_order.balance_pay}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用商家会员卡余额 ￥{pigcms{$now_order['merchant_balance']} &nbsp;&nbsp;使用商家会员卡赠送余额 ￥{pigcms{$now_order['card_give_money']}<br>
				<if condition="$now_order['wx_cheap'] neq '0.00'">微信优惠 ￥{pigcms{$now_order['wx_cheap']}<br></if>
				<br>{pigcms{$config.score_name}抵扣金额 ￥{pigcms{$now_order.score_deducte}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$config.score_name}使用数量 {pigcms{$now_order.score_used_count}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<br>
					平台优惠券抵扣金额:￥ {pigcms{$now_order.coupon_price}
				
					商家优惠券抵扣金额:￥ {pigcms{$now_order.card_price}
					
					微信优惠 ：￥{pigcms{$now_order.wx_cheap}
					
					<br>线下需向商家付金额：<font color="red">￥{pigcms{$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font>
				</th>
			</tr>
		<else />
			<tr>
				<th width="15%">支付方式</th>
				<th width="85%" colspan="3">
				使用商家会员卡折扣 {pigcms{$now_order.card_discount} 折<br>
				余额支付金额 ￥{pigcms{$now_order.balance_pay}<br>
				在线支付金额 ￥{pigcms{$now_order.payment_money}<br>
				使用商家会员卡余额 ￥{pigcms{$now_order.merchant_balance}<br>
				使用商家会员卡赠送余额 ￥{pigcms{$now_order['card_give_money']}<br>
				{pigcms{$config.score_name}抵扣金额 ￥{pigcms{$now_order.score_deducte}<br>
				{pigcms{$config.score_name}使用数量 {pigcms{$now_order.score_used_count}<br>
			
					平台优惠券抵扣金额:￥ {pigcms{$now_order.coupon_price}		<br>
				
					商家优惠券抵扣金额:￥ {pigcms{$now_order.card_price}		<br>
					
					微信优惠 ：￥{pigcms{$now_order.wx_cheap}		<br>
					
				</th>
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
				<th width="85%" colspan="3">
					<volist name="trade_hotel_info['price_list_txt']" id="vo">
						{pigcms{$vo.day}：{pigcms{$vo.price} 元 * {pigcms{$trade_hotel_info.num}<br/>
					</volist>
				</th>
			</tr>
		</if>
		<if condition="$now_order.refund_detail.err_msg neq ''">
			<th width="15%">退款信息</th>
			<td width="85%" colspan="3">{pigcms{$now_order.refund_detail.err_msg}</td>
		</if>
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
	<script>
		function refund_confirm(){
			layer.confirm('确认后订单状态改为已退款，金额请手动退款给客户！', {
				btn: ['确定','取消'] //按钮
			}, function(){
				window.location.href='{pigcms{:U('Group/refund_update',array('order_id'=>$now_order['order_id']))}';
			});
			//
		}
		
		function change_status(){
			layer.confirm('确认后订单状态改为已取消！', {
				btn: ['确定','取消'] //按钮
			}, function(){
				window.location.href='{pigcms{:U('Group/refund_update',array('order_id'=>$now_order['order_id'],'status'=>4))}';
			});
			//
		}
	</script>
<include file="Public:footer"/