<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style.css" />
	</head>
	<body>
		<div><a href="javascript:void(0);" onclick="history.go(-1);">返回</a></div>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="50">时间</th>
				<th>详情</th>
				<th>金额增加（元）</th>
				<th>金额减少（元）</th>
				<th>{pigcms{$config['score_name']}增加（分）</th>
				<th>{pigcms{$config['score_name']}减少（分）</th>
				<th>优惠券增加（元）</th>
				<th>优惠券减少（元）</th>
			</tr>
			<volist name="record" id="vo">
				<tr>
					<th width="50">{pigcms{$vo.time|date='Y-m-d H:i:s',###}</th>
					<th>{pigcms{$vo['desc']}</th>
					<th><font color="#2bb8aa"><if condition="$vo.money_add neq 0">+</if>{pigcms{$vo.money_add}</font></th>
					<th><font color="#f76120"><if condition="$vo.money_use neq 0">-</if>{pigcms{$vo.money_use}</font></th>
					<th><font color="#2bb8aa"><if condition="$vo.score_add neq 0">+</if>{pigcms{$vo.score_add}</font></th>
					<th><font color="#f76120"><if condition="$vo.score_use neq 0">-</if>{pigcms{$vo.score_use}</font></th>
					<th><font color="#2bb8aa"><if condition="$vo.coupon_add neq 0">+</if>{pigcms{$vo.coupon_add}</font></th>
					<th><font color="#f76120"><if condition="$vo.coupon_use neq 0">-</if>{pigcms{$vo.coupon_use}</font></th>
				</tr>
			</volist>
			<tr><td class="textcenter pagebar" colspan="8" style="border-bottom:1px solid #ccc;">{pigcms{$pagebar}</td></tr>
		</table>
	</body>
</html>