<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>技师系统</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/worker_deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script type="text/javascript">
		var location_url = "{pigcms{:U('grab')}", lat = "{pigcms{$store_session['lat']}", lng = "{pigcms{$store_session['lng']}";
	</script>
	<script src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/worker_grab.js?210" charset="utf-8"></script>
</head>
<body>
	<div class="Dgrab" id="container">
		<div class="scroller" id="scroller">
			<div id="grab_list"></div>
		</div>
		<!-- 空白图 -->
			<div class="psnone" style=" margin-top:20%">
				<img src="{pigcms{$static_path}images/qdz_02.jpg">
			</div>
		<!-- 空白图 -->
	</div>
	<include file="menu"/>
</body>
<script id="replyListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
<section class="robbed">
	<div class="Online c9 p10 f14">
		<span>订单编号: {{ d.list[i].order_id }}</span>
	</div>
	<div class="Title m10">
		<h2 class="f16 c3">{{ d.list[i].order_name }}</h2>
		{{# if(d.list[i].order_time) { }}<p class="f14 c9">下单时间：{{ d.list[i].order_time }}</p>{{# } }}
	</div>
	<div class="Namelist p10 f14">
		<h2 class="f15 c3">{{ d.list[i].nickname }} <span class="c6">{{ d.list[i].phone }}</span></h2>
		<h2 class="f15 c3">店铺信息：{{ d.list[i].store_name }} </h2>
		{{# if((d.list[i].appoint_date) && (d.list[i].appoint_time)) { }}<p class="c9">服务时间：{{ d.list[i].appoint_date }} {{ d.list[i].appoint_time }}</p>{{# } }}
		{{# if(d.list[i].deliver_cash != "0.00") { }}<p class="red">应收现金：<i>{{ d.list[i].deliver_cash }}</i>元</p>{{# } }}
		{{# if(d.list[i].prodcut_id > 0 ) { }}
			<p>名称：<i>{{ d.list[i].product_name }}</i></p>
			<p>全价：<i>{{ d.list[i].product_price }}
			</i></p>
		{{# }else{ }}
			<p>名称：<i>{{ d.list[i].appoint_name }}</i></p>
			<p>全价：<i>{{ d.list[i].appoint_price }}</i></p>
		{{# } }}
	</div>
	<div class="sign_bottom">
		<a href="javascript:void(0);" class="rob" data-supplyid="{{ d.list[i].supply_id }}">抢单</a>
	</div>
</section>
{{# } }}
</script>
</html>