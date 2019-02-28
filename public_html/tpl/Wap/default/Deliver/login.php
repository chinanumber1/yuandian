<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>配送员登录</title>
	<meta name="description" content="{pigcms{$config.seo_description}"/>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name='apple-touch-fullscreen' content='yes'/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="format-detection" content="address=no"/>

    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
	<style>
		#login{margin: 0.5rem 0.2rem;}
		.btn-wrapper{margin:.28rem 0;}
		dl.list{border-bottom:0;border:1px solid #ddd8ce;}
		dl.list:first-child{border-top:1px solid #ddd8ce;}
		dl.list dd dl{padding-right:0.2rem;}
		dl.list dd dl>.dd-padding, dl.list dd dl dd>.react, dl.list dd dl>dt{padding-right:0;}
	    .nav{text-align: center;}
	    .subline{margin:.28rem .2rem;}
	    .subline li{display:inline-block;}
	    .captcha img{margin-left:.2rem;}
	    .captcha .btn{margin-top:-.15rem;margin-bottom:-.15rem;margin-left:.2rem;}
	</style>
</head>
<body id="index" data-com="pagecommon">
        <div id="container">
        	<div id="tips" style="-webkit-transform-origin:0px 0px;opacity:1;-webkit-transform:scale(1, 1);"></div>
			<div id="login">
			    <form id="login_form" autocomplete="off" method="post" action="{pigcms{:U('Deliver/login')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input class="input-weak" type="text" name="phone" id="login_phone" placeholder="请输入您的配送员账号"/>
			            		</dd>
			            		<dd class="dd-padding">
									<input class="input-weak" type="password" name="pwd" id="login_pwd" placeholder="请输入您的配送员密码"/>
			            		</dd>
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<button type="submit" class="btn btn-larger btn-block">登录</button>
			        </div>
			    </form>
			</div>
		</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
	    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script type="text/javascript">
			var international_phone = {pigcms{$config.international_phone|intval=###};
			var static_public="{pigcms{$static_public}",
				static_path="{pigcms{$merchantstatic_path}",
				login_check="{pigcms{:U('Deliver/login')}",
				store_index="{pigcms{:U('Deliver/grab')}";

			<if condition="!empty($refererUrl)">
				store_index="{pigcms{$refererUrl}";
			</if>

			var openid=false;
			<if condition="isset($openid) AND !empty($openid)">
			   openid="{pigcms{$openid}";
			</if>
		
			$(function(){
			$('#login_account').focus();
			$('#login_form').submit(function(){
				if ($('#login_phone').val()=='') {
					layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'请输入帐号~',btn: ['确定'],end:function(){}});
					$('#login_phone').focus();
					return false;
				} else if (!international_phone && ! (/^1[3|4|5|7|8|9][0-9]\d{4,8}$/.test($('#login_phone').val()))) {
					layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'手机号格式不正确~',btn: ['确定'],end:function(){}});
					$('#login_phone').focus();
					return false;
				} else if ($('#login_pwd').val()=='') {
					layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'请输入密码~',btn: ['确定'],end:function(){}});
					$('#login_pwd').focus();
				} else {
					$.post(login_check,$("#login_form").serialize(),function(result){
						//result = $.parseJSON(result);
						if(result){
							if(result.error == 0 && openid){
							  layer.open({
								title:['提示：','background-color:#FF658E;color:#fff;'],
								content:'系统检测到您是在微信中访问的，是否需要绑定微信号，下次访问可以免登录！',
								btn: ['是', '否'],
								shadeClose: false,
								yes: function(){
									$.post("/wap.php?g=Wap&c=Deliver&a=freeLogin",{},function(ret){
										if(!ret.error){
											layer.open({title:['成功提示：','background-color:#FF658E;color:#fff;'],content:'恭喜您绑定成功！',btn: ['确定'],end:function(){window.parent.location = store_index;}});
										}else{
											layer.open({
												title:['错误提示：','background-color:#FF658E;color:#fff;'],
												content:'绑定失败，请下次登录再试',
												btn: ['确定'],
												end:function(){
													window.parent.location = store_index;
												}
											});
										}
									},'JSON');
		
								}, no: function(){
									setTimeout(function(){
										window.parent.location = store_index;
									},1000);
								}
							});
		
							}else if(result.error == 0 && !openid){
								setTimeout(function(){
									window.parent.location = store_index;
								},1000);
							}else{
								$('#login_'+result.dom_id).focus();
								alert(result.msg);
							}
						}else{
							layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'登录出现异常，请重试~',btn: ['确定'],end:function(){}});
						}
					},'JSON');
				}
				return false;
			});
		});

	</script>
	</body>
</html>