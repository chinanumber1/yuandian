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
		var supply_id = $(this).attr("data-supplyid");

		$.post(location_url, "supply_id=" + supply_id, function(json){
			mark = 0;
			if (json.status) {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){
					location.reload();
				}});
			} else {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){
					location.reload();
				}});
			}
		});
    });
	getList();
	var timer = setInterval(getList, 10000);
});

function getList() {
	
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			list_detail(r.point.lat, r.point.lng);
		} else {
			list_detail(lat, lng);
		}        
	},{enableHighAccuracy: true})
	return false;
	console.log(lat + '--------->lng:' + lng);
	$.get(location_url, {'lat':lat, 'lng':lng}, function(result){
		if (result.err_code) {
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


function list_detail(lat, lng)
{
	$.get(location_url, {'lat':lat, 'lng':lng}, function(result){
		if (result.err_code) {
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