<!DOCTYPE html>
<html style="font-size: 20px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>快递代收列表</title>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/express_service_list.css"/>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<header class="mui-bar mui-bar-nav clear">
	    <a href="{pigcms{:U('House/village_my',array('village_id'=>$_GET['village_id']))}" class="mui-pull-left return_add"></a>
	    <h1 class="mui-title">快递代发列表</h1>
	    <a href="{pigcms{:U('express_send_add',array('village_id'=>$_GET['village_id']))}" class="mui-pull-right yi_parts"></a>
	</header>
	<div class="contanir">
		<div class="all_conent" >
			<volist name="send_list" id="vo">
				<div class="currency all_express">
					<p class="">快递公司 : {pigcms{$express_list[$vo['express']]}</p>
					<p class="">提交时间 : {pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</p>
					<p class="">物品重量 : {pigcms{$vo.weight}（Kg）</p>
					<p class="">代发费用 : {pigcms{$vo.send_price}（元）</p>
					<p class="">寄件信息 : {pigcms{$vo.send_uname} {pigcms{$vo.send_phone} {pigcms{$vo.send_city} {pigcms{$vo.collect_adress}</p>
					<p class="">收件信息 : {pigcms{$vo.collect_uname} {pigcms{$vo.collect_phone} {pigcms{$vo.collect_city} {pigcms{$vo.collect_adress}</p>
					<p class="">备注 : {pigcms{$vo.remarks}</p>
				</div>
			</volist>
		</div>
		<div class="all_yi" style="display: none;">
			<p>暂无快递信息</p>
		</div>
	</div>
</body>
</html>