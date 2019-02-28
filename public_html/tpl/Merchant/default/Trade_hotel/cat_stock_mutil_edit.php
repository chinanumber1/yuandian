<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
	</head>
	<body>
		
		<form method="post" action="{pigcms{:U('Trade_hotel/cat_stock_mutil_edit')}">
			<input type="hidden" name="cat_id" value="{pigcms{$cat_id}"/>
			<table>
				<tr>
					<th width="15%">开始时间</th>
					<td width="85%"><input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
						</td>
				</tr>
				<tr>
					<th width="15%">结束时间</th>
					<td width="85%"><input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/></td>
				</tr>
				<tr>
					<th width="15%">价格</th>
					<td width="85%"><input type="text" class="input-text" name="price" style="width:120px;"/></td>
				</tr>
				<tr>
					<th width="15%">优惠价格</th>
					<td width="85%"><input type="text" class="input-text" name="discount_price" style="width:120px;"/></td>
				</tr>
				<tr>
					<th width="15%">库存</th>
					<td width="85%"><input type="text" class="input-text" name="stock" style="width:120px;"/></td>
				</tr>
			</table>
			<div class="btn">
			<button type="submit">确定</button>
			<button type="reset">取消</button>
		</div>
		</form>
		
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