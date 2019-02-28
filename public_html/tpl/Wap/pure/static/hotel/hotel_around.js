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
    if(typeof(is_google_map) != "undefined")
    {
        var result = {result:[{x:lng,y:lat}]};
        getStoreListBefore(result);
    }else{
        geoconv('getStoreListBefore', lng, lat);
    }
}
function getStoreListBefore(result){
	getMap(result.result[0].x,result.result[0].y);
}
var tmpLng,tmpLat,map,storeBox=[],storePoint=[],infoWindows=[];
function getMap(lng,lat) {

    if (typeof(is_google_map) != "undefined") {
        map = new google.maps.Map(document.getElementById("container"), {
            mapTypeControl: false,
            zoom: 15,
            center: {lng, lat}


        });
        tmpLng = lng;
        tmpLat = lat;
        getStoreList(tmpLng, tmpLat);

        map.addListener("dragend", function showInfo() {
            if (map.getZoom() >= 15) {
                motify.clearLog();
                var cp = map.getCenter();
                var range = GetDistance(tmpLng, tmpLat, cp.lng(), cp.lat());
                if (range > 300) {
                    tmpLng = cp.lng();
                    tmpLat = cp.lat();
                    getStoreList(tmpLng, tmpLat);
                }
            } else {
                motify.log('地图范围过大，请扩大后查看');
            }
        });
        map.addListener("zoom_changed", function () {
            motify.clearLog();
            if (this.getZoom() < 15) {
                $.each(storePoint, function (i, item) {
                    storePoint[i].setMap(null);  //这里清除
                });
                motify.log('地图范围过大，请扩大后查看');
            }
        });
    } else {
        map = new BMap.Map("container");            // 创建Map实例
        if (city_name != '') {
            point = city_name;
        } else {
            point = new BMap.Point(lng, lat)
        }
        map.centerAndZoom(point, 15);                 // 初始化地图,设置中心点坐标和地图级别。
        // map.addControl(new BMap.ZoomControl());      //添加地图缩放控件
        // var marker = new BMap.Marker(new BMap.Point(lng,lat));
        // map.addOverlay(marker);


        tmpLng = lng;
        tmpLat = lat;
        getStoreList(tmpLng, tmpLat);
        map.addEventListener('touchstart', function (e) {
            _ClickClassName = e.domEvent.srcElement.className;
            var dom = $(e.domEvent.srcElement)
            $.each($('.hotel_price'), function (index, v) {
                $(v).parent().css('backgroundColor', '#6caeca');
                $(v).parent().css('border', '#5993af');
                $(v).next().css('background', 'url(http://map.baidu.com/fwmap/upload/r/map/fwmap/static/house/images/label.png) 0px -20px no-repeat')
            })

            if (/(hotel_price)/.test(_ClickClassName)) {
                var x = _ClickClassName.replace(/[^0-9]/ig, "");
                map.openInfoWindow(infoWindows[x], storePoint[x]);

                dom.parent().css('backgroundColor', '#ff9625')
                dom.parent().css('border', '#de8921')
                dom.next().css('background', 'url(http://map.baidu.com/fwmap/upload/r/map/fwmap/static/house/images/label.png) 0px -10px no-repeat')
            }
            console.log(infoWindows)

        });

        map.addEventListener("infowindowclose", function (e) {
            console.log(e)
        })
        map.addEventListener("dragend", function showInfo() {
            if (map.getZoom() >= 15) {
                motify.clearLog();
                var cp = map.getCenter();
                var range = GetDistance(tmpLng, tmpLat, cp.lng, cp.lat);
                if (range > 300) {
                    tmpLng = cp.lng;
                    tmpLat = cp.lat;
                    getStoreList(tmpLng, tmpLat);
                }
            } else {
                motify.log('地图范围过大，请扩大后查看');
            }
        });
        map.addEventListener("zoomend", function () {
            motify.clearLog();
            if (this.getZoom() < 15) {
                map.clearOverlays();
                motify.log('地图范围过大，请扩大后查看');
            }
        });
    }
}
$('.windowBox').on('click',function(e){
    console.log(e);
});
if(typeof (is_google_map)!="undefined"){
    //附近店铺列表
    function getStoreList(lng,lat){
        $.each(storePoint,function(i,item){
            storePoint[i].setMap(null);  //这里清除
        });
        motify.log('正在加载周边店铺',0,{show:true});
        $.post(window.location.pathname+'?c=Hotel&a=ajax_hotel_around',{lng:lng,lat:lat},function(result){
            if(result.length > 0){
                storePoint = [];
                infoWindows = [];
                var listHtml = '';
                    $.each(result,function(i,item){
                        var listUrl = window.location.pathname+'?c=Group&a=detail&group_id='+item.group_id;
                        listHtml+= '<a href='+listUrl+'> <dd class="link-url" ><div class="title">'+item.sname+'</div><div class="phone">电话：'+item.sphone+'</div><div class="desc">地址：'+item.adress+'</div></dd></a>';
                        var contentString ="<div class='windowBox link-url' data-url="+listUrl+"><a href="+listUrl+"><div class='hotel_name'>"+item.sname+"</div></a><div class='reply'>"+item.score_mean+"分/"+item.reply_count+"条评论</div></div>";

                        var marker = new google.maps.Marker({
                            position: {lng:parseFloat(item['long']),lat:parseFloat(item['lat'])},
                            map: map,
                            icon: static_path+"images/red_marker.png"
                        });
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                        infoWindows[i] = infowindow;
                        marker.addListener('click', function() {
                            for(var index in infoWindows){
                                infoWindows[index].close();
                            }
                            infowindow.open(map, marker);
                        });
                        storePoint[i] = marker;

                        marker.addListener("click", function(){
                            $.each(result,function(k,ktem){
                                if(i == k){
                                    storePoint[k].setIcon(static_path+"images/blue_marker.png");
                                }else{
                                    storePoint[k].setIcon(static_path+"images/red_marker.png");
                                }
                            });

                        });
                    });

                $('#listList dl').html(listHtml);
            }else{
                $('#listList dl').empty();
            }
            motify.clearLog();
        },'json');
        $(document).on('click','#listBtn',function(){
            console.log(11)
            $('#listList').height('auto');
            if($('#listList dl').html() != ''){
                $('#listBg,#listList').show();
                if($('#listList dl').height() < $('#listList').height()){
                    $('#listList').css({height:$('#listList dl').height()-1+'px',top:(($(window).height()-$('#listList dl').height())/2)});
                }
                myScroll.refresh();
            }else{
                motify.log('屏幕地图中没有店铺');
            }
        });
        $(document).on('click','#listBg',function(){
            $('#listBg,#listList').hide();
        });
    }
}else{
    //附近店铺列表
    function getStoreList(lng,lat){
        motify.log('正在加载周边店铺',0,{show:true});
        // $.each(storePoint,function(i,item){
        // storePoint[i].closeInfoWindow();
        // });
        map.clearOverlays();
        storePoint = [];
        infoWindows = [];
        // lng = 117.238061;
        // lat = 31.814095;
        $.post(window.location.pathname+'?c=Hotel&a=ajax_hotel_around',{lng:lng,lat:lat},function(result){
            if(result.length > 0){
                var listHtml = '';
                $.each(result,function(i,item){
                    var listUrl = window.location.pathname+'?c=Group&a=detail&group_id='+item.group_id;
                    listHtml+= '<a href="'+listUrl+'"> <dd class="link-url" ><div class="title">'+item.sname+'</div><div class="phone">电话：'+item.sphone+'</div><div class="desc">地址：'+item.adress+'</div></dd></a>';

                    var point_ = new BMap.Point(item['long'],item['lat']);
                    var mySquare = new SquareOverlay(point_, 20, "￥"+item.price+'起',i);
                    map.addOverlay(mySquare);
                    storePoint[i] = point_;

                    infoWindows[i] = new BMap.InfoWindow('<div class="windowBox link-url" data-url="'+listUrl+'"><a href="'+listUrl+'"><div class="hotel_name">'+item.sname+'</div><div class="reply">'+item.score_mean+'分/'+item.reply_count+'条评论</div></a></div>');

                });

                $('#listList dl').html(listHtml);
            }else{
                $('#listList dl').empty();
            }
            motify.clearLog();
        },'json');
        $(document).on('click','#listBtn',function(){
            console.log(11)
            $('#listList').height('auto');
            if($('#listList dl').html() != ''){
                $('#listBg,#listList').show();
                if($('#listList dl').height() < $('#listList').height()){
                    $('#listList').css({height:$('#listList dl').height()-1+'px',top:(($(window).height()-$('#listList dl').height())/2)});
                }
                myScroll.refresh();
            }else{
                motify.log('屏幕地图中没有店铺');
            }
        });
        $(document).on('click','#listBg',function(){
            $('#listBg,#listList').hide();
        });
    }
}
