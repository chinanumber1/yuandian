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
body, html{width: 100%;height: 100%;margin:0;font-family:"微软雅黑"}
		#l-map{height:300px;width:100%;}
		#r-result,#r-result table{width:100%;font-size:12px;}
		.method{position:fixed;z-index:99}
</style>

<title>根据起终点经纬度驾车导航</title>
</head>
<body>
<div class="method">
<a  href="{pigcms{:U('My/map')}&origin_lat={pigcms{$_GET['origin_lat']}&origin_long={pigcms{$_GET['origin_long']}&end_lat={pigcms{$_GET['end_lat']}&end_long={pigcms{$_GET['end_long']}&drive=1"><button id="drive" <if condition="$_GET['drive']">disabled="disabled"</if>>驾车</button></a>
<a  href="{pigcms{:U('My/map')}&origin_lat={pigcms{$_GET['origin_lat']}&origin_long={pigcms{$_GET['origin_long']}&end_lat={pigcms{$_GET['end_lat']}&end_long={pigcms{$_GET['end_long']}"><button id="transit" <if condition="empty($_GET['drive'])">disabled="disabled"</if>>公交</button></a>
</div>
<div id="l-map"></div>
<div id="r-result"></div>
</body>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript">

        var p1 = {lng:parseFloat({pigcms{$_GET['origin_long']}), lat:parseFloat({pigcms{$_GET['origin_lat']})};
        var p2 = {lng:parseFloat({pigcms{$_GET['end_long']}), lat:parseFloat({pigcms{$_GET['end_lat']})};
        var directionsService = new google.maps.DirectionsService();
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var map = new google.maps.Map(document.getElementById('l-map'),{
            center: p1,
            zoom:16,
            streetViewControl:false,
            mapTypeControl:false
        });
        directionsDisplay.setMap(map);
        <if condition="$_GET['drive']">
            var travelMOde = "DRIVING";
        <else />
        var travelMOde = "TRANSIT";
        </if>
        calcRoute();
        function calcRoute() {
            var request = {
                origin: p1,
                destination: p2,
                travelMode: travelMOde
            };
            directionsService.route(request, function (result, status) {
                if (status == 'OK') {
                    directionsDisplay.setDirections(result);
                    directionsDisplay.setPanel(document.getElementById('r-result'));
                }
            });
        }
    </script>
    <else />
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
    <link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />
    <script type="text/javascript">

        // 百度地图API功能
        var map = new BMap.Map("l-map");
        var p1 = new BMap.Point({pigcms{$_GET['origin_long']},{pigcms{$_GET['origin_lat']});
        map.centerAndZoom(p1,15);
        var marker = new BMap.Marker(p1);  // 创建标注
        var p2 = new BMap.Point({pigcms{$_GET['end_long']},{pigcms{$_GET['end_lat']});

        <if condition="$_GET['drive']">
        var driving = new BMap.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});
        var driving = new BMap.DrivingRoute(map, {renderOptions: {map: map, panel: "r-result", autoViewport: true}});
        driving.search(p1, p2);
        <else />
        var transit = new BMap.TransitRoute(map, {
            renderOptions: {map: map, panel: "r-result"}
        });
        transit.search(p1, p2);
        </if>
    </script>
</if>
</html>