<include file="web_head"/>
<style>
	a:hover{color:blue;} a:visited {color:blue;} a:link {color:blue;} a {color:blue;}
</style>
<div id="header" class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div id="modify"></div>
	<div class="clearfix"></div>
</div>
<div id="details" class="app-content with-header text-left text-md bg-gray"></div>
<script>
	var	package_id	=	'{pigcms{$package_id}';
	var	status		=	'{pigcms{$status}';
	var crowdsourcing	=	'{pigcms{$crowdsourcing}';
	var order_id	=	'';
	if(crowdsourcing	== 0){
		var rideList	=	'<div class="text-center m-t m-b">未开通众包平台</div>';
		$('#details').html(rideList);
	}else{
	a.busy();
	$.ajax({
		type : "post",
		url : "{pigcms{:U('details_json')}",
		dataType : "json",
		data:{
			package_id	:	package_id,
		},
		async:false,
		success : function(result){
			if(result.errorCode	!=	0){
				a.msg(result.errorMsg);
			}else{
				var	ride_details	=	result.result;
				if(ride_details.status == 1){
					if(ride_details.package_status == 1){
						var	modify	=	'';
						modify	+=	'<div id="eidt" class="pull-right right_img right_bottom m-t-n-md">修改</div>';
						$('#modify').html(modify);
					}
				}
				order_id	=	ride_details.order.order_id;
				var	details 	=	'';
				details	+='<div class="padding-tb5 bg-gray"></div>';
				details	+='<div class="wrapper-xs padding-lr5 bg-white">';
				details	+='		<div class="m-t-sm text-base">'+ride_details.package_title+'</div>';
				details	+='		<div class="m-t-sm"><img class="thumb-xxs" src="'+ride_details.IMG.urlStar+'" /> '+ride_details.package_start+'</div>';
				details	+='		<div class="m-t-sm"><img class="thumb-xxs" src="'+ride_details.IMG.urlEnd+'" /> '+ride_details.package_end+'</div>';
				details	+='</div>';
				details	+='<div class="wrapper-sm">';
				details	+='		<div class="m-b-xs">用户信息</div>';
				details	+='		<div class="bg-white">';
				details	+='			<div class="padding-lr5 padding-tb5">';
				details	+='				<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;"><img class="avatar" src="'+ride_details.avatar+'" /></div>';
				details	+='				<div class="col-xs-6 no-padder text-base m-b-xs">';
				details	+='					<div class="m-t-xs">'+ride_details.user_name+'</div>';
				details	+='					<div>手机：<span class="text-danger">'+ride_details.user_phone+'</span></div>';
				details	+='				</div>';
				if(ride_details.status == 0){
					if(ride_details.package_status == 1){
						details	+='			<div id="xiadan" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">立即抢单</div></div>';
					}
				}else if(ride_details.status == 1){
					if(ride_details.package_status == 1){
						details	+='			<div id="close" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">关闭众包</div></div>';
					}else if(ride_details.order.status == 1){
						details	+='			<div id="cancel" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">取消抢单</div></div>';
					}
				}
				details	+='				<div class="clearfix"></div>';
				details	+='			</div>';
				details	+='		</div>';
				details	+='</div>';
				details	+='<div class="wrapper-sm">';
				details	+='		<div class="m-b-xs">其他信息</div>';
				details	+='			<div class="bg-white text-sm clearfix">';
				details	+='				<div class="m-t-xs clearfix">';
				details	+='					<div class="pull-left col-xs-3" style="padding-right:0px;padding-left:10px;">发布时间：</div>';
				details	+='					<div class="pull-left col-xs-9 no-padder">'+ride_details.add_tims_s+'</div>';
				details	+='				</div>';
				details	+='				<div class="m-t-xs clearfix">';
				details	+='					<div class="pull-left col-xs-3" style="padding-right:0px;padding-left:10px;">运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费：</div>';
				details	+='					<div class="pull-left col-xs-9 no-padder color-orange">'+ride_details.package_money+' 元</div>';
				details	+='				</div>';
				details	+='				<div class="m-t-xs clearfix">';
				details	+='					<div class="pull-left col-xs-3" style="padding-right:0px;padding-left:10px;">押&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;金：</div>';
				details	+='					<div class="pull-left col-xs-9 no-padder color-green">'+ride_details.package_deposit+' 元</div>';
				details	+='				</div>';
				details	+='				<div class="m-t-xs clearfix">';
				details	+='					<div class="pull-left col-xs-3" style="padding-right:0px;padding-left:10px;">认&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;证：</div>';
				details	+='					<div class="pull-left col-xs-9 no-padder"><img style="width:15px" src="'+ride_details.is_authentication_img+'" />'+ride_details.is_authentication+'</div>';
				details	+='				</div>';
				details	+='				<div class="m-t-xs clearfix">';
				details	+='					<div class="pull-left col-xs-3" style="padding-right:0px;padding-left:10px;">车&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;型：</div>';
				details	+='					<div class="pull-left col-xs-9 no-padder"><img style="width:18px" src="'+ride_details.car_type_img+'" />'+ride_details.car_type_s+'</div>';
				details	+='				</div>';
				details	+='				<div class="m-t-xs m-b-xs clearfix">';
				details	+='					<div class="pull-left col-xs-3" style="padding-right:0px;padding-left:10px;">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注：</div>';
				details	+='					<div class="pull-left col-xs-8 no-padder" style="word-break: break-all; word-wrap:break-word;">'+ride_details.package_remarks+'</div>';
				details	+='				</div>';
				details	+='			</div>';
				details	+='</div>';
				if(ride_details.order !== false){
					details	+='<div class="wrapper-sm">';
					details	+='	<div class="col-xs-7 no-padder m-b-xs text-left">千里追踪</div>';
					if(ride_details.status == 1){
						if(ride_details.order.status == 1){
							details	+='	<div id="goods_go" class="col-xs-5 m-b-xs text-right"><div class="btn btn-info">司机已收货</div></div>';
						}else if(ride_details.order.status == 3){
							details	+='	<div id="pay_go" class="col-xs-5 m-b-xs text-right"><div class="btn btn-info">付款</div></div>';
						}
					}
					details	+='	<div class="clearfix"></div>';
					details	+='	<div class="bg-white text-sm m-b-xs clearfix">';
					if(ride_details.order.complete_time != 0){
						details	+='			<div class="m-t-xs m-b-xs clearfix">';
						details	+='				<div class="pull-left col-xs-1" style="padding-right:0px;padding-left:10px;"><img class="thumb-xxxs" src="'+ride_details.IMG.urlKuaidi+'" /></div>';
						details	+='				<div class="pull-left col-xs-11 no-padder" style="word-break: break-all; word-wrap:break-word;">'+ride_details.order.complete_time_s+' 已付款</div>';
						details	+='			</div>';
					}
					if(ride_details.order.give_time != 0){
						details	+='			<div class="m-t-xs m-b-xs clearfix">';
						details	+='				<div class="pull-left col-xs-1" style="padding-right:0px;padding-left:10px;"><img class="thumb-xxxs" src="'+ride_details.IMG.urlKuaidi+'" /></div>';
						details	+='				<div class="pull-left col-xs-11 no-padder" style="word-break: break-all; word-wrap:break-word;">'+ride_details.order.give_time_s+' 已送达(司机完成送货，请付款)</div>';
						details	+='			</div>';
					}
					if(ride_details.order.collect_time != 0){
						details	+='			<div class="m-t-xs m-b-xs clearfix">';
						details	+='				<div class="pull-left col-xs-1" style="padding-right:0px;padding-left:10px;"><img class="thumb-xxxs" src="'+ride_details.IMG.urlKuaidi+'" /></div>';
						details	+='				<div class="pull-left col-xs-11 no-padder" style="word-break: break-all; word-wrap:break-word;">'+ride_details.order.collect_time_s+' 已收货 (等待司机送货)</div>';
						details	+='			</div>';
					}
					if(ride_details.order.cancel_time != 0){
						details	+='			<div class="m-t-xs m-b-xs clearfix">';
						details	+='				<div class="pull-left col-xs-1" style="padding-right:0px;padding-left:10px;"><img class="thumb-xxxs" src="'+ride_details.IMG.urlKuaidi+'" /></div>';
						details	+='				<div class="pull-left col-xs-11 no-padder" style="word-break: break-all; word-wrap:break-word;">'+ride_details.order.cancel_time_s+' 已取消</div>';
						details	+='			</div>';
					}
					if(ride_details.order.order_time != 0){
						details	+='			<div class="m-t-xs m-b-xs clearfix">';
						details	+='				<div class="pull-left col-xs-1" style="padding-right:0px;padding-left:10px;"><img class="thumb-xxxs" src="'+ride_details.IMG.urlKuaidi+'" /></div>';
						details	+='				<div class="pull-left col-xs-11 no-padder" style="word-break: break-all; word-wrap:break-word;">'+ride_details.order.order_time_s+' 已抢单 (司机已收货，请点击收货)</div>';
						details	+='			</div>';
					}
					if(ride_details.order.order_time != 0){
						details	+='			<div class="m-t-xs m-b-xs clearfix">';
						details	+='				<div class="pull-left col-xs-1" style="padding-right:0px;padding-left:10px;"><img class="thumb-xxxs" src="'+ride_details.IMG.urlKuaidi+'" /></div>';
						details	+='				<div class="pull-left col-xs-11 no-padder" style="word-break: break-all; word-wrap:break-word;">抢单人:'+ride_details.order.user_name+' <span class="text-primary"><a href="tel:'+ride_details.order.user_phone+'">'+ride_details.order.user_phone+'</a></span> (点击拨打电话)</div>';
						details	+='			</div>';
					}
					details	+='	</div>';
					details	+='</div>';
				}
				$('#details').html(details);
				a.busy(0);
			}
		},
		error:function(){
			a.msg('获取详情失败，请联系管理员');
			a.busy(0);
		}
	});
	}
	var windowHeight	=	$(window).height();
	var detailsHeight	=	$('#details').height();
	var headerHeight	=	$('#header').height();
	var suan	=	windowHeight-detailsHeight-headerHeight-9;
	if(suan > 0){
		$("#details").css("padding-bottom",suan);
	}
	$('#return_index').on('click',function(){
		if(status == 1){
			window.history.go(-1);
		}else{
			location.href = "{pigcms{:U('my_launch')}";
		}
    });
    $('#modify').on('click',function(){
		location.href = "{pigcms{:U('eidt',array('package_id'=>$package_id,'status'=>$status))}";
    });
    $('#xiadan').on('click',function(){
		location.href = "{pigcms{:U('grab_single',array('package_id'=>$package_id,'status'=>$status))}";
    });
    $('#goods_go').on('click',function(){
    	a.busy();
		$.ajax({
			type : "post",
			url : "{pigcms{:U('goods_go')}",
			dataType : "json",
			data:{
				order_id	:	order_id,
			},
			async:true,
			success : function(result){
				if(result.errorCode != 0){
					a.msg(result.errorMsg);
					a.busy(0);
				}else{
					a.msg('收货成功');
					a.busy(0);
					window.location.reload();
				}
			},
			error:function(){
				a.msg('更新失败，请联系管理员');
				a.busy(0);
			}
		});
	});
	$('#pay_go').on('click',function(){
		a.busy();
		$.ajax({
			type : "post",
			url : "{pigcms{:U('pay_go_json')}",
			dataType : "json",
			data:{
				order_id	:	order_id,
			},
			async:true,
			success : function(result){
				if(result.errorCode != 0){
					a.busy(0);
					a.msg(result.errorMsg);
				}else{
					a.msg('付款成功');
					a.busy(0);
					window.location.reload();
				}
			},
			error:function(){
				a.msg('更新失败，请联系管理员');
				a.busy(0);
			}
		});
	});
	$('#close').on('click',function(){
		a.busy();
		$.ajax({
			type : "post",
			url : "{pigcms{:U('close_json')}",
			dataType : "json",
			data:{
				package_id	:	package_id,
			},
			async:true,
			success : function(result){
				if(result.errorCode != 0){
					a.msg(result.errorMsg);
					a.busy(0);
				}else{
					a.msg('关闭众包成功');
					a.busy(0);
					location.href = "{pigcms{:U('my_launch')}";
				}
			},
			error:function(){
				a.msg('更新失败，请联系管理员');
				a.busy(0);
			}
		});
	});
	$('#cancel').on('click',function(){
		a.busy();
		$.ajax({
			type : "post",
			url : "{pigcms{:U('cancel_json')}",
			dataType : "json",
			data:{
				order_id	:	order_id,
				package_id	:	package_id,
				identity	:	1,
			},
			async:true,
			success : function(result){
				if(result.errorCode != 0){
					a.msg(result.errorMsg);
					a.busy(0);
				}else{
					a.msg('取消订单成功');
					a.busy(0);
					window.location.reload();
				}
			},
			error:function(){
				a.msg('更新失败，请联系管理员');
				a.busy(0);
			}
		});
	});
</script>
<include file="web_footer"/>