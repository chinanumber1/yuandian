$(function(){
	var local = null;
	
	var map = null;
	var oPoint = new BMap.Point(116.331398,39.897445);
	var marker = new BMap.Marker(oPoint);
	var setPoint = function(mk,b){
		var pt = mk.getPosition();
		if($('#url').size() > 0){
			$('#url').val('LBS://'+pt.lng+','+pt.lat);
		}else{
			$('#location').val(pt.lng+','+pt.lat);
		}
		(new BMap.Geocoder()).getLocation(pt,function(rs){
			addComp = rs.addressComponents;
			if (b===true){
				if(addComp.province && typeof($('#choose_province')) != 'undefined'){
					$.each($('#choose_province option'),function(i,item){
						var text = $(item).html();
						if(text && addComp.province.indexOf(text)!=-1){
							var choose_province = $('#choose_province');
							choose_province.find('option').eq(i).prop('selected',true);
							show_city(choose_province.find('option:selected').attr('value'),choose_province.find('option:selected').html(),1);
							return false;
						}
					});
				}
				if(addComp.city && typeof($('#choose_city')) != 'undefined'){
					$.each($('#choose_city option'),function(i,item){
						var text = $(item).html();
						if(text && addComp.city.indexOf(text)!=-1){
							var choose_city = $('#choose_city');
							choose_city.find('option').eq(i).prop('selected',true);
							show_area(choose_city.find('option:selected').attr('value'),choose_city.find('option:selected').html(),1);
							return false;
						}
					});
				}
				if(addComp.district && typeof($('#choose_area')) != 'undefined'){
					$.each($('#choose_area option'),function(i,item){
						var text = $(item).html();
						if(text && addComp.district.indexOf(text)!=-1){
							var choose_area = $('#choose_area');
							choose_area.find('option').eq(i).prop('selected',true);
							show_circle(choose_area.find('option:selected').attr('value'),choose_area.find('option:selected').html(),1);
							return false;
						}
					});
				}
				// $('#adress').val(addComp.street + addComp.streetNumber);
			}
		});
	};
	
	map = new BMap.Map("cmmap",{"enableMapClick":false});
	map.enableScrollWheelZoom();
	marker.enableDragging();
	
	map.centerAndZoom(oPoint, 12);
	if($('#location').size() > 0 && $.trim($('#location').val()) != ''){
		var locationArr = $('#location').val().split(',');
		oPoint = new BMap.Point(locationArr[0],locationArr[1]);
		map.centerAndZoom(oPoint,19);
		marker.setPosition(oPoint);
	}else if($('#villageLocation').size() > 0 && $.trim($('#villageLocation').val()) != ''){
		var locationArr = $('#villageLocation').val().split(',');
		oPoint = new BMap.Point(locationArr[0],locationArr[1]);
		map.centerAndZoom(oPoint,19);
		marker.setPosition(oPoint);
	}else{
		function myFun(result){
			oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
			map.centerAndZoom(oPoint,12);
			marker.setPosition(oPoint);
			$('#modal-table').hide();
		}
		var myCity = new BMap.LocalCity();
		myCity.get(myFun);
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
			if(results.getPoi(0) && results.getPoi(0).point){
				map.centerAndZoom(results.getPoi(0).point,$('#location').size() > 0?19:17);
				marker.setPosition(results.getPoi(0).point);
			}
		}
	});
	
	$('#map-search').submit(function(){
		$('#map-keyword').val($.trim($('#map-keyword').val()));
		if($('#map-keyword').val().length >0){
			local.search($('#map-keyword').val());
		}
		
		return false;
	});
	
	if($('#show_map_frame').size() > 0){
		$('#show_map_frame').click(function(){
			setTimeout(function(){
				console.log('show_map_frame');
				console.log('marker',marker);
				map.centerAndZoom(new BMap.Point(marker.point.lng,marker.point.lat), map.getZoom());
			},500);
		});
	}
});