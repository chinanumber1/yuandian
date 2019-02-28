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
					<form enctype="multipart/form-data" action="{pigcms{:U('StorePay/go_pay')}" class="form-horizontal" method="post" id="form_add" autocomplete="off">
				
						
							<div class="form-group">
								<label class="col-sm-2"><label for="total_price">项目</label></label>
								{pigcms{$order_info.order_name}
								<span class="form_tips"></span>
							</div>
							<div class="form-group">
								<label class="col-sm-2"><label for="total_price">总价</label></label>
								{pigcms{$order_info.money|floatval}
								<span class="form_tips">元</span>
							</div>
							<div class="form-group">
								<label class="col-sm-2"><label for="total_price">订单总额</label></label>
								￥{pigcms{$order_info.money|floatval}
								<span class="form_tips"></span>
							</div>
							
						
						<div class="form-group">
						<label class="col-sm-2"><label for="total_price">选择支付方式</label></label>
							<ul class="imgradio" style="list-style:none">
								<volist name="pay_method" id="vo">
									<php>if($pay_offline || $key != 'offline'){</php>
									<li>
										<label>
											<input type="radio" name="pay_type" value="{pigcms{$key}" <if condition="$i eq 1">checked="checked"</if>>
											<img src="{pigcms{$static_public}images/pay/{pigcms{$key}.gif" width="112" height="32" alt="{pigcms{$vo.name}" title="{pigcms{$vo.name}"/>
										</label>
									</li>
									<php>}</php>
								</volist>
							</ul>
						</div>
						
						
						
						<input type="hidden" name="store_id" value="{pigcms{$store['store_id']}">
						<div class="clearfix form-actions">
								<input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
				    		<input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
			                <input id="J-order-pay-button" type="submit" class="btn btn-info"  name="commit" value="去付款"/><br/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
	
	$("form").submit(function() {
	   $("#J-order-pay-button").val("正在处理...");
	   $("#J-order-pay-button").attr("disabled", "disabled");
	});
	
	$('#sysmsg-error .close').click(function(){
		$('#sysmsg-error').remove();
	});
	
	$('.see_tmp_qrcode').click(function(){
		var qrcode_href = $(this).attr('href');
		art.dialog.open(qrcode_href+"&"+Math.random(),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('login_iframe_handle',iframe);
			},
			id: 'login_handle',
			title:'请使用微信扫描二维码',
			padding: 0,
			width: 430,
			height: 433,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
	});
	
	$('#deal-buy-form').submit(function(){			
		if($('input[name="pay_type"]:checked').val() == 'weixin' || $('input[name="pay_type"]:checked').val() == 'weifutong'){
			art.dialog({
				title: '提示信息',
				id: 'weixin_pay_tip',
				opacity:'0.4',
				lock:true,
				fixed: true,
				resize: false,
				content: '正在获取微信支付相关信息，请稍等...'
			});
			$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
				art.dialog.list['weixin_pay_tip'].close();			
				if(result.status == 1){
					orderid = result.orderid;
					art.dialog({
						title: '请使用微信扫码支付',
						id: 'weixin_pay_qrcode',
						width:'350px',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">请使用微信扫描二维码进行支付</p><p style="text-align:center;"><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_own_qrcode&qrCon='+result.info+'" style="width:240px;height:240px;"></p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"><input id="J-order-weixin-button" type="button" class="btn_btn btn-large btn-pay" value="已支付完成"/></p>',
						cancel: function(){
							$("#J-order-pay-button").val("去付款");
							$("#J-order-pay-button").removeAttr("disabled");
						},
					});
				}else{
					$("#J-order-pay-button").val("去付款");
					$("#J-order-pay-button").removeAttr("disabled");
					art.dialog({
						title: '错误提示：',
						id: 'weixin_pay_error',
						opacity:'0.4',
						lock:true,
						fixed: true,
						resize: false,
						content: result.info
					});
					
				}
			});
			return false;
		}
	});
	
	$('#J-order-weixin-button').live('click',function(){
		window.location.href="{pigcms{:U('Pay/weixin_back',array('order_type'=>$order_info['order_type']))}&order_id="+orderid+'&pay_type='+$('input[name="pay_type"]:checked').val();
	});

});






</script>
</html>
