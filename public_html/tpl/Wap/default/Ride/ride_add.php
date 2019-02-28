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
			<div class="m-t-xs">标&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;题</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="ride_title" type="text" placeholder="请输入标题" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">出&nbsp;&nbsp;发&nbsp;&nbsp;地</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="departure_place" type="text" placeholder="请选择出发地" class="m-t-xs w-full no-border f-30" readonly>
		</div>
		<!--<div class="col-xs-1 f-10">
			<a href="http://www.baidu.com"><div class="m-t-xs text-lg pull-right"><b>+</b></div></a>
		</div>-->
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">目&nbsp;&nbsp;的&nbsp;&nbsp;地</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="destination" type="text" placeholder="请输入目的地" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">出发天数</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<!--<span>1天</span><input name="driver_seat" type="radio" value="1" class="m-t-xs no-border f-30">
			<span>每天</span><input name="driver_seat" type="radio" value="2" class="m-t-xs no-border f-30">-->
			<div id="one_day" class="col-xs-3 wrapper-xs">
				<button class="btn btn-info zai">一天</button>
			</div>
			<div id="all_day" class="col-xs-3 wrapper-xs">
				<button class="btn" style="background-color:#999;color:#fff;">每天</button>
			</div>
		</div>

	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs" id="start_time_s">出发时间</div>
		</div>
		<div id="start_time_f" class="col-xs-8 no-padder m-l-n">
			<input id="start_time" type="datetime-local" placeholder="请输入出发时间" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;价</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="ride_price" type="number" placeholder="(元)" class="m-t-xs w-full no-border f-30" onkeypress="return myNumberic(event)">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">违约时间</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="cancel_time" type="number" placeholder="默认发车(30分钟)前允许取消" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">违&nbsp;&nbsp;约&nbsp;&nbsp;金</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="penalty" type="number" placeholder="乘客取消，扣除相应违约金 0为不扣" class="m-t-xs w-full no-border f-30" onkeypress="return myNumberic(event)">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">提供座位</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="seat_number" type="number" placeholder="(个)" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<if condition="$truename">
				<input id="owner_name" type="text" value="{pigcms{$truename}" class="m-t-xs w-full no-border f-30">
			<else/>
				<input id="owner_name" type="text" placeholder="请输入您的姓名" class="m-t-xs w-full no-border f-30">
			</if>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<if condition="$phone">
				<input id="owner_phone" type="number" value="{pigcms{$phone}" class="m-t-xs w-full no-border f-30">
			<else/>
				<input id="owner_phone" type="number" placeholder="请输入您的手机" class="m-t-xs w-full no-border f-30">
			</if>
		</div>
	</div>
	<div id="submit" class="text-center m-b-sm"><div class="btn btn-info m-t w-p-90">提交信息</div></div>
</div>
<script>

		function myNumberic(e,len) {
			var obj=e.srcElement || e.target;
			var dot=obj.value.indexOf(".");//alert(e.which);
			len =(typeof(len)=="undefined")?2:len;
			var  key=e.keyCode|| e.which;
			if(key==8 || key==9 || key==46 || (key>=37  && key<=40))//这里为了兼容Firefox的backspace,tab,del,方向键
				return true;
			if (key<=57 && key>=48) { //数字
				if(dot==-1)//没有小数点
					return true;
				else if(obj.value.length<=dot+len)//小数位数
					return true;
				} else if((key==46) && dot==-1){//小数点
					return true;
			}       
			return false;
		}
	var is_indep_house = "{pigcms{:defined('IS_INDEP_HOUSE')}";
    if(is_indep_house){
            var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
    }else{
            var domain_host = 'wap.php';
    }
	var car_status	=	'{pigcms{$car_status}';
	if(!car_status){
		alert('您还未进行车主认证');
		if(is_indep_house){
			location.href = domain_host + "?g=Wap&c=House&a=car_apply";
		}else{
			location.href = "{pigcms{:U('My/car_apply')}";
		}
	}else if(car_status == 0){
		alert('您的车主认证未审核，请联系客服');
		if(is_indep_house){
			location.href = domain_host + "?g=Wap&c=House&a=car_owner";
		}else{
			location.href = "{pigcms{:U('My/car_owner')}";
		}
	}else if(car_status == 2){
		alert('您的车主认证未通过，请重新认证');
		if(is_indep_house){
			location.href = domain_host + "?g=Wap&c=House&a=car_owner";
		}else{
			location.href = "{pigcms{:U('My/car_owner')}";
		}
	}
	var	wx_host	=	'{pigcms{$site_url}';
	var type	=	'{pigcms{$type}';
	var now_city	=	'{pigcms{$now_city}';
	var province	=	'{pigcms{$province}';
	var houseVillage	=	'{pigcms{$houseVillage}';
	var ride_title	=	sessionStorage.getItem("ride_title");
    var departure_place	=	sessionStorage.getItem("adress");
    var destination	=	sessionStorage.getItem("destination");
    var start_time	=	sessionStorage.getItem("start_time");
    var ride_price	=	sessionStorage.getItem("ride_price");
    var cancel_time	=	sessionStorage.getItem("cancel_time");
    var penalty		=	sessionStorage.getItem("penalty");
    var seat_number	=	sessionStorage.getItem("seat_number");
    $('#ride_title').val(ride_title);
    if(departure_place == null){
		$('#departure_place').val(houseVillage);
    }else{
		$('#departure_place').val(departure_place);
    }
    $('#destination').val(destination);
    $('#start_time').val(start_time);
    $('#ride_price').val(ride_price);
    $('#cancel_time').val(cancel_time);
    $('#penalty').val(penalty);
    $('#seat_number').val(seat_number);
	$('#return_index').on('click',function(){
		sessionStorage.clear();
		if(type == 1){
			location.href = domain_host + "?g=Wap&c=Ride&a=ride_list";
		}else{
			location.href = domain_host + "?g=Wap&c=Ride&a=ride_history";
		}
    });
    $('#one_day').on('click',function(){
		var one_day	=	'<button class="btn btn-info zai">一天</button>';
		$('#one_day').html(one_day);
		var all_day	=	'<button class="btn" style="background-color:#999;color:#fff;">每天</button>';
		$('#all_day').html(all_day);
		$('#start_time_s').html('出发时间');
    })
    $('#all_day').on('click',function(){
		var all_day	=	'<button class="btn btn-info zai">每天</button>';
		$('#all_day').html(all_day);
		var one_day	=	'<button class="btn" style="background-color:#999;color:#fff;">一天</button>';
		$('#one_day').html(one_day);
		$('#start_time_s').html('开始日期');
    })
    $('#departure_place').on('click',function(){
    	sessionStorage.setItem("ride_title", $('#ride_title').val());
    	sessionStorage.setItem("destination", $('#destination').val());
    	sessionStorage.setItem("start_time", $('#start_time').val());
    	sessionStorage.setItem("ride_price", $('#ride_price').val());
    	sessionStorage.setItem("cancel_time", $('#cancel_time').val());
    	sessionStorage.setItem("penalty", $('#penalty').val());
    	sessionStorage.setItem("seat_number", $('#seat_number').val());
		location.href = "{pigcms{:U('Ride/adres_map')}";
    })
    $('#submit').on('click',function(){
		ride_title		=	$('#ride_title').val();
		departure_place	=	$('#departure_place').val();
		destination		=	$('#destination').val();
		start_time		=	$('#start_time').val();
		ride_price		=	$('#ride_price').val();
		cancel_time		=	$('#cancel_time').val();
		penalty			=	$('#penalty').val();
		seat_number		=	$('#seat_number').val();
		var owner_name		=	$('#owner_name').val();
		var owner_phone		=	$('#owner_phone').val();
		var one_day			=	$('#one_day button').css('background-color');
		if(cancel_time == ''){
			var is_cancel_time	=	1;
		}else if(cancel_time == 0){
			var is_cancel_time	=	2;
		}else{
			var is_cancel_time	=	3;
		}
		if(!ride_title){
			a.msg('标题不能为空');
		}else if(!departure_place){
			a.msg('出发地不能为空');
		}else if(!destination){
			a.msg('目的地不能为空');
		}else if(!start_time){
			a.msg('出发时间不能为空');
		}else if(!ride_price){
			a.msg('单价不能为空');
		}else if(Number(penalty) > Number(ride_price)){
			a.msg('违约金不能大于单价');
		}else if(Number(ride_price) == 0){
			a.msg('单价不能为0');
		}else if(!seat_number){
			a.msg('座位数不能为空');
		}else if(!owner_name){
			a.msg('姓名不能为空');
		}else if(!owner_phone){
			a.msg('手机号不能为空');
		}else{
			one_day		=	rgb2hex(one_day);
			if(one_day == '5bc0de'){
				var ride_date_number		=	1;
			}else{
				var ride_date_number		=	2;
			}
			a.busy();
			$.ajax({
				type : "post",
				url : wx_host+ domain_host +'?g=Wap&c=Ride&a=ride_add_api',
				dataType : "json",
				data:{
					ride_title	:	ride_title,
					departure_place	:	departure_place,
					destination	:	destination,
					start_time	:	start_time,
					ride_price	:	ride_price,
					cancel_time	:	cancel_time,
					penalty		:	penalty,
					seat_number	:	seat_number,
					owner_name	:	owner_name,
					owner_phone	:	owner_phone,
					ride_date_number	:	ride_date_number,
					is_cancel_time	:	is_cancel_time,
				},
				async:true,
				success : function(result){
					if(result.errorCode != 0){
						a.msg(result.errorMsg);
						a.busy(0);
					}else{
						a.busy(0);
						sessionStorage.clear();
						location.href = domain_host + "?g=Wap&c=Ride&a=ride_list";
					}
				},
				error:function(){
					a.msg('发布失败，请联系管理员');
					a.busy(0);
				}
			});
		}
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