<include file="Public:header"/>
	<style>
		.frame_form td{line-height:24px;}
	</style>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<tr>
				<th width="15%">订单编号</th>
				<td width="35%">{pigcms{$now_order.order_id}</td>
				<th width="15%">{pigcms{$config.group_alias_name}商品</th>
				<td width="35%"><a href="{pigcms{$now_order.url}" target="_blank" title="查看商品详情">{pigcms{$now_order.s_name}</a></td>
			</tr>
		</tr>
		<tr>
			<th colspan="1" >流水号</th>
			<td colspan="3"><if condition="$now_order['pay_type'] neq 'baidu'">group_</if>{pigcms{$now_order.orderid}</td>
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
									<font color="red">未发货</font>
								<php>}</php>
						<php>}</php>
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
				<th width="85%" colspan="3">总金额￥{pigcms{$now_order['total_money']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;平台余额支付{pigcms{$now_order.balance_pay}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用商户余额 ￥{pigcms{$now_order.merchant_balance}<br>
				<if condition="$now_order['wx_cheap'] neq '0.00'">微信优惠 ￥{pigcms{$now_order['wx_cheap']}<br></if>
				<br>积分抵扣金额 ￥{pigcms{$now_order.score_deducte}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;积分使用数量 {pigcms{$now_order.score_used_count}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<if condition="$system_coupon">
					平台优惠券抵扣金额:￥ {pigcms{$system_coupon.price}
					<elseif condition="$card" />
					商家优惠券抵扣金额:￥ {pigcms{$card.price}
					</if>
					
					<br>线下需向商家付金额：<font color="red">￥{pigcms{$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font>
				</th>
			</tr>
		<else />
			<tr>
				<th width="15%">支付方式</th>
				<th width="85%" colspan="3">余额支付金额 ￥{pigcms{$now_order.balance_pay}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;在线支付金额 ￥{pigcms{$now_order.payment_money}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用商户余额 ￥{pigcms{$now_order.merchant_balance}
				<br>积分抵扣金额 ￥{pigcms{$now_order.score_deducte}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;积分使用数量 {pigcms{$now_order.score_used_count}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<if condition="$system_coupon">
					平台优惠券抵扣金额:￥ {pigcms{$system_coupon.price}
					<elseif condition="$card" />
					商家优惠券抵扣金额:￥ {pigcms{$card.price}
					</if>
				</th>
			</tr>	
		</if>
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
<include file="Public:footer"/