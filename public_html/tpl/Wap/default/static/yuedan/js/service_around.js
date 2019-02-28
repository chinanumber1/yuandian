// var myScroll;
$(function(){
	$('#around-map').height($(window).height());
	// $('#scroller').css({'min-height':($(window).height()+1)+'px'});
	// myScroll = new IScroll('#listList', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	myScroll = new IScroll('#listList',{probeType:1,disableMouse:true,disablePointer:true,mouseWheel:false,scrollX:false,scrollY:true,click:iScrollClick()});
	
	motify.log('正在调用地图组件',0,{show:true});
	if(user_long == '0' || !user_long){
		if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50 && (motify.checkIos() || motify.checkAndroid())){
			if(motify.checkAndroid()){
				var locations = window.lifepasslogin.getLocation(false);
				var locationArr = locations.split(',');
				var user_long = $.trim(locationArr[0]);
				var user_lat = $.trim(locationArr[1]);
				getStoreListBefore({result:[{x:user_long,y:user_lat}]});
			}else{
				$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
			}
		}else{
			getUserLocation({okFunction:'geoconvPlace',useHistory:false});
		}
		// getUserLocation({okFunction:'geoconvPlace',useHistory:false});
	}else{
		getMap(user_long,user_lat);
	}
});
function callbackLocation(locations){
	var locationArr = locations.split(',');
	var user_long = $.trim(locationArr[0]);
	var user_lat = $.trim(locationArr[1]);
	getStoreListBefore({result:[{x:user_long,y:user_lat}]});
}
function geoconvPlace(userLongLat,lng,lat){
	geoconv('getStoreListBefore',lng,lat);
}
function getStoreListBefore(result){
	getMap(result.result[0].x,result.result[0].y);
}
var tmpLng,tmpLat,map,storeBox=[],storePoint=[];
function getMap(lng,lat){
	map = new BMap.Map("around-map",{enableMapClick:false});            // 创建Map实例
	map.centerAndZoom(new BMap.Point(lng,lat),15);                 // 初始化地图,设置中心点坐标和地图级别。
	// map.addControl(new BMap.ZoomControl());      //添加地图缩放控件	
	// var marker = new BMap.Marker(new BMap.Point(lng,lat));
	// map.addOverlay(marker);
	tmpLng = lng;
	tmpLat = lat;
	getStoreList(tmpLng,tmpLat);
	map.addEventListener("dragend", function showInfo(){
		if(map.getZoom() >= 15){
			motify.clearLog();
			var cp = map.getCenter();
			var range = GetDistance(tmpLng,tmpLat,cp.lng,cp.lat);
			if(range > 300){
				tmpLng = cp.lng;
				tmpLat = cp.lat;
				getStoreList(tmpLng,tmpLat);
			}
		}else{
			motify.log('地图范围过大，请扩大后查看');
		}
	});
	map.addEventListener("zoomend", function(){
		motify.clearLog();
		if(this.getZoom() < 15){
			map.clearOverlays();
			motify.log('地图范围过大，请扩大后查看');
		}
	});   
}
//附近服务列表
function getStoreList(lng,lat){
	motify.log('正在加载周边服务',0,{show:true});
	$.each(storePoint,function(i,item){
		storePoint[i].closeInfoWindow();
	});
	map.clearOverlays();
	storePoint = [];
	$.post('wap.php?c=Yuedan&a=ajaxAround',{lng:lng,lat:lat},function(result){
		if(result){
			var listHtml = '';
			$.each(result,function(i,item){
				var listUrl = window.location.pathname+'?c=Yuedan&a=service_detail&rid='+item.rid;
				listHtml+= '<dd class="link-url" data-url="'+listUrl+'"><div class="title">'+item.title+'</div><div class="desc">价格：'+item.price+'/'+item.unit+'</div><div class="phone">昵称：'+item.nickname+'</div></dd>';
				
				var marker = new BMap.Marker(new BMap.Point(item['address_lng'],item['address_lat']),{icon:new BMap.Icon(item.listimg, new BMap.Size(50,50))});
				

				map.addOverlay(marker);
				storePoint[i] = marker;
				// console.log(item.listimg);
				var infoWindow = new BMap.InfoWindow('<div class="windowBox link-url" data-url="'+listUrl+'"><a href="'+listUrl+'"><div><img id="imgDemo" style="width:80px;height:80px;" src="'+item.listimg+'"/></div><div><p>'+item.title+'</p><p>'+item.price+'/'+item.unit+'</p><p>'+item.nickname+'</p></div></a></div>');
				marker.addEventListener("click", function(){
					this.openInfoWindow(infoWindow);
				});
			});
			$('#listList dl').html(listHtml);
		}else{
			$('#listList dl').empty();
		}
		motify.clearLog();
	});
	$(document).on('click','#listBtn',function(){
		$('#listList').height('auto');
		if($('#listList dl').html() != ''){
			$('#listBg,#listList').show();
			if($('#listList dl').height() < $('#listList').height()){
				$('#listList').css({height:$('#listList dl').height()-1+'px',top:(($(window).height()-$('#listList dl').height())/2)});
			}
			myScroll.refresh();
		}else{
			motify.log('屏幕地图中没有服务');
		}
	});
	$(document).on('click','#listBg',function(){
		$('#listBg,#listList').hide();
	});
}