function open_map() {
	$("#map-layer").show();
	$(".pigcms-header,.container-fill").hide();

	var map = new BMap.Map("map", {"enableMapClick":false});
	map.enableScrollWheelZoom();
	map.enableContinuousZoom();
	
	
	var oPoint = new BMap.Point(116.331398,39.897445);
	var marker = new BMap.Marker(oPoint);
	var setPoint = function(mk,b){
		var pt = mk.getPosition();
		lng = pt.lng;
		lat = pt.lat;
		(new BMap.Geocoder()).getLocation(pt,function(rs){
			addComp = rs.addressComponents;
			address_detail = addComp.street + addComp.streetNumber;
		});
	};

	marker.enableDragging();

	map.centerAndZoom(oPoint, 16);
	if(lng == '' || lat == ''){
			var geolocation = new BMap.Geolocation();
			geolocation.getCurrentPosition(function(r){
				if(this.getStatus() == BMAP_STATUS_SUCCESS){
					oPoint = new BMap.Point(r.point.lng, r.point.lat);
					map.centerAndZoom(oPoint,16);
					marker.setPosition(oPoint);
				} else {
					alert('failed'+this.getStatus());
				}        
			},{enableHighAccuracy: true})
	}else{
		oPoint = new BMap.Point(lng, lat);
		map.centerAndZoom(oPoint, 16);
		marker.setPosition(oPoint);
	}

	map.addControl(new BMap.NavigationControl());
	map.enableScrollWheelZoom();  //启用滚轮放大缩小，默认禁用
	map.enableContinuousZoom();  

	map.addOverlay(marker);

	marker.addEventListener("dragend", function(){
		setPoint(marker,true);
	});
	marker.addEventListener("click", function(e){
		setPoint(marker,true);
	});
	local = new BMap.LocalSearch(map,{
		pageCapacity:1,
		onSearchComplete:function(results){
			map.centerAndZoom(results.getPoi(0).point, 16);
			marker.setPosition(results.getPoi(0).point);
		}
	});
}
$("#map-cancel").click(function() {
	$(".pigcms-header,.container-fill").show();
	$("#map-layer").hide();
}); 
$("#map-confirm").click(function() {
	$('#long').val(lng);
	$('#lat').val(lat);
	$("#location span").text(address_detail);
	$("#map-layer").hide();
	$(".pigcms-header,.container-fill").show();
});