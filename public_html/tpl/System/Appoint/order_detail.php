<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Appoint/order_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="appoint_id" value="{pigcms{$now_order.appoint_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息：</b></td>
			</tr>
			<tr>
				<th width="80">编号</th>
				<td><input type="text" readonly="value" class="input fl" name="order_id" id="order_id" value="{pigcms{$now_order.order_id}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
				<th width="80">用户昵称</th>
				<td><input type="text" readonly="value" class="input fl" name="nickname" id="nickname" value="{pigcms{$now_order.nickname}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<if condition="$now_order.orderid gt 0">
			<tr>
				<th width="80">流水号</th>
				<td colspan="3"><input type="text" readonly="value" class="input fl" name="order_id" id="order_id" value="appoint_{pigcms{$now_order.orderid}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			</if>
			<tr>
				<th width="80">用户手机</th>
				<td><input type="text" readonly="value" class="input fl" name="phone" id="phone" value="{pigcms{$now_order.phone}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
				<th width="80">服务名称</th>
				<td><input type="text" readonly="value" class="input fl" name="appoint_name" <if condition='$now_order["type"] eq 1'>value="{pigcms{$now_order.cat_name}"<else />value="{pigcms{$now_order.appoint_name}"</if> size="10" placeholder="" validate="maxlength:6,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">店铺名称</th>
				<td><input type="text" readonly="value" class="input fl" name="store_name" value="{pigcms{$now_order.store_name}" size="10" placeholder="" validate="maxlength:6,required:true" tips=""/></td>
				<th width="80">下单时间</th>
				<td><input type="text" readonly="value" class="input fl" name="order_time" value="{pigcms{$now_order.order_time|date='Y-m-d H:i:s',###}" size="10" placeholder="" validate="maxlength:6,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">预约日期</th>
				<td><input type="text" class="input fl" name="appoint_date" value="{pigcms{$now_order.appoint_date}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
				<th width="80">预约时间点</th>
				<td><input type="text" class="input fl" name="appoint_time" value="{pigcms{$now_order.appoint_time}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">定金金额</th>
				<td><input type="text" class="input fl" name="payment_money" value="{pigcms{$now_order.payment_money}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
				<th width="80">用户留言</th>
				<td><input type="text" class="input fl" name="content" value="{pigcms{$now_order.content}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">服务类型</th>
				<td>
					<if condition='$now_order["type"] eq 1'>
					<div class="show">平台自营</div>
					<else/>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['appoint_type'] eq 0">selected</if>"><span>到店</span><input type="radio" name="appoint_type" value="0" <if condition="$now_order['appoint_type'] eq 0">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_order['appoint_type'] eq 1">selected</if>"><span>上门</span><input type="radio" name="appoint_type" value="1"  <if condition="$now_order['appoint_type'] eq 1">checked="checked"</if> /></label></span>
					</if>
				</td>
				
				<th width="80">支付状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['paid'] eq 0">selected</if>"><span>未支付</span><input type="radio" name="paid" value="0" <if condition="$now_order['paid'] eq 0">checked="checked"</if>/></label></span>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['paid'] eq 1">selected</if>"><span>已支付</span><input type="radio" name="paid" value="1"  <if condition="$now_order['paid'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_order['paid'] eq 2">selected</if>"><span>已退款</span><input type="radio" name="paid" value="2"  <if condition="$now_order['paid'] eq 2">checked="checked"</if> /></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">服务状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_order['service_status'] eq 0">selected</if>"><span>未服务</span><input type="radio" name="service_status" value="0"  <if condition="$now_order['service_status'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="($now_order['service_status'] eq 1) || ($now_order['service_status'] eq 2)">selected</if>"><span>已服务</span><input type="radio" name="service_status" value="1"  <if condition="($now_order['service_status'] eq 1) || ($now_order['service_status'] eq 2)">checked="checked"</if> /></label></span>
				</td>
				<th width="80">验证店员</th>
				<td><input type="text" class="input fl" name="content" value="{pigcms{$now_order.last_staff}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
			</tr>
			
			<if condition='$now_order["is_del"]'>
				<tr>
					<th width="80">订单状态</th>
					<td>
						<span class="cb-enable"><label class="cb-enable <if condition="$now_order['is_del'] eq 0">selected</if>"><span>未取消</span><input type="radio" name="is_del" value="0"  <if condition="$now_order['is_del'] eq 0">checked="checked"</if> /></label></span>
						<span class="cb-enable"><label class="cb-enable <if condition="$now_order['is_del'] eq 1">selected</if>"><span>已取消【用户】【来源：PC端】</span><input type="radio" name="is_del" value="1"  <if condition="$now_order['is_del'] eq 1">checked="checked"</if> /></label></span>
						<span class="cb-enable"><label class="cb-enable <if condition="$now_order['is_del'] eq 2">selected</if>"><span>已取消【平台】</span><input type="radio" name="is_del" value="2"  <if condition="$now_order['is_del'] eq 2">checked="checked"</if> /></label></span>
						<span class="cb-enable"><label class="cb-enable <if condition="$now_order['is_del'] eq 3">selected</if>"><span>已取消【商家】</span><input type="radio" name="is_del" value="3"  <if condition="$now_order['is_del'] eq 3">checked="checked"</if> /></label></span>
						<span class="cb-enable"><label class="cb-enable <if condition="$now_order['is_del'] eq 4">selected</if>"><span>已取消【店员：{pigcms{$now_order['staff_name']}】</span><input type="radio" name="is_del" value="4"  <if condition="$now_order['is_del'] eq 4">checked="checked"</if> /></label></span>
						<span class="cb-enable"><label class="cb-enable <if condition="$now_order['is_del'] eq 5">selected</if>"><span>已取消【用户】【来源：WAP端】</span><input type="radio" name="is_del" value="4"  <if condition="$now_order['is_del'] eq 5">checked="checked"</if> /></label></span>
						</td>
					<th width="80">取消时间</th>
					<td><input type="text" class="input fl" name="del_time" <if condition="$now_order['del_time']">value="{pigcms{$now_order['del_time']|date='Y-m-d H:i:s',###}"</if> size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/></td>
				</tr>
			</if>
			<if condition="($now_order['paid'] eq '1')">
				<tr>
					<th width="15%">定金支付方式</th>
					<th width="85%" colspan="3">余额支付金额 ￥{pigcms{$now_order.balance_pay}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;实际支付金额 
					￥{pigcms{$now_order.pay_money}
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用商家会员卡余额 ￥{pigcms{$now_order['merchant_balance']+$now_order['card_give_money']}</th>
				</tr>
				<tr>
					<th width="15%">{pigcms{$config['score_name']}抵扣金额</th>
					<th width="35%" >{pigcms{$now_order.score_deducte}</th>
					<th width="15%">{pigcms{$config['score_name']}使用数量</th>
					<th width="35%" >{pigcms{$now_order.score_used_count}</th>
				</tr>
				<if condition="$system_coupon">
				<tr>
					<th width="15%">平台优惠券抵扣金额</th>
					<th width="85%" colspan="3"> {pigcms{$system_coupon.price}</th>
				</tr>
				<elseif condition="$card" />
				<tr>
					<th width="15%">商家优惠券抵扣金额</th>
					<th width="85%" colspan="3">{pigcms{$card.price}</th>
				</tr>
				</if>
			</if>
			
			<if condition="($now_order['paid'] eq '1') AND ($now_order['service_status'] eq '1')">
				<tr>
					<th width="15%">余额支付方式</th>
					<th width="85%" colspan="3">余额支付金额 ￥{pigcms{$now_order.product_balance_pay}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;实际支付金额 
					￥{pigcms{$now_order.product_real_balace_price}
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;使用商家会员卡余额 ￥{pigcms{$now_order['product_merchant_balance']+$now_order['product_card_give_money']}</th>
				</tr>
				<tr>
					<th width="15%">{pigcms{$config['score_name']}抵扣金额</th>
					<th width="35%" >{pigcms{$now_order.product_score_deducte}</th>
					<th width="15%">{pigcms{$config['score_name']}使用数量</th>
					<th width="35%" >{pigcms{$now_order.score_used_count}</th>
				</tr>
				<if condition="$system_coupon">
				<tr>
					<th width="15%">平台优惠券抵扣金额</th>
					<th width="85%" colspan="3"> {pigcms{$system_coupon.price}</th>
				</tr>
				<elseif condition="$card" />
				<tr>
					<th width="15%">商家优惠券抵扣金额</th>
					<th width="85%" colspan="3">{pigcms{$card.price}</th>
				</tr>
				</if>
			</if>
			
			
			<if condition='$now_order["product_detail"]'>
				<tr>
					<th width="15%">选择预约详情</th>
					<th width="85%" colspan="3"> 名称：{pigcms{$now_order["product_detail"]['name']}&nbsp;&nbsp;&nbsp;&nbsp;价格：￥{pigcms{$now_order["product_detail"]['price']}</th>
				</tr>
			</if>
			
			<if condition="$cue_list">
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>自定义填写项：</b></td>
				</tr>
				<volist name="cue_list" id="val">
					<if condition="$val['type'] eq 2">
						<tr>
							<th width="80">{pigcms{$val.name}</th>
							<td colspan="3">
								地址：{pigcms{$val.address}
								<!--{pigcms{$val.value}-->
							</td>
						</tr>
					<else/>
						<tr>
							<th width="80">{pigcms{$val.name}</th>
							<td colspan="3">{pigcms{$val.value}</td>
						</tr>
					</if>
				</volist>
			</if>
		</table>
	</form>
<include file="Public:footer"/>