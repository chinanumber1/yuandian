<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>收货地址管理</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}/css/component.css" />
    <style>
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
	        padding-right: .2rem;
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
	color锛況gba(242, 242, 242, 1);
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
	</style>
</head>
<body id="index">
        <div id="tips" class="tips">
			<a href="javascript:void(0)" onclick="location.href='{pigcms{:U(\'myinfo\')}'"><img src="{pigcms{$static_path}/images/u70.png" /></a>
			<a href="javascript:void(0)" id="top-plus"><img src="{pigcms{$static_path}/images/u123.png" width="25px" height="25px"  /></a>
		</div>
<volist name="adress_list" id="vo">
		<dl class="list" onclick="location.href='{pigcms{$vo.edit_url}'">
		        <dd class="address-wrapper dd-padding">
				<if condition="$vo['select_url']">
		           		<a class="react" href="{pigcms{$vo.select_url}">
		                <div class="address-select"><input class="mt" type="radio" name="addr" <if condition="$vo['adress_id'] eq $_GET['current_id']">checked="checked"</if>/></div>
			         </if>
		        	<div class="address-container">
			                <div class="kv-line">
			                    <h6>收货人：</h6><p>{pigcms{$vo.name}</p>
								<if condition="$vo['default']"><span style=" color:#06c1bb">【默认】</span></if>
			                </div>
			                <div class="kv-line">
			                    <h6>手&nbsp;&nbsp;&nbsp;&nbsp;机：</h6><p>{pigcms{$vo.phone}</p>
			                </div>
			                <div class="kv-line">
			                    <h6>地&nbsp;&nbsp;&nbsp;&nbsp;址：</h6><p>{pigcms{$vo.province_txt} {pigcms{$vo.city_txt}  {pigcms{$vo.area_txt} {pigcms{$vo.adress} {pigcms{$vo.detail}</p>
			                </div>
					</div>
					<if condition="$vo['select_url']">
		            	</a>
		            </if>
			    </dd>

		    </dl>
</volist>

		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/jquery.cookie.js"></script> 
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}/layer/layer.m.js"></script>
		<script src="{pigcms{$static_path}js/classie.js"></script>
		<script>
			$(function(){
				<if condition="strpos(__SELF__,'adress_id')">
					$('#cbp-spmenu-s2').addClass('cbp-spmenu-open');
				</if>
				
				$('.mj-del').click(function(){
					var now_dom = $(this);
					if(confirm('您确定要删除此地址吗？')){
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
					window.location.href = $(this).closest('a').attr('href');
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
			
			
			$('#top-plus').click(function(){
				var url = "{pigcms{:U('edit_adress')}";
				location.href = url;
			});
			
			
			//menuRight = document.getElementById( 'cbp-spmenu-s2' );
			//showRight = document.getElementById( 'top-plus' );
			//var timer = '';
			//showRight.onclick = function() {
				//classie.toggle( this, 'active' );
				//classie.toggle( menuRight, 'cbp-spmenu-open' );
				
				//var twice_verify_wallet = true;
				//var twice_verify = true;
				// timer = setTimeout(function(){
					// bio_verify({location:"/wap.php?g=Wap&c=My&a=my_money",twice:twice_verify,hide:'',visible:'',submit:'',cookie:1});
				// },1000)
			//};
			
			// hideRight = document.getElementById('btn-delete');
			// hideRight.onclick=function(){
				// classie.toggle( this, 'active' );
				// classie.toggle( menuRight, 'cbp-spmenu-open' );
				// clearInterval(timer);
			// }

			
			$('#btn-delete').click(function(){
				var address_id = "{pigcms{$_GET['adress_id']}";
				if(!address_id){
					return false;
				}
				
				if(confirm('确认删除?')){
					var url = "{pigcms{:U('My/del_adress')}";
					$.get(url,{'adress_id':address_id},function(data){
						location.href="{pigcms{:U('adress')}"
					})
				}
				
			});
		</script>
<include file="Public:footer"/>
{pigcms{$hideScript}
<div id="pwd_bg" style="height: 921px; display: none;">

		</div>
<div id="pwd_verify" class="pwd_verify" style="display:none" >
			<div class="verify_pwd">
				<p class="tips"></p>
				<p style="text-align:center">是否同步微信地址？</p>
				<div class="verify_button" id="verify">
				<p class="cancle">取消</p>
				<p class="sync_address">同步</p>
			</div>
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}/js/bioAuth.js"></script>
</body>
</html>