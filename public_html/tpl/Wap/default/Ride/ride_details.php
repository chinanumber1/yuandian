<include file="web_head"/>
<div id="header" class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div id="modify"></div>
	<div class="clearfix"></div>
</div>
<div id="details" class="app-content with-header text-left text-md bg-gray"></div>
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Ride",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Ride/ride_details',$_GET)}",
		"tTitle": "【拼车】-{pigcms{$config.site_name}",
		"tContent": "{pigcms{$config.site_name}拼车"
	};
</script>
{pigcms{$shareScript}
<script>
	var	ride_id	=	'{pigcms{$ride_id}';
	var	wx_host	=	'{pigcms{$site_url}';
	var	status	=	'{pigcms{$status}';
	var seat_number	=	'';
	var remain_number	=	'';
        var is_indep_house = "{pigcms{:defined('IS_INDEP_HOUSE')}";
        if(is_indep_house){
                var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
        }else{
                var domain_host = 'wap.php';
        }
	a.busy();
	$.ajax({
		type : "post",
		url : wx_host+ domain_host +'?g=Wap&c=Ride&a=ride_details_api',
		dataType : "json",
		data:{
			ride_id	:	ride_id,
		},
		async:false,
		success : function(result){
			if(result	==	'20100003'){
				a.msg('顺风车ID不能为空，请联系管理员');
			}else if(result	==	'20100004'){
				a.msg('顺风车详情出错，请联系管理员');
			}else{
				var	ride_details	=	result.result.ride_details;
				var	ride_order	=	result.result.ride_order;
				var	defaultImg	=	result.result.defaultImg;
				var	user_id	=	result.result.user_id;
				var	proportion	=	result.result.ride_log.proportion;
				seat_number	=	ride_details.seat_number;
				remain_number	=	ride_details.remain_number;
				if(user_id == 1){
					if(ride_details.status == 1 || ride_details.status == 3){
						var	modify	=	'';
						modify	+=	'<div id="eidt" class="pull-right right_img right_bottom m-t-n-md">修改</div>';
						$('#modify').html(modify);
					}
				}
				var	details 	=	'';
				details	+='<div class="wrapper-xs padding-lr5 bg-white">';
				if(ride_details.ride_date_number == 1){
					details	+='	<div class="m-t-sm text-base">'+ride_details.start_date+' 出发</div>';
				}else{
					details	+='	<div class="m-t-sm text-base">每天'+ride_details.start_date+' 出发</div>';
				}
				
				window.shareData.tTitle = '[拼车]'+ride_details.start_date+'从'+ride_details.departure_place.substring(3)+'到'+ride_details.destination+' '+ride_details.remain_number+'位已预约'+ride_details.sit_number+'位';
				
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
				if(proportion <= 0){
					details	+='				<div>车主信用度：<span class="text-danger">0%</span></div>';
				}else{
					details	+='				<div>车主信用度：<span class="text-danger">'+proportion+'%</span></div>';
				}
				details	+='			</div>';
				if(user_id != 1){
					if(ride_details.is_time == 0){
						details	+='			<div id="xiadan" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">立即下单</div></div>';
					}
				}else{
					if(ride_details.status == 5){
						details	+='			<div id="qiyong" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">启用</div></div>';
					}else if(ride_details.status == 1 || ride_details.status == 3){
						details	+='			<div id="zanting" class="col-xs-3 text-right no-padder m-r-xs pull-right m-t text-sm"><div class="btn btn-info m-t-n-sm">暂停</div></div>';
					}
				}
				details	+='			<div class="clearfix"></div>';
				details	+='		</div>';
				details	+='	</div>';
				details	+='</div>';
				details	+='<div class="wrapper-sm">';
				details	+='	<div class="m-b-xs">其他信息</div>';
				details	+='	<div class="bg-white padding-lr5 text-sm clearfix">';
				details	+='		<div class="pull-left">';
				details	+='			<div class="m-t-sm">发布时间：</div>';
				details	+='			<div class="m-t-xs">出发时间：</div>';
				details	+='			<div class="m-t-xs">费&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 用：</div>';
				details	+='			<div class="m-t-xs">违约时间：</div>';
				details	+='			<div class="m-t-xs">违&nbsp;&nbsp;约&nbsp;&nbsp;金：</div>';
				details	+='			<div class="m-t-xs">提供座位：</div>';
				details	+='			<div class="m-t-xs m-b-sm">剩余座位：</div>';
				details	+='		</div>';
				details	+='		<div class="pull-left">';
				details	+='			<div class="m-t-sm">'+ride_details.time+'</div>';
				details	+='			<div class="m-t-xs">'+ride_details.start_time_s+'</div>';
				details	+='			<div class="m-t-xs color-orange">'+ride_details.ride_price+'元/人</div>';
				if(ride_details.cancel_time == 0){
					details	+='			<div class="m-t-xs">不允许违约</div>';
				}else{
					details	+='			<div class="m-t-xs">发车'+ride_details.cancel_time+'分钟以前允许违约</div>';
				}
				details	+='			<div class="m-t-xs">'+ride_details.penalty+'元</div>';
				details	+='			<div class="m-t-xs">'+ride_details.seat_number+'位</div>';
				details	+='			<div class="m-t-xs m-b-sm color-green">'+ride_details.remain_number+'位</div>';
				details	+='		</div>';
				details	+='	</div>';

				if(ride_details.status == 6){
					details	+='</div>';
					details	+='<div class="wrapper-sm" style=" text-align: center;">';
					details	+='<span style="height:150px; color:red;" >该条顺风车信息已经关闭，暂时无法操作。</span>';
					details	+='</div>';
				}
				
				var	ride_order_length	=	ride_order.length;
				if(ride_order_length > 0){
					details	+='<div class="wrapper-sm">';
					details	+='	<div class="m-b-xs">已预约 <span class="color-orange">'+ride_details.sit_number+'</span> 位</div>';
					details	+='	<div class="bg-white padding-lr5 text-sm clearfix">';
					var length	=	ride_order_length-1;
					for(var y=0; y<ride_order_length;y++){
						if(y != length){
							details	+='		<div class="b-b">';
						}
						details	+='			<div class="col-xs-2 m-l-n m-t-xs" style="width:70px;"><img class="avatar" src="'+ride_order[y].avatar+'" /></div>';
						details	+='			<div class="col-xs-7 no-padder text-base m-b-xs">';
						details	+='				<div class="m-t-xs">'+ride_order[y].nickname+'</div>';
						details	+='				<div>预定：'+ride_order[y].sit_number+'位</div>';
						if(user_id == 1){
							details	+='				<div>电话：<a href="tel:'+ride_order[y].phone+'" style="color:blue">'+ride_order[y].phone+'</a></div>';
						}
						details	+='			</div>';
						if(user_id == 1){
							details	+='			<div class="col-xs-2 m-t-sm"><div onclick="back('+ride_order[y].order_id+')" class="btn btn-info">退单</div></div>';
						}
						details	+='		<div class="clearfix"></div>';
						if(y != length){
							details	+='		</div>';
						}
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
	var windowHeight	=	$(window).height();
	var detailsHeight	=	$('#details').height();
	var headerHeight	=	$('#header').height();
	var suan	=	windowHeight-detailsHeight-headerHeight-9;
	if(suan > 0){
		$("#details").css("padding-bottom",suan);
	}
	$('#return_index').on('click',function(){
		window.history.go(-1);
    });
    $('#eidt').on('click',function(){
		location.href = domain_host + "?g=Wap&c=Ride&a=ride_eidt&ride_id="+ride_id+"&status="+status+"&seat_number="+seat_number;
    });
    $('#xiadan').on('click',function(){
		location.href = domain_host + "?g=Wap&c=Ride&a=ride_place_order&ride_id="+ride_id+"&status="+status;
    });
    $('#zanting').on('click',function(){
		a.busy();
		$.ajax({
			type : "post",
			url : wx_host+ domain_host +'?g=Wap&c=Ride&a=ride_eidt_api',
			dataType : "json",
			data:{
				is_status	:	5,
				ride_id		:	ride_id,
			},
			async:true,
			success : function(result){
				if(result.errorCode != 0){
					a.msg(result.errorMsg);
					a.busy(0);
				}else{
					a.busy(0);
					window.location.reload();
				}
			},
			error:function(){
				a.msg('暂停失败，请联系管理员');
				a.busy(0);
			}
		});
    });
    $('#qiyong').on('click',function(){
		a.busy();
		$.ajax({
			type : "post",
			url : wx_host+ domain_host +'?g=Wap&c=Ride&a=ride_start_api',
			dataType : "json",
			data:{
				ride_id		:	ride_id,
			},
			async:true,
			success : function(result){
				if(result.errorCode != 0){
					a.msg(result.errorMsg);
					a.busy(0);
				}else{
					a.busy(0);
					window.location.reload();
				}
			},
			error:function(){
				a.msg('启用失败，请联系管理员');
				a.busy(0);
			}
		});
    });
    function	back(order_id){
    	if(!order_id){
			a.msg('订单ID不能为空');
    	}else{
			location.href = domain_host + "?g=Wap&c=Ride&a=ride_order_cancel_driver&order_id="+order_id;
    	}
    }

var motify = {
	checkIos:function(){
        if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
            return true;
        }else{
            return false;
        }
    },
    checkAndroid:function(){
        if(/(android)/.test(navigator.userAgent.toLowerCase())){
            return true;
        }else{
            return false;
        }
    },
	checkLifeApp:function(){
        if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
            return true;
        }else{
            return false;
        }
    },
	getLifeAppVersion:function(){
		var reg = /versioncode=(\d+),/;
		var arr = reg.exec(navigator.userAgent.toLowerCase());
		if(arr == null){
			return 0;
		}else{
			return parseInt(arr[1]);
		}
	},
	getAndroidVersion:function(){
		var index = navigator.userAgent.indexOf("Android");
		if(index >= 0){
			var androidVersion = parseFloat(navigator.userAgent.slice(index+8));
			if(androidVersion > 1){
				return androidVersion;
			}else{
				return 100;
			}
		}else{
			return 100;
		}
	}
}

if((motify.checkLifeApp() && motify.getLifeAppVersion() >= 50 && (motify.checkIos() || motify.checkAndroid())) || window.__wxjs_environment === 'miniprogram'){
	$('#header').hide();
	$('.app .app-content.with-header').css('padding-top','0px');
	if(motify.checkAndroid()){
		window.lifepasslogin.hideWebViewHeader(true);
	}else if(motify.checkIos()){
		$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
	}
}
function changeWebviewWindow(){
	$('#details').css({'padding-top':'0px',"padding-bottom":'0'});
	var windowHeight	=	$(window).height();
	var detailsHeight	=	$('#details').height();
	var suan	=	windowHeight-detailsHeight;
	$("#details").css("padding-bottom",suan);
}
</script>
<include file="web_footer"/>