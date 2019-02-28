<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 自提点管理</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Config/see_pick_pwd', array('pick_id' => $pick_address['pick_addr_id']))}" enctype="multipart/form-data">
		<input type="hidden" name="order_id" value="{pigcms{$order.order_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th>登录秘钥</th>
				<th>{pigcms{$pick_address['pwd']}</th>
				<th><button type="submit">更换秘钥</button></th>
			</tr>
		</table>
	</form>
	</body>
</html>