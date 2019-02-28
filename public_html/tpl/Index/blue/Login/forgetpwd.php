<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>忘记密码 | {pigcms{$config.site_name}</title>
    <!--[if IE 6]>
		<script src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a-min.v86c6ab94.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
		<script src="{pigcms{$static_path}js/html5shiv.min-min.v01cbd8f0.js"></script>
    <![endif]-->
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/common.v113ea197.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/base.v492b572b.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login.v7e870f72.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login-section.vfa22738e.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/qrcode.v74a11a81.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/footer.css" />
	<script src="{pigcms{$static_public}js/jquery.min.js"></script>
	<style type="text/css">.noact{background-image:none !important;color: #969696 !important;background-color: #CACACA !important;}</style>
</head>
<body id="login" class="theme--www" style="position: static;">
	<header id="site-mast" class="site-mast site-mast--mini">
	    <div class="site-mast__branding cf">
			<a href="{pigcms{$config.site_url}"><img src="{pigcms{$config.site_logo}" alt="{pigcms{$config.site_name}" title="{pigcms{$config.site_name}" style="width:190px;height:60px;"/></a>
	    </div>
	</header>
	<div class="site-body pg-login cf">
	    <div class="promotion-banner">
	        <img src="{pigcms{$config.site_url}/tpl/Static/default/css/img/web_login/{pigcms{:mt_rand(1,4)}.jpg" width="480" height="370">
	    </div>
	    <div class="component-login-section component-login-section--page mt-component--booted" >
		    <div class="origin-part theme--www">
			    <div class="validate-info" style="visibility:hidden"></div>
		        <h2>您的手机账号</h2>
		        <form id="J-login-form" method="post" class="form form--stack J-wwwtracker-form">
			        <div class="form-field form-field--icon">
			            <i class="icon icon-user"></i>
			            <input type="text" id="login-phone" class="f-text" name="phone" placeholder="手机号" value="{pigcms{$accphone}"/>
			        </div>
					<if condition="$config.sms_verify_fleshcode eq 1">
						<input id="sms_flesh_type" name = "type" type="hidden" value="sms" />
						<input id="sms_flesh_verify" name = "verify" type="hidden" value="" />
					</if>
			        <div class="form-field form-field--icon"  id="vfycodediv">
			            <i class="icon icon-password"></i>
			            <input type="text" id="vfycode" class="f-text" name="vfycode" placeholder="输入短信验证码" value=""/>
			        </div>
			        <div class="form-field form-field--ops">
			            <input type="submit" class="btn" id="commit" value="发送短信验证" style="width:55%"/>
						&nbsp;&nbsp;&nbsp;<span class="btn noact" style="width:15%;"><span id="reciprocal">60</span>秒</span>
						<a class="btn" id="submitcommit" style="margin-top:15px;width:85%" href="javascript:;" />提 交</a>
			        </div>
			    </form>
		    </div>
		</div>
	</div>
	<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
	     var flage=false;var islock=false;
		$(document).ready(function(){
			if($('body').height() < $(window).height()){
				$('.site-info-w').css({'position':'absolute','width':'100%','bottom':'0'});
			}
			$('#vfycode').focus(function(){
				if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
					verify_flesh();
				}
			});
			$("#J-login-form").submit(function(){
				if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
					verify_flesh();
					return false;
				}
				$('.validate-info').css('visibility','hidden');
				$('#commit').val('正在发短信...').prop('disabled',true);
				var phone = $.trim($("#login-phone").val());
				var vfycode = $.trim($("#vfycode").val());
				if (phone == '' || phone == null) {
					$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>手机号不能为空').css('visibility','visible');
					$("#commit").val('发送短信验证').prop('disabled',false);
					return false;
				}
				<if condition="C('config.sms_verify_fleshcode') eq 1">
					var fleshcode = $("#sms_flesh_verify").val();
					var fleshtype = $("#sms_flesh_type").val();
					if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
						verify_flesh();
					}
				</if>

				$.post("{pigcms{:U('Index/Login/Generate')}", {'phone':phone,vfycode:'',tmpid:0<if condition="$config.sms_verify_fleshcode eq 1 ">,verify:fleshcode,type:fleshtype</if>}, function(data){
					data.error_code=parseInt(data.error_code);
					if (!data.error_code) {
						$("#commit").val('重发送短信验证');
						$("#vfycodediv").css('visibility','visible');
						$('.validate-info').html('<i class="tip-status tip-status--success"></i>请输入短信验证码').css('visibility','visible');
						flage=data.id;
						Reciprocal();
						return false;
					} else {
						if(data.error_code == 1){
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
							  $("#commit").val('重发送短信验证').prop('disabled',false);
						}else if(data.error_code == 2){
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>验证成功,跳转密码修改页面').css('visibility','visible');
						  setTimeout(function(){
						    window.location.href="{pigcms{:U('Index/Login/pwdModify')}&pm="+data.urlpm;
						  }, 800);
						}else if(data.error_code == 3){
						   $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg+'<a href="{pigcms{:U(\'Index/Login/reg\')}">去注册</a>').css('visibility','visible');
							  $("#commit").val('重发送短信验证').prop('disabled',false);
						}

					}
				}, 'json');
				return false;
			});

			$('#submitcommit').click(function(){
				<if condition="C('config.sms_verify_fleshcode') eq 1">
					var fleshcode = $("#sms_flesh_verify").val();
					var fleshtype = $("#sms_flesh_type").val();
					if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
						verify_flesh();
					}
				</if>
				if(islock || !flage) return false;
				islock=true;
			    $('.validate-info').css('visibility','hidden');
				  $('#submitcommit').val('正在提交数据...').prop('disabled',true);
				var phone = $.trim($("#login-phone").val());
				var vfycode = $.trim($("#vfycode").val());
				if (phone == '' || phone == null) {
					$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>手机号不能为空').css('visibility','visible');
					  $("#submitcommit").val('提 交');
					  islock=false;
					  return false;
				}
				if (vfycode == '' || vfycode == null) {
					$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>验证码不能为空').css('visibility','visible');
					 $("#submitcommit").val('提 交');
					 islock=false;
					 return false;
				}

				$.post("{pigcms{:U('Index/Login/Generate')}", {'phone':phone,vfycode:vfycode,tmpid:flage<if condition="$config.sms_verify_fleshcode eq 1 ">,verify:fleshcode,type:fleshtype</if>}, function(data){
					data.error_code=parseInt(data.error_code);
					if (data.error_code == 2) {
						$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>验证成功,跳转密码修改页面').css('visibility','visible');
						  setTimeout(function(){
						    window.location.href="{pigcms{:U('Index/Login/pwdModify')}&pm="+data.urlpm;
						 }, 800);
						 islock=false;
						return false;
					} else {
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
						  islock=false;
						  $("#submitcommit").val('提 交');
					}
				}, 'json');
				return false;
			});
		});
		
		
	function verify_flesh(){
		art.dialog.open("{pigcms{:U('Index/Verify/verify_fleshcode')}&"+Math.random(),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('login_iframe_handle',iframe);
			},
			id: 'login_handle',
			title:'发送短信前请验证短信码',
			padding: 0,
			width: 430,
			height: 200,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4',
			close: function () {   
				var verify = art.dialog.data('sms_flesh_verify'); // 读取子窗口返回的数据  
				if(verify){
					$('#sms_flesh_verify').val(verify);
					$('#send_sms').attr('onclick','sendsms(this);')
				}
			}  
		});
	}
    function Reciprocal(){
	   $("#reciprocal").parent('.btn').removeClass('noact');
	     var inttmp=window.setInterval(function(){
		  num = $("#reciprocal").text();
		  num = parseInt(num);
	     $("#reciprocal").text(num-1);
		 if(num==1){
		    $("#reciprocal").parent('.btn').addClass('noact');
			//flage=0;
			$("#commit").val('重发送短信验证').prop('disabled',false);
			window.clearInterval(inttmp);
			setTimeout(function(){
				$("#reciprocal").text(60);
			}, 1000);
		 }
	   },1000);
    }
	function sendsms(val){
	
			if(typeof($('#sms_flesh_verify').val())!='undefined' && $('#sms_flesh_verify').val()==''){
				error_tips('请先验证4位验证码','login-repassword');
				return false;
			}
			if($("input[name='phone']").val()==''){
				alert('手机号码不能为空！');
			}else{
				
				if(countdown==60){
					$.ajax({
						url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send',
						type: 'POST',
						dataType: 'json',
						data: {phone: $("input[name='phone']").val(),reg:1},
						success:function(date){
							if(date.error_code){
								$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+date.msg).css('visibility','visible');
							}
						}

					});
				}
				if (countdown == 0) {
					val.removeAttribute("disabled");
					val.value="验证短信";
					countdown = 60;
					//clearTimeout(t);
				} else {
					val.setAttribute("disabled", true);
					val.value="重新发送(" + countdown + ")";
					countdown--;
					setTimeout(function() {
						sendsms(val);
					},1000)
				}
			}
		}
	</script>
	<include file="Public:footer"/>
</body>
</html>