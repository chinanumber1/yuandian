<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
	<style>
		.frame_form td{line-height:24px;}
	</style>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="15%">订单编号</th>
			<td colspan="3" width="85%">{pigcms{$now_order.order_id}</td>
		</tr>
		<if condition="$now_order.orderid neq 0">
		<tr>
			<th width="15%">订单流水号</th>
			<td colspan="3" width="85%">{pigcms{$now_order.orderid}</td>
		</tr>
		</if>
		<tr>
			<th width="15%">分类商品</th>
			<td colspan="3" width="85%"><a href="{pigcms{$now_order.url}" target="_blank" title="查看商品详情">{pigcms{$now_order.order_name}</a></td>
		</tr>
		
		
		<tr>
			<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息</b></td>
		</tr>
		<tr>
			<th width="15%">订单状态</th>
			<td width="35%">
				<if condition="$now_order['paid'] eq 0">
					<font color="red">未付款</font>
				<elseif condition='($now_order["paid"] eq 1) AND ($now_order["status"] eq 1)' />
					<font color="green">已收货</font>
				<else />
					<font color="green">已付款</font>
				</if>
			</td>
		</tr>
		<tr>
			<th width="15%">数量</th>
			<td width="35%">{pigcms{$now_order.num}</td>
			<th width="15%">总价</th>
			<td width="35%">￥ {pigcms{$now_order.total_price}</td>
		</tr>
		<tr>
			<th width="15%">下单时间</th>
			<td width="35%">{pigcms{$now_order.order_time|date='Y-m-d H:i:s',###}</td>
			<if condition="$now_order['paid']">
				<th width="15%">付款时间</th>
				<td width="35%">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</td>
			<else/>
				<th width="15%"></th>
				<td width="35%"></td>
			</if>
		</tr>
		
		
		<if condition='$now_order["seller_user_info"]'>
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>卖家信息：</b></td>
			</tr>
			<tr>
				<th width="15%">卖家ID</th>
				<td width="35%">{pigcms{$now_order["seller_user_info"]['uid']}</td>
				<th width="15%">卖家昵称</th>
				<td width="35%">{pigcms{$now_order["seller_user_info"]['nickname']}</td>
			</tr>
			<tr>
				<th width="15%">卖家手机号</th>
				<td width="85%" colspan="3">{pigcms{$now_order["seller_user_info"]['phone']}</td>
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
		
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>

<include file="Public:footer"/