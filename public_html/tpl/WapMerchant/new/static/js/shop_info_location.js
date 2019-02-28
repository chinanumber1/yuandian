if(container == 'wechat'){
			if(lat == '0.000000' || lng == '0.000000'){
				wx.ready(function () {
					wx.getLocation({
					    success: function (res) {
					        lat = parseFloat(res.latitude); // 纬度，浮点数，范围为90 ~ -90
					        lng = parseFloat(res.longitude); // 经度，浮点数，范围为180 ~ -180。
					        var gcjloc = transformFromWGSToGCJ(lng, lat);
						    center = new qq.maps.LatLng(gcjloc.lat, gcjloc.lng);
							shop_latlng = center;
							init_map();
					    }
					});
				});
			}else{
				init_map();
			}
		}else if(container == 'browser'){
			if(lat == '0.000000' || lng == '0.000000'){
				getLocation();
			}else{
				init_map();
			}
		}else if(container == 'web'){
			init_map();
		}
		function getLocation(){
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(function(position){
					lat=position.coords.latitude;
					lng=position.coords.longitude;
					center = new qq.maps.LatLng(lat, lng);
					shop_latlng = center;
					init_map();
				});
			}else{
				alert("您的浏览器不支持地理定位");
			}
		}
		
		function open_map(){
		    $("#map-layer").show();
		    $(".pigcms-header").hide();
		}

		function init_map() {
		    if(lat == '0.000000' || lng == '0.000000'){
			    var map = new qq.maps.Map(document.getElementById("map"), {
			        disableDefaultUI: true,
			        zoom: 13
			    });
		    	citylocation = new qq.maps.CityService({
			        complete: function(result) {
			            map.setCenter(result.detail.latLng);
			        }
			    });
			    citylocation.searchLocalCity();
			    var marker = new qq.maps.Marker({
			        map: map
			    });
		    }else{
		  		var map = new qq.maps.Map(document.getElementById("map"), {
			    	center: center,
			        disableDefaultUI: true,
			        zoom: 17
			    });
			    var marker = new qq.maps.Marker({
			    	position: center,
			        map: map
			    });
		    }
			geocoder = new qq.maps.Geocoder({
			    complete : function(result){
			        address_detail = result.detail.address;
			        $("#location span").text(address_detail);
			    }
			});
			geocoder.getAddress(center);
		    qq.maps.event.addListener(map, 'click',function(e) {
		        marker.setPosition(e.latLng);
		        shop_latlng = e.latLng;
				geocoder.getAddress(shop_latlng);
		    });
			$("[name='lat']").val(shop_latlng.lat);
		   	$("[name='long']").val(shop_latlng.lng);
		}
		$("#map-cancel").click(function() {
		    $(".pigcms-header").show();
		    $("#map-layer").hide();
		}); 
		$("#map-confirm").click(function() {
		    $("[name='lat']").val(shop_latlng.lat);
		    $("[name='long']").val(shop_latlng.lng);
		    $("#map-layer").hide();
		    $(".pigcms-header").show();
		});