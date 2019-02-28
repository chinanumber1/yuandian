<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="mainBox">
			<div class="rightMain">
				<div class="grid-view">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="form_add" autocomplete="off">
						<div class="form-group">
							<label class="col-sm-2"><label for="total_price">当前余额</label></label>
							{pigcms{$store.money|floatval}
							<span class="form_tips">元</span>
						</div>
						<div class="form-group">
							<label class="col-sm-2"><label for="total_price">充值金额</label></label>
							<input class="col-sm-4" size="10" name="money" id="total_price" type="text" value=""/>
							<span class="form_tips">元</span>
						</div>
						<input type="hidden" name="store_id" value="{pigcms{$store['store_id']}">
						<div class="clearfix form-actions">
							<div class="col-md-offset-3 col-md-9">
								<button class="btn btn-info" type="submit" id="submit_btn">
									生成订单
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
<script>
$(function(){
	$('#total_price').focus();

	
	if($('#total_price').val() != ''){
		$('#total_price').trigger('keyup');
	}
	$('#form_add').submit(function(){
		var total_price = parseFloat($('#total_price').val());
		if(total_price < 0){
			alert('请输入正确的订单金额');
			$('#total_price').focus();
			return false;
		}
		$('#submit_btn').html('生成中...').prop('disabled',true);
		$.post("{pigcms{:U('recharge')}",$('#form_add').serialize(),function(result){
			$('#submit_btn').html('生成订单').prop('disabled',false);
			if(result.status == 1){
				if (result.info === 'SUCCESS') {
					window.top.location.reload();
				
				}else {
    				parent.layer.open({
    				  type: 2,
    				  title: '支付订单',
    				  shadeClose: false,
    				  shade: 0.6,
    				  area: ['820px', '610px'],
    				  content: "{pigcms{:U('StorePay/check')}&type=strecharge&order_id="+result.info
    				});
				}
			}else{
				alert(result.info);
			}
		});
		return false;
	});
});

</script>
</html>