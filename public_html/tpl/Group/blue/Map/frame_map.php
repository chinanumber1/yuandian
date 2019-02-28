<!doctype html>
<html>
	<head>
        <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
            <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}"</script>
        <else />
			<script src="https://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2&s=1"></script>
			<script type="text/javascript" src="https://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
			<link rel="stylesheet" href="https://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />
        </if>
		<style>body,html{margin:0;padding:0;width:100%;height:100%;overflow:hidden;font-family:'微软雅黑'}.BMapLib_SearchInfoWindow{font-size:14px;font-family:'微软雅黑'}#container{height:500px;margin:15px 15px 0;width:770px;}.declaration{color:#999;font-size:12px;text-align:right;margin-right:20px;}.BMap_cpyCtrl{display:none;}.BMapLib_bubble_content{height:85px!important;}.BMapLib_trans{top:123px!important;}</style>
	</head>
	<body>
		<div id="cmmap" style="overflow:hidden;zoom:1;position:relative;">
			<div id="container"></div>
		</div>
		<p class="declaration">注：地图位置坐标仅供参考，具体情况以实际道路标识信息为准</p>
		<script type="text/javascript">
if(typeof(is_google_map)!="undefined"){
    var pos = "{pigcms{$_GET['map_point']}";
    pos = pos.split(',');
    pos = {lng:parseFloat(pos[0]),lat:parseFloat(pos[1])};
    var map = new google.maps.Map(document.getElementById('container'), {
        mapTypeControl:false,
        zoom: 18,
        center: pos
    });
    var contentString = "<b>{pigcms{$_GET['store_name']}</b><br/><b>地址：</b>{pigcms{$_GET['store_adress']}<br/><b>电话：</b>{pigcms{$_GET['store_phone']}<br/><b>线路：</b><a href='https://www.google.com/maps/place/{pigcms{$_GET['store_adress']|urlencode=###}' target='_blank'>公交/驾车路线查询»</a>";

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });
    var marker = new google.maps.Marker({
        position: pos,
        map: map
    });
    infowindow.open(map, marker);
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });
}else{
    var map = new BMap.Map('container');
    //添加缩放
    map.addControl(new BMap.NavigationControl());

    //定位
    var point = new BMap.Point({pigcms{$_GET['map_point']});
    map.centerAndZoom(point,18);

    //添加缩放条
    map.addControl(new BMap.NavigationControl());
    //启用滚轮放大缩小
    map.enableScrollWheelZoom();

    //标记
    var marker = new BMap.Marker(point);
    map.addOverlay(marker);

    var content = "<b>地址：</b>{pigcms{$_GET['store_adress']}<br/><b>电话：</b>{pigcms{$_GET['store_phone']}<br/><b>线路：</b><a href='http://map.baidu.com/m?word={pigcms{$_GET['store_adress']|urlencode=###}' target='_blank'>公交/驾车路线查询»</a>";
    //创建检索信息窗口对象
    var searchInfoWindow = null;
    searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
        title  : "{pigcms{$_GET['store_name']}",      //标题
        width  : 290,             //宽度
        height : 60,              //高度
        panel  : "panel",         //检索结果面板
        enableAutoPan : true,     //自动平移
        searchTypes   :[]
    });

    searchInfoWindow.open(marker); //在marker上打开检索信息串口
    marker.addEventListener("click", function(){
        searchInfoWindow.open(marker); //开启信息窗口
    });
}

			
		</script>
	</body>
</html>