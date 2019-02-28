<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-base">
	<div class="wrapper-sm bg-gray">项目信息</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">项目单价：</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="ride_price" type="text" value="{pigcms{$ride_price} 元" class="m-t-xs w-full no-border f-30 pull-left" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">购买座位：</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="sit_number" type="text" value="{pigcms{$sit_number} 位" class="m-t-xs w-full no-border f-30" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">总&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;额：</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="total" type="text" value="{pigcms{$total} 元" class="m-t-xs w-full no-border f-30" readonly>
		</div>
	</div>
	<div class="wrapper-sm bg-gray">返还金额</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">违约金：</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="sit_number" type="text" value="{pigcms{$penalty} 元" class="m-t-xs w-full no-border f-30" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">返还金额：</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="sit_number" type="text" value="{pigcms{$back} 元" class="m-t-xs w-full no-border f-30" readonly>
		</div>
	</div>
	<div class="text-center m-t-xl">账号余额会增加<span class="text-danger">{pigcms{$back}元</span>，请注意查收</div>
	<div id="submit" class="text-center m-b-sm"><div class="btn btn-info m-t-xl w-p-90">确定取消</div></div>
	<div id="recharge" class="text-center m-t-xxl"></div>
</div>
<script>
	var	wx_host	=	'{pigcms{$site_url}';
	var	total	=	'{pigcms{$total}';
	var	order_id	=	'{pigcms{$order_id}';
	var penalty	=	'{pigcms{$back}';
        var is_indep_house = "{pigcms{:defined('IS_INDEP_HOUSE')}";
        if(is_indep_house){
		var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
	}else{
		var domain_host = 'wap.php';
	}

	$('#return_index').on('click',function(){
		location.href = history.back(-1);
    });
    $('#submit').on('click',function(){
		if(total=='undefined' || total=='' || total==null || total==0){
			a.msg('总价不能为空');
		}else{
			a.busy();
			$.ajax({
				type : "post",
				url : wx_host+ domain_host +'?g=Wap&c=Ride&a=ride_order_cancel_api',
				dataType : "json",
				data:{
					total	:	total,
					order_id	:	order_id,
					penalty	:	penalty,
				},
				async:true,
				success : function(result){
					if(result.errorCode != 0){
						a.msg(result.errorMsg);
					}else{
						a.msg('退款成功');
						location.href = domain_host + '?g=Wap&c=Ride&a=ride_carpooling';
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
</script>
<include file="web_footer"/>