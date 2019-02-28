<include file="Public:header"/>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th colspan="1">商品编号</th>
		<th colspan="3">{pigcms{$order['id']}</th>
	</tr>
	<tr>
		<th colspan="1">活动名称</th>
		<th colspan="3">{pigcms{$order['name']}</th>
	</tr>
	<tr>
		<th colspan="1">活动标题</th>
		<th colspan="3">{pigcms{$order['title']}</th>
	</tr>
	<tr>
		<th colspan="1">优惠券码</th>
		<th colspan="3">{pigcms{$order['number']}</th>
	</tr>
	
	
	<tr>
		<th colspan="4">使用优惠券客户姓名：{pigcms{$order['nickname']}</th>
	</tr>
	
	<tr>
		<th colspan="4">使用优惠券客户手机：{pigcms{$order['phone']}</th>
	</tr>
	
	<tr>
		<th colspan="4">验证时间：{pigcms{$order['check_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	<tr>
		<th colspan="4">验证店员：{pigcms{$order.last_staff} </th>
	</tr>
	
	

</table>
<include file="Public:footer"/>