<!DOCTYPE html>
 <html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>技师导航</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
</head>

<body class=" hIphone" style="padding-bottom: initial;background: #ecedf1;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}images/hm.gif" width="0" height="0" style="display:block">
<div id="wrapper" class="">
    <div id="fis_elm__2">
        <div id="common-widget-nav" class="common-widget-nav ">
            <div class="left-slogan"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="javaScript:history.back(-1);"></a> </div>
            <div class="center-title"> <a href="javascript:void(0)">技师导航</a> </div>
            <div class="right-slogan "> <a class="tel-btn icon-refresh-image" href="javascript:" id="refresh"></a> </div>
        </div>
    </div>
    <div id="fis_elm__4">
        <div id="map" class="order-widget-orderhistory" style="min-height:100px;">
        </div>
    </div>
</div>
<div class="global-mask layout"></div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>

<script>
var wHeight = $(window).height() - 50;
$("#map").css('height', wHeight);
var start = 0;
var end = 0;
$(function(){
    // 百度地图API功能
    var map = new BMap.Map("map");
	
    map.centerAndZoom(new BMap.Point({pigcms{$supply['store_long']}, {pigcms{$supply['store_lat']}), 15);
	
	var point = new BMap.Point(0,0);
	map.centerAndZoom(point,15);

	var geolocation = new BMap.Geolocation();
	var my_lng = 0;
	var my_lat = 0;
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			var mk = new BMap.Marker(r.point);
			map.addOverlay(mk);
			map.panTo(r.point);
			my_lng = r.point.lng;
			my_lat = r.point.lat;
		
		
		//我的图标
		var pt1 = new BMap.Point(my_lng, my_lat);
		var myIcon = new BMap.Icon("{pigcms{$static_path}images/map/my_pos.png", new BMap.Size(60,60));
		var marker1 = new BMap.Marker(pt1,{icon:myIcon});  // 创建标注
		map.addOverlay(marker1);
		//店铺图标
		var pt2 = new BMap.Point({pigcms{$supply['cue_field'][0]['long']}, {pigcms{$supply['cue_field'][0]['lat']});
		var storeIcon = new BMap.Icon("{pigcms{$static_path}images/map/deliver_pos.png", new BMap.Size(22,60));
		var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
		map.addOverlay(marker2);

		var walking = new BMap.WalkingRoute(map, {renderOptions:{map: map, autoViewport: true}});

		walking.search(pt1, pt2);
		}else {
			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true})
});

</script>

</body>
</html>