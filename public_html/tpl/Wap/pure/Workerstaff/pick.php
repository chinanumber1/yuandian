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
</head>
<body>
<section class="nav_end clr">
	<div class="Dgrab" id="Dgrab1">
		<if condition='$list'>
		<volist name="list" id="row">
			<section class="robbed supply_{pigcms{$row['supply_id']}" data-id="{pigcms{$row.supply_id}">
				<div class="Online c9 p10 f14">
					<span>订单编号: {pigcms{$row['order_id']}</span>
					<if condition='$row["appoint_type"] eq 1'><a class="cd f14 fr" href="{pigcms{:U('map',array('supply_id'=>$row['supply_id']))}">查看路线</a></if>
				</div>
				<div class="Title m10">
					<h2 class="f16 c3">{pigcms{$row['store_name']}</h2>
					<p class="f14 c9">下单时间：{pigcms{$row['order_time']}</p>
					<if condition='!empty($row["appoint_date"]) && !empty($row["appoint_time"])'><p class="f14 c9">预约时间：{pigcms{$row["appoint_date"]} {pigcms{$row["appoint_time"]}</p></if>
					
					<if condition='$row["product_id"] gt 0'>
						<p class="f14 c9">名称：{pigcms{$row['product_name']}</p>
						<p class="f14 c9">全价：{pigcms{$row['product_price']}</p>
					<else />
						<p class="f14 c9">名称：{pigcms{$row['appoint_name']}</p>
						<p class="f14 c9">全价：{pigcms{$row['appoint_price']}</p>
					</if>
				</div>
				<div class="Namelist p10 f14">
					<h2 class="f15 c3">{pigcms{$row['nickname']} <span class="c6">{pigcms{$row['phone']}</span></h2> 
					<if condition="$row['note']">
						<p>客户备注：</p>
							<volist name='row["note"]' id='note'>
								<p class="c9">&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$note}</p><br />
							</volist>
					</if>
				</div>
				<div class="sign_bottom">
					<a href="javascript:;" class="Pick" data-id="{pigcms{$row['supply_id']}">确认服务</a>
				</div>
			</section>
		</volist>
		<else />
		<!-- 空白图 -->
		<div class="psnone" style="margin-top:20%">
			<img src="{pigcms{$static_path}images/qdz_02.jpg">
		</div>
		<!-- 空白图 -->
		</if>
		<include file="menu"/>
	</div>
</section>

<script>
$(function(){
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	$(".nav_end .Dgrab").width($(window).width());

	var DeliverListUrl = "{pigcms{:U('pick')}";
	var mark = 0;

	function grab(e) {
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		$.post(DeliverListUrl, "supply_id="+supply_id, function(json){
			mark = 0;
			if (json.status) {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'服务完成',btn: ['确定'],end:function(){
                    var location_url = "{pigcms{:U('Workerstaff/finish')}";
                    var site_url = "{pigcms{$config['site_url']}";
                    var url = site_url + location_url;
                    window.location.href = url;
                }});
			} else {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){
                    window.location.href = window.location.href;
                }});
			}
			$(".supply_"+supply_id).remove();
		});
	}

	$(".Pick").bind("click", grab);
});
	
</script>
</body>
</html> 