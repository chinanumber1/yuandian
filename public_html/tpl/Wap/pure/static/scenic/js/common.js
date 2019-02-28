/* jQuery cookie 操作*/
jQuery.cookie = function (key, value, options) {
    if (arguments.length > 1 && (value === null || typeof value !== "object")){
        options = jQuery.extend({}, options);
        if (value === null) {
            options.expires = -1;
        }
        if (typeof options.expires === 'number'){
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }
        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? String(value) : encodeURIComponent(String(value)),
            options.expires ? '; expires=' + options.expires.toUTCString() : '',
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
/*
 *
 *获得用户经纬度方法
 *
 * 无返回值！如果需要返回值，只能使用 okFunction 调用其他方法
 *
*/
function getUserLocation(options){
	this.options = {
		'useHistory':true,				//boolean 是否使用历史数据
		'historyTime':120,				//number  使用历史数据的时效，也是保存数据的有效期
		'okFunction':false,				//string 得到位置之后调用的方法， refresh 代表刷新页面
		'okFunctionParam':[],		//array 调用方法的自定义参数，以数组形式传参！系统参数：
		'errorFunction':false,			//string 没得到位置调用的方法， refresh 代表刷新页面
		'errorFunctionParam':[],		//array 调用方法的自定义参数，以数组形式传参！系统参数：
		'errorAction':1,				//number 1代表给指定“errorTipTime”参数时间内的提示，2代表弹层给出确认按钮的提示后跳转“errorUrl”（需要自行加载layer.js支持），3代表直接跳转到URL，4代表不提示
		'errorUrl':'',					//string errorAction 等于2或3时生效，值 history 代表上一页 值  href 代表刷新当前页面
		'errorTipTime':3,				//number  给有效时间提示的时间 	0代表长时间存在
		'errorTipTitle':'错误提示：',	//string errorAction 等于2生效
		'errorContentSuffix':'',	//string errorAction 等于2生效
		'errorShade':false,				//boolean errorAction 等于1时，是否开启遮罩层
		'reportLocation':true,			//boolean 是否上报用户地理位置，默认开启
		'okFunctionReport':true			//boolean 是否在上报用户地理位置成功后再执行方法，在 reportLocation为true时生效
	}
	for (var i in options){
		this.options[i] = options[i];
	}
	options = this.options;
	if(options.useHistory && $.cookie('userLocationLong') && $.cookie('userLocationLat')){
		options['userLocation'] = $.cookie('userLocation');
		options['userLocationLong'] = $.cookie('userLocationLong');
		options['userLocationLat'] = $.cookie('userLocationLat');
		locationOkFun(options);
		return false;
	}
	if(typeof(wxSdkLoad) != "undefined"){
		wx.ready(function () {
			wx.getLocation({
				type: 'wgs84',
				success: function (res) {
					var userLat = res.latitude;
					var userLong = res.longitude;
					options['userLocation'] = userLong+','+userLat;
					options['userLocationLong'] = userLong;
					options['userLocationLat'] = userLat;
					//通过 cookie 记录用户的经纬度，不通过H5的本地存储方便PHP调用
					var expire =  new Date();
					expire.setTime(expire.getTime() + options.historyTime*1000);
					options.historyTime = expire;
					$.cookie('userLocation',options['userLocation'],{expires:options.historyTime,path:'/'});
					$.cookie('userLocationLong',options['userLocationLong'],{expires:options.historyTime,path:'/'});
					$.cookie('userLocationLat',options['userLocationLat'],{expires:options.historyTime,path:'/'});
					$.cookie('userLocationHasRecord',0,{expires:options.historyTime,path:'/'});

					if(options.reportLocation){
						$.post(window.location.pathname+'?c=Userlonglat&a=report',{userLong:userLong,userLat:userLat},function(){
							if(options.okFunctionReport){
								locationOkFun(options);
							}
						});
						if(!options.okFunctionReport){
							locationOkFun(options);
						}
					}else{
						locationOkFun(options);
					}
				},
				fail: function(res){
					locationErorrTip(res.errMsg);
				},
				cancel: function(res){
					if(res.errMsg == 'getLocation:cancel'){
						options['errorMsg'] = '获取位置信息失败,用户拒绝请求地理定位';
					}
					locationErorrTip(options);
				}
			});
		});
	}else if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(function(position){
			var userLong = position.coords.longitude.toFixed(6);
			var userLat  = position.coords.latitude.toFixed(6);
			options['userLocation'] = userLong+','+userLat;
			options['userLocationLong'] = userLong;
			options['userLocationLat'] = userLat;
			//通过 cookie 记录用户的经纬度，不通过H5的本地存储方便PHP调用
			var expire =  new Date();
			expire.setTime(expire.getTime() + options.historyTime*1000);
			options.historyTime = expire;
			$.cookie('userLocation',options['userLocation'],{expires:options.historyTime,path:'/'});
			$.cookie('userLocationLong',options['userLocationLong'],{expires:options.historyTime,path:'/'});
			$.cookie('userLocationLat',options['userLocationLat'],{expires:options.historyTime,path:'/'});
			$.cookie('userLocationHasRecord',0,{expires:options.historyTime,path:'/'});

			if(options.reportLocation){
				$.post(window.location.pathname+'?c=Userlonglat&a=report',{userLong:userLong,userLat:userLat},function(){
					if(options.okFunctionReport){
						locationOkFun(options);
					}
				});
				if(!options.okFunctionReport){
					locationOkFun(options);
				}
			}else{
				locationOkFun(options);
			}
		},function(error){
			switch (error.code){
				case error.PERMISSION_DENIED:
					options['errorMsg'] = '获取位置信息失败,用户拒绝请求地理定位';
					break;
				case error.POSITION_UNAVAILABLE:
					options['errorMsg'] = '获取位置信息失败,位置信息不可用';
					break;
				case error.TIMEOUT:
					options['errorMsg'] = '获取位置信息失败,请求获取用户位置超时';
					break;
				case error.UNKNOWN_ERROR:
					options['errorMsg'] = '获取位置信息失败,定位系统失效';
					break;
			}
			locationErorrTip(options);
		},{enableHighAccuracy:true});
	}else{
		options['errorMsg'] = '获取位置失败,用户浏览器不支持或已禁用位置获取权限';
		locationErorrTip(options);
	}
	function locationOkFun(options){
		if(options.okFunction){
			if(options.okFunction == 'refresh'){
				window.location.reload();
			}else{
				options.okFunctionParam.push(options.userLocation);
				options.okFunctionParam.push(options.userLocationLong);
				options.okFunctionParam.push(options.userLocationLat);
				call_user_func(options.okFunction,options.okFunctionParam);
			}
		}
	}
	function locationErorrTip(options){
		if(options.errorMsg && options.errorContentSuffix){
			options.errorMsg = options.errorMsg + '<br/>' + options.errorContentSuffix;
		}
		if(options.errorFunction){
			if(options.errorFunction == 'refresh'){
				window.location.reload();
			}else{
				options.errorFunctionParam.push(options.errorMsg);
				call_user_func(options.errorFunction,options.errorFunctionParam);
			}
		}else{
			if(options.errorMsg){
				switch(options.errorAction){
					case 1:
						motify.log(options.errorMsg,options.errorTipTime*1000,(options.errorShade ? {show:true} : null));
						break;
					case 2:
						layer.open({
							title:[options.errorTipTitle,'background-color:#FF658E;color:#fff;'],
							content:options.errorMsg,
							btn: ['确定'],
							end:function(){
								if(options.errorUrl != ''){
									if(options.errorUrl == 'history'){
										window.history.go(-1);
									}else if(options.errorUrl == 'href'){
										window.location.reload();
									}else{
										window.location.href=options.errorUrl;
									}
								}
							}
						});
						break;
					case 3:
						if(options.errorUrl != ''){
							if(options.errorUrl == 'history'){
								window.history.go(-1);
							}else if(options.errorUrl == 'href'){
								window.location.reload();
							}else{
								window.location.href=options.errorUrl;
							}
						}else{
							motify.log('调用了“直接跳转URL”，却没有传URL地址',0);
						}
						break;
				}
			}
		}
	}
}

function geoconv(funName,lng,lat){
	$.getJSON('http://api.map.baidu.com/geoconv/v1/?coords='+lng+','+lat+'&ak=4c1bb2055e24296bbaef36574877b4e2&from=1&to=5&callback='+funName+'&jsoncallback=?');
}
function geocoder(funName,lng,lat){
	$.getJSON('http://api.map.baidu.com/geocoder/v2/?location='+lat+','+lng+'&ak=4c1bb2055e24296bbaef36574877b4e2&output=json&pois=1&callback='+funName+'&jsoncallback=?');
}
/* 得到经纬度之间的距离 */
function Rad(d){
   return d * Math.PI / 180.0;//经纬度转换成三角函数中度分表形式。
}
//输出为米
function GetDistance(lng1,lat1,lng2,lat2){
	var radLat1 = Rad(lat1);
	var radLat2 = Rad(lat2);
	var a = radLat1 - radLat2;
	var  b = Rad(lng1) - Rad(lng2);
	var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a/2),2) +
	Math.cos(radLat1)*Math.cos(radLat2)*Math.pow(Math.sin(b/2),2)));
	s = s *6378.137 ;// EARTH_RADIUS;
	s = Math.round(s * 10000) / 10; //输出为公里
	return s;
}

/*调用JS 自定义方法并带参数*/
function call_user_func(cb, options){
	func = window[cb];
	func.apply(cb,options);
}
/* function alerts(params,params2){
	console.log(arguments);
}
call_user_func('alerts',['1111','22222']); */