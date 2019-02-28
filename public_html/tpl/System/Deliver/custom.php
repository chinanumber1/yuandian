<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Deliver/customSave')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$custom['id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
            <tr>
                <th width="15%">配送区域名称</th>
                <td width="35%" colspan=3><input type="text" class="input fl" name="name" value="{pigcms{$custom['name']}" tips="配送区域名称" /></td>
            </tr>
			<tr class="delivery_range_type">
				<td>自定义范围</td>
				<td><input type="button" class="button" value="绘制配送范围" id="baiduMap"/></td>
			</tr>
			<tr class="delivery_range_type">
			    <input type="hidden" name="delivery_range_polygon" id="delivery_range_polygon" />
			    <input type="hidden" name="lng_lat" id="lng_lat" value="{pigcms{$custom['lng_lat']}"/>
				<td colspan="2"><div id="allmap" style="height:350px;"></div></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing&key={pigcms{$config.google_map_ak}"></script>
    <script>
        var polygon = '{pigcms{$custom['delivery_range_polygon']}';
        if(polygon){
            polygon = $.parseJSON(polygon);
            var nowlat = '{pigcms{$custom["lat"]}';
            var nowlng = '{pigcms{$custom["lng"]}';
        }else{
            nowlat = 31.817797156213604;
            nowlng = 117.22220727680337;
        }
        var bermudaTriangle;
        var map;
        var drawingManager;
        var selectedShape;
        initMap();
        function initMap() {
            map = new google.maps.Map(document.getElementById('allmap'), {
                zoom: 14,
                center: {lat: parseFloat(nowlat), lng: parseFloat(nowlng)}
            });
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(nowlat), lng: parseFloat(nowlng)},
                map: map,
                title: '默认位置'
            });
            google.maps.event.addDomListener(document.getElementById('baiduMap'), 'click', deleteSelectedShape);
            // Define the LatLng coordinates for the polygon's path.
            //绘画工具 设置
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON
                    ]
                },
                //设置图形显示样式
                circleOptions: {
                    fillColor: '#ffff00',
                    fillOpacity: 1,
                    strokeWeight: 5,
                    clickable: false,
                    editable: true,
                    zIndex: 1
                },
                polygonOptions: {
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                    editable: true,
                }
            });
            drawingManager.setMap(map);
            //注册 多边形 绘制完成事件
            google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
                var array = polygon.getPath().getArray();
                showLonLat(array);
            });
            //将写入的加入数组
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
                var newShape = e.overlay;

                newShape.type = e.type;

                if (e.type !== google.maps.drawing.OverlayType.MARKER) {
                    // Switch back to non-drawing mode after drawing a shape.
                    drawingManager.setDrawingMode(null);

                    // Add an event listener that selects the newly-drawn shape when the user
                    // mouses down on it.
                    google.maps.event.addListener(newShape, 'click', function (e) {
                        if (e.vertex !== undefined) {
                            if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
                                var path = newShape.getPaths().getAt(e.path);
                                path.removeAt(e.vertex);
                                if (path.length < 3) {
                                    newShape.setMap(null);
                                }
                            }
                            if (newShape.type === google.maps.drawing.OverlayType.POLYLINE) {
                                var path = newShape.getPath();
                                path.removeAt(e.vertex);
                                if (path.length < 2) {
                                    newShape.setMap(null);
                                }
                            }
                        }
                        setSelection(newShape);
                    });
                    setSelection(newShape);
                }
                else {
                    google.maps.event.addListener(newShape, 'click', function (e) {
                        setSelection(newShape);
                    });
                    setSelection(newShape);
                }
            });
            //循环显示 经纬度
            var latlng = [];
            function showLonLat(arr) {
                var bounds = new google.maps.LatLngBounds();

                for (var i = 0; i < arr.length; i++) {
                    bounds.extend(arr[i]);
                    latlng.push(arr[i].lat() + ',' + arr[i].lng());
                    $('#delivery_range_polygon').val(latlng.join('|'));
                }
                $('#lng_lat').val(bounds.getCenter().lng()+','+bounds.getCenter().lat());
            }

            var polyCoords = [];
            var lat_lng = [];
            for (i = 0; i < polygon.length; i++) {
                for (k = 0; k < polygon[i].length; k++) {
                    polyCoords.push({lat: +parseFloat(polygon[i][k].lat), lng: +parseFloat(polygon[i][k].lng)});
                    lat_lng.push(polygon[i][k].lat + ',' + polygon[i][k].lng);
                }
            }
            $('#delivery_range_polygon').val(lat_lng.join('|'));
            // Construct the polygon.
            bermudaTriangle = new google.maps.Polygon({
                paths: polyCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });
            addPolygon();
            function setSelection (shape) {
                selectedShape = shape;
            }
            function deleteSelectedShape(){
                bermudaTriangle.setMap(null);
                if (selectedShape) {
                    selectedShape.setMap(null);
                }
            }

            function addPolygon() {
                bermudaTriangle.setMap(map);
            }
        }

    </script>
    <else />
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="https://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
<script>
var polygon = '{pigcms{$custom['delivery_range_polygon']}';
polygon = $.parseJSON(polygon);
var oldOverlay = [];
$(document).ready(function(){
    var map = new BMap.Map("allmap",{"enableMapClick":false}), point = new BMap.Point('{pigcms{$custom["lng"]}', '{pigcms{$custom["lat"]}');
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom();
    if ($('#lng_lat').val() == '') {
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var marker = new BMap.Marker(r.point);
                map.addOverlay(marker);
                map.panTo(r.point);
            } else {
                alert('failed'+this.getStatus());
            }
        },{enableHighAccuracy: true})
    } else {
        var marker = new BMap.Marker(point);// 创建标注
        map.addOverlay(marker);
        marker.enableDragging();
    }

    if (polygon != null) {
        for (var i in polygon) {
            var polygonArr = [];
            var lat_lng = [];
            for (var ii in polygon[i]) {
                polygonArr.push(new BMap.Point(polygon[i][ii].lng, polygon[i][ii].lat));
                lat_lng.push(polygon[i][ii].lat + ',' + polygon[i][ii].lng);
            }
            $('#delivery_range_polygon').val(lat_lng.join('|'));
            
            var poly = new BMap.Polygon(polygonArr, {strokeColor:"rgb(51, 136, 255)",fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});
            
            map.addOverlay(poly);  //创建多边形
			poly.enableEditing();
            oldOverlay.push(poly);
			poly.addEventListener("lineupdate", function (e) {
				lat_lng = [];
				delivery_area = $.map(e.target.getPath(), function (item) {
					  lat_lng.push(item.lat + ',' + item.lng);
					  return {'lng': item.lng, 'lat': item.lat};
				});
				$('#delivery_range_polygon').val(lat_lng.join('|'));
			
			});
        }
    }
    
    var overlays = [];
    var overlaycomplete = function(e){
        overlays.push(e.overlay);
        var latLng = e.overlay.getPath();
        console.log(e.overlay.getBounds().getCenter().lng)
        console.log(e.overlay.getBounds().getCenter().lat)
        var lat_lng = [];
		var polygonArr = [];
        for (var i in latLng) {
        	lat_lng.push(latLng[i].lat + ',' + latLng[i].lng);
			 polygonArr.push(new BMap.Point(latLng[i].lng, latLng[i].lat));
               
        }
		
		var poly = new BMap.Polygon(polygonArr, {strokeColor:"rgb(51, 136, 255)",fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});
            
		map.addOverlay(poly);  //创建多边形
		poly.enableEditing();
		poly.addEventListener("lineupdate", function (e) {
			lat_lng = [];
			delivery_area = $.map(e.target.getPath(), function (item) {
				  lat_lng.push(item.lat + ',' + item.lng);
				  return {'lng': item.lng, 'lat': item.lat};
			});
			$('#delivery_range_polygon').val(lat_lng.join('|'));
			console.log( $('#delivery_range_polygon').val())
		});
		oldOverlay.push(poly)
			
		map.removeOverlay(e.overlay);
        $('#delivery_range_polygon').val(lat_lng.join('|'));
		console.log( $('#delivery_range_polygon').val())
        $('#lng_lat').val(e.overlay.getBounds().getCenter().lng + ',' + e.overlay.getBounds().getCenter().lat);
    };
    var styleOptions = {
        strokeColor:"rgb(51, 136, 255)",    //边线颜色。
        fillColor:"rgb(51, 136, 255)",      //填充颜色。当参数为空时，圆形将没有填充效果。
        strokeWeight: 3,       //边线的宽度，以像素为单位。
        strokeOpacity: 0.8,	   //边线透明度，取值范围0 - 1。
        fillOpacity: 0.6,      //填充的透明度，取值范围0 - 1。
        strokeStyle: 'dashed' //边线的样式，solid或dashed。
    }
    //实例化鼠标绘制工具
    var drawingManager = new BMapLib.DrawingManager(map, {
        isOpen: false, //是否开启绘制模式
        enableDrawingTool: false, //是否显示工具栏
        drawingMode:BMAP_DRAWING_POLYGON,
        drawingToolOptions: {
            anchor: BMAP_ANCHOR_TOP_RIGHT, //位置
            offset: new BMap.Size(5, 5), //偏离值
        },
        circleOptions: styleOptions, //圆的样式
        polylineOptions: styleOptions, //线的样式
        polygonOptions: styleOptions, //多边形的样式
        rectangleOptions: styleOptions //矩形的样式
    });


    $('#baiduMap').click(function(){
        drawingManager.open();
        for(var i = 0; i < overlays.length; i++){
            map.removeOverlay(overlays[i]);
        }
        if (oldOverlay.length > 0) {
            console.log(oldOverlay);
        	for(var i = 0; i < oldOverlay.length; i++){
                map.removeOverlay(oldOverlay[i]);
            }
        }
        overlays = [];
		oldOverlay = [];
		polygonArr = [];
    });

    //添加鼠标绘制工具监听事件，用于获取绘制结果
    drawingManager.addEventListener('overlaycomplete', overlaycomplete);
});
</script>
</if>
<include file="Public:footer"/>