<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div id="add" class="app-content with-header text-left text-base">
	<div class="padding-tb5 bg-gray"></div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">标&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;题</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_title" type="text" placeholder="请输入标题" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">出&nbsp;&nbsp;发&nbsp;&nbsp;地</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_start" type="text" placeholder="请选择出发地" class="m-t-xs w-full no-border f-30" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">目&nbsp;&nbsp;的&nbsp;&nbsp;地</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_end" type="text" placeholder="请输入目的地" class="m-t-xs w-full no-border f-30" readonly>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_money" type="number" placeholder="您要付多少运费" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">需交押金</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_deposit" type="number" placeholder="您要送货师傅给多少押金" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">是否认证</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<div id="need" class="col-xs-3 wrapper-xs m-r">
				<button class="btn btn-info m-r">需认证</button>
			</div>
			<div id="needNo" class="col-xs-3 wrapper-xs">
				<button class="btn" style="background-color:#999;color:#fff;">不认证</button>
			</div>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">需要车型</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input type="text" id="city_dummy" class="m-t-xs w-full no-border f-30" >
			<select id="city" class="demo-test-select dw-hsel" data-role="none" tabindex="-1">
				<volist name="category" id="vo">
					<option value="{pigcms{$vo['category_id']}"  data-bgimg="{pigcms{$vo.category_img}">{pigcms{$vo['category_name']}</option>
				</volist>
			</select>
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="user_name" type="text" placeholder="请输入您的姓名" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="user_phone" type="number" placeholder="请输入您的手机" class="m-t-xs w-full no-border f-30">
		</div>
	</div>
	<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<textarea id="package_remarks" type="text" placeholder="请输入对货物的详情说明" class="m-t-xs w-full no-border f-n-60"></textarea>
		</div>
	</div>
	<div id="pay_s" class="text-center m-t-sm b-b"></div>
	<div id="submit" class="text-center m-b-sm"><div class="btn btn-info m-t w-p-90">提交信息</div></div>
</div>
<script>
	var crowdsourcing	=	'{pigcms{$crowdsourcing}';
	var category_count	=	'{pigcms{$category_count}';
	if(category_count == 0){
		a.msg('没有可用车型，请联系管理员');
//		return false;
	}else{
		if(crowdsourcing	== 0){
			var rideList	=	'<div class="text-center m-t m-b">未开通众包平台</div>';
			$('#add').html(rideList);
		}else{
			var package_title	=	sessionStorage.getItem("package_title");
		    var package_start	=	sessionStorage.getItem("package_start");
		    var package_start_long	=	sessionStorage.getItem("package_start_long");
		    var package_start_lat	=	sessionStorage.getItem("package_start_lat");
		    var package_end		=	sessionStorage.getItem("package_end");
		    var package_end_long	=	sessionStorage.getItem("package_end_long");
		    var package_end_lat		=	sessionStorage.getItem("package_end_lat");
		    var package_money	=	sessionStorage.getItem("package_money");
		    var package_deposit	=	sessionStorage.getItem("package_deposit");
		    var is_authentication=	sessionStorage.getItem("is_authentication");
		    //var car_type		=	sessionStorage.getItem("car_type");
//		    if(car_type == null){
//				car_type	=	"{pigcms{$category[0]['category_id']}";
//		    }
		    var user_name		=	sessionStorage.getItem("user_name");
		    var user_phone		=	sessionStorage.getItem("user_phone");
		    var package_remarks	=	sessionStorage.getItem("package_remarks");
		    $('#package_title').val(package_title);
		    $('#package_start').val(package_start);
		    $('#package_end').val(package_end);
		    $('#package_money').val(package_money);
		    $('#package_deposit').val(package_deposit);
		    if(is_authentication == 1){
				need();
		    }else if(is_authentication == 2){
				needNo();
		    }
		    //if(car_type == "{pigcms{$category[0]['category_id']}"){
//				small();
//		    }else if(car_type == "{pigcms{$category[1]['category_id']}"){
//				large();
//		    }else if(car_type == "{pigcms{$category[2]['category_id']}"){
//				middle();
//		    }
		    if(user_name == null){
				user_name	=	'{pigcms{$user.truename}';
		    }
		    if(user_phone == null){
				user_phone	=	'{pigcms{$user.phone}';
		    }
		}
	}
	$('#user_name').val(user_name);
	$('#user_phone').val(user_phone);
	$('#package_remarks').val(package_remarks);
	$('#return_index').on('click',function(){
		sessionStorage.clear();
		location.href =	"{pigcms{:U('index')}";
	});
	$('#need').on('click',function(){
		need();
	})
	$('#needNo').on('click',function(){
		needNo();
	})

    function need(){
		var need	=	'<button class="btn btn-info">需认证</button>';
		$('#need').html(need);
		var needNo	=	'<button class="btn" style="background-color:#999;color:#fff;">不认证</button>';
		$('#needNo').html(needNo);
		is_authentication	=	1;
    }
    function needNo(){
		var needNo	=	'<button class="btn btn-info">不认证</button>';
		$('#needNo').html(needNo);
		var need	=	'<button class="btn" style="background-color:#999;color:#fff;">需认证</button>';
		$('#need').html(need);
		is_authentication	=	2;
    }
	//$('#small').on('click',function(){
//		small();
//	})
//	$('#large').on('click',function(){
//		large();
//	})
//	$('#middle').on('click',function(){
//		middle();
//	})
//	function small(){
//		var small	=	'<button class="btn btn-info">{pigcms{$category[0]["category_name"]}</button>';
//		$('#small').html(small);
//		var large	=	'<button class="btn" style="background-color:#999;color:#fff;">{pigcms{$category[1]["category_name"]}</button>';
//		$('#large').html(large);
//		var middle	=	'<button class="btn" style="background-color:#999;color:#fff;">{pigcms{$category[2]["category_name"]}</button>';
//		$('#middle').html(middle);
//		car_type	=	"{pigcms{$category[0]['category_id']}";
//	}
//	function large(){
//		var small	=	'<button class="btn" style="background-color:#999;color:#fff;">{pigcms{$category[0]["category_name"]}</button>';
//		$('#small').html(small);
//		var large	=	'<button class="btn btn-info">{pigcms{$category[1]["category_name"]}</button>';
//		$('#large').html(large);
//		var middle	=	'<button class="btn" style="background-color:#999;color:#fff;">{pigcms{$category[2]["category_name"]}</button>';
//		$('#middle').html(middle);
//		car_type	=	"{pigcms{$category[1]['category_id']}";
//	}
//	function middle(){
//		var small	=	'<button class="btn" style="background-color:#999;color:#fff;">{pigcms{$category[0]["category_name"]}</button>';
//		$('#small').html(small);
//		var large	=	'<button class="btn" style="background-color:#999;color:#fff;">{pigcms{$category[1]["category_name"]}</button>';
//		$('#large').html(large);
//		var middle	=	'<button class="btn btn-info">{pigcms{$category[2]["category_name"]}</button>';
//		$('#middle').html(middle);
//		car_type	=	"{pigcms{$category[2]['category_id']}";
//	}
    $('#package_start').on('click',function(){
		setSession();
		location.href = "{pigcms{:U('adres_map',array('type'=>1))}";
    });
    $('#package_end').on('click',function(){
    	setSession();
		location.href = "{pigcms{:U('adres_map',array('type'=>2))}";
    });
    function setSession(){
		sessionStorage.setItem("package_title", $('#package_title').val());
    	sessionStorage.setItem("package_start", $('#package_start').val());
    	sessionStorage.setItem("package_end", $('#package_end').val());
    	sessionStorage.setItem("package_money", $('#package_money').val());
    	sessionStorage.setItem("package_deposit", $('#package_deposit').val());
    	sessionStorage.setItem("is_authentication",is_authentication);
    	sessionStorage.setItem("user_name", $('#user_name').val());
    	sessionStorage.setItem("user_phone", $('#user_phone').val());
    	sessionStorage.setItem("package_remarks", $('#package_remarks').val());
    }
    $('#submit').on('click',function(){
		package_title	=	$('#package_title').val();
		package_start	=	$('#package_start').val();
		package_end		=	$('#package_end').val();
		package_money	=	$('#package_money').val();
		package_deposit	=	$('#package_deposit').val();
		user_name		=	$('#user_name').val();
		user_phone		=	$('#user_phone').val();
		package_remarks	=	$('#package_remarks').val();
		car_type		=	$('#city').val();
		if(!package_title){
			a.msg('标题不能为空');
		}else if(!package_start){
			a.msg('出发地不能为空');
		}else if(!package_end){
			a.msg('目的地不能为空');
		}else if(!package_money){
			a.msg('众包运费不能为空');
		}else if(!user_name){
			a.msg('姓名不能为空');
		}else if(!user_phone){
			a.msg('电话不能为空');
		}else{
			//a.busy();
			$.ajax({
				type : "post",
				url : "{pigcms{:U('add_json')}",
				dataType : "json",
				data:{
					package_title	:	package_title,
					package_start	:	package_start,
					package_start_long	:	package_start_long,
					package_start_lat	:	package_start_lat,
					package_end		:	package_end,
					package_end_long	:	package_end_long,
					package_end_lat		:	package_end_lat,
					package_money	:	package_money,
					package_deposit	:	package_deposit,
					need			:	is_authentication,
					car_type		:	car_type,
					user_name		:	user_name,
					user_phone		:	user_phone,
					package_remarks	:	package_remarks,
				},
				async:true,
				success : function(result){
					if(result.errorCode == 0){
						sessionStorage.clear();
						a.msg('发布成功');
						a.busy(0);
						location.href =	"{pigcms{:U('index')}";
					}else{
						if(result.errorCode == '20110023'){
							var now_money	=	'{pigcms{$user.now_money}';
							var money = package_money - now_money;
							var pay	=	'';
							pay	+=	'<div id="pay" class="m-b-sm " onclick="location.href = \'{pigcms{:U('My/recharge')}\'">您的余额<span class="color-green2">'+now_money+'</span>，还差'+money+'<br>请点击 <span class="color-orange">充值</span></div>';
							$('#pay_s').html(pay);
						}
						a.msg(result.errorMsg);
						a.busy(0);
					}
				},
				error:function(){
					a.msg('发布失败，请联系管理员');
					a.busy(0);
				}
			});
		}
    })
</script>
<script type="text/javascript" src="{pigcms{$static_path}bbs/Tablejs/mobiscroll.2.13.2.js"></script>
<script type="text/javascript">
var cat_img_arr = [];
$(function () {
    var opt = {
        'select': {
            preset: 'select'
        }
    }
    $('.demo-test-select').scroller($.extend(opt['select'],opt['default']));
});
</script>
<include file="web_footer"/>