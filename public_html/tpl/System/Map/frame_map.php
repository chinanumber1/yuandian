<include file="Public:header"/>
<div id="frame_map_tips" style="margin:0">(用鼠标滚轮可以缩放地图)&nbsp;&nbsp;&nbsp;&nbsp;拖动红色图标，左侧经纬度框内将自动填充经纬度。</div>
<div class="modal-body no-padding" style="width:100%;">
    <form id="map-search" style="margin:10px;">
        <input id="map-keyword" type="textbox" style="width:300px;" placeholder="尽量填写城市、区域、街道名" value=""/>
        <input type="submit" value="搜索"/>
    </form>
    <div id="cmmap" style="height:478px;"></div>
</div>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script>
        var store_add_frame = window.top.frames['Openstore_add'].document;
        var markers_circle = [];
        var markers = [];
        var long_lat = '{pigcms{$long_lat}';
        $('#frame_map_tips').text('先找到所在的城市然后搜索位置，拖动红色图标即可自动填充位置和坐标');
        var map;
        var long_lat = long_lat.split(',');
        if (long_lat) {
            var latlng = {lng: parseFloat(long_lat[0]), lat: parseFloat(long_lat[1])};
        } else {
            var latlng = {lng: 117.2285309, lat: 31.8291397};
        }

        initMap();

        function initMap() {
            map = new google.maps.Map(document.getElementById('cmmap'), {
                zoom: 14,
                center: latlng
            });
            var infoWindow = new google.maps.InfoWindow({map: map});
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                draggable: true
            });
            markers.push(marker);


            //结束拖动
            google.maps.event.addListener(marker, 'dragend', function (MouseEvent) {
                var latlng = MouseEvent.latLng;
                $('#long_lat',store_add_frame).val(latlng.lng() + ',' + latlng.lat());
            });

            //搜索
            var service = new google.maps.places.PlacesService(map);

            $('#map-search').submit(function (e) {
                e.preventDefault();
                $('#map-keyword').val($.trim($('#map-keyword').val()));
                if ($('#map-keyword').val().length > 0) {
                    performSearch($('#map-keyword').val());
                }
                return false;
            });


            function performSearch(keyword) {
                var request = {
                    bounds: map.getBounds(),
                    query: keyword
                };
                service.textSearch(request, callback);
            }

            function callback(results, status) {
                if (status !== google.maps.places.PlacesServiceStatus.OK) {
                    console.error(status);
                    return;
                }
                for (var i = 0; i < markers_circle.length; i++) {
                    markers_circle[i].setMap(null);
                }

                for (var i = 0, result; result = results[i]; i++) {
                    addMarker(result);
                }
            }

            function addMarker(place) {
                var marker = new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    icon: {
                        url: 'https://developers.google.com/maps/documentation/javascript/images/circle.png',
                        anchor: new google.maps.Point(10, 10),
                        scaledSize: new google.maps.Size(10, 17)
                    }
                });
                markers_circle.push(marker);
                google.maps.event.addListener(marker, 'click', function () {
                    service.getDetails(place, function (result, status) {
                        if (status !== google.maps.places.PlacesServiceStatus.OK) {
                            console.error(status);
                            return;
                        }
                        infoWindow.setContent(result.name);
                        infoWindow.open(map, marker);
                    });
                });
            }
        }

</script>
    <else />
	<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
	<script type="text/javascript">
		$(function(){
			var local = null, store_add_dom = window.top.frames['Openstore_add'];
			var store_add_frame = window.top.frames['Openstore_add'].document;
			var map = null;
			var oPoint = new BMap.Point({pigcms{$long_lat});
			var marker = new BMap.Marker(oPoint);
			var setPoint = function(mk,b){
				var pt = mk.getPosition();
				$('#long_lat',store_add_frame).val(pt.lng+','+pt.lat);
				(new BMap.Geocoder()).getLocation(pt,function(rs){
					addComp = rs.addressComponents;
					if (b===true){
						if(addComp.province && typeof($('#choose_province',store_add_frame)) != 'undefined'){
							$.each($('#choose_province option',store_add_frame),function(i,item){
								var text = $(item).html();
								if(text && addComp.province.indexOf(text)!=-1){
									var choose_province = $('#choose_province',store_add_frame);
									choose_province.find('option').eq(i).prop('selected',true);
									store_add_dom.show_city(choose_province.find('option:selected').attr('value'),choose_province.find('option:selected').html(),1);
									return false;
								}
							});
						}
						if(addComp.city && typeof($('#choose_city',store_add_frame)) != 'undefined'){
							$.each($('#choose_city option',store_add_frame),function(i,item){
								var text = $(item).html();
								if(text && addComp.city.indexOf(text)!=-1){
									var choose_city = $('#choose_city',store_add_frame);
									choose_city.find('option').eq(i).prop('selected',true);

									if($('#choose_cityarea',store_add_frame).attr('circle') != '-1'){
										store_add_dom.show_area(choose_city.find('option:selected').attr('value'),choose_city.find('option:selected').html(),1);
										return false;
									}
								}
							});
						}
						if(addComp.district && typeof($('#choose_area',store_add_frame)) != 'undefined'){
							$.each($('#choose_area option',store_add_frame),function(i,item){
								var text = $(item).html();
								if(text && addComp.district.indexOf(text)!=-1){
									var choose_area = $('#choose_area',store_add_frame);
									choose_area.find('option').eq(i).prop('selected',true);
									if($('#choose_cityarea',store_add_frame).attr('circle') != '-1'){
										store_add_dom.show_circle(choose_area.find('option:selected').attr('value'),choose_area.find('option:selected').html(),1);
										return false;
									}
								}
							});
						}
						$('#adress',store_add_frame).val(addComp.street + addComp.streetNumber);
					}
				});
			};
			
			map = new BMap.Map("cmmap",{"enableMapClick":false});
			map.enableScrollWheelZoom();
			marker.enableDragging();
			
			<if condition="empty($_GET['long_lat'])">
				map.centerAndZoom(oPoint, 14);
				function myFun(result){
					oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
					map.centerAndZoom(oPoint,14);
					marker.setPosition(oPoint);
				}
				var myCity = new BMap.LocalCity();
				myCity.get(myFun);
			<else/>
				map.centerAndZoom(oPoint,18);
			</if>
		

			map.addControl(new BMap.NavigationControl());
			map.enableScrollWheelZoom();

			map.addOverlay(marker);
			
			marker.addEventListener("dragend", function(){
				setPoint(marker,true);
			});
			marker.addEventListener("click", function(e){	
				setPoint(marker,true);
			});	
			/*map.addEventListener("click",function(e){
				alert(e.point.lng + "," + e.point.lat);
			});*/
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
		});
	</script>
	<style>.BMap_cpyCtrl{display:none;}</style>
</if>
<include file="Public:footer"/>