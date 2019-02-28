var postdata={};
function checkSubmit(){
	var message = "请正确填写以下信息:<br>";
	if($("input[name='target_shop_id']")[0] && (!Number($("input[name='target_shop_id']").val()) || Number($("input[name='target_shop_id']").val()) <= 0)){
		message += '目标店铺编号<br>';
	}
	if($("input[name='target_phone']")[0] && ($("input[name='target_phone']").val() == '' || !checkMobile($("input[name='target_phone']").val()))){
		message += '目标店铺店长手机号<br>';
	}
	if($("input[name='target_password']")[0] && $("input[name='target_password']").val() == ''){
		message += '该店长密码<br>';
	}
	if($("input[name='manager_phone']")[0] && ($("input[name='manager_phone']").val() == '' || !checkMobile($("input[name='manager_phone']").val()))){
		message += '店长手机号<br>';
	}
	if($("input[name='manager_password']")[0] && $("input[name='manager_password']").val() == ''){
		message += '店长密码<br>';
	}
	if($("input[name='account']")[0] && ($("input[name='account']").val() == '')){
		message += '帐 号<br>';
	}else if($("input[name='account']").size() >0){
	   postdata.account=$.trim($("input[name='account']").val());
	   
	}
	if($("input[name='pwd']")[0] && $("input[name='pwd']").val() == ''){
		message += '密 码<br>';
	}else if($("input[name='pwd']").size() >0){
	    postdata.pwd=$.trim($("input[name='pwd']").val());
		if(postdata.account.pwd < 2){
	      message += '密 码长度小于2位<br>';
	   }
	}
	if($("input[name='mername']")[0] && $("input[name='mername']").val() == ''){
		message += '商家名称<br>';
	}else{
	   postdata.mername=$.trim($("input[name='mername']").val());
	}


	postdata.email=$.trim($("input[name='email']").val());

	if($("input[name='shop_name']")[0] && $("input[name='shop_name']").val() == ''){
		message += '店铺名称<br>';
	}
	if($("textarea[name='txt_info']")[0] && $("textarea[name='txt_info']").val() == ''){
		message += '店铺描述<br>';
	}
	if($("input[name='name']")[0] && $("input[name='name']").val() == ''){
		message += '店铺名称<br>';
	}
	if($("input[name='staff_phone']")[0] && ($("input[name='staff_phone']").val() == '' || !checkMobile($("input[name='staff_phone']").val()))){
		message += '员工手机号<br>';
	}
	if($("input[name='staff_name']")[0] && $("input[name='staff_name']").val() == ''){
		message += '员工姓名<br>';
	}
	if($("input[name='staff_username']")[0] && $("input[name='staff_username']").val() == ''){
		message += '员工用户名<br>';
	}
	if($("input[name='staff_password']")[0] && $("input[name='staff_password']").val() == ''){
		message += '初始密码<br>';
	}
	if(!international_phone && $("input[name='phone']")[0] && $("input[name='phone']").val() != '' ){
		var str = $("input[name='phone']").val();
		var type = str.substring(0,1);
		if(type == 0){
			var result=str.match(/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/);
			if( result == null){
				message += '联系电话，座机号码格式为：区号-座机号<br>';
			}
		}else{
			var result=str.match(/^[0-9]{11}$/);
			if( result == null || str.length != 11){
				message += '联系电话<br>';
			}
		}
		postdata.phone=$.trim(str);
	}else if($("input[name='phone']").val() == '' ){
		message += '联系电话<br>';
	}
	if($("input[name='address']")[0] && $("input[name='address']").val() == ''){
		message += '详细地址<br>';
	}
	if($("input[name='title']")[0] && $("input[name='title']").val() == ''){
		message += '商品名称<br>';
	}
	if($("input[name='price']")[0] && $("input[name='price']").val() == ''){
		message += '商品价格<br>';
	}
	if($("select[name='top_cat_id']")[0] && $("select[name='top_cat_id']").val() == ''){
		message += '商品分类<br>';
	}
	if($("input[name='realname']")[0] && $("input[name='realname']").val() == ''){
		message += '真实姓名<br>';
	}
	if(message != "请正确填写以下信息:<br>"){
		alert_open(message);
		return false;
	}
	var type_id=$.trim($("input[name='type_id']").val());
	if(type_id=='login'){
		checkLogin();
		return false;
	}
	if(type_id=='staff_add'){
		staff_add();
		return false;
	}
	if(type_id=='staff_edit'){
		staff_edit();
		return false;
	}
	if(type_id=='mer_reg'){
		if($("input[name='verify']")[0] && $("input[name='verify']").val() == ''){
		   alert_open('验证码没有填写');
		   return false;
	    }
		postdata.area_id=$("select[name='area_id']").val();
		postdata.circle_id=$("select[name='circle_id']").val();
		postdata.city_id=$("select[name='city_id']").val();
		postdata.province_id=$("select[name='province_id']").val();
		postdata.verify=$("input[name='verify']").val();
		postdata.spread_code=$("input[name='spread_code']").val();
		postdata.uid=$("input[name='uid']").val();
		mer_reg();
		return false;
	}
	return true;
}
function hardware_add(){
	//alert($("select[name='store_id']").val());
	$.ajax({
	   type: "POST",
	   url: "/index.php?g=WapMerchant&c=Index&a=hardware_add",
	   data: "&mcode="+$("input[name='mcode']").val()+"&username="+$("input[name='username']").val()+"&mkey="+$("input[name='mkey']").val()+"&count="+$("input[name='count']").val()+"&mp="+$("input[name='mp']").val()+"&store_id="+$("select[name='store_id']").val()+"&paid="+$("input[name='paid']").val()+"&pigcms_id="+$("input[name='pigcms_id']").val(),
	   success: function(msg){
		var	data=$.parseJSON(msg);
			if(data.error==0){
				location.href ='/index.php?g=WapMerchant&c=Index&a=hardware';
				}else{
					alert_open(data.msg);
				}
		   }
		});
		
}
function checkLogin(){
	var lgaccount=$.trim($("input[name='account']").val());
	lgaccount=encodeURIComponent(lgaccount);
	$.ajax({
	   type: "POST",
	   url: "/index.php?g=WapMerchant&c=Index&a=login",
	   data: "account="+lgaccount+"&pwd="+$("input[name='pwd']").val(),
	   success: function(msg){
		var	data=$.parseJSON(msg);
			if(data.error==0){
				location.href ='/index.php?g=WapMerchant&c=Index&a=index';
				}else{
					alert_open(data.msg);
				}
		   }
		});
		
}

function mer_reg(){
	$.ajax({
	   type: "POST",
	   url: "/index.php?g=WapMerchant&c=Index&a=mer_reg",
	   data: postdata,
	   success: function(msg){
		var	data=$.parseJSON(msg);
			if(data.error==0){
				alert_open('添加成功');
				 setTimeout(function () { 
				location.href ='/index.php?g=WapMerchant&c=Index&a=login';
				}, 1500);
				}else{
					alert_open(data.msg);
				}
		   }
		});
}

function staff_add(){
	$.ajax({
	   type: "POST",
	   url: "/index.php?g=WapMerchant&c=Index&a=staff_add",
	   data: "tel="+$("input[name='staff_phone']").val()+"&store_id="+$("#inputstore_id").val()+"&username="+$("input[name='staff_username']").val()+"&password="+$("input[name='staff_password']").val()+"&name="+$("input[name='staff_name']").val(),
	   success: function(msg){
		var	data=$.parseJSON(msg);
			if(data.error==0){
				alert_open('添加成功');
				 setTimeout(function () { 
				location.href ='/index.php?g=WapMerchant&c=Index&a=staff';
				}, 2000);
				}else{
					alert_open(data.msg);
				}
		   }
		});
	
}

function staff_edit(){
	$.ajax({
	   type: "POST",
	   url: "/index.php?g=WapMerchant&c=Index&a=staff_edit",
	   data: "tel="+$("input[name='staff_phone']").val()+"&store_id="+$("#inputstore_id").val()+"&id="+$("input[name='staff_id']").val()+"&password="+$("input[name='staff_password']").val()+"&name="+$("input[name='staff_name']").val(),
	  success: function(msg){
		var	data=$.parseJSON(msg);
			if(data.error==0){
				alert_open('修改成功');
				 setTimeout(function () { 
				location.href ='/index.php?g=WapMerchant&c=Index&a=staff';
				}, 2000);
				}else{
					alert_open(data.msg);
				}
		   }
		});
	
}
function checkMobile(str){
	var type = str.substring(0,1);
	if(type == 0){
		var result=str.match(/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/);
	}else{
		var result=str.match(/1[3|4|5|7|8|][0-9]{9}/);
		if( str.length != 11){
			return false;
		}
	}
	if( result == null){
		return false;
	}
	
	return true;
}

function confirm_open(title,detail,text,obj){
	var content = "<div id='confirm-layer'>"+
		"<div id='confirm-container'>"+
			"<div id='confirm-text-container'>"+
				"<p id='confirm-title'>"+title+"</p>"+
				"<p id='confirm-detail'>"+detail+"</p>"+
				"<p id='confirm-text'>"+text+"</p>"+
			"</div>"+
			"<div id='confirm-btn'>"+
				"<div id='confirm-cancel'>取消</div>"+
				"<div id='confirm-confirm'>确定</div>"+
				"<div class='clearfix'></div>"+
			"</div>"+
		"</div>"+
	"</div>";
	$(content).appendTo($('body'));
	$('#confirm-layer').fadeIn();
	confirm_cancel();
	confirm_confirm(obj);
}
function confirm_cancel(){
	$("#confirm-cancel").click(function(){
		$('#confirm-layer').fadeOut()
		setTimeout(function(){$('#confirm-layer').remove();},500);
	})
}
function confirm_confirm(obj){
	$("#confirm-confirm").click(function(){
		if($(obj).attr('data-href')){
			window.location.href = $(obj).attr('data-href');
		}else if($(obj).attr('type') == 'submit' || $(obj).find("input[type='checkbox']")[0]){
			$(obj).trigger('click');
			$('#confirm-layer').fadeOut();
		}else if(typeof obj == 'function'){
			obj();
			$('#confirm-layer').fadeOut();
		}
	})
}

function alert_open(arg1,arg2){
	if(arguments.length == 1){
		var content = "<div id='alert-layer'>"+
			"<div id='alert-container'>"+
				"<div id='alert-text-container'>"+
					"<p id='alert-title'>提示信息</p>"+
					"<p id='alert-detail'>"+arg1+"</p>"+
				"</div>"+
				"<div id='alert-btn'>"+
					"<div id='alert-confirm'>确定</div>"+
				"</div>"+
			"</div>"+
		"</div>";
	}else if(arguments.length == 2){
		var content = "<div id='alert-layer'>"+
			"<div id='alert-container'>"+
				"<div id='alert-text-container'>"+
					"<p id='alert-title'>"+arg1+"</p>"+
					"<p id='alert-detail'>"+arg2+"</p>"+
				"</div>"+
				"<div id='alert-btn'>"+
					"<div id='alert-confirm'>确定</div>"+
				"</div>"+
			"</div>"+
		"</div>";
	}
	$(content).appendTo($('body'));
	$('#alert-layer').fadeIn();
	alert_confirm();
}
function alert_confirm(){
	$("#alert-confirm").click(function(){
		$('#alert-layer').fadeOut();
		setTimeout(function(){$('#alert-layer').remove();},500);
	})
}