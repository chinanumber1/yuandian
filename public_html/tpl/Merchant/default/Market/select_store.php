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
	<th>选择</th>
	<th>店铺名称</th>
</tr>
<volist name="stores" id="vo">
<tr>
	<th><input type="radio" name="store_id" value="{pigcms{$vo['store_id']}" id="store_{pigcms{$vo['store_id']}"/></th>
	<th><label for="store_{pigcms{$vo['store_id']}">{pigcms{$vo['name']}</label></th>
</tr>
</volist>
<tr><td></td><td style="float:right"><button onClick="window.top.location.href='{pigcms{:U('Market/add_to_store', array('order_id' => $_GET['order_id']))}&store_id=' + $('input:radio:checked').val();">确认上架到该店铺</button></td></tr>
</table>
</body>
</html>