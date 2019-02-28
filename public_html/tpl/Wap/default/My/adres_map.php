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
</head>
<body class=" hIphone" style="padding-bottom: initial;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_5e96991.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}images/hm.gif" width="0" height="0" style="display:block">
<div id="wrapper" class="">
    <div id="fis_elm__2">
        <div id="address-widget-map" class="address-widget-map">
            <div class="address-map-nav">
                <div class="left-slogan" style="margin-top: 12px"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="javascript:history.go(-1);"></a></div>
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
<div id="fis_elm__4"></div>
<div class="global-mask layout"></div>
<div id="fis_elm__6"></div>
<div id="fis_elm__7"></div>

</body>
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
		info = JSON.parse($.cookie('user_address'));
		info.adress = $(this).attr("sname");
		info.longitude = $(this).attr("lng");
		info.latitude = $(this).attr("lat");
		$.cookie('user_address', JSON.stringify(info));
		location.href = "{pigcms{:U('My/edit_adress', $params)}&adress_id="+info.id;
	}); 
// 	$.getJSON('http://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&address='+address+'&output=json&callback=getPositionLocation&json=?');
	$.getScript("http://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2",function(){
		var geolocation = new BMap.Geolocation();
		geolocation.getCurrentPosition(function(r){
			if(this.getStatus() == BMAP_STATUS_SUCCESS){
				getPositionInfo(r.point.lat,r.point.lng);
			} else {
				alert('failed'+this.getStatus());
			}        
		},{enableHighAccuracy: true})
// 		var map = new BMap.Map("cmmap",{"enableMapClick":false});
// 		map.centerAndZoom(new BMap.Point(116.331398,39.897445), 16);
// 		map.centerAndZoom(address, 16);
// 		$.getJSON('http://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&address='+address+'&output=json&callback=getPositionLocation&json=?');
// // 		var geolocation = new BMap.Geolocation();
// // 		geolocation.getCurrentPosition(function(r){
// // 			if(this.getStatus() == BMAP_STATUS_SUCCESS){
// // 				map.panTo(r.point);
// // 				getPositionInfo(r.point.lat,r.point.lng);
// // 			}else{
// // 				function myFun(result){
// // 					map.panTo(new BMap.Point(result.center['lng'],result.center['lat']));
// // 					getPositionInfo(result.center['lat'],result.center['lng']);
// // 				}
// // 				var myCity = new BMap.LocalCity();
// // 				myCity.get(myFun);
// // 			}
// // 		},{enableHighAccuracy: true});
	
		
			
			
// 		map.addControl(new BMap.NavigationControl());
// 		map.enableScrollWheelZoom();

// 		map.addEventListener("dragend", function(e){
// 			$('#addressShow').empty();
// 			var centerMap = map.getCenter();
// 			getPositionInfo(centerMap.lat,centerMap.lng);
// 		});
	});
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
		alert('获取位置失败！');
	}
}
function getPositionInfo(lat,lng){
	$.getJSON('http://api.map.baidu.com/geocoder/v2/?ak=4c1bb2055e24296bbaef36574877b4e2&callback=renderReverse&location='+lat+','+lng+'&output=json&pois=1&callback=getPositionAdress&json=?');
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
</html>