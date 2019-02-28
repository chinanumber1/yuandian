<include file="web_head"/>
<div class="app-header bg-gray2 padder w-full p-t8 b-b color-while">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22" style="padding: 10px 20px 15px 18px;margin-left:-15px;margin-top:-6px;"></div>
	<div id="index" class="text-lg margin-top3 color-while">{pigcms{$index}</div>
	<div class="clearfix"></div>
</div>
<div id="eidt" class="app-content with-header text-left text-base">
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
	<!--<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_money" type="number" placeholder="您要付多少运费" class="m-t-xs w-full no-border f-30">
		</div>
	</div>-->
	<!--<div class="clearfix b-b" style="padding-bottom:5px;">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">需交押金</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<input id="package_deposit" type="number" placeholder="您要送货师傅给多少押金" class="m-t-xs w-full no-border f-30">
		</div>
	</div>-->
	<div class="clearfix b-b">
		<div class="col-xs-4 f-10">
			<div class="m-t-xs">是否认证</div>
		</div>
		<div class="col-xs-8 no-padder m-l-n">
			<div id="need" class="col-xs-3 wrapper-xs m-r"></div>
			<div id="needNo" class="col-xs-3 wrapper-xs"></div>
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
					<option value="{pigcms{$vo['category_id']}" data-bgimg="{pigcms{$vo.category_img}" <if condition="$vo['category_id'] eq $details['car_type']">selected</if>>{pigcms{$vo['category_name']}</option>
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
	<div id="submit" class="text-center m-b-sm"><div class="btn btn-info m-t w-p-90">提交信息</div></div>
</div>
<script>
var crowdsourcing	=	'{pigcms{$crowdsourcing}';
if(crowdsourcing	== 0){
	var rideList	=	'<div class="text-center m-t m-b">未开通众包平台</div>';
	$('#eidt').html(rideList);
}else{
	if("{pigcms{$details['package_status']}" != 1){
		a.msg('众包不能修改');
		setTimeout(function () {
        	history.back();
    	}, 300);
	}
	var package_id			=	"{pigcms{$details['package_id']}";
	var package_title	=	sessionStorage.getItem("package_title");
    var package_start	=	sessionStorage.getItem("package_start");
    var package_start_long	=	sessionStorage.getItem("package_start_long");
    var package_start_lat	=	sessionStorage.getItem("package_start_lat");
    var package_end		=	sessionStorage.getItem("package_end");
    var package_end_long	=	sessionStorage.getItem("package_end_long");
    var package_end_lat		=	sessionStorage.getItem("package_end_lat");
    // var package_money	=	sessionStorage.getItem("package_money");
    var package_deposit	=	sessionStorage.getItem("package_deposit");
    var is_authentication=	sessionStorage.getItem("is_authentication");
//    var car_type		=	sessionStorage.getItem("car_type");
    var user_name		=	sessionStorage.getItem("user_name");
    var user_phone		=	sessionStorage.getItem("user_phone");
    var package_remarks	=	sessionStorage.getItem("package_remarks");
    if(package_title == null){
		package_title	=	"{pigcms{$details['package_title']}";
    }
    if(package_start == null){
		package_start	=	"{pigcms{$details['package_start']}";
    }
    if(package_start_long == null){
		package_start_long	=	"{pigcms{$details['package_start_long']}";
    }
    if(package_start_lat == null){
		package_start_lat	=	"{pigcms{$details['package_start_lat']}";
    }
	if(package_end == null){
		package_end	=	"{pigcms{$details['package_end']}";
	}
	if(package_end_long == null){
		package_end_long	=	"{pigcms{$details['package_end_long']}";
	}
	if(package_end_lat == null){
		package_end_lat	=	"{pigcms{$details['package_end_lat']}";
	}
	// if(package_money == null){
	// 	package_money	=	"{pigcms{$details['package_money']}";
	// }
    if(package_deposit == null){
		package_deposit	=	"{pigcms{$details['package_deposit']}";
    }
    if(user_name == null){
		user_name	=	"{pigcms{$details['user_name']}";
    }
    if(user_phone == null){
		user_phone	=	"{pigcms{$details['user_phone']}";
    }
    if(package_remarks == null){
		package_remarks	=	"{pigcms{$details['package_remarks']}";
    }
    if(is_authentication == null){
		is_authentication	=	"{pigcms{$details['is_authentication']}";
    }
    if(is_authentication == 1){
		need();
    }else if(is_authentication == 2){
		needNo();
    }
    //if(car_type == null){
//		car_type	=	"{pigcms{$details['car_type']}";
//    }
//    if(car_type == 1){
//		small();
//    }else if(car_type == 2){
//		large();
//    }else if(car_type == 3){
//		middle();
//    }
    $('#package_title').val(package_title);
	$('#package_start').val(package_start);
	$('#package_end').val(package_end);
	// $('#package_money').val(package_money);
	$('#package_deposit').val(package_deposit);
	$('#user_name').val(user_name);
	$('#user_phone').val(user_phone);
	$('#package_remarks').val(package_remarks);
}
	//	选择出发地
	$('#package_start').on('click',function(){
		setSession();
		location.href = "{pigcms{:U('adres_map',array('type'=>3,'package_id'=>$details['package_id']))}";
    });
    //	选择目的地
    $('#package_end').on('click',function(){
    	setSession();
		location.href = "{pigcms{:U('adres_map',array('type'=>4,'package_id'=>$details['package_id']))}";
    });
    function setSession(){
		sessionStorage.setItem("package_title", $('#package_title').val());
    	sessionStorage.setItem("package_start", $('#package_start').val());
    	sessionStorage.setItem("package_end", $('#package_end').val());
    	// sessionStorage.setItem("package_money", $('#package_money').val());
    	sessionStorage.setItem("package_deposit", $('#package_deposit').val());
    	sessionStorage.setItem("is_authentication",is_authentication);
//    	sessionStorage.setItem("car_type",car_type);
    	sessionStorage.setItem("user_name", $('#user_name').val());
    	sessionStorage.setItem("user_phone", $('#user_phone').val());
    	sessionStorage.setItem("package_remarks", $('#package_remarks').val());
    }
    //	选择是否认证
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
	//	返回来源页面
	$('#return_index').on('click',function(){
		sessionStorage.clear();
		location.href =	"{pigcms{:U('details',array('package_id'=>$details['package_id'],'status'=>$status))}";
    });
    //	提交修改按钮
    $('#submit').on('click',function(){
		var package_title	=	$('#package_title').val();
		var package_start	=	$('#package_start').val();
		var package_end		=	$('#package_end').val();
		// var package_money	=	$('#package_money').val();
		var package_deposit	=	$('#package_deposit').val();
		var user_name		=	$('#user_name').val();
		var user_phone		=	$('#user_phone').val();
		var package_remarks	=	$('#package_remarks').val();
		var car_type		=	$('#city').val();
		if(!package_title){
			a.msg('标题不能为空');
		}else if(!package_start){
			a.msg('出发地不能为空');
		}else if(!package_end){
			a.msg('目的地不能为空');
		// }else if(!package_money){
		// 	a.msg('众包运费不能为空');
		}else if(!user_name){
			a.msg('姓名不能为空');
		}else if(!user_phone){
			a.msg('电话不能为空');
		}else if(!package_remarks){
			a.msg('备注不能为空');
		}else{
			a.busy();
			$.ajax({
				type : "post",
				url : "{pigcms{:U('eidt_json')}",
				dataType : "json",
				data:{
					package_id	:	package_id,
					package_title	:	package_title,
					package_start	:	package_start,
					package_start_long	:	package_start_long,
					package_start_lat	:	package_start_lat,
					package_end		:	package_end,
					package_end_long	:	package_end_long,
					package_end_lat		:	package_end_lat,
					// package_money	:	package_money,
					package_deposit	:	package_deposit,
					is_authentication:	is_authentication,
					car_type		:	car_type,
					user_name		:	user_name,
					user_phone		:	user_phone,
					package_remarks	:	package_remarks,
				},
				async:true,
				success : function(result){
					if(result.errorCode == 0){
						sessionStorage.clear();
						a.msg('修改成功');
						a.busy(0);
						location.href =	"{pigcms{:U('details',array('package_id'=>$details['package_id']))}";
					}else{
						a.msg(result.errorMsg);
						a.busy(0);
					}
				},
				error:function(){
					a.msg('修改失败，请联系管理员');
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