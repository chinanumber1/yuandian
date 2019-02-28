<!DOCTYPE html>
 <html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>{pigcms{$storeName.name}</title>
	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
	<meta name="description" content="{pigcms{$config.seo_description}" />
    <link href="{pigcms{$static_path}shop/css/order_detail.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
</head>

<body>
<section class="public">
    <a class="return link-url" href="javascript:window.history.go(-1);"></a>
    <div class="content">订单状态</div>
</section>
<div class="h44"></div>
<section class="g_details">
    <div class="orders_list">
        <ul>
            <volist name="status" id="vo">
            <li>
                <div class="time">{pigcms{$vo.dateline|date="Y-m-d H:i",###}</div>
                <div class="p18">
                    <div class="con">
                        <if condition="$vo['status'] eq 0"> <h2>订单生成成功</h2> <p>订单编号：{pigcms{$order.real_orderid}</p>
                        <elseif condition="$vo['status'] eq 1"/> <h2>订单支付成功</h2> <p>订单编号：{pigcms{$order.real_orderid}</p>
                        <elseif condition="$vo['status'] eq 2"/> <h2>店员接单</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正在为您准备商品</p>
                        <elseif condition="$vo['status'] eq 3"/> <h2><php>if ($vo['from_type'] == 2) {</php>更换配送员<php>}else{</php>配送员接单<php>}</php></h2> <p>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正在赶往店铺取货</p>
                        <elseif condition="$vo['status'] eq 4"/> <h2>配送员取货</h2> <p>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已取货，准备配送，请耐心等待</p>
                        <elseif condition="$vo['status'] eq 5"/> <h2>配送员配送中</h2> <p>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正快速向您靠拢，请耐心等待！</p>
                        <elseif condition="$vo['status'] eq 6"/> <h2>订单已完成</h2> <p><php>if($order['is_pick_in_store'] < 2){</php>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已完成配送，欢迎下次光临！<php>}else{</php>订单编号：{pigcms{$order.real_orderid}<php>}</php></p>
                        <elseif condition="$vo['status'] eq 7"/>
                            <php>if ($order['is_pick_in_store'] == 3) { </php><h2>店员已发货</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已发货给快递公司<strong style="color:red">【{pigcms{$order['express_name']}】</strong>，快递单号:<strong style="color:green">{pigcms{$order['express_number']}</strong></p>
                            <php>}else{</php><h2>店员验证消费</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>将订单改成已消费</p>
                            <php>}</php>
                        <elseif condition="$vo['status'] eq 8"/> <h2>完成评论</h2> <p>您已完成评论，谢谢您提出宝贵意见！</p>
                        <elseif condition="$vo['status'] eq 9"/> <h2>已完成退款</h2> <p>您已完成退款</p>
                        <elseif condition="$vo['status'] eq 10"/> <h2>已取消订单</h2> <p>您已经取消订单</p>
                        <elseif condition="$vo['status'] eq 11"/> <h2>商家分配自提点</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>给您分配</p>
                        <elseif condition="$vo['status'] eq 12"/> <h2>商家发货到自提点</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已经给您发货到配送点</p>
                        <elseif condition="$vo['status'] eq 13"/> <h2>自提点已接货</h2> <p>自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经接到您的货物了</p>
                        <elseif condition="$vo['status'] eq 14"/> <h2>自提点已发货</h2> <p>自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经给您发货了</p>
                        <elseif condition="$vo['status'] eq 15"/> <h2>您在自提点取货</h2> <p>您在自提点<php>if($vo['name']){</php><strong style="color:red">【{pigcms{$vo.name}】</strong><php>}</php> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经把您的货提走了！</p>
                        <elseif condition="$vo['status'] eq 30"/> <h2>店员为您修改了价格</h2> <p>店员<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已将订单的总价修改成{pigcms{$vo.note}</p>
                        </if>
                        <if condition="$i eq 1">
                        <div class="map clr">
                            <div class="pho" id="allmap"></div>
                            <div class="refresh fr"></div>
                        </div>
                        </if>
                    </div>
                </div>
            </li>
            </volist>
        </ul>
    </div>
</section>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript">
        var status = '{pigcms{$order['order_status']}';
        if (status > 1 && status < 5) {
            $(document).ready(function(){
                line();
                $('.refresh').click(function(){
                    line();
                });
            });
        } else {
            $('.map').remove();
        }

        function line()
        {
            $.get('{pigcms{:U('Shop/line', array('order_id' => $order['order_id']))}', function(response){
            if (response.err_code == false) {
                var lines = response.lines, center = response.center, points = response.points;
                var map = new google.maps.Map(document.getElementById('allmap'), {
                    mapTypeControl:false,
                    zoom: 13,
                    center: {lng:parseFloat(center.lng),lat:parseFloat(center.lat)}
                });
                var point = {lng:parseFloat(center.lng), lat:parseFloat(center.lat)};
                var from_point = {lng:parseFloat(points.from_site.lng), lat:parseFloat(points.from_site.lat)};
                var aim_point ={lng:parseFloat(points.aim_site.lng), lat:parseFloat(points.aim_site.lat)};

                var deliverIcon = new google.maps.Marker({
                    position: point,
                    map:map,
                    icon:  {url:'{pigcms{$static_path}/images/map/deliver_pos.png', anchor: new google.maps.Point(16, 16)}
                });

                var storeIcon = new google.maps.Marker({
                    position: from_point,
                    map:map,
                    icon:  {url:'{pigcms{$static_path}/images/map/store_pos.png', anchor: new google.maps.Point(16, 16)}
                });

                var userIcon =  new google.maps.Marker({
                    position: aim_point,
                    map:map,
                    icon:  {url:'{pigcms{$static_path}/images/map/my_pos.png', anchor: new google.maps.Point(32, 32)}
                });

                if (lines != null) {
                    var polygonArr = [];
                    for (var i in lines) {
                        polygonArr.push({lng:lines[i].lng, lat:lines[i].lat});
                    }
                    var flightPath = new google.maps.Polyline({
                        path: polygonArr,
                        geodesic: true,
                        strokeColor: 'green',
                        strokeOpacity: 0.5,
                        strokeWeight: 2
                    });

                    flightPath.setMap(map);

                    if (response.status < 4) {
                        var flightPath = new google.maps.Polyline({
                            path:[point, from_point],
                            geodesic: true,
                            strokeColor: 'red',
                            strokeOpacity: 0.5,
                            strokeWeight: 2
                        });
                        flightPath.setMap(map);
                    } else {
                        var flightPath = new google.maps.Polyline({
                            path:[point, aim_point],
                            geodesic: true,
                            strokeColor: 'red',
                            strokeOpacity: 0.5,
                            strokeWeight: 2
                        });
                        flightPath.setMap(map);

                    }
                } else if (response.status < 4) {
                    var flightPath = new google.maps.Polyline({
                        path:[point, from_point, aim_point],
                        geodesic: true,
                        strokeColor: 'red',
                        strokeOpacity: 0.5,
                        strokeWeight: 2
                    });
                    flightPath.setMap(map);
                } else {
                    var flightPath = new google.maps.Polyline({
                        path:[from_point, point, aim_point],
                        geodesic: true,
                        strokeColor: 'red',
                        strokeOpacity: 0.5,
                        strokeWeight: 2
                    });
                    flightPath.setMap(map);
                }
            }
        }, 'json');
        }
    </script>
    <else />
<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
<script type="text/javascript">
var status = '{pigcms{$order['order_status']}';
if (status > 1 && status < 5) {
    $(document).ready(function(){
    	line();
    	$('.refresh').click(function(){
    		line();
        });
    });
} else {
	$('.map').remove();
}

function line()
{
	$.get('{pigcms{:U('Shop/line', array('order_id' => $order['order_id']))}', function(response){
		if (response.err_code == false) {
			var lines = response.lines, center = response.center, points = response.points;
			var map = new BMap.Map("allmap", {"enableMapClick":false});
			var point = new BMap.Point(center.lng, center.lat);
	        var from_point = new BMap.Point(points.from_site.lng, points.from_site.lat);
	        var aim_point = new BMap.Point(points.aim_site.lng, points.aim_site.lat);
	        map.centerAndZoom(point, 13);
	        map.enableScrollWheelZoom();

	        var deliverIcon = new BMap.Icon("{pigcms{$static_path}/images/map/deliver_pos.png", new BMap.Size(22,60));
	        map.addOverlay(new BMap.Marker(point, {icon:deliverIcon}));

	        var storeIcon = new BMap.Icon("{pigcms{$static_path}/images/map/store_pos.png", new BMap.Size(22,60));
	        map.addOverlay(new BMap.Marker(from_point, {icon:storeIcon}));
	        var userIcon = new BMap.Icon("{pigcms{$static_path}/images/map/my_pos.png", new BMap.Size(60,60));
	        map.addOverlay(new BMap.Marker(aim_point, {icon:userIcon}));

	        if (lines != null) {
	        	var polygonArr = [];
	        	for (var i in lines) {
	    			polygonArr.push(new BMap.Point(lines[i].lng, lines[i].lat));
	        	}
	        	var polyline = new BMap.Polyline(polygonArr, {strokeColor:"green", strokeWeight:2, strokeOpacity:0.5}); //创建折线
	        	map.addOverlay(polyline); //增加折线
	        }

	        var polyline = new BMap.Polyline([point, aim_point], {strokeColor:"red", strokeWeight:2, strokeOpacity:0.5, strokeStyle: 'dashed'}); //创建折线
	        map.addOverlay(polyline);
		}
	}, 'json');
}
</script>
</if>
</body>
</html>