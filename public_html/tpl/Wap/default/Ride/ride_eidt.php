<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-base">
	<div class="padding-tb5 bg-gray"></div>
	<div class="clearfix b-b">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">出发天数</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<div id="one_day" class="col-xs-3 wrapper-xs">
				<button class="btn btn-info zai">开启</button>
			</div>
			<div id="all_day" class="col-xs-3 wrapper-xs">
				<button class="btn" style="background-color:#999;color:#fff;">关闭</button>
			</div>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">提供座位</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="seat_number" type="number" placeholder="{pigcms{$seat_number}" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="wrapper-sm m-t text-center">如果选择<span class="text-danger">关闭</span>，会把已经付款的订单，(金额)退还给乘客。<br><br><span class="text-success">关闭以后不能再开启</span></div>
	<div id="submit" class="text-center m-b-sm"><div class="btn btn-info m-t w-p-90">提交信息</div></div>
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
		location.href = domain_host + "?g=Wap&c=Ride&a=ride_details&ride_id="+ride_id+"&status="+status;
    });
    $('#one_day').on('click',function(){
		var one_day	=	'<button class="btn btn-info zai">开启</button>';
		$('#one_day').html(one_day);
		var all_day	=	'<button class="btn" style="background-color:#999;color:#fff;">关闭</button>';
		$('#all_day').html(all_day);
    })
    $('#all_day').on('click',function(){
		var all_day	=	'<button class="btn btn-info zai">关闭</button>';
		$('#all_day').html(all_day);
		var one_day	=	'<button class="btn" style="background-color:#999;color:#fff;">开启</button>';
		$('#one_day').html(one_day);
    })
    $('#submit').on('click',function(){
    	a.busy();
		var seat_number		=	$('#seat_number').val();
		var one_day			=	$('#one_day button').css('background-color');
		one_day		=	rgb2hex(one_day);
		if(one_day == '5bc0de'){
			var is_status		=	1;
		}else{
			var is_status		=	4;
		}
		$.ajax({
			type : "post",
			url : wx_host+ domain_host +'?g=Wap&c=Ride&a=ride_eidt_api',
			dataType : "json",
			data:{
				seat_number	:	seat_number,
				is_status	:	is_status,
				ride_id		:	ride_id,
			},
			async:true,
			success : function(result){
				if(result.errorCode != 0){
					a.msg(result.errorMsg);
					a.busy(0);
				}else{
					a.busy(0);
					location.href = domain_host + "?g=Wap&c=Ride&a=ride_details&ride_id="+ride_id+"&status="+status;
				}
			},
			error:function(){
				a.msg('修改失败，请联系管理员');
				a.busy(0);
			}
		});
    })
    //	转换css的颜色值，把gbk(1,1,1)转换为#111111
    function rgb2hex(rgbString) {
		var parts = rgbString.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		// parts now should be ["rgb(0, 70, 255", "0", "70", "255"]
		delete (parts[0]);
		for (var i = 1; i <= 3; ++i) {
		  parts[i] = parseInt(parts[i]).toString(16);
		  if (parts[i].length == 1) parts[i] = '0' + parts[i];
		}
		return parts.join('');
	}
</script>
<include file="web_footer"/>