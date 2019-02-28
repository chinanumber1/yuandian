$(function(){
	var local = null;
	$.getScript("http://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2",function(){
		var map = null;
		var oPoint = new BMap.Point(116.331398,39.897445);
		var marker = new BMap.Marker(oPoint);
		var setPoint = function(mk,b){
			var pt = mk.getPosition();
			$('#long_lat').val(pt.lng+','+pt.lat);
			alert('坐标设置成功');
		};
		map = new BMap.Map("cmmap",{"enableMapClick":false});
		map.enableScrollWheelZoom();
		marker.enableDragging();
		
		var tileLayer = new BMap.TileLayer({isTransparentPng: true});
		tileLayer.getTilesUrl = function(tileCoord, zoom) {
			var x = tileCoord.x;
			var y = tileCoord.y;
			return 'upload/scenic/map/1/tiles/' + zoom + '/tile' + x + '_' + y + '.png';  //根据当前坐标，选取合适的瓦片图
		}
		map.addTileLayer(tileLayer);
		
		map.centerAndZoom(oPoint, 12);
		if($('#long_lat').val() == ''){
			function myFun(result){
				oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
				map.centerAndZoom(oPoint,12);
				marker.setPosition(oPoint);
				$('#modal-table').hide();
			}
			var myCity = new BMap.LocalCity();
			myCity.get(myFun);
		}else{
			var tmpLongLat = $('#long_lat').val().split(',');
			oPoint = new BMap.Point(tmpLongLat[0],tmpLongLat[1]);
			map.centerAndZoom(oPoint,12);
			marker.setPosition(oPoint);
			$('#modal-table').hide();
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
	});

	$('#map-search').submit(function(){
		$('#map-keyword').val($.trim($('#map-keyword').val()));
		if($('#map-keyword').val().length >0){
			local.search($('#map-keyword').val());
		}

		return false;
	});
});