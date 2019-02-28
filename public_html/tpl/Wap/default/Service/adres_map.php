<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="keywords" content="{pigcms{$config.seo_keywords}">
	<meta name="description" content="{pigcms{$config.seo_description}">
	<title>查看地址</title>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_5e96991.css">
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css">
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	 <style>
            #address-widget-map .address-map-nav .left-slogan .left-arrow {
                color: #999;
                padding-left: 7px;
                display: inline-block;
                margin-top: 14px;
            }
            #address-widget-map .address-map-nav .center-title input {
                border: 0;
                border-radius: 0;
                color: #404142;
                font-size: 16px;
                height: 16px;
                padding: 20px 0 20px 25px;
                background: #f3f3f3;

            }
            .mapaddress{
                width: 100%;
            }
            #address-widget-map .address-map-nav .center-title {
                float: left;
                width: 80%;
            }
        </style>
</head>
<body class="hIphone" style="padding-bottom:initial;">
<div id="hidden_map" style="display: none;"></div>
<input type="hidden" id="now_center" value="" />
	<div id="wrapper">
		<div id="fis_elm__2">
			<div id="address-widget-map" class="address-widget-map">
				<div class="address-map-nav">
					<div class="left-slogan" onclick="history.go(-1);"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="javascript:;"></a></div>
					<div class="center-title" style="margin-top: 4px"> <i class="icon-location" data-node="icon"></i>
						<div class="ui-suggestion-mask">
							<input type="text" placeholder="请输入小区、大厦或学校" id="se-input-wd" autocomplete="off">
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
					<!--div class="map" style="display: none">
						<div class="MapHolder" id="cmmap"></div>
						<div class="dot" style="display:block;"></div>
					</div-->
					<div class="mapaddress" data-node="mapaddress">
						<ul id="addressShow"> </ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript">
        var address = '{pigcms{$address}';
        var timeout = 0;
        $(document).ready(function(){


            $("#se-input-wd").bind('input', function(e){
                var address = $.trim($('#se-input-wd').val());
                if(address.length>0 && address !== '请输入小区、大厦或学校'){
                    $('#addressShow').empty();
                    clearTimeout(timeout);
                    timeout = setTimeout("search('"+address+"')", 500);
                }
            });

            $('#addressShow').delegate("li","click",function(){
                var sname = $(this).attr("sname");
                var lng = $(this).attr("lng");
                var lat = $(this).attr("lat");
                var cityMatchingUrl = "{pigcms{:U('Service/cityMatching')}";
                $.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
                    if(data.error == 1){
                        location.href = "{pigcms{:U('Service/service_publish')}&sname="+sname+"&lng="+lng+"&lat="+lat+"&area_id="+data.area_id+"&area_name="+data.area_name;
                    }else{
                        layer.open({
                            content: data.msg
                            ,btn: ['确定']
                        });
                    }
                },'json');
            });
            //初始化
            $('#now_center').val('31.822943,117.228692');
            getPositionInfo(31.822943,117.228692);

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
                    $('#now_center').val(parseFloat(position.coords.latitude)+','+parseFloat(position.coords.longitude));
                    getPositionInfo(position.coords.latitude,position.coords.longitude);
                });
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
        function getPositionLocation(result)
        {
            if(result.status == 0){
                result = result.result;
                getPositionInfo(result.location.lat,result.location.lng);
            }else{
                layer.open({
                    content: '获取位置失败！'
                    ,btn: ['确定']
                });
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
            if(result.status == 0){
                result = result.result;
                var re = [];
                re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
                for(var i in result.pois){
                    re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
                }
                getAdress(re);
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
var address = '{pigcms{$address}';
var timeout = 0;
$(document).ready(function(){
	$("#se-input-wd").bind('input', function(e){
		var address = $.trim($('#se-input-wd').val());
		if(address.length>0 && address !== '请输入小区、大厦或学校'){
			$('#addressShow').empty();
			clearTimeout(timeout);
			timeout = setTimeout("search('"+address+"')", 500);
		}
	});

	$('#addressShow').delegate("li","click",function(){ 
		var sname = $(this).attr("sname");
		var lng = $(this).attr("lng");
		var lat = $(this).attr("lat");
		var cityMatchingUrl = "{pigcms{:U('Service/cityMatching')}";
		$.get(cityMatchingUrl, {'lng':lng,'lat':lat}, function(data){
			if(data.error == 1){
				location.href = "{pigcms{:U('Service/service_publish')}&sname="+sname+"&lng="+lng+"&lat="+lat+"&area_id="+data.area_id+"&area_name="+data.area_name;
			}else{
				layer.open({
			    	content: data.msg
				    ,btn: ['确定']
			  	});
			}
		},'json');
	}); 

	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			getPositionInfo(r.point.lat,r.point.lng);
		} else {
			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true});
});

function search(address)
{
	$.get('index.php?g=Index&c=Map&a=suggestion', {query:address}, function(data){
		if(data.status == 1){
			getAdress(data.result);
		}
	});
}
function getPositionLocation(result)
{
	if(result.status == 0){
		result = result.result;
		getPositionInfo(result.location.lat,result.location.lng);
	}else{
		layer.open({
	    	content: '获取位置失败！'
		    ,btn: ['确定']
	  	});
	}
}
function getPositionInfo(lat,lng){
	$.getJSON('https://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=renderReverse&location='+lat+','+lng+'&output=json&pois=1&callback=getPositionAdress&json=?');
}
function getPositionAdress(result){
	if(result.status == 0){
		result = result.result;
		var re = [];
		re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
		for(var i in result.pois){
			re.push({'name':result.pois[i].name,'address':result.pois[i].addr,'long':result.pois[i].point.x,'lat':result.pois[i].point.y});
		}
		getAdress(re);
	}else{
		alert('获取位置失败！');
	}
}
function getAdress(re){
	$('#addressShow').html('');
	var addressHtml = '';
	for(var i=0;i<re.length;i++){
		addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'" sname="'+re[i]['name']+'" class="addresslist">';
		addressHtml += '<div class="mapaddress-title"> <span class="icon-location" data-node="icon"></span> <span class="recommend"> '+(i == 0 ? '[推荐位置]' : '')+'   '+re[i]['name']+' </span> </div>';
		addressHtml += '<div class="mapaddress-body"> '+re[i]['address']+' </div>';
		addressHtml += '</li>';
	}
	$('#addressShow').append(addressHtml);
}
</script>
</if>
</html>