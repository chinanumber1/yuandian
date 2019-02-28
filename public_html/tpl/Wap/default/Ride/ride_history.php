<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div id="xinzeng" class="pull-right right_img right_bottom m-t-n-md">发起</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-md">
	<div class="padding-tb5 bg-gray"></div>
	<div class="m-b-xxl"><div id="newBbsAricleList"></div></div>
	<!--<div class="m-b-sm">
		<a href="/wap.php?g=Wap&c=Ride&a=ride_details">
			<div class="padding-lr5 m-l-xs m-t-sm pull-left text-base">每天早上8点出发</div><div class="pull-right m-t-sm m-r-sm text-sm">发布时间:2016-02-19 15:50</div><div class="clearfix"></div>
			<div class="padding-lr5 padding-tb5">
				<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;"><img class="avatar" src="http://www.group.com/tpl/Wap/default/static/bbs/img/tou.png" /><div class="text-xs m-l-sm">韩露</div></div>
				<div class="col-xs-6 no-padder text-base">
					<div class="m-t-sm">新地中心</div>
					<div class="m-t-sm">万科城</div>
				</div>
				<div class="col-xs-4 text-right no-padder pull-right m-t text-sm">
					<div><span class="text-md color-orange">8</span>&nbsp;&nbsp;元</div>
					<div class="color-green"><span class="text-md">2</span>&nbsp;&nbsp;座</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</a>
	</div>
	<div class="padding-tb5 bg-gray"></div>-->
</div>
<div id="footer" class="app-footer b-t bgBottom">
	<div id="launch" class="btn btn-success b-r col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlCar}" /> 我的发起</div>
	<div id="me" class="btn b-l col-xs-6 padding-tb10"><img class="thumb-xxxs" src="{pigcms{$defaultImg.urlMe}" /> 我的订单</div>
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
			destination	=	$('#destination').text();
			ride_price	=	$('#ride_price').text();
			departure_place	=	$('#departure_place').text();
			remain_number	=	$('#remain_number').text();
			list(false,1,destination,ride_price,remain_number);
		}else{
			$('#jia').remove();
		}
	}
});
$('#return_index').on('click',function(){
	location.href = domain_host + "?g=Wap&c=Ride&a=ride_list&type=2";
});
$('#index').on('click',function(){
	location.href = "javascript:scroll(0,0)";
});
$('#xinzeng').on('click',function(){
	location.href = domain_host + "?g=Wap&c=Ride&a=ride_add";
});
$('#me').on('click',function(){
	location.href = domain_host + "?g=Wap&c=Ride&a=ride_carpooling";
});
function	list(is){
	a.busy();
	$.ajax({
		type : "post",
		url : wx_host+ domain_host +'?g=Wap&c=Ride&a=ride_history_api',
		dataType : "json",
		data:{
			village_id	:	village_id,
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
						rideList	+='		<a href="/'+domain_host+'?g=Wap&c=Ride&a=ride_details&ride_id='+ride_list[x].ride_id+'&status=2">';
						rideList	+='			<div class="padding-lr5 m-l-xs m-t-sm text-md">'+ride_list[x].ride_title+'</div>';
						if(ride_list[x].ride_date_number == 1){
							rideList	+='			<div class="padding-lr5 m-l-xs pull-left text-base">'+ride_list[x].start_date+'出发</div>';
						}else{
							rideList	+='			<div class="padding-lr5 m-l-xs pull-left text-base">每天'+ride_list[x].start_date+'出发</div>';
						}
						rideList	+='			<div class="pull-right m-r-sm text-sm">';
						if(ride_list[x].status == 1 || ride_list[x].status == 3){
							rideList	+='			<span class="text-success">'+ride_list[x].status_s+'</span></div><div class="clearfix"></div>';
						}else if(ride_list[x].status == 2){
							rideList	+='			<span class="text-primary">'+ride_list[x].status_s+'</span></div><div class="clearfix"></div>';
						}else{
							rideList	+='			<span class="text-danger">'+ride_list[x].status_s+'</span></div><div class="clearfix"></div>';
						}
						rideList	+='			</div><div class="clearfix"></div>';
						rideList	+='			<div class="padding-lr5 padding-tb5">';
						rideList	+='				<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;">';
						rideList	+='				<img class="avatar" src="'+ride_list[x].avatar+'" />';
						rideList	+='					<div class="text-xs m-l-sm">'+ride_list[x].owner_name+'</div></div>';
						rideList	+='				<div class="col-xs-6 no-padder text-base">';
						rideList	+='					<div class="m-t-sm"><img class="thumb-xxxs" src="'+defaultImg.urlStar+'" /> '+ride_list[x].departure_place+'</div>';
						rideList	+='					<div class="m-t-sm"><img class="thumb-xxxs" src="'+defaultImg.urlEnd+'" /> '+ride_list[x].destination+'</div>';
						rideList	+='				</div>';
						rideList	+='				<div class="col-xs-3 text-right no-padder pull-right m-t text-sm">';
						rideList	+='					<div><span class="text-md color-orange">'+ride_list[x].ride_price+'</span>&nbsp;&nbsp;元</div>';
						rideList	+='					<div class="color-green"><span class="text-md">'+ride_list[x].remain_number+'</span>&nbsp;&nbsp;座</div>';
						rideList	+='				</div>';
						rideList	+='				<div class="clearfix"></div>';
						rideList	+='			</div>';
						rideList	+='		</a>';
						rideList	+='	</div>';
						rideList	+='	<div class="padding-tb5 bg-gray"></div>';
					}
					if(ride_list_length <= 9){
						rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有顺风车记录了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}else{
						rideList	+=	'<div id="mais" class="text-center m-t m-b">上拉会有更多顺风车记录哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}else{
					rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有顺风车记录了<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}
			}else{
				if(page == 1){
					rideList	+=	'<div id="is_null" class="text-center m-t m-b">您没有发起过顺风车</div>';
				}else{
					rideList	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有顺风车记录了<p>点击({pigcms{$index})可以返回顶部</p></div>';
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