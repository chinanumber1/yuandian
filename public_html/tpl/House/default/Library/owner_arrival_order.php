<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<link rel="stylesheet" href="{pigcms{$static_path}layer/css/layui.css"  media="all">
		<script src="{pigcms{$static_path}layer/layer.js"></script>
	</head>
	<body>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		
		<tr>
			<th>订单详情</th>
			<th>{pigcms{$now_order.order_name}<if condition="$now_order.order_type eq 'property'">物业服务周期：{pigcms{$now_order['property_month_num']}个月 &nbsp;&nbsp;&nbsp;&nbsp;额外：<if condition='$now_order["diy_type"] gt 0'>{pigcms{$now_order["diy_content"]}<else />赠送{pigcms{$now_order["presented_property_month_num"]}个月</if><else />{pigcms{$pay_name[$now_order['order_type']]}</if>
			</th>
		</tr>
		
		<tr>
			<th>订单总价</th>
			<th>￥{pigcms{$now_order['money']|floatval}</th>
		</tr>
		<tr>
			<th>实付金额</th>
			<th>￥{pigcms{$now_order['money']|floatval}</th>
		</tr>
		<tr>
			<th  style="text-align:center;">
				<div>
					<input type="button" value="确认现金付款" onclick="chk_cash()" />
				</div>
			</th>
		
			<th style="text-align:center;">
				<h3 style="margin:20px 0;">用户微信扫码支付</h3>
				<div>
					<img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$now_order['order_id']+700000000}" style="width:250px;height:250px;"/>
				</div>
			</th>
		</tr>
	</table>
	<script type="text/javascript">
		var nowPayMethod = 'weixin';
		var checkPayStatus = null;
		$(function(){
			$('#changeToAlipay').click(function(){
				$('#qrcodeUserAlipayCodeBox').show();
				$('#qrcodeUserWeixinCodeBox').hide();
				nowPayMethod = 'alipay';
				$('#userAlipayQrcode').focus();
			});
			$('#changeToWeixin').click(function(){
				$('#qrcodeUserWeixinCodeBox').show();
				$('#qrcodeUserAlipayCodeBox').hide();
				nowPayMethod = 'weixin';
				$('#userQrcode').focus();
			});
			$('#userQrcode').focus();
			setInterval(function(){
				if(nowPayMethod == 'weixin'){
					if(document.activeElement.id != 'userQrcode'){
						$('#userQrcode').focus();
					}
				}else{
					if(document.activeElement.id != 'userAlipayQrcode'){
						$('#userAlipayQrcode').focus();
					}
				}
			},1000);
			var postPrintNow = false; 
			$('#orderprinter').click(function(){
				if(postPrintNow == true){
					alert('正在请求中，请稍等');
					return false;
				}
				postPrintNow = true;
				$.post("{pigcms{:U('store_arrival_print')}",{order_id:{pigcms{$now_order['order_id']}},function(){
					alert('已发送打印');
					postPrintNow = false;
				});
			});
			$('.orderofflinepay').click(function(){
				var tip_index = parent.layer.load(0, {shade: [0.5,'#fff']});
				$.post("{pigcms{:U('store_arrival_pay')}",{order_id:{pigcms{$now_order.order_id},'offline_pay':$(this).data('id')},function(result){
					parent.layer.close(tip_index);
					if(result.status == 1){
						clearInterval(checkPayStatus);
						alert('支付成功');
						window.top.location.reload();
					}else{
						alert(result.info);
					}
				});
			});
			
			$('#userQrcodeBtn').click(function(){
				postUserQrcode();
			});
			$('#userQrcode').keyup(function(e){
				if(e.keyCode == 32){
					$('#changeToAlipay').trigger('click');
					$('#userQrcode').val('');
				}else if(e.keyCode == 13){
					postUserQrcode();
				}
			});
			$('#userAlipayQrcode').keyup(function(e){
				if(e.keyCode == 32){
					$('#changeToWeixin').trigger('click');
					$('#userAlipayQrcode').val('');
				}else if(e.keyCode == 13){
					postUserQrcode();
				}
			});
			$('#userAlipayQrcodeBtn').click(function(){
				postUserQrcode();
			});
			checkPayStatus = setInterval(function(){
				$.post("{pigcms{:U('store_arrival_check')}",{order_id:{pigcms{$now_order['order_id']}},function(result){
					if(result.status == 1){
						layer.msg('支付成功', function(){
							window.parent.location.href = "{pigcms{:U('Unit/pay_order')}";
						});
					}
				},'json');
			},3000);
		});
		var postNow = false;
		function postUserQrcode(){
			if(nowPayMethod == 'weixin'){
				$('#userQrcode').val($.trim($('#userQrcode').val()));
				if($('#userQrcode').val() == ''){
					$('#userQrcode').focus();
					return false;
				}
			}else{
				$('#userAlipayQrcode').val($.trim($('#userAlipayQrcode').val()));
				if($('#userAlipayQrcode').val() == ''){
					$('#userAlipayQrcode').focus();
					return false;
				}
			}
			if(postNow == true){
				alert('正在请求中，请稍等');
				return false;
			}
			$('#userQrcodeBtn,#userAlipayQrcodeBtn').html('请求中...');
			postNow = true;
			var auth_code = nowPayMethod == 'weixin' ? $('#userQrcode').val() : $('#userAlipayQrcode').val();
			$.post("{pigcms{:U('store_arrival_pay')}",{order_id:{pigcms{$now_order.order_id},'auth_code':auth_code,'auth_type':nowPayMethod},function(result){
				if(result.status == 1){
					clearInterval(checkPayStatus);
					alert('支付成功');
					window.top.location.reload();
					// window.parent.location.href = "{pigcms{:U('store_arrival')}";
				}else{
					alert(result.info);
				}
				$('#userQrcodeBtn,#userAlipayQrcodeBtn').html('确认支付');
				postNow = false;
			});
		}
		
		
		
		function chk_cash(){
			if(confirm('是否确认提交')){
				location.href='{pigcms{:U("chk_cash")}&order_id={pigcms{$_GET["order_id"]}';
			}
			
		}
	</script>
	</body>
</html>