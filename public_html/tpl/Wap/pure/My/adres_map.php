<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="keywords" content="{pigcms{$config.seo_keywords}"/>
	<meta name="description" content="{pigcms{$config.seo_description}"/>
	<title>选择位置</title>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_5e96991.css"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css"/>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/common.js"></script>
</head>
<body class="hIphone" style="padding-bottom:initial;">
    <div id="hidden_map" style="display: none;"></div>
    <input type="hidden" id="now_center" value="" />
	<input type="hidden" id="area_id"/>
	<input type="hidden" id="city_id"/>
	<input type="hidden" id="province_id"/>
	<div id="wrapper">
		<div id="fis_elm__2">
			<div id="address-widget-map" class="address-widget-map">
				<div class="address-map-nav">
					<div class="left-slogan" onclick="history.go(-1);">
						<a class="left-arrow icon-arrow-left2"></a>
					</div>
					<div class="city_box">
						<span>读取中</span>
					</div>
					<div class="center-title">
						<div class="ui-suggestion-mask">
							<input type="text" placeholder="请输入你的收货地址" id="se-input-wd" autocomplete="off"/>
							<div class="ui-suggestion-quickdel"></div>
						</div>
					</div>
					<div class="his-postion" data-node="historypos" style="">
						<div class="ui-suggestion" id="ui-suggestion-0" style="top: 0px; left: 0px; position: relative;">
							<div class="ui-suggestion-content" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></div>
							<div class="ui-suggestion-button"><span class="ui-suggestion-clear" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);">清除历史记录</span><span class="ui-suggestion-close" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></span></div>
						</div>
					</div>
				</div>
				<div id="fis_elm__3">
					<div class="map">
						<div class="MapHolder" id="cmmap"></div>
						<div class="dot" style="display:block;"></div>
					</div>
					<div class="mapaddress" data-node="mapaddress">
						<ul id="addressShow"> </ul>
					</div>
				</div>
				<div id="fis_elm__4" style="display:none;">
					<section class="citylistBox">
						<dl>
							<volist name="all_city" id="vo">
								<dt id="city_{pigcms{$key}" class="cityKey" data-city_key="{pigcms{$key}">{pigcms{$key}</dt>
								<volist name="vo" id="voo">
									<dd class="city_location" data-city_url="{pigcms{$voo.area_url}">{pigcms{$voo.area_name}</dd>
								</volist>
							</volist>
						</dl>
					</section>
					<div id="selectCharBox"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="global-mask layout"></div>
	{pigcms{$hideScript}
</body>
	<script type="text/javascript" src="{pigcms{$static_path}js/SelectChar.js?210" charset="utf-8"></script>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript">
        var address = '';
        var timeout = 0;
        $(document).ready(function(){
            $('.map .MapHolder').height(($(window).height()-50)*0.4);
            $('.mapaddress').height(($(window).height()-52)*0.6);

            $('#fis_elm__4').height($(window).height()-52);

            var cityKey = [];
            $.each($('.cityKey'),function(i,item){
                cityKey.push($(item).data('city_key'));
            });
            $("#selectCharBox").css({'float':'right',height:($(window).height()-50),width:50,'z-index':9998}).seleChar({
                chars:cityKey,
                callback:function(ret){
                    $('#fis_elm__4').scrollTop(($('#city_'+ret).position().top) + $('#fis_elm__4').scrollTop() - 50);
                }
            });

            $('.city_location').click(function(){
                $('.city_box span').html($(this).html());
                $('.city_box').removeClass('arrow');
                $('#fis_elm__3').show();
                $('#fis_elm__4').hide();

                var geocoder =  new google.maps.Geocoder();
                geocoder.geocode( { 'address': $(this).html()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter({lat:results[0].geometry.location.lat(),lng:results[0].geometry.location.lng()},16);
                        $('#now_center').val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
                    } else {
                        alert("Something got wrong " + status);
                    }
                });
                setTimeout(function(){
                    var centerMap = map.getCenter();
                    getPositionInfo(centerMap.lat(),centerMap.lng());
                },700);
            });

            $('.city_box').click(function(){
                if($(this).hasClass('arrow')){
                    $(this).removeClass('arrow');
                    $('#fis_elm__3').show();
                    $('#fis_elm__4').hide();
                }else{
                    $(this).addClass('arrow');
                    $('#fis_elm__3').hide();
                    $('#fis_elm__4').show();
                }
            });

            // 谷歌地图API功能

            var map = new google.maps.Map(document.getElementById('cmmap'), {
                mapTypeControl:false,
                zoom: 16,
                center: {lng:parseFloat(117.228692),lat:parseFloat(31.822943)}
            });
            //初始化
            $('#now_center').val('31.822943,117.228692');
            $('.city_box span').html('合肥');
            getPositionInfo(31.822943,117.228692);

            map.addListener("dragend", function(e){
                $('#addressShow').empty();
                var centerMap = map.getCenter();
                $('#now_center').val(centerMap.lat()+','+centerMap.lng());
                getPositionInfo(centerMap.lat(),centerMap.lng());
            });

            $("#se-input-wd").bind('input', function(e){
                var address = $.trim($('#se-input-wd').val());
                if(address.length > 0){
                    $('#cmmap').hide();
                    $('.mapaddress').height(($(window).height()-52));

                    $('#addressShow').empty();
                    clearTimeout(timeout);
                    timeout = setTimeout("search('"+address+"')", 500);
                }else{
                    $('#cmmap').show();
                    $('.mapaddress').height(($(window).height()-52)*0.6);
                }
            });

            $('#addressShow').delegate("li","click",function(){
                info = JSON.parse($.cookie('user_address'));
                info.adress = $(this).attr("sname");
                info.longitude = $(this).attr("lng");
                info.latitude = $(this).attr("lat");
                if($(this).data('search')){
                    $.post("{pigcms{:U('Home/cityMatching')}",{'city_name':$(this).data('city'),'area_name':$(this).data('district'),'get_province':'1','all_city':'1'},function(res){
                        if(res.status == 1){
                            $('.city_box span').html(res.info.area_name);
                            $('#province_id').val(res.info.province_id);
                            $('#city_id').val(res.info.area_id);
                            $('#area_id').val(res.info.now_area_id);
                            info.province = res.info.province_id;
                            info.city = res.info.area_id;
                            info.area = res.info.now_area_id;
                            $.cookie('user_address', JSON.stringify(info));
                            location.href = "{pigcms{:U('My/edit_adress',$params)}&adress_id="+info.id;
                        }else{
                            alert('当前城市不可用');
                            return false;
                        }
                    });

                }else{
                    info.province = $('#province_id').val();
                    info.city = $('#city_id').val();
                    info.area = $('#area_id').val();
                    $.cookie('user_address', JSON.stringify(info));
                    location.href = "{pigcms{:U('My/edit_adress',$params)}&adress_id="+info.id;
                }
            });


            var user_address = $.cookie('user_address');

            if(user_address){
                user_address = $.parseJSON(user_address);
            }
            if(user_address && user_address.longitude && user_address.latitude){
                map.setCenter({lng:parseFloat(user_address.longitude),lat:parseFloat(user_address.latitude)}, 16);
                $('#now_center').val(parseFloat(user_address.latitude)+','+parseFloat(user_address.longitude));
                getPositionInfo(user_address.latitude,user_address.longitude);
            }else{
                navigator.geolocation.getCurrentPosition(function(position){
                    map.setCenter({lng:parseFloat(position.coords.longitude),lat:parseFloat(position.coords.latitude)}, 16);
                    getPositionInfo(position.coords.latitude,position.coords.longitude);
                })
            }
        });

        function search(address){

            var map;
            var service;
            var centerMap = $('#now_center').val();
            centerMap = centerMap.split(',');

            if(centerMap){
                user_long = parseFloat(centerMap[1]);
                user_lat = parseFloat(centerMap[0]);
            }else{
                user_long = 117.228692;
                user_lat = 31.822943;
            }

            map = new google.maps.Map(document.getElementById('hidden_map'), {
                center:{lat:user_lat,lng:user_long},
                zoom: 15
            });

            var request = {
                bounds:map.getBounds(),
                query: address
            };

            service = new google.maps.places.PlacesService(map);
            service.textSearch(request, callback);

            function callback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    getAdress(results,false,1);
                }
            }
        }

        function getPositionInfo(lat,lng){
            var map;
            var service;
            var centerMap = $('#now_center').val();
            centerMap = centerMap.split(',');
            if(centerMap){
                user_long = parseFloat(centerMap[1]);
                user_lat = parseFloat(centerMap[0]);
            } else{
                user_long = parseFloat(lng);
                user_lat = parseFloat(lat);
            }

            map = new google.maps.Map(document.getElementById('hidden_map'), {
                center:{lat:user_lat,lng:user_long},
                zoom: 15
            });

            var request = {
                location: {lat:user_lat,lng:user_long},
                radius: '200'
            };

            service = new google.maps.places.PlacesService(map);
            service.nearbySearch(request, callback);

            function callback(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    getAdress(results,false);
                }
            }
        }

        function getPositionAdress(result){
           if(result){
               var len = result.length-1;
               $.post("{pigcms{:U('Home/cityMatching')}",{'city_name':result[0].name,'area_name':result[len].name,'get_province':'1','all_city':'1'},function(res) {
                   console.log(result);
                   console.log(res);
                   if(res.status == 1){
                       $('.city_box span').html(res.info.area_name);
                       $('#province_id').val(res.info.province_id);
                       $('#city_id').val(res.info.area_id);
                       $('#area_id').val(res.info.now_area_id);
                       getAdress(result,false,0);
                   }else{
                       alert('当前城市不可用');
                   }
               });
           }else{
               alert('获取位置失败！');
           }

        }
        function getAdress(re,isSearch,isText){
            $('#addressShow').html('');
            var addressHtml = '';
            if(isText==1){
                for(var i=0;i<re.length;i++){
                    if (re[i].geometry.location.lng() == null || re[i].geometry.location.lat() == null) continue;
                    addressHtml += '<li lng="'+re[i].geometry.location.lng()+'" lat="'+re[i].geometry.location.lat()+'" sug_address="'+re[i].name+'" address="'+re[i].formatted_address+'" sname="'+re[i].name+'" class="addresslist" '+(isSearch ? 'data-search="true" data-city="'+re[0].name+'" data-district="'+re[re.length-1]['district']+'"' : '')+'>';
                    addressHtml += '<div class="mapaddress-title '+(i!=0 ? 'notself' : '')+'">';
                    addressHtml += '<span class="icon-location" data-node="icon"></span>';
                    addressHtml += '<span class="recommend"> '+(i == 0 ? '[建议位置]' : '')+'   '+re[i].name+' </span> </div>';
                    addressHtml += '<div class="mapaddress-body"> '+re[i].formatted_address+' </div>';
                    addressHtml += '</li>';
                }
            }else{
                for(var i=1;i<re.length-1;i++){
                    if (re[i].geometry.location.lng() == null || re[i].geometry.location.lat() == null) continue;
                    addressHtml += '<li lng="'+re[i].geometry.location.lng()+'" lat="'+re[i].geometry.location.lat()+'" sug_address="'+re[i].name+'" address="'+re[i].vicinity+'" sname="'+re[i].name+'" class="addresslist" '+(isSearch ? 'data-search="true" data-city="'+re[0].name+'" data-district="'+re[re.length-1]['district']+'"' : '')+'>';
                    addressHtml += '<div class="mapaddress-title '+(i!=1 ? 'notself' : '')+'">';
                    addressHtml += '<span class="icon-location" data-node="icon"></span>';
                    addressHtml += '<span class="recommend"> '+(i == 1 ? '[建议位置]' : '')+'   '+re[i].name+' </span> </div>';
                    addressHtml += '<div class="mapaddress-body"> '+re[i].vicinity+' </div>';
                    addressHtml += '</li>';
                }
            }
            $('#addressShow').append(addressHtml);
        }
    </script>
    <else />
	<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
	<script type="text/javascript">
	var address = '';
	var timeout = 0;
	var map;
	$(document).ready(function(){
		$('.map .MapHolder').height(($(window).height()-50)*0.4);
		$('.mapaddress').height(($(window).height()-52)*0.6);
		
		$('#fis_elm__4').height($(window).height()-52);
		
		var cityKey = [];
		$.each($('.cityKey'),function(i,item){
			cityKey.push($(item).data('city_key'));
		});
		$("#selectCharBox").css({'float':'right',height:($(window).height()-50),width:50,'z-index':9998}).seleChar({
			chars:cityKey,
			callback:function(ret){
				$('#fis_elm__4').scrollTop(($('#city_'+ret).position().top) + $('#fis_elm__4').scrollTop() - 50);
			}
		});
		
		$('.city_location').click(function(){
			$('.city_box span').html($(this).html());
			$('.city_box').removeClass('arrow');
			$('#fis_elm__3').show();
			$('#fis_elm__4').hide();
			
			map.centerAndZoom($(this).html(), 16);
			setTimeout(function(){
				var centerMap = map.getCenter();
				getPositionInfo(centerMap.lat,centerMap.lng);
			},700);		
		});
		
		$('.city_box').click(function(){
			if($(this).hasClass('arrow')){
				$(this).removeClass('arrow');
				$('#fis_elm__3').show();
				$('#fis_elm__4').hide();
			}else{
				$(this).addClass('arrow');
				$('#fis_elm__3').hide();
				$('#fis_elm__4').show();
			}
		});
		
		// 百度地图API功能
		map = new BMap.Map("cmmap",{enableMapClick:false});
		map.centerAndZoom(new BMap.Point(117.228692,31.822943), 16);
		
		
		map.addEventListener("dragend", function(e){
			$('#addressShow').empty();
			var centerMap = map.getCenter();
			getPositionInfo(centerMap.lat,centerMap.lng);
		});
		
		$("#se-input-wd").bind('input', function(e){
			var address = $.trim($('#se-input-wd').val());
			if(address.length > 0){
				$('#cmmap').hide();
				$('.mapaddress').height(($(window).height()-52));
				
				$('#addressShow').empty();
				clearTimeout(timeout);
				timeout = setTimeout("search('"+address+"')", 500);
			}else{
				$('#cmmap').show();
				$('.mapaddress').height(($(window).height()-52)*0.6);
			}
		});

		$('#addressShow').delegate("li","click",function(){
			info = JSON.parse($.cookie('user_address'));
			info.adress = $(this).attr("sname");
			info.longitude = $(this).attr("lng");
			info.latitude = $(this).attr("lat");
			if($(this).data('search')){
				$.post("{pigcms{:U('Home/cityMatching')}",{'city_name':$(this).data('city'),'area_name':$(this).data('district'),'get_province':'1','all_city':'1'},function(res){
					if(res.status == 1){
						$('.city_box span').html(res.info.area_name);
						$('#province_id').val(res.info.province_id);
						$('#city_id').val(res.info.area_id);
						$('#area_id').val(res.info.now_area_id);
						info.province = res.info.province_id;
						info.city = res.info.area_id;
						info.area = res.info.now_area_id;
						$.cookie('user_address', JSON.stringify(info));
						location.href = "{pigcms{:U('My/edit_adress',$params)}&adress_id="+info.id;
					}else{
						alert('当前城市不可用');
						return false;
					}
				});
				
			}else{
				info.province = $('#province_id').val();
				info.city = $('#city_id').val();
				info.area = $('#area_id').val();
				$.cookie('user_address', JSON.stringify(info));
				location.href = "{pigcms{:U('My/edit_adress',$params)}&adress_id="+info.id;
			}
		});

		// var geolocation = new BMap.Geolocation();
		
		
		var user_address = $.cookie('user_address');
		if(user_address){
			user_address = $.parseJSON(user_address);
		}
		if(user_address && user_address.longitude && user_address.latitude){
			map.centerAndZoom(new BMap.Point(user_address.longitude,user_address.latitude), 16);
			getPositionInfo(user_address.latitude,user_address.longitude);
		}else{
			getUserLocation({useHistory:false,okFunction:'getIframe'});
			/* geolocation.getCurrentPosition(function(r){
				if(this.getStatus() == BMAP_STATUS_SUCCESS){
					map.centerAndZoom(new BMap.Point(r.point.lng,r.point.lat), 16);
					getPositionInfo(r.point.lat,r.point.lng);
				}else{
					alert('failed：'+this.getStatus());
				}
			},{enableHighAccuracy: true}); */
		}
	});
	function getIframe(userLonglat,userLong,userLat){
		geoconv('realResult',userLong,userLat);
	}
	function realResult(result){
		var lng = result.result[0].x;
		var lat = result.result[0].y;
		var point = new BMap.Point(lng, lat);
		// alert(JSON.stringify(result));
		map.centerAndZoom(point, 16);
		getPositionInfo(lat, lng);
	}
	function search(address){
		$.get('index.php?g=Index&c=Map&a=suggestion', {city_id:$('#city_id').val(),query:$('.city_box span').html() + address}, function(data){
			if(data.status == 1){
				if(data.result[0] && data.result[0].city && data.result[0].district){
					getAdress(data.result,true);
				}
			}
			
		/* 	$.post("{pigcms{:U('Home/cityMatching')}",{'city_name':data.result[0].city,'area_name':data.result[0].district,'get_province':'1','all_city':'1'},function(res){
				if(res.status == 1){
					$('.city_box span').html(res.info.area_name);
					$('#province_id').val(res.info.province_id);
					$('#city_id').val(res.info.area_id);
					$('#area_id').val(res.info.now_area_id);
					
				}else{
					alert('当前城市不可用');
				}
			}); */
		});
	}
	function getPositionLocation(result){
		if(result.status == 0){
			result = result.result;
			getPositionInfo(result.location.lat,result.location.lng);
		}else{
			alert('获取位置失败！');
		}
	}
	function getPositionInfo(lat,lng){
		$.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&location='+lat+','+lng+'&output=json&pois=1&callback=getPositionAdress&json=?');
	}
	function getPositionAdress(result){
		if(result.status == 0){
			result = result.result;
			$.post("{pigcms{:U('Home/cityMatching')}",{'city_name':result.addressComponent.city,'area_name':result.addressComponent.district,'get_province':'1','all_city':'1'},function(res){
				console.log(result);
				console.log(res);
				if(res.status == 1){
					$('.city_box span').html(res.info.area_name);
					$('#province_id').val(res.info.province_id);
					$('#city_id').val(res.info.area_id);
					$('#area_id').val(res.info.now_area_id);
					var re = [];
					if(result.sematic_description.indexOf("附近0米") < 0){
						re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
					}
					for(var i in result.pois){
						re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
					}
					getAdress(re,false);
				}else{
					alert('当前城市不可用');
				}
			});
		}else{
			alert('获取位置失败！');
		}
	}
	function getAdress(re,isSearch){
		$('#addressShow').html('');
		var addressHtml = '';
		for(var i=0;i<re.length;i++){
			if (re[i]['long'] == null || re[i]['lat'] == null) continue;
			addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'" sname="'+re[i]['name']+'" class="addresslist" '+(isSearch ? 'data-search="true" data-city="'+re[i]['city']+'" data-district="'+re[i]['district']+'"' : '')+'>';
			addressHtml += '<div class="mapaddress-title '+(i!=0 ? 'notself' : '')+'">';
			addressHtml += '<span class="icon-location" data-node="icon"></span>';
			addressHtml += '<span class="recommend"> '+(i == 0 ? '[建议位置]' : '')+'   '+re[i]['name']+' </span> </div>';
			addressHtml += '<div class="mapaddress-body"> '+re[i]['address']+' </div>';
			addressHtml += '</li>';
		}
		$('#addressShow').append(addressHtml);
	}
	</script>
</if>
</html>