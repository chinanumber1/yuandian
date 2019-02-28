<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-md">
	<div class="padding-tb5 bg-gray"></div>
	<div class="m-b-xxl"><div id="newBbsAricleList"></div></div>
</div>
<div id="footer" class="app-footer b-t bgBottom">
	<div id="launch" class="btn b-r col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlCar}" /> 我的发起</div>
	<div id="me" class="btn btn-success b-l col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlMe}" /> 我的订单</div>
</div>
<script>
var	wx_host	=	'{pigcms{$site_url}';
var page	=	1;
var village_id	=	'{pigcms{$village_id}';
var is_indep_house = "{pigcms{:defined('IS_INDEP_HOUSE')}";
if(is_indep_house){
        var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
}else{
        var domain_host = 'wap.php';
}
list(true);
$(window).scroll(function(){
	if($(window).scrollTop() == $(document).height() - $(window).height()){
		$('#mais').remove();
		var jia	=	'';
    	jia	+=	'<div id="jia" class="text-center m-t m-b">正在加载</div>';
    	$('#newBbsAricleList').append(jia);
		if($('#is_null').length < 1){
			list(false);
		}else{
			$('#jia').remove();
		}
	}
});
$('#return_index').on('click',function(){
	location.href = domain_host + "?g=Wap&c=Ride&a=ride_list";
});
$('#index').on('click',function(){
	location.href = "javascript:scroll(0,0)";
});
$('#launch').on('click',function(){
	location.href = domain_host + "?g=Wap&c=Ride&a=ride_history";
});
function	list(is){
	a.busy();
	$.ajax({
		type : "post",
		url : wx_host + domain_host +'?g=Wap&c=Ride&a=ride_carpooling_api',
		dataType : "json",
		data:{
			page	:	page,
		},
		async:is,
		success : function(result){
			var rideList	=	'';
			if(result.result){
				var	ride_list	=	result.result.ride_list;
				var	defaultImg	=	result.result.defaultImg;
				var	ride_list_length	=	ride_list.length;
				if(ride_list_length){
					page++;
					for(var x=0;x<ride_list_length;x++){
						rideList	+='<div class="m-b-sm">';
						rideList	+='	<a href="/'+domain_host+'?g=Wap&c=Ride&a=ride_carpoling_details&order_id='+ride_list[x].order_id+'">';
						rideList	+='		<div class="padding-lr5 m-l-xs m-t-sm pull-left text-base">出发时间：'+ride_list[x].start_time+'</div>';
						if(ride_list[x].status == 1 || ride_list[x].status == 4){
							rideList	+='		<div class="pull-right m-t-sm m-r-sm text-sm text-success">'+ride_list[x].statuss+'</div>';
						}else{
							rideList	+='		<div class="pull-right m-t-sm m-r-sm text-sm text-danger">'+ride_list[x].statuss+'</div>';
						}
						rideList	+='		<div class="clearfix"></div><div class="padding-lr5">';
						rideList	+='			<div class="col-xs-10 no-padder text-base">';
						rideList	+='				<div class="m-t-sm"><img class="thumb-xxxs" src="'+defaultImg.urlStar+'" /> '+ride_list[x].departure_place+'</div>';
						rideList	+='				<div class="m-t-sm"><img class="thumb-xxxs" src="'+defaultImg.urlEnd+'" /> '+ride_list[x].destination+'</div>';
						rideList	+='			</div>';
						rideList	+='			<div class="col-xs-2 text-right no-padder pull-right m-t text-sm">';
						rideList	+='				<div><img class="thumb-xxxs" src="'+defaultImg.urlGo+'" /></div>';
						rideList	+='			</div>';
						rideList	+='			<div class="clearfix"></div>';
						rideList	+='		</div>';
						rideList	+='	</a>';
						rideList	+='</div>';
						rideList	+='<div class="padding-tb5 bg-gray"></div>';
					}
					if(ride_list_length <= 9){
						rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有拼车记录了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}else{
						rideList	+=	'<div id="mais" class="text-center m-t m-b">上拉会有更多拼车记录哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}else{
					rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有拼车记录了<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}
			}else{
				if(page == 1){
					rideList	+=	'<div id="is_null" class="text-center m-t m-b">您没有拼过顺风车</div>';
				}else{
					rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有拼车记录了<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}
			}
			$('#jia').remove();
			$('#newBbsAricleList').append(rideList);
			a.busy(0);
		},
		error:function(){
			a.msg('页面错误，请联系管理员');
			a.busy(0);
		}
	})
}
</script>
<include file="web_footer"/>