<include file="web_head"/>
<div id="header" class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div id="details" class="app-content with-header text-left text-md bg-gray">
<!--	<div class="wrapper-xs padding-lr5 bg-white">
		<div class="m-t-sm text-base">每天早上8点出发</div>
		<div class="m-t-sm">新地中心</div>
		<div class="m-t-sm">万科城</div>
	</div>
	<div class="wrapper-sm">
		<div class="bg-white">
			<div class="padding-lr5 padding-tb5">
				<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;"><img class="avatar" src="http://www.group.com/tpl/Wap/default/static/bbs/img/tou.png" /></div>
				<div class="col-xs-7 no-padder text-base m-b-xs">
					<div class="m-t-xs">韩露</div>
					<div>电话：18318938008</div>
				</div>
				<div class="col-xs-3 text-right no-padder pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">立即下单</div></div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="wrapper-sm">
		<div class="m-b-xs">其他信息</div>
		<div class="bg-white padding-lr5 text-sm clearfix">
			<div class="pull-left">
				<div class="m-t-sm">发布时间：</div>
				<div class="m-t-xs">出发时间：</div>
				<div class="m-t-xs">返回时间：</div>
				<div class="m-t-xs">费&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 用：</div>
				<div class="m-t-xs m-b-sm">空&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 位：</div>
			</div>
			<div class="pull-left">
				<div class="m-t-sm">2016-02-19 15:50</div>
				<div class="m-t-xs">09:00</div>
				<div class="m-t-xs">18:00</div>
				<div class="m-t-xs">8元/人</div>
				<div class="m-t-xs m-b-sm">2位</div>
			</div>
		</div>
	</div>
	<div class="wrapper-sm">
		<div class="m-b-xs">已预约</div>
		<div class="bg-white padding-lr5 text-sm clearfix">
			<div class="clearfix b-b">
				<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;"><img class="avatar" src="http://www.group.com/tpl/Wap/default/static/bbs/img/tou.png" /></div>
				<div class="col-xs-10 no-padder text-base m-b-xs">
					<div class="m-t-xs">韩露</div>
					<div>电话：18318938008</div>
				</div>
			</div>
			<div class="clearfix b-b">
				<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;"><img class="avatar" src="http://www.group.com/tpl/Wap/default/static/bbs/img/tou.png" /></div>
				<div class="col-xs-10 no-padder text-base m-b-xs">
					<div class="m-t-xs">韩露</div>
					<div>电话：18318938008</div>
				</div>
			</div>
		</div>
	</div>-->
</div>
<script>
	var	order_id	=	'{pigcms{$order_id}';
	var	wx_host	=	'{pigcms{$site_url}';
        var is_indep_house = "{pigcms{:defined('IS_INDEP_HOUSE')}";
        if(is_indep_house){
                var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
        }else{
                var domain_host = 'wap.php';
        }
	a.busy();
	$.ajax({
		type : "post",
		url : wx_host+ domain_host + '?g=Wap&c=Ride&a=ride_carpoling_details_api',
		dataType : "json",
		data:{
			order_id	:	order_id,
		},
		async:false,
		success : function(result){
			if(result.errorCode	!=	0){
				a.msg(result.errorMsg);
			}else{
				var	ride_details	=	result.result.ride;
				var	ride_order	=	result.result.ride_order;
				var	defaultImg	=	result.result.defaultImg;
				var	proportion	=	result.result.ride_log.proportion;
				var	details 	=	'';
				details	+='<div class="wrapper-xs padding-lr5 bg-white">';
//				details	+='	<div class="m-t-sm text-base">'+ride_details.start_date+' 出发</div>';
				details	+='		<div class="m-t-sm pull-left text-base">出发时间：'+ride_details.start_date+'</div>';
				if(ride_order.status == 1 || ride_order.status == 4){
					details	+='		<div class="pull-right m-t-sm m-r-sm text-sm text-success">'+ride_order.statuss+'</div>';
				}else{
					details	+='		<div class="pull-right m-t-sm m-r-sm text-sm text-danger">'+ride_order.statuss+'</div>';
				}
				details	+='			<div class="clearfix"></div>';
				details	+='	<div class="m-t-sm"><img class="thumb-xxs" src="'+defaultImg.urlStar+'" /> '+ride_details.departure_place+'</div>';
				details	+='	<div class="m-t-sm"><img class="thumb-xxs" src="'+defaultImg.urlEnd+'" /> '+ride_details.destination+'</div>';
				details	+='</div>';
				details	+='<div class="wrapper-sm">';
				details	+='	<div class="m-b-xs">车主信息</div>';
				details	+='	<div class="bg-white">';
				details	+='		<div class="padding-lr5 padding-tb5">';
				details	+='			<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;"><img class="avatar" src="'+ride_details.avatar+'" /></div>';
				details	+='			<div class="col-xs-6 no-padder text-base m-b-xs">';
				details	+='				<div class="m-t-xs">'+ride_details.owner_name+'</div>';
				details	+='				<div>车主信用度：<span class="text-danger">'+proportion+'%</span></div>';
				details	+='			</div>';
				if(ride_order.status == 1){
					if(ride_order.order_date != 0){
						details	+='			<div id="xiadan" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">立即退单</div></div>';
					}
				}
				//else if(ride_order.status == 4){
//					if(ride_order.paid == 3){
//						details	+='			<div class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">已付</div></div>';
//					}else{
//						details	+='			<div id="fukuan" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">付款</div></div>';
//					}
//				}
				details	+='			<div class="clearfix"></div>';
				details	+='		</div>';
				details	+='	</div>';
				details	+='</div>';
				details	+='<div class="wrapper-sm">';
				details	+='	<div class="m-b-xs">其他信息</div>';
				details	+='	<div class="bg-white padding-lr5 text-sm clearfix">';
				details	+='		<div class="pull-left">';
				details	+='			<div class="m-t-sm">下单时间：</div>';
				details	+='			<div class="m-t-xs">车主手机：</div>';
				details	+='			<div class="m-t-xs">车牌号码：</div>';
				details	+='			<div class="m-t-xs">车主业务：</div>';
				details	+='			<div class="m-t-xs">费&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 用：</div>';
				details	+='			<div class="m-t-xs">取消订单：</div>';
				details	+='			<div class="m-t-xs">违&nbsp;&nbsp;约&nbsp;&nbsp;金：</div>';
				details	+='			<div class="m-t-xs m-b-sm">预定座位：</div>';
				details	+='		</div>';
				details	+='		<div class="pull-left">';
				details	+='			<div class="m-t-sm">'+ride_order.order_time_s+'</div>';
				details	+='			<div class="m-t-xs"><a href="tel:'+ride_details.owner_phone+'">'+ride_details.owner_phone+'</a></div>';
				details	+='			<div class="m-t-xs">'+ride_details.car_number+'</div>';
				if(ride_details.ride_date_number == 1){
					details	+='			<div class="m-t-xs color-orange">每天</div>';
				}else{
					details	+='			<div class="m-t-xs">一次</div>';
				}
				details	+='			<div class="m-t-xs">'+ride_details.ride_price+'元/人</div>';
				if(ride_details.cancel_time == 0){
					details	+='			<div class="m-t-xs">不能取消订单</div>';
				}else{
					if(ride_order.order_date == 0){
						details	+='			<div class="m-t-xs">不能取消订单</div>';
					}else{
						details	+='			<div class="m-t-xs">发车'+ride_order.order_date+'分钟以前可以退单</div>';
					}
				}
				details	+='			<div class="m-t-xs">'+ride_details.penalty+'元</div>';
				details	+='			<div class="m-t-xs m-b-sm color-green">'+ride_order.sit_number+'位</div>';
				details	+='		</div>';
				details	+='	</div>';
				details	+='</div>';
				if(ride_order.status == 1){
					details	+=	'<div id="fukuan" class="wrapper"><div class="btn btn-info w-full">确定完成</div></div>';
				}
				$('#details').html(details);
				a.busy(0);
			}
		},
		error:function(){
			a.msg('发布失败，请联系管理员');
			a.busy(0);
		}
	});
	var windowHeight	=	$(window).height();
	var detailsHeight	=	$('#details').height();
	var headerHeight	=	$('#header').height();
	var suan	=	windowHeight-detailsHeight-headerHeight-9;
	if(suan > 0){
		$("#details").css("padding-bottom",suan);
	}
	$('#return_index').on('click',function(){
		location.href = domain_host + "?g=Wap&c=Ride&a=ride_carpooling";
    });
    $('#xiadan').on('click',function(){
		location.href = domain_host + "?g=Wap&c=Ride&a=ride_order_cancel&order_id="+order_id;
    });
    $('#fukuan').on('click',function(){
		a.busy();
		$.ajax({
			type : "post",
			url : wx_host+ domain_host +'?g=Wap&c=Ride&a=complete_pay_api',
			dataType : "json",
			data:{
				order_id	:	order_id,
			},
			async:false,
			success : function(result){
				if(result.errorCode != 0){
					a.msg(result.errorMsg);
				}else{
					 window.location.reload();
				}
				a.busy(0);
			},
			error:function(){
				a.msg('发布失败，请联系管理员');
				a.busy(0);
			}
		});

    });
</script>
<include file="web_footer"/>