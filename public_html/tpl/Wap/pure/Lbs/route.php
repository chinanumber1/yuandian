<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>查看线路</title>
	<meta name="description" content="{pigcms{$config.seo_description}">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/detail.css?210"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	<script type="text/javascript"><if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if></script>
</head>
<body>
    <iframe id="frame_src" style="width:100%;height:100%;border:none;"></iframe>
    <script>
        $(window).resize(function(){
            window.location.reload();
        });
        $('#frame_src').height($(window).height());
        getUserLocation({useHistory:false,okFunction:'getIframe'});
        motify.log('正在加载地图',0);
        function getIframe(userLonglat,userLong,userLat){
            geoconv('realResult',userLong,userLat);
        }
        function realResult(result){
        	var origin = encodeURIComponent('当前位置');
            var destination = encodeURIComponent('{pigcms{$title}');
            $('#frame_src').attr('src','http://api.map.baidu.com/direction?origin=latlng:'+result.result[0].y+','+result.result[0].x+'|name:'+origin+'&destination=latlng:{pigcms{$lat},{pigcms{$long}|name:'+destination+'&mode=driving&region=100010000&src=baidu|jsapi&output=html');
			motify.clearLog();
        }
    </script>
<!--    <php>$no_footer=true;</php>-->
<!--	<include file="Public:footer"/>-->
</body>
</html>