<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
	</head>
	<body>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th>订单编号</th>
			<th colspan="2">{pigcms{$now_order['orderid']}</th>
		</tr>
		<tr>
			<th>订单总价</th>
			<th>￥{pigcms{$now_order['total_price']|floatval}</th>	
			<th rowspan="4" width="50%">
				<if condition="$offline_pay_list">
					<div>
						<volist name="offline_pay_list" id="vo">
							<button class="orderofflinepay" data-id="{pigcms{$vo.id}" style="margin-top:20px;">{pigcms{$vo.name}</button>
						</volist>
						<br/>
					</div>
				</if>
				<if condition="$orderprinter">
					<div style="text-align:center;margin-top:30px;">
						<button style="background:#4f99c6!important;text-shadow:none;color:white;padding:10px 13px;" id="orderprinter">打印订单</button>
					</div>
				</if>
				
			</th>
		</tr>
		<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">
		<tr>
			<th>元宝价格（只支持线上支付）</th>
			<th>￥{pigcms{$now_order['total_price']-$now_order['discount_price']|floatval}+{pigcms{$now_order.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</th>
		</tr>
		</if>
		<tr>
			<th>优惠价格</th>
			<th>￥{pigcms{$now_order['discount_price']|floatval}</th>
		</tr>
		<tr>
			<th>实付金额</th>
			<th>￥{pigcms{$now_order['price']|floatval}</th>
		</tr>
		<tr>
			<th width="15%">订单备注</th>
			<th width="35%">{pigcms{$now_order['desc']}</th>
		</tr>
		<tr>
			<th colspan="2" style="text-align:center;">
				<div id="qrcodeUserWeixinCodeBox">
					<h3 style="margin:20px 0;">扫用户付款码支付</h3>
					<div style="margin-top:60px;" id="qrcodeBox">
						<input type="text" style="height:30px;line-height:30px;padding-left:5px;" id="userQrcode"/>
						<button style="background:#4f99c6!important;text-shadow:none;color:white;" id="userQrcodeBtn">确认支付</button>
					</div>
					<div style="margin-top:60px;text-align:left;margin-left:30px;">
						<font style="color:red;">支持扫用户微信<if condition="$config['arrival_alipay_open'] OR $is_sub_alipay">、支付宝</if>付款码。</font></if><br/>
						建议使用扫码枪直接扫描得到值。<br/>如果扫码提示返回错误，可以关闭本页面重新创建订单。
					</div>
				</div>
			</th>
			<th style="text-align:center;">
				<h3 class="wxcode" style="margin:20px 0;">用户<if condition="$config['cash_pay_qrcode']">微信</if>扫码支付
				<span class="wxcode" id="changeToAppScan"style="margin-left:20px;font-size:12px;color:blue;cursor:pointer;font-weight:normal; <if condition="$config.pay_weixinapp_key neq '' AND $config.open_score_fenrun eq 0">display:none;</if>">APP扫码支付</span></h3>
				<h3 class="appcode" style="margin:20px 0;">APP扫码支付
				<span  class="appcode"  id="changeToWxScan" style="margin-left:20px;font-size:12px;color:blue;cursor:pointer;font-weight:normal;">用户<if condition="$config['cash_pay_qrcode']">微信</if>扫码支付</span></h3>
				<div>
					<if condition="$config['cash_pay_qrcode']">
						<img class="wxcode userCode" src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$now_order['order_id']+3600000000}" style="width:250px;height:250px;"/>
					<else/>
						<img class="wxcode userCode" src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_own_qrcode&qrCon={pigcms{:urlencode($config['site_url'].'/wap.php?c=My&a=store_order_before&order_id='.$now_order['order_id'])}" style="width:250px;height:250px;"/>
					</if>
					<img class="appcode" src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_own_qrcode&qrCon=store_{pigcms{$now_order.order_id}" style="width:250px;height:250px;display:none"/>
				</div>
			</th>
		</tr>
	</table>
	<script type="text/javascript">
		var canUseAlipay = <if condition="$config['arrival_alipay_open'] OR $is_sub_alipay">true<else/>false</if>;
	</script>
	<script type="text/javascript">
		var checkPayStatus = null;
		$(function(){
			if(checkApp()){
				$('#userQrcode').remove();
				$('#qrcodeBox').prepend('<input type="tel" style="height:30px;line-height:30px;padding-left:5px;" id="userQrcode"/>');
				if(parent.customDisplayCan){
					window.pigcmspackapp.custom_display_work('',$('.userCode').attr('src'));
				}
			}
			$('.appcode').hide();
			
			$('#changeToAppScan').click(function(){
				$('.appcode').show();
				$('.wxcode').hide();
			});
			
			$('#changeToWxScan').click(function(){
				$('.wxcode').show();
				$('.appcode').hide();
			});
			
			$('#userQrcode').focus();
			setInterval(function(){
				if(document.activeElement.id != 'userQrcode'){
					$('#userQrcode').focus();
				}
			},1000);
			var postPrintNow = false; 
			$('#orderprinter').click(function(){
				if(postPrintNow == true){
					parent.layer.alert('正在请求中，请稍等');
					return false;
				}
				postPrintNow = true;
				$.post("{pigcms{:U('store_arrival_print')}",{order_id:{pigcms{$now_order['order_id']}},function(){
					parent.layer.alert('已发送打印');
					postPrintNow = false;
				});
			});
			$('.orderofflinepay').click(function(){
				var tip_index = parent.layer.load(0, {shade: [0.5,'#fff']});
				$.post("{pigcms{:U('store_arrival_pay')}",{order_id:{pigcms{$now_order.order_id},'offline_pay':$(this).data('id')},function(result){
					parent.layer.close(tip_index);
					if(result.status == 1){
						clearInterval(checkPayStatus);
						parent.layer.alert('支付成功', function(index){
							window.top.location.reload();
						});
					}else{
						parent.layer.alert(result.info);
					}
				});
			});
			
			$('#userQrcodeBtn').click(function(){
				postUserQrcode();
			});
			$('#userQrcode').keyup(function(e){
				if(e.keyCode == 13){
					postUserQrcode();
				}
			});
			checkPayStatus = setInterval(function(){
				$.post("{pigcms{:U('store_arrival_check')}",{order_id:{pigcms{$now_order['order_id']}},function(result){
					if(result.status == 1){
						clearInterval(checkPayStatus);
						parent.layer.alert('支付成功', function(index){
							top.layer.load(0,{shade:0.3});
							top.location.reload();
						});
					}
				});
			},3000);
		});
		var postNow = false;
		function postUserQrcode(){
			$('#userQrcode').val($.trim($('#userQrcode').val()));
			if(postNow == true){
				parent.layer.alert('正在请求中，请稍等');
				return false;
			}
			var auth_code = $('#userQrcode').val();
			var reg = new RegExp("^[0-9]*$");
			if(!reg.test(auth_code)){
				parent.layer.alert('付款码仅允许输入数字');
				return false;
			}
			if(auth_code.length < 2){
				$('#userQrcode').focus();
				return false;
			}
			var nowPayMethod = '';			
			var codePrefix =  auth_code.substr(0,2);		
			if(codePrefix == '10' || codePrefix == '11' || codePrefix == '12' || codePrefix == '13' || codePrefix == '14' || codePrefix == '15'){
				nowPayMethod = 'weixin';
				if(auth_code.length != 18){
					parent.layer.alert('微信付款码（以10/11/12/13/14/15为前缀的18位数字）', function(index){
						$('#userQrcode').focus();
						parent.layer.close(index);
					});
					return false;
				}
			}else if(canUseAlipay == true && (codePrefix == '25' || codePrefix == '26' || codePrefix == '27' || codePrefix == '28' || codePrefix == '29' || codePrefix == '30')){
				nowPayMethod = 'alipay';
				if(auth_code.length < 16 || auth_code.length > 24){
					parent.layer.alert('支付宝付款码（以25/26/27/28/29/30为前缀的16-24位数字）', function(index){
						$('#userQrcode').focus();
						parent.layer.close(index);
					});
					return false;
				}
			}else{
				parent.layer.alert('请扫描微信'+(canUseAlipay ? '或支付宝' : '')+'的付款码');
				return false;
			}
			$('#userQrcodeBtn').html('请求中...');
			postNow = true;
			$.post("{pigcms{:U('store_arrival_pay')}",{order_id:{pigcms{$now_order.order_id},'auth_code':auth_code,'auth_type':nowPayMethod},function(result){
				if(result.status == 1){
					clearInterval(checkPayStatus);
					parent.layer.alert('支付成功', function(index){
						top.layer.load(0,{shade:0.3});
						top.location.reload();
					});
				}else{
					parent.layer.alert(result.info);
				}
				$('#userQrcodeBtn,#userAlipayQrcodeBtn').html('确认支付');
				postNow = false;
			});
		}
	</script>
	</body>
</html>