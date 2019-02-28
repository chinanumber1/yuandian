<!DOCTYPE html>
<html lang="zh-CN">
<head>
<title>在线支付</title>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta content="application/xhtml+xml;charset=UTF-8"	http-equiv="Content-Type" />
<meta content="no-cache,must-revalidate" http-equiv="Cache-Control" />
<meta content="no-cache" http-equiv="pragma" />
<meta content="0" http-equiv="expires" />
<meta content="telephone=no, address=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<style type="text/css">
html,body {margin:0;padding:0;height: 100%;}
.alipay{
	width:100%;
	height:100%;
}
p{
	margin-top:30%;
	text-align: center;
}
.title{
	width:70%;
	margin:0 auto;
	line-height:2;
	color: #666;
}
.browser_tip{
	width:100%;
}
.browser_tip img{
	width:100%;
}
</style>

</head>
<body style=" height: 100%;">
<div class="alipay">
	<div class="browser_tip"><img src="{pigcms{$static_path}images/browser_tip.png"/></div>
	<p><img src="{pigcms{$static_path}images/jinggao.png"></p>
	<div class="title">微信屏蔽了支付宝支付，请您点击右上角按钮，选择在浏览器中打开</div>
	<div class="title" style="position:fixed;bottom:40px;color:#ccc;left:15%;line-height:1.5;font-size:12px;">若您在浏览器中支付完成，请回到微信此页面，此页面会自动跳转</div>
</div>
<script>
	function ajax_get_paid(){
		var order_id = "{pigcms{$_GET['order_id']}";
		var type = "{pigcms{$_GET['type']}";
		$.post("{pigcms{:U('Alipay_jump/ajax_get_order_paid')}", {order_id: order_id,type:type}, function(data, textStatus, xhr) {
			if(data.status==1){
				window.location.href="{pigcms{:U('Pay/alipay_return')}&pay_type=alipay&out_trade_no="+type+'_'+data.info;
				//console.log("{pigcms{:U('Pay/alipay_return')}&out_trade_no="+type+'_'+data.info);
			}
		});
	}
	setInterval("ajax_get_paid()",500);
</script>
</body>
{pigcms{$shareScript}
</html>