<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-base">
	<div class="padding-tb5 bg-gray"></div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">单价：</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="ride_price" type="text" value="{pigcms{$ride_price} 元" class="m-t-xs w-full no-border f-30 pull-left" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">剩余座位</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="remain_number" type="text" value="{pigcms{$remain_number} 位" class="m-t-xs w-full no-border f-30" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">您的姓名</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="reserve_name" type="text" placeholder="请输入您的姓名" value="{pigcms{$truename}" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">您的手机</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="reserve_phone" type="number" placeholder="请输入您的手机号码" value="{pigcms{$phone}" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">预定座位</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="reserve_number" type="number" placeholder="不能大于提供座位" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div id="submit" class="text-center m-b-sm"><div class="btn btn-info m-t-xxl w-p-90">确定下单</div></div>
	<div id="recharge" class="text-center m-t-xxl"></div>
</div>
<script>
	var	wx_host	=	'{pigcms{$site_url}';
	var	ride_id	=	'{pigcms{$ride_id}';
	var	status	=	'{pigcms{$status}';
    var is_indep_house = "{pigcms{:defined('IS_INDEP_HOUSE')}";
    if(is_indep_house){
		var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
	}else{
		var domain_host = 'wap.php';
	}
	$('#return_index').on('click',function(){
		location.href = domain_host+"?g=Wap&c=Ride&a=ride_details&ride_id="+ride_id+"&status="+status;
    });
    $('#submit').on('click',function(){
		var reserve_number		=	$('#reserve_number').val();
		var reserve_name		=	$('#reserve_name').val();
		var reserve_phone		=	$('#reserve_phone').val();
		if(reserve_name=='undefined' || reserve_name=='' || reserve_name==null){
			a.msg('您的姓名不能为空');
		}else if(!reserve_phone){
			a.msg('您的手机号不能为空');
		}else if(!reserve_number){
			a.msg('预定座位不能为空');
		}else{
			a.busy();
			$.ajax({
				type : "post",
				url : wx_host+domain_host+'?g=Wap&c=Ride&a=ride_place_order_api',
				dataType : "json",
				data:{
					reserve_number	:	reserve_number,
					reserve_name	:	reserve_name,
					reserve_phone	:	reserve_phone,
					ride_id	:	ride_id,
				},
				async:true,
				success : function(result){
					if(result.errorCode != 0){
						a.msg(result.errorMsg);
					}else{
						if(result.result.errorCode == 1){
							var recharge	=	'';
							recharge	+='<div class="m-b">"您的账号余额为<span class="color-green">'+result.result.errorMsg+'</span> 元,请先充值账户金额"</div>';
							recharge	+='<div>"还差<span class="color-orange">'+result.result.result+'元</span>就能预定顺风车了"</div>';
							if(is_indep_house){
								recharge	+='<a href="'+domain_host+'?g=Wap&c=My&a=recharge&label=wap_ride_1_'+ride_id+'_'+status+'"><div id="chong" class="btn btn-info w-p-60 m-t">立即充值</div></a>';
							}else{
								recharge	+='<a href="'+domain_host+'?g=Wap&c=My&a=recharge&label=wap_ride_2_'+ride_id+'_'+status+'"><div id="chong" class="btn btn-info w-p-60 m-t">立即充值</div></a>';
							}
							$('#recharge').html(recharge);
						}else if(result.result.errorCode == 2){
							a.msg(result.result.errorMsg);
						}else{
							console.log(result)
							// a.msg(result.result.errorMsg);
							layer.open({
								content: result.result.errorMsg
								,btn: '确定'
								 ,yes: function(index){
								  location.href = domain_host+"?g=Wap&c=Ride&a=ride_carpooling";
								}
							  });
							  
							  layer.open({
								  content:result.result.errorMsg,
								  btn: ['好的'],
								  shadeClose: false,
								  skin:'msg',
								  yes: function(){
									location.href = domain_host+"?g=Wap&c=Ride&a=ride_carpooling";
								  }
								});
							
						}
					}
					a.busy(0);
				},
				error:function(){
					a.msg('页面错误，请联系管理员');
					a.busy(0);
				}
			});
		}
    })

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
	$('.app .app-header').hide();
	$('.app .app-content.with-header').css('padding-top','0px');
	if(motify.checkAndroid()){
		window.lifepasslogin.hideWebViewHeader(true);
	}else if(motify.checkIos()){
		$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
	}
}
</script>
<include file="web_footer"/>