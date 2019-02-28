<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
		<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Store/pick', array('order_id' => $order['order_id']))}" enctype="multipart/form-data">
		<input type="hidden" name="order_id" value="{pigcms{$order.order_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th>选择</th>
				<th>地址</th>
				<th>详细地址</th>
				<th>距离</th>
			</tr>
			<volist name="pick_addr" id="vo">
			<tr>
				<th width=""><input type="radio" name="pick_id" value="{pigcms{$vo.pick_addr_id}" <if condition="$vo['pick_addr_id'] eq $pick_order['pick_id']">checked</if>></th>
				<th>{pigcms{$vo['area_info']['province']}, {pigcms{$vo['area_info']['city']}, {pigcms{$vo['area_info']['area']}</th>
				<th>{pigcms{$vo['name']}</th>
				<th>{pigcms{$vo['range']}</th>
			</tr>
			</volist>
			<tr><td colspan="4"><button type="button" id="btn">提交</button></td></tr>
		</table>
	</form>
	</body>
	<script>
	$(document).ready(function(){
		var flag = false;
		$('#btn').click(function(){
			if (flag) return false;
			flag = true;
			
			var pick_id = $('input[name=pick_id]:checked').val();
			$.post("{pigcms{:U('Store/pick', array('order_id' => $order['order_id']))}", {pick_id:pick_id}, function(response){
				if (response.status == 1) {
					art.dialog.close();
					parent.location.reload();
				} else {
					alert(response.info);
					flag = false;
				}
			}, 'json');
		});
	});
	</script>
</html>