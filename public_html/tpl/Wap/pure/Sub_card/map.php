<!DOCTYPE html>
 <html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>路线导航</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
<style type="text/css">
body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
	<link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />
<title>根据起终点经纬度驾车导航</title>
</head>
<body>
<div id="allmap"></div>
</body>
</html>
<script type="text/javascript">

// 百度地图API功能
var map = new BMap.Map("allmap");
<if condition="$_GET['point']">
var point = new BMap.Point({pigcms{$_GET['origin_long']},{pigcms{$_GET['origin_lat']});
	map.centerAndZoom(point,15);

			var marker = new BMap.Marker(point);  // 创建标注
			map.addOverlay(marker);              // 将标注添加到地图中
			map.panTo(point);      
		map.enableScrollWheelZoom(true);
		
		// var searchInfoWindow1 = new BMapLib.SearchInfoWindow(map, "", {
		// title: "到这里去", //标题
		// panel : "panel", //检索结果面板
		// enableAutoPan : true, //自动平移
		// searchTypes :[
			// BMAPLIB_TAB_TO_HERE,
			// BMAPLIB_TAB_FROM_HERE, //从这里出发
			// BMAPLIB_TAB_SEARCH   //周边检索
		// ]
	// });
	// searchInfoWindow1.open(marker);
	// function openInfoWindow1() {
		// searchInfoWindow1.open(new BMap.Point(116.319852,40.057031));
	// }
	
<else />
//map.centerAndZoom(new BMap.Point({pigcms{$_GET['origin_lat']},{pigcms{$_GET['origin_long']}), 15);

var p1 = new BMap.Point({pigcms{$_GET['origin_long']},{pigcms{$_GET['origin_lat']});
var p2 = new BMap.Point({pigcms{$_GET['scenic_long']},{pigcms{$_GET['scenic_lat']});

var driving = new BMap.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});
driving.search(p1, p2);
</if>
</script>