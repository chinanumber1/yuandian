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
				<td width="85%" colspan="3">{pigcms{$now_order.order_id}</td>
			</tr>
			<if condition="$now_order.orderid gt 0">
				<tr>
					<th width="15%">订单流水号</th>
					<td width="85%" colspan="3">{pigcms{$now_order.order_id}</td>
				</tr>
			</if>
			<tr>
				<th width="15%">服务名称</th>
				<td width="85%" colspan="3">{pigcms{$now_order.appoint_name}</td>
			</tr>
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息：</b></td>
			</tr>
			<tr>
				<th width="15%">用户名</th>
				<td width="35%">{pigcms{$now_order.nickname}</td>
				<th width="15%">手机号</th>
				<td width="35%">{pigcms{$now_order.phone}</td>
			</tr>
			<tr>
				<th width="15%">预约日期</th>
				<td width="35%">{pigcms{$now_order.appoint_date}</td>
				<th width="15%">预约时间点</th>
				<td width="35%">{pigcms{$now_order.appoint_time}</td>
			</tr>
			<tr>
				<th width="15%">下单时间</th>
				<td width="35%">{pigcms{$now_order.order_time|date='Y-m-d H:i:s',###}</td>
				<if condition="$now_order['payment_status']">
				<th width="15%">定金</th>
				<td width="35%"><if condition='$now_order["product_id"] gt 0'>￥ {pigcms{$now_order.product_payment_price}<else />￥ {pigcms{$now_order.payment_money}</if></td>
				</if>
			</tr>
			<tr>
				<th width="15%">服务类型</th>
				<td width="35%">
					<if condition="$now_order['appoint_type'] eq 0"><span style="color:red">到店</span>
					<elseif condition="$now_order['appoint_type'] eq 1" /><span style="color:red">上门</span>
					</if>
				</td>
				<th width="15%">总价</th>
				<td width="35%">
				
				<if condition="$now_order['paid'] eq 1 AND $now_order['service_status'] eq 0 AND $now_order.is_appoint_price eq 0">
					￥<input type="text" name="price" value="{pigcms{$now_order.product_price|floatval}" class="input" style="    width: 100px;">
				<else />
					<if condition="$now_order['product_price'] gt 0">
					￥{pigcms{$now_order.product_price|floatval}
					<else/>
					￥{pigcms{$now_order.appoint_price|floatval}
					</if>
				</if>  
				<if condition="$now_order['paid'] eq 1 AND $now_order['service_status'] eq 0 AND $now_order['is_appoint_price'] eq 0"><button style="float:right;display:inline" onclick="change_price(this);">修改价格</button></if>
				</td>
			</tr>
            <tr>
				<th width="15%">订单状态</th>
				<td width="35%">
					<if condition="$now_order['paid'] == 0" >
					   	<font color="red">未支付</font>
					<elseif condition="$now_order['paid'] == 1" />
						<font color="green">已支付</font>
					<elseif condition="$now_order['paid'] == 2" />
						<font color="red">已退款</font>
					</if>
					
					<if condition="$now_order['service_status'] == 0" >
						<font color="red">未服务</font>
						<if condition='($now_order["is_del"] eq 0) && ($now_order["paid"] eq 1) || (($now_order["is_del"] eq 0) && ($now_order["payment_status"] eq 0))'><a href="{pigcms{:U('appoint_verify',array('order_id'=>$now_order['order_id']))}" class="group_verify_btn">验证服务</a></if>
					<elseif condition="$now_order['service_status'] == 1" />
						<font color="green">已服务</font>
					<elseif condition="$now_order['service_status'] == 2" />
						<font color="green">已评价</font>
					</if>
					
					<if condition='$now_order["is_del"] neq 0'>
					&nbsp;|&nbsp;
				<font color="red">
					<switch name='now_order["is_del"]'>
						<case value="1">已取消【用户】【PC端】</case>
						<case value="2">已取消【平台】</case>
						<case value="3">已取消【商家】</case>
						<case value="4">已取消【店员】</case>
						<case value="5">已取消【用户】【WAP端】</case>
					</switch>
					</font>
					</if>
				</td>
				
				
				<!--th width="15%">定金</th>
				<td width="35%">￥ {pigcms{$now_order.payment_money}</td-->
			</tr>
			<if condition='$now_order["del_time"]'>
				<tr>
					<th width="15%">取消时间</th>
					<td width="85%" colspan="3">{pigcms{$now_order.del_time|date='Y-m-d H:i:s',###}</td>
				</tr>
			</if>
             <if condition='$now_order["product_detail"]'>
				<tr>
					<th width="15%">选择预约详情</th>
					<th width="85%" colspan="3"> 名称：{pigcms{$now_order["product_detail"]['name']}&nbsp;&nbsp;&nbsp;&nbsp;价格：￥{pigcms{$now_order["product_detail"]['price']}</th>
				</tr>
			</if>
			
			<tr>
				<th width="15%">买家留言</th>
				<td width="85%" colspan="3">{pigcms{$now_order.content}</td>
			</tr>
            
            
           <if condition="$merchant_workers_info">
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>服务工作人员信息：</b></td>
				</tr>
                
                <if condition='$now_order["store_id"]'>
					<tr>
						<th width="15%">店铺名称</th>
						<td width="85%" colspan="3">
							{pigcms{$now_order['store_name']}
						</td>
					</tr>
                </if>
                
            	<tr>
                    <th width="15%">服务工作人员</th>
                    <td width="35%">
                    	{pigcms{$merchant_workers_info['name']}
                    </td>
                    <th width="15%">联系电话</th>
                    <td width="35%">
                    {pigcms{$merchant_workers_info['mobile']}
                    </td>
                </tr>
            </if>
            
            
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
					<th width="15%">用户手机号</th>
					<td width="35%">{pigcms{$now_order.phone}</td>
					<th width="15%">使用商户余额</th>
						<td width="35%">会员卡余额:{pigcms{$now_order['merchant_balance']} + 会员卡赠送余额: {pigcms{$now_order['card_give_money']}</td>
				</tr>
				<tr>
					<th width="15%">余额支付金额</th>
					<td width="35%">
						<if condition='$now_order["is_initiative"] AND $now_order["service_status"]'>
							{pigcms{$now_order['balance_pay'] + $now_order['product_balance_pay']}
						<elseif condition='$now_order["is_initiative"] AND !$now_order["service_status"]' />
							{pigcms{$now_order['product_balance_pay']}
						<else />
							{pigcms{$now_order.balance_pay}
						</if>
					</td>
					<th width="15%">在线支付金额</th>
					<td width="35%">
					<if condition='$now_order["is_initiative"] AND $now_order["service_status"]'>
						{pigcms{$now_order['product_real_payment_price'] + $now_order['product_real_balace_price']|number_format=###,2}
					<elseif condition='$now_order["is_initiative"] AND !$now_order["service_status"]' />
						{pigcms{$now_order['product_real_payment_price']|number_format=###,2}
					<else />
						{pigcms{$now_order.pay_money|round=###,2}
					</if>
					</td>
				</tr>
				
				<tr>
					<if condition="$now_order['payment_status']">
					<th width="15%">定金{pigcms{$config['score_name']}抵现金额</th>
					<td width="35%">{pigcms{$now_order.score_deducte|number_format=###,2}</td>
					</if>
					<th width="15%">商品规格余额商家赠送余额</th>
					<td width="35%">{pigcms{$now_order.product_card_give_money}</td>
				</tr>
				
                 <if condition='($now_order["product_id"]) AND ($now_order["paid"] == 1) AND ($now_order["pay_time"] gt 0) AND $now_order["payment_status"]'>
					<tr>
						<th width="15%">商品规格实际支付定金</th>
						<td width="35%">{pigcms{$now_order['product_payment_price']}</td>
						<th width="15%">商品规格实际支付定金时间</th>
						<td width="35%">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</td>
					</tr>
				</if>
				<if condition='($now_order["service_status"] gt 0) AND $now_order["is_initiative"]'>
					<tr>
						<th width="15%">商品规格实际支付余额</th>
						<td width="35%">{pigcms{$now_order['user_pay_money'] + $now_order['product_balance_pay']|number_format=###,2}</td>
						<th width="15%">商品规格实际支付余额时间</th>
						<td width="35%">{pigcms{$now_order.user_pay_time|date='Y-m-d H:i:s',###}</td>
					</tr>
					
					<tr>
						<th width="15%">商品规格实际支付{pigcms{$config['score_name']}金额</th>
						<td width="35%">{pigcms{$now_order['product_score_deducte']|number_format=###,2}</td>
						<th width="15%">商品规格实际支付平台优惠券金额</th>
						<td width="35%">{pigcms{$now_order['product_coupon_price']}</td>
					</tr>
					
					<tr>
						<th width="15%">商品规格实际支付商家优惠券金额</th>
						<td width="85%" colspan="3">{pigcms{$now_order['product_card_price']}</td>
					</tr>
				</if>
			</if>
			
			
			<if condition="$cue_list">
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>自定义填写项：</b></td>
				</tr>
				<volist name="cue_list" id="val">
					<if condition="$val['type'] eq 2">
						<tr>
							<th width="15%">{pigcms{$val.name}</th>
							<td width="35%" colspan="3">
								地址：{pigcms{$val.address}	<!--{pigcms{$val.value}-->
							</td>
						</tr>
					<else/>
						<tr>
							<th width="15%">{pigcms{$val.name}</th>
							<td width="35%" colspan="3">{pigcms{$val.value}</td>
						</tr>
					</if>
				</volist>
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
			
			function change_price(obj){
				var price = $(obj).siblings('input[name="price"]').val();
				$.post('{pigcms{:U('change_order_price')}',{order_id:'{pigcms{$_GET['order_id']}',price:price},function(date){
					
					alert(date.msg)
					window.location.reload();
				},'json')
				
			}
		</script>
	</body>
</html>