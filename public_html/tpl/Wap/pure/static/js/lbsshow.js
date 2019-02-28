var myScroll;
var isApp = motify.checkApp();
$(function(){
	$('#backBtn').click(function(){
		if(document.referrer){
			redirect(document.referrer,'openLeftWindow');
		}else{
			redirect(backUrl,'openLeftWindow');
		}
	});
	if($('header').size() > 0){
		$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	}else{
		 $('#container').css({'top':'0px'});
		$('#scroller').css({'min-height':($(window).height())+'px'});
	}
	
    if(isApp){
        $('#container').css({'top':'0px'});
        $('#container,#scroller').css({'position':'static'});
        $('body').append('<style>::-webkit-scrollbar{width:0px;}</style>');
    }else{
		// $('#container').css({'bottom':'57px'});
        // myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    }
	
	$('#biz-map').height($('#scroller').height()-$('.serviceListBox').height());
	// alert(go_title);
	// alert(go_long);
	// alert(go_lat);
	// 百度地图API功能
	var map = new BMap.Map("biz-map",{enableMapClick:false});
	map.centerAndZoom(new BMap.Point(go_long,go_lat), 16);

	map.addControl(new BMap.ZoomControl());  //添加地图缩放控件
	var marker1 = new BMap.Marker(new BMap.Point(go_long,go_lat));  //创建标注
	map.addOverlay(marker1);                 // 将标注添加到地图中
	//创建信息窗口
	var infoWindow1 = new BMap.InfoWindow(go_title);
	marker1.openInfoWindow(infoWindow1);
	marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});
	
	if(typeof(wxSdkLoad) != "undefined"){
		$('.goHere').hide();
		if(go_address == ""){
			geocoder("renderReverse",go_long,go_lat);
		}else{
			renderReverse({status:1});
		}
	}else{
		if(go_address == ""){
			geocoder("renderReverse",go_long,go_lat);
		}
	}
});
function renderReverse(obj){
	if(obj.status == 0){
		console.log(JSON.stringify(obj.result));
		go_address = obj.result.formatted_address;
		if($('.line-desc').html() == ''){
			$('.line-desc').html(go_address).css({'color':'#999'});
		}
	}
	if(typeof(wxSdkLoad) != "undefined"){
		$.getJSON(window.location.pathname+"?c=Userlonglat&a=baiduToGcj02&baidu_lat="+go_lat+"&baidu_lng="+go_long,function(result){
			$('.goHere').show();
			if(result['status'] == 1){
				$('.goHere').click(function(){
					wx.ready(function (){
						wx.openLocation({
							latitude: result['info']['lat'],
							longitude: result['info']['lng'],
							name: go_title, // 位置名
							address: go_address, // 地址详情说明
							scale: 22, // 地图缩放级别,整形值,范围从1~28。默认为最大
							infoUrl: window.location.href // 在查看位置界面底部显示的超链接,可点击跳转
						});
					});
					return false;
				});
			}
		});
	}
}