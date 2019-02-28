var itemArr = [];
var android_baidumap_has = false;
var android_baidumap_loading = true;
var android_gaodemap_has = false;
var android_gaodemap_loading = true;
var android_tencentmap_has = false;
var android_tencentmap_loading = true;

var ios_baidumap_has = false;
var ios_baidumap_loading = true;
var ios_gaodemap_has = false;
var ios_gaodemap_loading = true;
var ios_tencentmap_has = false;
var ios_tencentmap_loading = true;

var nowMapLabel = null;
$(document).ready(function(){
	
	if(common.checkWeixin()){
		common.fillPageBg(1,'#f4f4f4');
	}
	
	nowMapLabel = common.getCache('navigation_type');
	if(nowMapLabel){
		$('.navigation .enquire').html(nowMapLabel.text);
	}else{
		$('.navigation .enquire').html('询问');
	}
	
	var DeliverUser = common.getCache('deliver_user',true);
	if(common.checkWeixin()){
		if(urlParam.openid){
			if(urlParam.openid != DeliverUser.openid){
				DeliverUser.openid = urlParam.openid;
				common.setCache('deliver_user',DeliverUser,true);
				common.http('Deliver&a=save_openid',{openid:DeliverUser.openid}, function(data){
					motify.log('已成功绑定微信');
				});
			}
		}
		if(DeliverUser.openid){
			$('.openid .enquire').html('已绑定');
		}else{
			$('.openid .enquire').html('未绑定');
		}
		$('.openid').show();
	}
	
	if(!DeliverUser.is_notice || DeliverUser.is_notice == 0){
		$('.notice .enquire').html('接收');
	}else{
		$('.notice .enquire').html('不接收');
	}
	$('.notice').click(function(event){
		var itemArr = ['接收','不接收'];
		common.actionsheet({
			itemArr:itemArr,
			showCancel:false,
			success:function(index){
				common.http('Deliver&a=save_notice',{is_notice:index}, function(data){
					DeliverUser.is_notice = index;
					common.setCache('deliver_user',DeliverUser,true);
					$('.notice .enquire').html(itemArr[index]);
				});
			}
		});
	});
	
	
	if(common.checkAndroidApp()){
		//判断安卓百度地图
		window.pigcmspackapp.judgeappexist('com.baidu.BaiduMap','android_check_baidumap_callback');
		//判断安卓高德地图
		window.pigcmspackapp.judgeappexist('com.autonavi.minimap','android_check_gaodemap_callback');
		//判断安卓腾讯地图
		window.pigcmspackapp.judgeappexist('com.tencent.map','android_check_tencentmap_callback');
	}else if(common.checkIosApp()){
		//判断IOS百度地图
		common.iosFunction('judgeappexist/baidumap/ios_check_baidumap_callback');
		//判断IOS高德地图
		common.iosFunction('judgeappexist/iosamap/ios_check_gaodemap_callback');
		//判断IOS腾讯地图
		common.iosFunction('judgeappexist/qqmap/ios_check_qqmap_callback');
	}
	
	$('.navigation').click(function(event){
		itemArr = [];
		if(common.checkIosApp()){
			itemArr.push({text:'苹果地图',label:'ios_map'});
			if(ios_baidumap_has){
				itemArr.push({text:'百度地图',label:'ios_baidu'});
			}
			if(ios_gaodemap_has){	
				itemArr.push({text:'高德地图',label:'ios_gaode'});
			}
			if(ios_tencentmap_has){
				itemArr.push({text:'腾讯地图',label:'ios_tencent'});
			}
		}else if(common.checkAndroidApp()){
			if(android_baidumap_has){
				itemArr.push({text:'百度地图',label:'android_baidu'});
			}
			if(android_gaodemap_has){	
				itemArr.push({text:'高德地图',label:'android_gaode'});
			}
			if(android_tencentmap_has){
				itemArr.push({text:'腾讯地图',label:'android_tencent'});
			}
		}else if(common.checkWeixin()){
			itemArr.push({text:'微信内置地图',label:'weixin'});
		}else{
			itemArr.push({text:'百度地图wap',label:'browser'});
		}
		common.actionsheet({
			itemArr:itemArr,
			success:function(obj){
				nowMapLabel = obj;
				common.setCache('navigation_type',obj);
				$('.navigation .enquire').html(obj.text);
			}
		});
		event.stopPropagation();
	});
	$('.openid').click(function(event){
		layer.open({
			content: DeliverUser.openid ? '您确认前往重新授权绑定微信？' : '您确认前往绑定微信？'
			,btn: ['前往', '取消']
			,yes: function(index){
				location.href = 'http://'+requestDomain+'/wap.php?c=Packapp&a=bind&referer='+encodeURI(location.href);
				// location.href = 'login.html';
				layer.close(index);
			}
		});
	});
	$('#logout').click(function(){
		layer.open({
			content: '您确定要退出吗？'
			,btn: ['确定', '取消']
			,yes: function(index){
				common.removeCache('ticket');
				common.removeCache('ticket',true);
				if(common.checkApp()){
					common.setCache('isLogout','true',true);
					if(common.checkAndroidApp()){
						window.pigcmspackapp.closewebview(2);
					}else{
						common.iosFunction('closewebview/2');
					}
				}else{
					location.href = 'login.html';
				}
				
				layer.close(index);
			}
		});
	});
});

//安卓
function android_check_baidumap_callback(text){
	if(text == '1'){
		android_baidumap_has = true;
	}else{
		if(nowMapLabel.label == 'android_baidu'){
			removeMapCache();
		}
	}
	android_baidumap_loading = false;
}
function android_check_gaodemap_callback(text){
	if(text == '1'){
		android_gaodemap_has = true;
	}else{
		if(nowMapLabel.label == 'android_gaode'){
			removeMapCache();
		}
	}
	android_gaodemap_loading = false;
}
function android_check_tencentmap_callback(text){
	if(text == '1'){
		android_tencentmap_has = true;
	}else{
		if(nowMapLabel.label == 'android_tencent'){
			removeMapCache();
		}
	}
	android_tencentmap_loading = false;
}

//IOS
function ios_check_baidumap_callback(text){
	if(text == '1'){
		ios_baidumap_has = true;
	}else{
		if(nowMapLabel.label == 'ios_baidu'){
			removeMapCache();
		}
	}
	ios_baidumap_loading = false;
}
function ios_check_gaodemap_callback(text){
	if(text == '1'){
		ios_gaodemap_has = true;
	}else{
		if(nowMapLabel.label == 'ios_gaode'){
			removeMapCache();
		}
	}
	ios_gaodemap_loading = false;
}
function ios_check_qqmap_callback(text){
	if(text == '1'){
		ios_tencentmap_has = true;
	}else{
		if(nowMapLabel.label == 'ios_tencent'){
			removeMapCache();
		}
	}
	ios_tencentmap_loading = false;
}
function removeMapCache(){
	common.removeCache('navigation_type');
	$('.navigation .enquire').html('询问');
}