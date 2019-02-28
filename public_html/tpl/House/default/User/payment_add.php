<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 社区管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
    <style type="text/css">
    .green{ color:green}
     .input-text::-webkit-input-placeholder {
            /*color: #b5b5b5;*/
            font-size: 6px;   
        }

    </style>
	<body>
		<form id="myform" action="{pigcms{:U('user_payment_add')}" method='post'>
			<table>
				<tr>
					<th width="15%">收费项目</th>
					<td width="85%">
						<select name="payment_id" id="payment">
							<option value="">——选择收费项——</option>
							<volist name="payment_list" id="vo">
								<option value="{pigcms{$vo.payment_id}">{pigcms{$vo.payment_name}</option>
							</volist>
						</select>
					</td>
				</tr>
				
				<script>
					$("#payment").on("change",function(){
						var payment_id = $("option:selected",this).val();
						var payment_standard_choice_url = "{pigcms{:U('payment_standard_choice')}";
						$.post(payment_standard_choice_url,{'payment_id':payment_id},function(data){
							// alert(data.data)
							$("#payment_standard").html(data.data);
						},'json');
					});
				</script>

				<tr>
					<th width="15%">收费项目</th>
					<td width="85%">
						<select name="standard_id" id="payment_standard">
							<option value="">——选择收费标准——</option>
						</select>
					</td>
				</tr>
				
				<tr id="pay_type" style="display: none;">
					<th width="15%" id="metering_mode"></th>
					<td width="85%">
						<input type="text" name="metering_mode_val" onkeyup="if(isNaN(value))execCommand('undo')" value="" style="line-height: 20px;" >
					</td>
				</tr>
				<input type="hidden" name="metering_mode_type" value="" id="metering_mode_type">
				<script>
					$("#payment_standard").on("change",function(){
						var payment_standard_id = $("option:selected",this).val();
						$("#metering_mode_type").val($("option:selected",this).data('metering_mode_type'));
						$("#max_cycle").val($("option:selected",this).data('max_cycle'));
						if($("option:selected",this).data('pay_type') == 2 && $("option:selected",this).data('metering_mode_type') == 2 ){
							$("#metering_mode").html($("option:selected",this).data('metering_mode'));
							$('#pay_type').css('display','');
						}else{
							$('#pay_type').css('display','none');
						}

					});
				</script>
				
				<tr>
					<th width="15%">计费开始时间</th>
					<td width="85%">
						<input style="height: 25px; " type="text" name="start_time" class="input-text" value="" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="不填写表示从今天开始计费"/>
						<font color="red">* 费用账单开始生成时间</font>
					</td>
				</tr>

				<tr>
					<th width="15%">计费结束周期</th>
					<td width="85%">
						<input style="height: 25px; font-size: 8px;" type="text" name="cycle_sum" id="cycle_sum" value="" placeholder="不填写表示按最大周期计算" />
						<font color="red">&nbsp;*超过结束周期，不再生成费用账单，业主也无需再缴纳费用</font>
					</td>
				</tr>

				<tr>
					<th width="15%">标准备注</th>
					<td width="85%">
						<input style="height: 25px; font-size: 8px;" type="text" name="remarks" value="" />
					</td>
				</tr>
				<input type="hidden" id="max_cycle" value="" />
				<input type="hidden" name="pigcms_id" value="{pigcms{$_GET['pigcms_id']}"/>
				<input type="hidden" name="uid" value="{pigcms{$_GET['uid']}"/>
				<tr>
					<td colspan="2"><button class="chk_express" style=" margin:0 auto; display:block;">保存</button></td>
				</tr>
			 
			</table>
		</form>
	</body>
	<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>

	<script>
	$(".chk_express").click(function(){

		if($("option:selected","#payment").val() == ''){
	        alert('请选择收费项！');
	        return false;
	    }

	    if($("option:selected","#payment_standard").val() == ''){
	        alert('请选择收费标准！');
	        return false;
	    }

	    var cycle_sum = parseInt($("#cycle_sum").val());
	    var max_cycle = parseInt($("#max_cycle").val());
	    if(cycle_sum > max_cycle){
	    	alert('您输入的周期超过最大周期了请重新填写！');
	    	return false;
	    }
	})

	</script>
</html>