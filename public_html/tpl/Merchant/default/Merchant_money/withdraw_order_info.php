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
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="15%">提现编号</th>
			<td colspan="3" width="85%">{pigcms{$withdraw.id}</td>
		</tr>
		
		<tr>
			<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息</b></td>
		</tr>
		<tr>
			<th width="15%">提现金额</th>
			<td width="85%" colspan="3">{pigcms{$withdraw['old_money']/100}元</td>
		</tr>
		<tr>
			<th width="15%">提现手续费</th>
			<td width="85%" colspan="3"><php>echo floatval($withdraw['system_take']);</php>元</td>
		</tr>
		<tr>
			<th width="15%">实际提现金额</th>
			<td width="85%" colspan="3">{pigcms{$withdraw['money']/100}元</td>
		</tr>
		<tr>
			<th width="15%">订单状态</th>
			<td width="85%" colspan="3">
				<if condition="$withdraw['status'] eq 0">
					<font color="red">未审核</font>
				<elseif condition="$withdraw['status'] eq 1" />
					<font color="green">通过审核</font>
				<elseif condition="$withdraw['status'] eq 2" />
					<font color="red">被驳回</font>
				</if>
			</td>
		</tr>
		
		
		<tr>
			<th width="15%">提现时间</th>
			<td width="85%" colspan="3">{pigcms{$withdraw.withdraw_time|date='Y-m-d H:i:s',###}</td>
		
		</tr>
		
		<tr>
			<th width="15%">备注</th>
			<td width="85%" colspan="3">{pigcms{$withdraw.remark}</td>
		</tr>
		
		<tr>
			<th width="15%">商户ID</th>
			<td width="35%">{pigcms{$now_merchant.mer_id}</td>
			<th width="15%">商户名称</th>
			<td width="35%">{pigcms{$now_merchant.name}</td>
		</tr>
		<tr>
			<th width="15%">商户手机号</th>
			<td width="85%" colspan="3">{pigcms{$now_merchant.phone}</td>
			
		</tr>
		
	</table>

	</body>
</html>