<!DOCTYPE html>
 <html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>配送员位置查看</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
</head>

<body class=" hIphone" style="padding-bottom: initial;background: #ecedf1;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/style_dd39d16.css">
<!-- <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/orderhistory_c6670c7.css"> -->
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/order_4bc7e9e.css">
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}shop/images/hm.gif" width="0" height="0" style="display:block">
<div id="wrapper" class="">
    <div id="fis_elm__2">
        <div id="common-widget-nav" class="common-widget-nav ">
            <div class="left-slogan"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="{pigcms{:U('Shop/status', array('order_id'=>$order_id))}"></a> </div>
            <div class="center-title"> <a href="javascript:void(0)">配送员位置</a> </div>
            <div class="right-slogan "> <a class="tel-btn icon-refresh-image" href="javascript:" id="refresh"></a> </div>
        </div>
    </div>
    <div id="fis_elm__4">
        <div id="order-widget-orderhistory" class="order-widget-orderhistory" style="min-height:100px;">
        </div>
    </div>
</div>
<div class="global-mask layout"></div>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}"</script>
    <else />
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
</if>
<script>
var wHeight = $(window).height() - 50;
$("#order-widget-orderhistory").css('height', wHeight);
$(function(){


    if(typeof(is_google_map) != "undefined"){
        var lng = {pigcms{$center['lng']};
        var lat = {pigcms{$center['lat']};
        var from_site = {lng:parseFloat("{pigcms{$point['from_site']['lng']}"),lat:parseFloat("{pigcms{$point['from_site']['lat']}")};
        var aimlng = {pigcms{$point['aim_site']['lng']};
        var aimlat = {pigcms{$point['aim_site']['lat']};
        var aim_site = {lng: parseFloat(aimlng), lat: parseFloat(aimlat)};
        map = new google.maps.Map(document.getElementById('order-widget-orderhistory'), {
            mapTypeControl:false,
            zoom: 15,
            center: {lng:parseFloat(lng),lat:parseFloat(lat)}
        });
        var polygonArr = [];
        polygonArr.push({lng:from_site.lng, lat:from_site.lat});
        <if condition="$lines">
            <volist name="lines" id="vo">
                var volng = "{pigcms{$vo['lng']}";
                var volat = "{pigcms{$vo['lat']}";
                polygonArr.push({lng:parseFloat(volng), lat:parseFloat(volat)});
            </volist>
        </if>

        <if condition="$supply['status'] eq 5">
            polygonArr.push({lng: aim_site.lng, lat: aim_site.lat});
        </if>
        var flightPath = new google.maps.Polyline({
            path: polygonArr,
            geodesic: true,
            strokeColor: 'red',
            strokeOpacity: 0.8,
            strokeWeight: 5
        });

        flightPath.setMap(map);

        var userIcon =  new google.maps.Marker({
            position: aim_site,
            map:map,
            icon:  {url:'{pigcms{$static_path}/images/map/my_pos.png', anchor: new google.maps.Point(16, 16)}
        });
        var storeIcon = new google.maps.Marker({
            position: from_site,
            map:map,
            icon:  {url:'{pigcms{$static_path}/images/map/store_pos.png', anchor: new google.maps.Point(16, 16)}
        });
    }else{
        // 百度地图API功能
        var map = new BMap.Map("order-widget-orderhistory");
        map.centerAndZoom(new BMap.Point({pigcms{$center['lng']}, {pigcms{$center['lat']}), 15);
        map.enableScrollWheelZoom();

        var polyline = new BMap.Polyline([
                new BMap.Point({pigcms{$point['from_site']['lng']},{pigcms{$point['from_site']['lat']}),
            <if condition="$lines">
            <volist name="lines" id="vo">
            new BMap.Point({pigcms{$vo['lng']}, {pigcms{$vo['lat']}),
    </volist>
        </if>
        <if condition="$supply['status'] eq 5">
            new BMap.Point({pigcms{$point['aim_site']['lng']},{pigcms{$point['aim_site']['lat']}),
    </if>
    ], {strokeColor:"red", strokeWeight:5, strokeOpacity:0.8});   //创建折线
        map.addOverlay(polyline);   //增加折线

        //我的图标
        var pt1 = new BMap.Point({pigcms{$point['aim_site']['lng']},{pigcms{$point['aim_site']['lat']});
        var myIcon = new BMap.Icon("{pigcms{$static_path}shop/images/map/my_pos.png", new BMap.Size(60,60));
        var marker1 = new BMap.Marker(pt1,{icon:myIcon});  // 创建标注
        map.addOverlay(marker1);
        //店铺图标
        var pt2 = new BMap.Point({pigcms{$point['from_site']['lng']},{pigcms{$point['from_site']['lat']});
        var storeIcon = new BMap.Icon("{pigcms{$static_path}shop/images/map/store_pos.png", new BMap.Size(22,60));
        var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
        map.addOverlay(marker2);
    }



    //配送员图标
    <?php
        $temp = $lines;
        $deliver_pos = array_pop($temp);
        if (! $deliver_pos) {
            $deliver_pos = array('lng'=>$point['from_site']['lng'], 'lat'=>$point['from_site']['lat']);
        }
    ?>
        if(typeof(is_google_map) != "undefined"){
            var deliverIcon = new google.maps.Marker({
                position: {lng:{pigcms{$deliver_pos['lng']},lat:{pigcms{$deliver_pos['lat']}},
                map:map,
                icon:  {url:'{pigcms{$static_path}/images/map/deliver_pos.png', anchor: new google.maps.Point(16, 16)}
            });

        }else{
            var pt2 = new BMap.Point({pigcms{$deliver_pos['lng']},{pigcms{$deliver_pos['lat']});
            var storeIcon = new BMap.Icon("{pigcms{$static_path}shop/images/map/deliver_pos.png", new BMap.Size(22,60));
            var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
            map.addOverlay(marker2);

            map.setViewport([
                new BMap.Point({pigcms{$point['from_site']['lng']},{pigcms{$point['from_site']['lat']}),
        <if condition="$lines">
                <volist name="lines" id="vo">
                new BMap.Point({pigcms{$vo['lng']}, {pigcms{$vo['lat']}),
        </volist>
            </if>
            new BMap.Point({pigcms{$point['aim_site']['lng']},{pigcms{$point['aim_site']['lat']}),
        ]);
            map.enableScrollWheelZoom();
            map.enableContinuousZoom();
        }

});
$("#refresh").click(function(){
    location.href = "{pigcms{:U('Shop/map', array('order_id'=>$order_id))}"+"&"+Math.random();
});
</script>
</body>
</html>