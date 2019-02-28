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
			<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息</b></td>
		</tr>
		<tr>
			<th width="15%">订单类型</th>
			<td width="35%">充值</td>
			<th width="15%">订单状态</th>
			<td width="35%">
				<if condition="$now_order['paid']">
					<font color="green">已付款</font>
				<else/>
					<font color="red">未付款</font>
				</if>
				
			</td>
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
			<td colspan="4" style="padding-left:5px;color:black;"><b>用户信息：</b></td>
		</tr>
		<tr>
			<th width="15%">用户ID</th>
			<td width="35%">{pigcms{$now_order.uid}</td>
			<th width="15%">用户昵称</th>
			<td width="35%">{pigcms{$now_order.nickname}</td>
		</tr>
		<tr>
			<th width="15%">用户手机号</th>
			<td width="35%">{pigcms{$now_order.phone}</td>
	
		</tr>
		
		
		
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
	</script>
<include file="Public:footer"/