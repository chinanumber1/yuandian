$(document).ready(function(){
	if(typeof(is_google_map) == 'string' && is_google_map != "" && is_google_map.length > 5){
		$.getScript('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places&key='+is_google_map+'&callback=initialize_map');
	}else{
		$.getScript('https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2&callback=initialize_map');
	}

	if(common.getCache('isStarted',true)){
		$('#mainPage').show();
		$('#startBg').hide();
		$(".group_list a").each(function(){
			$(this).height($(this).width()*0.94);
		});
	}else{
		common.setCache('isStarted','true',true);
		$('#startBg img').css({height:$(window).height(),width:$(window).width()});
		$('#startBg').show();
		$('#mainPage').hide();
	}
	
	if(common.checkIosApp()){
		$('#biz-map').height($(window).height()-267-64);
		if(common.checkIphoneXApp()){
			$('.bottom').css('padding-bottom','34px');
			$('.map_location').css('bottom','114px');
			$('#biz-map').height($('#biz-map').height()-34);
		}
		common.iosFunction('changecolor/#289FFD');
	}else if(common.checkAndroidApp()){
		$('#biz-map').height($(window).height()-267-44);
		window.pigcmspackapp.changecolor('#289FFD');
	}else{
		$('#biz-map').height($(window).height()-267);
	}
	
	if(common.checkWeixin()){
		common.fillPageBg(1,'#f4f4f4');
	}
	
	if(common.checkLogin() == false){
		return false;
	}else{
		var DeliverUser = common.getCache('deliver_user',true);
		if(DeliverUser){
			indexData(DeliverUser);
			
			//预加载本地资源
			var localData = common.getCache('indexData',true);
			if(localData){
				$('.clerk_img span').css('background-image','url(' + localData.deliver_info.logo + ')');
				common.setData(localData);
			}
		}else{
			var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
			common.http('Deliver&a=login',{noTip:true, 'client':client}, function(data){
				common.setCache('ticket',data.ticket,true);
				common.setCache('deliver_user',data.user,true);
				indexData(data.user);
			},function(data){
				common.removeCache('ticket');
				common.removeCache('ticket',true);
				location.href = 'login.html';
			});
		}
	}
	
	$('#map_location').click(function(){
		map.panTo(mk.getPosition());
	});
});
var map=null,mk=null,index_data=null;
function indexData(DeliverUser){
	common.http('Deliver&a=new_index',{noTip:true}, function(data){
		data.deliver_info.logo = data.deliver_info.is_system ? data.deliver_info.system_image : data.deliver_info.store_image;
		data.deliver_info.tip = data.deliver_info.is_system ? '系统配送员' : '商家配送员 '+data.deliver_info.store_name+'店铺';
		index_data = data;
		$('.clerk_img span').css('background-image','url(' + data.deliver_info.logo + ')');
		common.setData(data);
		
		if(data.deliver_info){
			common.setCache('indexData',data,true);
		}
		
		//请求完页面参数判断是否需要直接跳转，例如页面列表，或订单详情。
		if(urlParam.gopage && !common.getCache('isGoOtherPage',true)){
			var href = location.protocol+'//'+location.host+'/packapp/'+visitWork+'/'+urlParam.gopage+'.html' + (urlParam.goparam ? '?'+urlParam.goparam : '');
		
			if(common.checkApp()){
				if(common.checkAndroidApp()){
					window.pigcmspackapp.createwebview(href);
				}else{
					common.iosFunction('createwebview/'+window.btoa(href));
				}
			}else{
				common.setCache('isGoOtherPage','true',true);
				location.href = href;
				return false;
			}
		}
		
		$('#mainPage').show();
		$('#startBg').hide();
		
		locationEvent();
		setInterval('locationEvent()',30000);	//30秒轮询上报一次
		setInterval('pollorder()',3000);	//3秒一次轮询新订单
	},function(data){
		if(data.errorCode == '20030102'){
			layer.open({
				content: data.errorMsg
				,btn: ['确定']
				,yes: function(index){
					common.removeCache('ticket');
					common.removeCache('ticket',true);
					layer.close(index);
					location.href = 'login.html';
				}
			});
		}else{
			motify.log(data.errorMsg);
		}
	});
	
	if(common.checkIos()){
		console.log('IOS判断');
		window.addEventListener('touchstart',loadTipMp3, false);
	}else{
		loadTipMp3();
	}
}

var isLoaded = false;
function loadTipMp3(){
	if(isLoaded == false){
		console.log('加载音乐');
		var myVideo=document.getElementById("newOrderMp3");
		myVideo.load();
		isLoaded = true;
	}
}

var newOrderTipIndex = -2;
function pollorder(){
	common.http('Deliver&a=new_index',{noTip:true,poll:1}, function(data){
		/*var myVideo = document.getElementById("newOrderMp3");
		var gray_count = parseInt($('#gray_count').html());
		var data_gray_count = parseInt(data.gray_count);
		
		console.log('======');
		console.log(gray_count);
		console.log(data_gray_count);
		
		if(gray_count != 0 && data_gray_count == 0){
			myVideo.pause();
			myVideo.currentTime = 0.0;
			if(newOrderTipIndex != -2){
				console.log('没有订单了，关闭了提示层');
				layer.close(newOrderTipIndex);
			}
		}else if(data_gray_count > gray_count){
			newOrderTipIndex = layer.open({
				content: '您有新的待抢订单需要处理'
				,btn: ['前往', '关闭']
				,shadeClose:false
				,yes: function(index){
					myVideo.pause();
					myVideo.currentTime = 0.0;
					window.location.href = 'grab.html';
					layer.close(index);
				}
				,end: function(index){
					console.log('关闭了音乐-提示层ID:'+newOrderTipIndex);
					myVideo.pause();
					myVideo.currentTime = 0.0;
					layer.close(index);
				}
			});
			myVideo.play();
		}*/
		common.setData(data);
	});
}

function locationEvent(){
	if(common.checkIosApp()){
		common.iosFunction('getlocation/locationOk');
	}else if(common.checkAndroidApp()){
		window.pigcmspackapp.getlocation('locationOk');
	}else if(mk != null){
		if(is_google_map != ""){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                	if(position){
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        common.setCache('lastAddress',pos,true);
                        map.panTo(pos);
                        mk.setPosition(pos);
                        map.setCenter(pos);
                        locationReport(pos.lng,pos.lat);
					}else{
                        motify.log('failed');
					}
                })
            }
		}else{
            var geolocation = new BMap.Geolocation();
            geolocation.getCurrentPosition(function(r){
                if(this.getStatus() == BMAP_STATUS_SUCCESS){
                    common.setCache('lastAddress',r.point,true);
                    map.panTo(r.point);
                    mk.setPosition(r.point);
                    locationReport(r.point.lng,r.point.lat);
                }else{
                    motify.log('failed'+this.getStatus());
                }
            },{enableHighAccuracy:true});
		}

	}
}

function locationOk(name,lng,lat){
	if(mk != null){
		var point = new BMap.Point(lng,lat);
		// alert(JSON.stringify(point));
		map.panTo(point);
		mk.setPosition(point);
		locationReport(lng,lat);
	}else{
		var locationMapTimer = setInterval(function(){
			if(mk != null){
				clearInterval(locationMapTimer);
				var point = new BMap.Point(lng,lat);
				// alert(JSON.stringify(point));
				map.panTo(point);
				mk.setPosition(point);
				locationReport(lng,lat);
			}
		},200);
	}
}

function locationReport(lng,lat){
	common.setCache('deliver_lng',lng);
	common.setCache('deliver_lat',lat);
	console.log('上报：'+lng+'-'+lat);
	common.http('Deliver&a=location',{noTip:true,lng:lng,lat:lat}, function(data){
		console.log('上报成功：'+JSON.stringify(data));
	},function(data){
		console.log('上报失败：'+JSON.stringify(data));
	});
}

var exitLayer = -1;
function appbackmonitor(){
	if(exitLayer != -1){
		window.pigcmspackapp.closewebview(2);
	}else{
		layer.closeAll();
		exitLayer = layer.open({
			content: '您确定要退出程序吗？再次按返回键将退出。'
			,btn: ['确定', '取消']
			,yes: function(index){
				window.pigcmspackapp.closewebview(2);
				layer.close(index);
			}
			,end: function(index){
				exitLayer = -1;
			}
		});
	}
}

function initialize_map(){
    if(typeof(is_google_map) == 'string' && is_google_map != "" && is_google_map.length > 5){

        var createMapTimer = setInterval(function() {
            var lastAddress = common.getCache('lastAddress', true);
            var tmpPoint = null;
            if (lastAddress) {
                tmpPoint = {lng: parseFloat(lastAddress.lng), lat: parseFloat(lastAddress.lat)};
            } else if (index_data != null) {
                tmpPoint = {lng: parseFloat(index_data.deliver_info.map_lng), lat: parseFloat(index_data.deliver_info.map_lat)};
            }
            map = new google.maps.Map(document.getElementById('biz-map'),{
                center: tmpPoint,
                zoom:16,
                streetViewControl:false,
                mapTypeControl:false
            });
            if(tmpPoint){
                clearInterval(createMapTimer);
                // alert(JSON.stringify(tmpPoint));
                map.setCenter(tmpPoint,16);
                mk = new google.maps.Marker({
                    position: tmpPoint,
                    map:map
                });
            }
        },200);
	}else{
        map = new BMap.Map("biz-map");

        var createMapTimer = setInterval(function(){
            var lastAddress = common.getCache('lastAddress',true);
            var tmpPoint = null;
            if(lastAddress){
                tmpPoint = new BMap.Point(lastAddress.lng,lastAddress.lat);
            }else if(index_data != null){
                tmpPoint = new BMap.Point(index_data.deliver_info.map_lng,index_data.deliver_info.map_lat);
            }
            if(tmpPoint){
                clearInterval(createMapTimer);
                // alert(JSON.stringify(tmpPoint));
                map.centerAndZoom(tmpPoint,16);
                mk = new BMap.Marker(tmpPoint);
                map.addOverlay(mk);
            }
        },200);
	}

}