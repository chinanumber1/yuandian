<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>店员中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />
<style>
.green{color:green;}
.btn{
margin: 0;
text-align: center;
height: 2.2rem;
line-height: 2.2rem;
padding: 0 .32rem;
border-radius: .3rem;
color: #fff;
border: 0;
background-color: #FF658E;
font-size: .28rem;
vertical-align: middle;
box-sizing: border-box;
cursor: pointer;
-webkit-user-select: none;}
.totel{color: green;}
.cpbiaoge td{font-size:1rem;}
</style>
</head>
<body>

<div style="padding: 0.2rem;"> 
	
	<ul class="round">
		<form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/pick', array('order_id' => $order['order_id']))}">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<th>选择</th>
					<!--th>地址</th-->
					<th>详细地址</th>
					<th>距离</th>
				</tr>
				<volist name="pick_addr" id="vo">
				<tr>
					<label>
					<th width=""><input type="radio" name="pick_id" value="{pigcms{$vo.pick_addr_id}" <if condition="$vo['pick_addr_id'] eq $pick_order['pick_id']">checked</if>></th>
					<!--th>{pigcms{$vo['area_info']['province']}, {pigcms{$vo['area_info']['city']}, {pigcms{$vo['area_info']['area']}</th-->
					<th>{pigcms{$vo['name']}</th>
					<th>{pigcms{$vo['range']}</th>
					</label>
				</tr>
				</volist>
				<tr>
					<td colspan="3">
						<button type="submit" class="btn" style="width:5rem;font-size:1rem;">提交</button>
						<a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;position:absolute;width:5rem;font-size:1rem;">返 回</a>
					</td>
				</tr>
			</tbody>
		</table>
		</form>
	</ul>
</div>
<div class="footReturn">
	<div class="clr"></div>
	<div class="window" id="windowcenter">
		<div id="title" class="wtitle">操作成功<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>
</script>
<!---<include file="Storestaff:footer"/>--->