<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div id="grab_single" class="app-content with-header text-left text-base">
	<div class="padding-tb5 bg-gray"></div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">标&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;题</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_title" type="text" class="m-t-xs w-full color-black2 no-border f-30" value="{pigcms{$details['package_title']}" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">出&nbsp;&nbsp;发&nbsp;&nbsp;地</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_start" type="text" class="m-t-xs w-full color-black2 no-border f-30" value="{pigcms{$details['package_start']}" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">目&nbsp;&nbsp;的&nbsp;&nbsp;地</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_end" type="text" class="m-t-xs w-full color-black2 no-border f-30" value="{pigcms{$details['package_end']}" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_money" type="number" class="m-t-xs w-full color-black2 no-border f-30" value="{pigcms{$details['package_money']}" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">需交押金</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_deposit" type="number" class="m-t-xs w-full color-black2 no-border f-30" value="{pigcms{$details['package_deposit']}" readonly>
		</div>
	</div>
	<div class="padding-tb5 bg-gray m-t-sm m-b-sm"></div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">您的姓名</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="name" type="text" class="m-t-xs w-full no-border f-30" value="{pigcms{$details['user']['truename']}">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">您的电话</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="phone" type="number" class="m-t-xs w-full no-border f-30" value="{pigcms{$details['user']['phone']}">
		</div>
	</div>
	<div id="submit" class="text-center m-b-sm"><div class="btn btn-info m-t w-p-90">确定抢单</div></div>
	<div id="pay_s" class="text-center m-b-sm"></div>
	<div id="real_name" class="text-center m-b-sm"></div>
</div>
<script>
	var crowdsourcing	=	'{pigcms{$crowdsourcing}';
	if(crowdsourcing	== 0){
		var rideList	=	'<div class="text-center m-t m-b">未开通众包平台</div>';
		$('#grab_single').html(rideList);
	}else{
		var package_id		=	"{pigcms{$details['package_id']}";
		var now_money		=	"{pigcms{$details['user']['now_money']}";
		var package_deposit			=	"{pigcms{$details['package_deposit']}";
		var money	=	package_deposit	-	now_money;
		money	=	Math.round(money*100)/100;
	}
	var is_indep_house = "{pigcms{:defined('IS_INDEP_HOUSE')}";
	if(is_indep_house){
		var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
	}else{
		var domain_host = 'wap.php';
	}
	$('#return_index').on('click',function(){
		window.history.go(-1);
    });
    $('#submit').on('click',function(){
    	var name		=	$('#name').val();
    	var phone		=	$('#phone').val();
    	var package_deposit		=	$('#package_deposit').val();
    	if(!name){
			a.msg('您的姓名不能为空!');
    	}else if(!phone){
			a.msg('您的电话不能为空!');
    	}else{
			a.busy();
			$.ajax({
				type : "post",
				url : "{pigcms{:U('grab_single_json')}",
				dataType : "json",
				data:{
					package_id	:	package_id,
					name	:	name,
					phone	:	phone,
					package_deposit	:	package_deposit,
				},
				async:true,
				success : function(result){
					if(result.errorCode == 0){
						a.busy(0);
						layer.open({
						  content:'下单成功',
						  btn: ['好的'],
						  shadeClose: false,
						  skin:'msg',
						  yes: function(){
							
							location.href =	"{pigcms{:U('my_response')}";
						  }
						});
						
						
					}else{
						if(result.errorCode == '20110013'){
							var pay	=	'';
							pay	+=	'<div class="m-t m-b">需要交付押金'+package_deposit+'，您的余额<span class="color-green2">'+now_money+'</span>，还差'+money+'元<br>请点击 <span class="color-orange">充值</span></div>';
							$('#submit').remove();
							$('#pay_s').html(pay);
						}else if(result.errorCode == '20110015'){
							var real_name	=	'';
							real_name += '<div class="m-t m-b">此单需要实名认证，请<span class="color-orange">点击</span>跳转到实名页面</div>';
							$('#submit').remove();
							$('#real_name').html(real_name);
						}else{
							a.msg(result.errorMsg);
						}
						a.busy(0);
					}
				},
				error:function(){
					a.msg('下单失败，请联系管理员');
					a.busy(0);
				}
			});
		}
    })
	$('#pay_s').on('click',function(){
		if(is_indep_house){
			location.href =	"{pigcms{:U('My/recharge',array('label'=>'wap_crowdsourcing_1_'.$_GET['package_id'].'_'.$_GET['status']))}";
		}else{
			location.href =	"{pigcms{:U('My/recharge',array('label'=>'wap_crowdsourcing_2_'.$_GET['package_id'].'_'.$_GET['status']))}";
		}

	});
	$('#real_name').on('click',function(){
		if(is_indep_house){
			location.href = domain_host + "?g=Wap&c=House&a=authentication&village_id={pigcms{$_SESSION['now_village_bind']['pigcms_id']}";
		}else{
			location.href =	"{pigcms{:U('My/authentication')}";
		}
	});
</script>
<include file="web_footer"/>