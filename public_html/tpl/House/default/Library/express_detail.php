<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 社区管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_path}layer/layer.js"></script>
	</head>
    <style type="text/css">
    .green{ color:green}
    </style>
	<body>
		<table>
        	<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>快递代收信息：</b></td>
			</tr>
			<tr>
				<th width="15%">快递类型</th>
				<td width="35%">{pigcms{$detail.express_name}</td>
				<th width="15%">快递单号</th>
				<td width="35%">{pigcms{$detail.express_no}</td>
			</tr>
            
            <tr>
				<th width="15%">收件人手机号</th>
				<td width="35%">{pigcms{$detail.phone}</td>
				<th width="15%">状态</th>
				<td width="35%">
                	<if condition='$detail["status"] eq 0'>
                        <span class="red">未取件</span>
                        <a href="javascript:void(0)" class="chk_express" data-id="{pigcms{$detail['id']}">确认取件</a>
                    <elseif condition='$detail["status"] eq 1'/>
                        <span class="green">已取件（业主）{pigcms{$detail.take_nickname}</span>
                    <else />
                        <span class="green">已取件（社区）</span>
                    </if>
                </td>
			</tr>
            <tr>
				<th width="15%">业主单元</th>
				<td width="85%" colspan="3">{pigcms{$detail.floor_name}</td>
			
			</tr>
            <tr>
				<th width="15%">预约代送时间</th>
				<td width="35%"><if condition='$detail["send_time"]'>{pigcms{$detail.send_time|date='Y-m-d H:i',###}</if></td>
				<th width="15%">取件时间</th>
				<td width="35%"><if condition='$detail["delivery_time"]'>{pigcms{$detail.delivery_time|date='Y-m-d H:i:s',###}</if></td>
			</tr>
			<tr>
				<th width="15%">代送费用</th>
				<td width="35%" style="color:red;"><if condition='$detail["money"] gt 0'>￥{pigcms{$detail.money|floatval}</if></td>
				<th width="15%">代送状态</th>
				<td width="35%"><if condition='$detail["send_status"]'>{pigcms{$detail.delivery_time|date='Y-m-d H:i:s',###}</if></td>
			</tr>
		
            <tr>
			
				<th width="15%">备注</th>
				<td width="85%" colspan="3">{pigcms{$detail.memo}</td>
				
			</tr>
			
			<if condition='$detail["order_info"]'>
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>快递代送信息：</b></td>
				</tr>
				<tr>
					<th width="15%">费用</th>
					<td width="35%">{pigcms{$detail["order_info"]['express_collection_price']}</td>
					<th width="15%">支付时间</th>
					<td width="35%"><php>if($detail["order_info"]['pay_time']>0){</php>{pigcms{$detail["order_info"]['pay_time']|date='Y-m-d H:i:s',###}<php>}</php></td>
				</tr>
			</if>
            
            <if condition='$_GET["flag"]'>
                <tr>
                    <td colspan="4"><button style=" margin:0 auto; display:block" onClick="history.go(-1);">返回</button></td>
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
				
				$('.chk_express').click(function(){
				var express_edit_url = "{pigcms{:U('express_edit')}";
				var id = "{pigcms{$detail.id}";
				var status = 2;
				layer.prompt({title: '输入取件码，并确认', formType: 3}, function(fetch_code, index){
					layer.close(index);
					$.post(express_edit_url,{'id':id,'status':status,'fetch_code':fetch_code},function(data){
						if(data['status']){
							alert(data['msg']);
							location.reload();
						}else{
							alert(data['msg']);
						}
					},'json')

				});
			});
			});
		</script>
	</body>
</html>