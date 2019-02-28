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
			<if condition="$downloadUrl">
				<tr>
					<td width="100%" align="center">手机扫描二维码下载</td>
				</tr>
				<tr>
					<td width="100%" align="center">
						<img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_own_qrcode&qrCon={pigcms{:urlencode($downloadUrl)}" style="width:200px;"/>
					</td>
				</tr>
				<tr>
					<td width="100%" align="center"><a href="{pigcms{$downloadUrl}" target="_blank">下载到电脑</a></td>
				</tr>
			<else/>
				<tr>
					<td width="100%" align="center">{pigcms{$err_msg}</td>
				</tr>
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
		</script>
	</body>
</html>