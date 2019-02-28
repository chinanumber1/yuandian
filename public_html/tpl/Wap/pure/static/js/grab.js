$(function(){
    $(".delivery p em").each(function(){
       $(this).width($(window).width() - $(this).siblings("i").width() -55) 
    });
    var mark = 0;
    $(document).on('click', '.rob', function(e){
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-spid");
		$.post(location_url, "supply_id="+supply_id, function(json){
			mark = 0;
			if (json.status) {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){}});
			} else {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){}});
			}
			$(".supply_"+supply_id).remove();
		});
    });
	getList();
	var timer = setInterval(getList, 10000);
	
	$(document).on("click", '.go_detail', function(e){
		e.stopPropagation();
		location.href = detail_url + '&supply_id=' + $(this).attr("data-id");
	});
});
function getList() {
	
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
//			lat = r.point.lat;
//			lng = r.point.lng;
			list_detail(r.point.lat, r.point.lng);
//			console.log(lat + '--------->lng:' + lng);
//			map.panTo(r.point);
//			var mk = new BMap.Marker(r.point);
//			map.addOverlay(mk);
//			mk.setAnimation(BMAP_ANIMATION_BOUNCE); 
//			alert('您的位置：'+r.point.lng+','+r.point.lat);
		} else {
			list_detail(lat, lng);
//			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true})
	return false;
	console.log(lat + '--------->lng:' + lng);
	$.get(location_url, {'lat':lat, 'lng':lng}, function(result){
		if (result.err_code) {
			$('.psnone').show();
			return false;
		}
		$('.psnone').hide();
		laytpl($('#replyListBoxTpl').html()).render(result, function(html){
			$('#container').html(html);
		    $(".delivery p em").each(function(){
		        $(this).width($(window).width() - $(this).siblings("i").width() -55) 
	    	});
		});
	}, 'json');
}


function list_detail(lat, lng)
{
	$.get(location_url, {'lat':lat, 'lng':lng}, function(result){
		if (result.err_code) {
			$('#container').html('<div class="psnone" ><img src="' + static_path + 'images/qdz_02.jpg"></div>');
			return false;
		}
		laytpl($('#replyListBoxTpl').html()).render(result, function(html){
			$('#container').html(html);
		    $(".delivery p em").each(function(){
		        $(this).width($(window).width() - $(this).siblings("i").width() -55) 
	    	});
		});
	}, 'json');
}