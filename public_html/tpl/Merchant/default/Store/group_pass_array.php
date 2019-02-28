<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>小猪{pigcms{$config.meal_alias_name}{pigcms{$config.group_alias_name}系统 - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
		<table>
			<tr>
				<th width="15%">订单编号</th>
				<td width="35%">{pigcms{$now_order.real_orderid}</td>
				<th width="15%">{pigcms{$config.group_alias_name}商品</th>
				<td width="35%"><a href="{pigcms{$now_order.url}" target="_blank" title="查看商品详情">{pigcms{$now_order.s_name}</a></td>
			</tr>
			<tr>
				<th colspan="3" >消费码详情</th>
				<th colspan="3" >操作&nbsp;<if condition="$now_order['status'] eq 0 AND $un_consume_num==$now_order['num']"><a href="{pigcms{:U('Store/group_verify',array('order_id'=>$now_order['order_id']))}" class="group_verify_btn">全部验证</a><elseif condition="($now_order['status'] eq 1) OR ($now_order['status'] eq 2)"/><font color="green">全部已消费</font></if></th>
			</tr>
			<volist name="pass_array" id="vo">
			<tr>
				<th colspan="3" >{pigcms{$vo.group_pass}</th>
				<td width="35%"><if condition="$vo.status eq 0" ><font color="red">未消费</font>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="group_array_verify({pigcms{$now_order.order_id},'{pigcms{$vo.group_pass}',this);" title="查看商品详情">验证消费</a><elseif condition="$vo.status eq 3" /><font color="red">还需支付：{pigcms{$vo.need_pay} 元</font>&nbsp;<a href="javascript:void(0);" onclick="group_array_verify({pigcms{$now_order.order_id},'{pigcms{$vo.group_pass}',this);" title="查看商品详情">验证付款</a><elseif condition="$vo.status eq 2" /><font color="red">已退款<elseif condition="$now_order.status eq 1 OR $now_order.status eq 2" /><font color="green">已消费</font><else /><font color="green">已消费</font></if></td>
			</tr>
			</volist>
			
		</table>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript">
			function group_array_verify(order_id,group_pass,val){
				  	$('a').removeAttr('onclick');
					$.post("{pigcms{:U('Store/group_array_verify')}",{order_id:order_id,group_pass:group_pass},function(result){
						window.location.href = window.location.href;
					});
					motify.log('验证成功!');
			}
			$(function(){
				<if condition="$now_order['paid'] eq 1 && $now_order['status'] eq 0">var fahuo=1;<else/>var fahuo=0;</if>
				$('#express_id_btn').click(function(){
					if(fahuo == 1){
						if(confirm("您确定要提交快递信息吗？提交后订单状态会修改为已发货。")){
							express_post();
						}
					}else{
						express_post();
					}
				});
				$('#pickup').click(function(){
				
					if(confirm("您确定用户已经到店取货了吗？请确保用户信息，支付信息正确，提交后订单状态会修改为已自取。")){
						$.post("{pigcms{:U('Store/group_pick',array('order_id'=>$now_order['order_id']))}",function(result){
							$('#merchant_remark_btn').html('提交中...').prop('disabled',false);
							alert(result.info);
							window.location.href = window.location.href;
							$('#pickup').attr('disabled','true');
						});
					}
					
				});
				$('#merchant_remark_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Store/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						$('#merchant_remark_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				function express_post(){
					$('#express_id_btn').html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Store/group_express',array('order_id'=>$now_order['order_id']))}",{express_type:$('#express_type').val(),express_id:$('#express_id').val()},function(result){
						if(result.status == 1){
							fahuo=0;
							window.location.href = window.location.href;
						}
						$('#express_id_btn').html('填写').prop('disabled',false);
						alert(result.info);
					});
				}
			});
		</script>
	</body>
</html>