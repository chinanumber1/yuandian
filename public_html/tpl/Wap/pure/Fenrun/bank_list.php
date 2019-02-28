<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>银行卡管理</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/component.css" />
    <style>
		body{background-color: #f4f4f4;padding-bottom:56px;}
	    .address-container {
	        font-size: .3rem;
	        -webkit-box-flex: 1;
	    }
	    .kv-line h6 {
	        width: 4em;
			color: #868686;
	    }
	    .btn-wrapper {
	        margin: .2rem .2rem;
	        padding: 0;
	    }
	
	    .address-wrapper a {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .address-select {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	        padding-right: 4px;
	        -webkit-box-align: center;
	        -webkit-box-pack: center;
	        -moz-box-align: center;
	        -moz-box-pack: center;
	        -ms-box-align: center;
	        -ms-flex-pack: justify;
	    }
	
	    .list.active dd {
	        background-color: #fff5e3;
	    }
	
	    .confirmlist {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .confirmlist li {
	        -ms-flex: 1;
	        -moz-box-flex: 1;
	        -webkit-box-flex: 1;
	        height: .88rem;
	        line-height: .88rem;
	        border-right: 1px solid #C9C3B7;
	        text-align: center;
	    }
	
	    .confirmlist li a {
	        color: #2bb2a3;
	    }
	
	    .confirmlist li:last-child {
	        border-right: none;
	    }
		
		
		#tips{ display:block; height:20px; background:none; border-bottom:none;}
		#tips a{ display:block;}
		#tips a:first-child{ float:left;}
		#tips a:last-child{ float:right;}
		
		


input:disabled{
	background-color:#fff;
	color:gba(242, 242, 242, 1);
}

#pwd_bg {
	background-color: #000;
	position: fixed;
	z-index: 1000;
	left: 0;
	top: 0;
	display: none;
	width: 100%;
	height: 100%;
	opacity: 0.3;
	filter: alpha(opacity=30);
	-moz-opacity: 0.3;
}
.pwd_background{
	z-index: 998;
	position: absolute;
	overflow: hidden;
	text-align: center;
	background-color: #ECECEC;
}
.pwd_verify{
	z-index: 1001;
	width: 70%;
	position: fixed;
	left: 15%;
	top: 25%;
	border-radius: 6px;
	overflow: hidden;
	text-align: center;
	background-color: #fff;
}
.pwd_verify .pwd_menu{
	width:100%;
	height: 51px;
	margin: 0 auto;
	position: absolute;
	top:0px;
}
.pwd_verify .pwd_menu img{
	width: 20px;
    height: 20px;
    position: absolute;
    left: 17px;
    top: 16px;
}
.pwd_verify .pwd_menu div{
	width:100%;
	float:left;
	line-height: 34px;
	height: 100%;
}

.pwd_verify #pwd{
	width: 113px;
	height:36px;
	margin-top:30px;
	padding:3px;
	border: none;
    border-bottom: 1px solid rgb(73, 180, 79);
	font-size: 12px;
	color: #333333;
	outline:none
}
.pwd_verify .pwd_title{
	color: #fff;
	height: 32px;
	background-color: #BD3192;
}
.pwd_verify .pwd_button{
	float:left;
	text-align: center;
	line-height: 38px;
	
}

.pwd_verify .verify_pwd ,.verify_sms {
	margin: 30px auto;
	height: 100px;
	width: 90%;
}
.verify_pwd  .tips{
	width: 100%;
    text-align: center;
    font-size: 14px;
    color: red;
    position: absolute;
}

.verify_pwd .forget_pwd{
	text-align: center;
	margin-top: 27px;
	font-size: 14px;
    color: #999999;
}

.pwd_verify input{
	text-align:center;
	color:rgb(153, 153, 153);
}
.verify_sms{
	position:relative;
	margin-left:268px;
}
.verify_pwd{
	position:relative;
}
.verify_sms input{    
	width: 88px;
	height: 26px;
	margin-top: 24px;
	padding: 3px;
}
.verify_sms p{
	text-align: center;
	margin-top: 16px;
	color:red;
}
.verify_pwd a{
	/* float: left;
    padding: 20px;
	font-size:12px;
	color:red; */
}
.pwd_verify .verify_button{
	width:100%;
	margin: 0 auto;
	position: absolute;
	bottom:0px;
	
}
.pwd_menu p{
	margin: 0 auto;
	text-align: center;
	top: 15px;
	position: absolute;
	width: 100%;
	z-index:-1;
}
.pwd_verify .verify_button p{
	font-size: 18px;
	color: #949494;
	margin: 0 auto;
	text-align: center;
	width: 100%;
	display:block;
	width:45%;
	float:left;
}
.verify_sms button{
	width: 94px;
	height: 36px;
	background: inherit;
	background-color: rgba(25, 158, 216, 1);
	color:#fff;
	border: none;
	border-radius: 2px;
	font-size: 10px;
}

.pwd_verify  .cancle{
	float:left;
	width: 51px;
	height: 51px;
	
}
.pwd_verify  .sure{
	float: right;
	width: 94px;
	height: 40px;
	background: inherit;
	background-color: rgba(25, 158, 216, 1);
	border: none;
	border-radius: 2px;
	-moz-box-shadow: none;
	-webkit-box-shadow: none;
	box-shadow: none;
	color: #FFFFFF;
}

.pwd_verify .verify_button p{ float:left;position:static;line-height:51px; height:51px; margin-top:5px;background-color: rgba(242, 242, 242, 1);}
.pwd_verify .verify_button p:last-child{ float:right; background:#06c1bb; color:#fff}
.btn{ background:#06c1bb}
.btn:active{background-color:#fff}
.btn-delete{ margin-top:5px; background:#fff; color:#000; border:1px solid #e5e5e5}
dl.list-in dd{ border-bottom:1px dashed #e5e5e5; }
dl.list:first-child{ height:100%}
.cbp-spmenu-right.cbp-spmenu-open{overflow-y: auto; clear:both;position：relative}
dl.list{ border:none}
.ko {
    z-index: 100;
    position: fixed;
    bottom: 0;
    width: 100%;
    text-align: center;
    font-size: 16px;
    color: #fff;
    background: #06c1ae;
    line-height: 16px;
    padding: 20px 0;
}
input.mt[type=radio], input.mt[type=checkbox] {
	display:none;
}
input.mt[type=radio]:checked, input.mt[type=checkbox]:checked {
    border: 0;
    color: #fff;
    background: url({pigcms{$static_path}images/icon_jiesuan_mark.png) no-repeat center;
    background-size: 100%;
    height: 22px;
    width: 22px;
	line-height:22px;
    position: absolute;
    top: 0;
    display: inline-block;
    left: 0;
    margin: 0;
    border-radius: 0;
}
input.mt[type=radio]:checked:after, input.mt[type=checkbox]:checked:after{
	content:''
}
dl.list:first-child{
	margin-top:10px;
}
.address-wrapper .edit{
    background: url({pigcms{$static_path}images/icon_address_edit.png) no-repeat center;
    background-size: 100%;
    height: 18px;
    width: 18px;
    display: inline-block;
    position: absolute;
    right: 15px;
	top: 50%;
    margin-top: -9px;
	z-index:98;
}
.address-wrapper .edit_bg{
	position:absolute;
	right:0;
	top:0;
	width:45px;
	height:100%;
	z-index:99;
}
.listceng p{
	color: #bcbcc1;
}
.listceng h3{
	color: #323232;
    font-size: 16px;
    font-weight: 600;
    padding-left: 10px;
}
.address-wrapper .edit.active{
    background: url({pigcms{$static_path}images/icon_address_edit11.png) no-repeat center;
    background-size: 100%;
    height: 18px;
    width: 18px;
    display: inline-block;
    position: absolute;
    right: 15px;
	top: 50%;
    margin-top: -9px;
	z-index:98;
}
	</style>
</head>
<body id="index">
		<volist name="bank_list" id="vo">
			<dl class="list">
		        <dd class="address-wrapper dd-padding" >
		        	<div class="address-container" <if condition="!$vo['select_url']">style="padding-right:36px;"</if>>
						<div class="kv-line">
							<p>开户名：{pigcms{$vo.account_name}&nbsp; 银行卡号：{pigcms{$vo.account}</p>
							<if condition="$vo['is_default']"><span style=" color:#06c1bb">【默认】</span></if>
						</div>
						<div class="kv-line">
							<p>所在银行：{pigcms{$vo.remark} </p>
						</div>
					</div>
				
				
					<i class="edit" onclick="location.href='{pigcms{:U('create_bank_account',array('bank_id'=>$vo['id']))}'"></i>
					<a href="{pigcms{:U('delete_bank_account',array('bank_id'=>$vo['id']))}" style="float:right;z-index:100">删除</a>
			    </dd>

		    </dl>
		</volist>
       
		<p class="ko" id="top-plus">新建银行卡</p>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/jquery.cookie.js"></script> 
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script src="{pigcms{$static_path}js/classie.js"></script>
		<script>
			$(function(){
				$('.react').click(function(){
					$('.mt').prop('checked',false);
					$(this).find('.mt').prop('checked',true);
				});
				$('.mj-del').click(function(){
					var now_dom = $(this);
					if(confirm('您确定要删除这个银行卡吗？')){
						$.post(now_dom.attr('href'),function(result){
							if(result.status == '1'){
								now_dom.closest('dl').remove();
							}else{
								alert(result.info);
							}
						});
					}
					return false;
				});
				$('.address-wrapper input.mt').click(function(){
					location.href = $(this).closest('a').attr('href');
				});
				$.cookie("user_address", '');
				
				$("select[name='province']").change(function(){
					show_city($(this).find('option:selected').attr('value'));
				});
				$("select[name='city']").change(function(){
					show_area($(this).find('option:selected').attr('value'));
				});
				$("#color-gray").click(function(){
					var detail = new Object();
					detail.name = $('input[name="name"]').val();
					detail.province = $('select[name="province"]').val();
					detail.area = $('select[name="area"]').val();
					detail.city = $('select[name="city"]').val();
					detail.defaul = $('input[name="default"]').val();
					detail.detail = $('input[name="detail"]').val();
					detail.zipcode = $('input[name="zipcode"]').val();
					detail.phone = $('input[name="phone"]').val();
					detail.id = $('input[name="adress_id"]').val();
					
					$.cookie("user_address", JSON.stringify(detail));
					location.href = "{pigcms{:U('My/adres_map', $params)}";
				});

				
				$('#form').submit(function(){
					$('#tips').removeClass('tips-err').empty();
					var form_input = $(this).find("input[type='text'],input[type='tel'],textarea");
					$.each(form_input,function(i,item){
						if($(item).attr('pattern')){
							var re = new RegExp($(item).attr('pattern'));
							if($(item).val().length == 0 || !re.test($(item).val())){
								alert($(item).attr('data-err'))
								//$('#tips').addClass('tips-err').html($(item).attr('data-err'));
								return false;
							}
						}

						if(i+1 == form_input.size()){
							layer.open({type:2,content:'提交中，请稍候'});
							$.post($('#form').attr('action'),$('#form').serialize(),function(result){
								layer.closeAll();
								if(result.status == 1){
									<if condition="$_GET['referer']">
										window.location.href="{pigcms{$_GET.referer|htmlspecialchars_decode=###}";
									<else/>
										window.location.href="{pigcms{:U('My/adress',$params)}";
									</if>
								}else{
									$('#tips').addClass('tips-err').html(result.info);
								}
							});
						}
					});
			
					return false;
				});
			});
			function show_city(id){
				$.post("{pigcms{:U('My/select_area')}",{pid:id},function(result){
					result = $.parseJSON(result);
					if(result.error == 0){
						var area_dom = '';
						$.each(result.list,function(i,item){
							area_dom+= '<option value="'+item.area_id+'">'+item.area_name+'</option>'; 
						});
						$("select[name='city']").html(area_dom);
						show_area(result.list[0].area_id);
					}
				});
			}
			function show_area(id){
				$.post("{pigcms{:U('My/select_area')}",{pid:id},function(result){
					result = $.parseJSON(result);
					if(result.error == 0){
						var area_dom = '';
						$.each(result.list,function(i,item){
							area_dom+= '<option value="'+item.area_id+'">'+item.area_name+'</option>'; 
						});
						$("select[name='area']").html(area_dom);
					}else{
						$("select[name='area']").html('<option value="0">请手动填写区域</option>');
					}
				});
			}
			
			$('#btn-delete').click(function(){
			    var backUrl = "{pigcms{:U('adress')}";
				var store_id = "{pigcms{$_GET['store_id']}";
				var mer_id = "{pigcms{$_GET['mer_id']}";
				var current_id = "{pigcms{$_GET['current_id']}";
				var buy_type = "{pigcms{$_GET['buy_type']}"
				var type = "{pigcms{$_GET['type']}"
				var cid = "{pigcms{$_GET['cid']}"
			    backUrl += '&store_id=' + store_id + '&mer_id=' + mer_id + '&current_id=' + current_id + '&buy_type=' + buy_type + "&cid=" + cid + '&type=' + type;
				var address_id = "{pigcms{$_GET['adress_id']}";
				if(!address_id){
					return false;
				}
				if(confirm('确认删除?')){
					$.get("{pigcms{:U('My/del_adress')}", {'adress_id':address_id}, function(data){
					    location.href = backUrl;
					});
				}
			});
			$('#top-plus').click(function(){
				
				location.href = "{pigcms{:U('create_bank_account')}";
			});
		</script>
{pigcms{$hideScript}

</body>
</html>