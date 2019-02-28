<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>订单详情</title>
  	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/order_details.css"/>

</head>
<body>
	<header>
		<a href="JavaScript:history.back(-1)"><i></i></a>
		<span>订单详情</span>
	</header>
	<div class="content">
		<!--订单状态-->
		<div class="order_status">
			<p class="active"></p>
			<ul>
				<if condition="$orderInfo.status eq 1">
					<li>待支付</li>
				<elseif condition="$orderInfo.status eq 2"/>
					<li>已支付待服务</li>
				<elseif condition="$orderInfo.status eq 3"/>
					<li>已服务</li>
				<elseif condition="$orderInfo.status eq 4"/>
					<li>订单完成</li>
				<elseif condition="$orderInfo.status eq 5"/>
					<li>完成已评价</li>
				<elseif condition="$orderInfo.status eq 6"/>
					<li>已关闭</li>
				</if>
				<!-- <li>订单关闭</li> -->
				<!-- <li><span>订单时,已取消,钱款将自动退回</span></li> -->
			</ul>
		</div>
		<!--地址-->
		<!--<div class="adress">
			<img src="imanges/a6.png"/>
			<ul>
				<li>杨明 13677885599</li>
				<li>安徽省黄山市黄山区汤口镇南湖路12号</li>
			</ul>
		</div>-->
		<!--时间-->
		<div class="time">
			<img src="{pigcms{$static_path}yuedan/imanges/t1.png"/>
			<ul>
				<li>{pigcms{$orderInfo.add_time|date="Y年m月d日 H:i",###}</li>
			</ul>
		</div>


		<!--信息-->
		<div class="order_xin">
			<p><img src="{pigcms{$userInfo.avatar}"/><span>{pigcms{$userInfo.nickname}</span></p>
			<ul>
				<li>
					<!-- <a href="" style="margin-right: 15px;"><img  src="{pigcms{$static_path}yuedan/imanges/d1.png"/> </a> -->
					<a href="{pigcms{:U('Yuedan/message',array('order_id'=>$_GET['order_id']))}"><img src="{pigcms{$static_path}yuedan/imanges/j1.png"/></a>
				</li>
			</ul>
		</div>
		
		<div class="beizhu">
			<span>备注:</span>
			<p>
				{pigcms{$orderInfo.remarks}&nbsp;
			</p>
		</div>


		<!--购买信息-->
		<a href="{pigcms{:U('Yuedan/service_detail',array('rid'=>$orderInfo['rid']))}" class="buy" style="margin-top: 15px;">
			<img src="{pigcms{$releaseInfo.listimg}"/>
			<ul>
				<li>{pigcms{$orderInfo.title}</li>
				<li><span>{pigcms{$orderInfo.price}/{pigcms{$releaseInfo.unit}</span></li>
				<li>×{pigcms{$orderInfo.sum}</li>
			</ul>
		</a>
		<!--合计-->
		<p class="heji">合计: <span>{pigcms{$orderInfo.total_price}元</span></p>
		<!--删除订单-->
		<!-- <p class="delate"><a href="javascript:;">删除订单</a></p> -->
		<!--创建时间单号-->
		<!--<ul class="new_time">
			<li><span>创建时间</span> 2017年03月28日 14::07</li>
			<li><span>订单号</span> 1649841263134324849498492</li>
		</ul>-->


	</div>
<script type="text/javascript" charset="utf-8">

</script>
</body>
</html>