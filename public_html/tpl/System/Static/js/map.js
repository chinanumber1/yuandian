$(function(){
	var local = null;

	var map = null;
	if ($('#long_lat').val() == '') {
		var oPoint = new BMap.Point(117.2285309,31.8291397);
	} else {
		var long_lat = $('#long_lat').val().split(',');
		var oPoint = new BMap.Point(long_lat[0], long_lat[1]);
	}
	var marker = new BMap.Marker(oPoint);
	var setPoint = function(mk,b){
		var pt = mk.getPosition();
		$('#long_lat').val(pt.lng+','+pt.lat);
		(new BMap.Geocoder()).getLocation(pt,function(rs){
			addComp = rs.addressComponents;
			if(rs.surroundingPois && rs.surroundingPois.length > 0){
				$('#adress').val(rs.surroundingPois[0].title);
			}else if(addComp.street != ''){
				$('#adress').val(addComp.street + addComp.streetNumber);
			}else{
				$('#adress').val(addComp.city + addComp.district);
			}
		});
	};
	
	map = new BMap.Map("cmmap",{"enableMapClick":false});
	map.enableScrollWheelZoom();
	marker.enableDragging();
	
	if ($('#long_lat').val() == '') {
		map.centerAndZoom(oPoint, 12);
		function myFun(result){
			oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
			map.centerAndZoom(oPoint,12);
			marker.setPosition(oPoint);
//				$('#modal-table').hide();
		}
		var myCity = new BMap.LocalCity();
		myCity.get(myFun);
	} else {
		map.centerAndZoom(oPoint,18);
	}

	map.addControl(new BMap.NavigationControl());
	map.enableScrollWheelZoom();

	map.addOverlay(marker);
	
	var info_opts = {
	  width : 100,
	  height: 30,
	  title : "提示：",
	  enableMessage:false,
	  message:""
	};
	var infoWindow = new BMap.InfoWindow("您的坐标设置成功！",info_opts);
	
	marker.addEventListener("dragend", function(){
		setPoint(marker,true);
	});
	marker.addEventListener("click", function(e){	
		setPoint(marker,true);
		map.openInfoWindow(infoWindow,marker.getPosition());
	});
	local = new BMap.LocalSearch(map,{
		pageCapacity:1,
		onSearchComplete:function(results){
			map.centerAndZoom(results.getPoi(0).point, 17);
			marker.setPosition(results.getPoi(0).point);
		}
	});
	
	$('#map-search').submit(function(){
		$('#map-keyword').val($.trim($('#map-keyword').val()));
		if($('#map-keyword').val().length >0){
			local.search($('#map-keyword').val());
		}
		
		return false;
	});
    if (typeof(polygonMap) != 'undefined') {
        var polygon = $('#delivery_range_polygon').val();
        var oldOverlay = [];
        if (typeof(polygon) != 'undefined' && polygon != '') {
            polygon = polygon.split('|');
            var polygonArr = [];
            for (var i in polygon) {
                var tStr = polygon[i].split('-');
                polygonArr.push(new BMap.Point(tStr[1], tStr[0]));
            }
            var poly = new BMap.Polygon(polygonArr,{strokeColor:"rgb(51, 136, 255)",fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});
			
			
            map.addOverlay(poly);  //创建多边形
            poly.enableEditing();
            oldOverlay.push(poly);
			poly.addEventListener("lineupdate", function (e) {
				lat_lng = [];
				delivery_area = $.map(e.target.getPath(), function (item) {
					  lat_lng.push(item.lat + '-' + item.lng);
					  return {'lng': item.lng, 'lat': item.lat};
				});
				$('#delivery_range_polygon').val(lat_lng.join('|'));
				
				console.log($('#delivery_range_polygon').val())
			
			});
        }
        
        var overlays = [];
        var polygonArr = [];

        var overlaycomplete = function(e){
            overlays.push(e.overlay);
            var latLng = e.overlay.getPath();
            var lat_lng = [];
            for (var i in latLng) {
                lat_lng.push(latLng[i].lat + '-' + latLng[i].lng);
				polygonArr.push(new BMap.Point(latLng[i].lng, latLng[i].lat));
            }
			
			var poly = new BMap.Polygon(polygonArr, {strokeColor:"rgb(51, 136, 255)",fillColor:"rgb(51, 136, 255)", strokeWeight:2, fillOpacity: 0.2, strokeOpacity:0.8,strokeStyle:'dashed'});
            
			map.addOverlay(poly);  //创建多边形
			poly.enableEditing();
			poly.addEventListener("lineupdate", function (e) {
				lat_lng = [];
				delivery_area = $.map(e.target.getPath(), function (item) {
					lat_lng.push(item.lat + '-' + item.lng);
					return {'lng': item.lng, 'lat': item.lat};
				});
				$('#delivery_range_polygon').val(lat_lng.join('|'));
				
			});
			oldOverlay.push(poly)
				
			map.removeOverlay(e.overlay);
			
            $('#delivery_range_polygon').val(lat_lng.join('|'));
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
    
    
        //添加鼠标绘制工具监听事件，用于获取绘制结果
        drawingManager.addEventListener('overlaycomplete', overlaycomplete);
    
    
        $('select[name=delivery_range_type]').change(function(){
            if ($(this).val() == 0) {
                $('.range').show();
                drawingManager.close();
                for(var i = 0; i < overlays.length; i++){
                    map.removeOverlay(overlays[i]);
                }
                if (oldOverlay.length > 0) {
                    for(var i = 0; i < oldOverlay.length; i++){
                        map.removeOverlay(oldOverlay[i]);
                    }
                }
				overlays = [];
				oldOverlay=[];
				polygonArr = [];
            } else {
                $('.range').hide();
                drawingManager.open();
               
                for(var i = 0; i < overlays.length; i++){
                    map.removeOverlay(overlays[i]);
                }
                if (oldOverlay.length > 0) {
                    for(var i = 0; i < oldOverlay.length; i++){
                        map.removeOverlay(oldOverlay[i]);
                    }
                }
				overlays = [];
				oldOverlay=[];
				polygonArr = [];
            }
        });
    }
});