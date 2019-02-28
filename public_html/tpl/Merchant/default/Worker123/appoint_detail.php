<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>小猪{pigcms{$config.meal_alias_name}预约系统 - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
		<table>
			<tr>
				<th width="15%">订单编号</th>
				<td width="35%">{pigcms{$now_order.order_id}</td>
				<th width="15%">预约信息</th>
				<td width="35%">{pigcms{$now_order.appoint_name}</td>
			</tr>
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息：</b></td>
			</tr>
			<tr>
				<th width="15%">预约日期</th>
				<td width="35%">{pigcms{$now_order.appoint_date}</td>
				<th width="15%">预约时间点</th>
				<td width="35%"> {pigcms{$now_order.appoint_time}</td>
			</tr>
			<tr>
				<th width="15%">订单状态</th>
				<td width="35%">
					<?php if($now_order['paid'] == 0){ ?>
					   	<font color="red">未支付</font>
					   
						<?php if($now_order['service_status'] == 0) { ?>
					   		<font color="red">未服务</font>
					   		<?php if(($now_order['is_del'] == 0) && ($now_order['paid'] == 1)) {?><a href="{pigcms{:U('appoint_verify',array('order_id'=>$now_order['order_id']))}" class="group_verify_btn">验证服务</a><?php }?>
					   	
						<?php }elseif($now_order['service_status'] == 1) {?>
					   		<font color="green">已服务</font>
						<?php } ?>
					
					<?php }elseif($now_order['paid'] == 1){ ?>
						<font color="green">已支付</font>
						
						<?php if($now_order['service_status'] == 0) { ?>
					   		<font color="red">未服务</font>
					   		<?php if(($now_order['is_del'] == 0) && ($now_order['paid'] == 1)) {?><a href="{pigcms{:U('appoint_verify',array('order_id'=>$now_order['order_id']))}" class="group_verify_btn">验证服务</a><?php }?>
						<?php }elseif($now_order['service_status'] == 1){ ?>
					   		<font color="green">已服务</font>
						<?php }elseif($now_order['service_status'] == 2){ ?>
					   		<font color="green">已评价</font>
					   	<?php } ?>
					
					<?php }elseif($now_order['paid'] == 2){ ?>
						<font color="red">已退款</font>
				
					<?php } ?>
					
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
				
				<th width="15%">定金</th>
				<td width="35%">￥ {pigcms{$now_order.payment_money}</td>
			</tr>
			<tr>
				<th width="15%">下单时间</th>
				<td width="35%">{pigcms{$now_order.order_time|date='Y-m-d H:i:s',###}</td>
				<th width="15%">总价</th>
				<td width="35%">￥ {pigcms{$now_order.appoint_price}</td>
			</tr>
			<if condition="$now_order['paid']">
				<tr>
					<th width="15%">付款时间</th>
					<td width="35%"><if condition="$now_order['pay_time']">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}<else/>无</if></td>
					<th width="15%">支付方式</th>
					<td width="35%" colspan="3">{pigcms{$now_order.pay_type}</td>
				</tr>
			</if>
            
            <tr>
				<th width="15%">服务类型</th>
				<td width="35%">
					<if condition="$now_order['appoint_type'] eq 0"><span style="color:red">到店</span>
					<elseif condition="$now_order['appoint_type'] eq 1" /><span style="color:red">上门</span>
					</if>
				</td>
				<th width="15%">买家留言</th>
					<td width="35%">{pigcms{$now_order.content}</td>
			</tr>
			
			<if condition='$now_order["product_detail"]'>
				<tr>
					<th width="15%">选择预约详情</th>
					<th width="85%" colspan="3"> 名称：{pigcms{$now_order["product_detail"]['name']}&nbsp;&nbsp;&nbsp;&nbsp;价格：￥{pigcms{$now_order["product_detail"]['price']}</th>
				</tr>
			</if>
				<if condition="!empty($now_order['last_time'])">		
					<tr>
						<th width="15%">验证时间</th>
						<td width="35%">{pigcms{$now_order.last_time|date='Y-m-d H:i:s',###}</td>
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
						<th width="15%">使用商家会员卡余额</th>
						<td width="85%" colspan="3">{pigcms{$now_order.merchant_balance}</td>
					</tr>
				</if>
				<tr>
					<th width="15%">余额支付金额</th>
					<td width="35%">{pigcms{$now_order.balance_pay}</td>
					<th width="15%">实际支付金额</th>
					<td width="35%">{pigcms{$now_order.pay_money}</td>
				</tr>
				
				<if condition='$now_order["del_time"]'>
				<tr>
					<th width="15%">取消时间</th>
					<td width="85%" colspan="3">{pigcms{$now_order.del_time|date='Y-m-d H:i:s',###}</td>
				</tr>
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
									地址：{pigcms{$val.address}
									{pigcms{$val.value}
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
                
                
                 
           <if condition="$merchant_workers_info">
            <tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>服务工作人员信息：</b></td>
				</tr>
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
		</table>
	</body>
</html>