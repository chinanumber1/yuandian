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
	<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	<script type="text/javascript">
		var location_url = "{pigcms{:U('ajaxFinish')}", del_url = "{pigcms{:U('del')}";
	</script>
	<script type="text/javascript" src="{pigcms{$static_path}js/worker_deliver_finish.js?v=210" charset="utf-8"></script>
</head>
<body>
<div class="Dgrab" id="container">
	<div class="scroller" id="scroller">
		<div class="pullDown" id="pullDown">
			<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
		</div>
		<div id="finish_list"></div>
		<div class="pullUp" id="pullUp">
			<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
		</div>
		
		<!-- 空白图 -->
		<div class="psnone" style=" padding-top:20%">
			<img src="{pigcms{$static_path}images/qdz_02.jpg">
		</div>
		<!-- 空白图 -->
		
	</div>

</div>
<script id="replyListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
<section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
	<div class="Online c9 p10 f14">
		<span>订单编号: {{ d.list[i].order_id }}</span>
	</div>
		<div class="Title m10">
			<h2 class="f16 c3">{{ d.list[i].store_name }}</h2>
			<p class="f14 c9">下单时间：{{ d.list[i].order_time }}</p>
			
			{{# if(d.list[i].product_id > 0 ){ }}
				<p class="f14 c9">名称：{{ d.list[i].product_name }}</p>
				<p class="f14 c9">全价：{{ d.list[i].product_price }}</p>
			{{# }else{ }}
				<p class="f14 c9">名称：{{ d.list[i].appoint_name }}</p>
				<p class="f14 c9">全价：{{ d.list[i].appoint_price }}</p>
			{{# } }}
		</div>
		<div class="Namelist p10 f14">
			<h2 class="f15 c3">{{ d.list[i].name }} <span class="c6">{{ d.list[i].phone }}</span></h2>
				{{# if(d.list[i].note) { }}
					{{# for(var j = 0, nlen = d.list[i]['note'].length; j < nlen; j++){ }}
						{{ d.list[i]['note'][j]}}<br />
					{{# } }}
				{{# } }}
		</div>
		<div class="sign_bottom">
			<a href="javascript:;" class="del" data-id="{{ d.list[i].supply_id }}">删除</a>
		</div>
</section>
{{# } }}
</script>
<include file="menu"/>
</section>
</body>
</html>