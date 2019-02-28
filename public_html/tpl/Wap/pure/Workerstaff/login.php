<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>技师登录</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/worker_deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<body style="background:url({pigcms{$static_path}images/login_02.jpg) left bottom no-repeat #ebf3f8; background-size: 100% 137px;">
	<section class="Land">
	<div class="Land_top">
		<span class="fillet" style="background: url(<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>) center no-repeat;background-size: contain;"></span>
		<h2>技师中心</h2>
	</div>
	<div class="Land_end">
		<ul>
			<li class="number">
			  	<input type="text" placeholder="请输入账号" id="username">
				<a href="javascript:void(0)"></a>
			</li>
			<li class="Password">
				<input type="password" placeholder="请输入密码" id="login_pwd">
				<a href="javascript:void(0)"></a>
			</li>
			<li class="Landd">
				<input type="button" value="登录" id="login_form">
			</li>
		</ul>
	</div>     
	</section>
</body>
<script type="text/javascript">
var international_phone = {pigcms{$config.international_phone|intval=###};
var store_index = "{pigcms{:U('index')}";
<if condition="!empty($refererUrl)">
	store_index = "{pigcms{$refererUrl}";
</if>
var openid = false;
<if condition="isset($openid) AND !empty($openid)">
	openid = "{pigcms{$openid}";
</if>
$(function(){
	$('#login_account').focus();
	var is_click_login = false;
	$('#login_form').click(function(){
		if (is_click_login) return false;
		is_click_login = true;
		if ($('#username').val()=='') {
			layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'请输入帐号~',btn: ['确定'],end:function(){}});
			$('#username').focus();
			is_click_login = false;
			return false;
		} else if (!international_phone && !(/^1[3|4|5|7|8|9][0-9]\d{4,8}$/.test($('#username').val()))) {
			layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'手机号格式不正确~',btn: ['确定'],end:function(){}});
			$('#username').focus();
			is_click_login = false;
			return false;
		} else if ($('#login_pwd').val()=='') {
			layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'请输入密码~',btn: ['确定'],end:function(){}});
			$('#login_pwd').focus();
			is_click_login = false;
			return false;
		} else {
			$.post("{pigcms{:U('login')}", {'username':$('#username').val(), 'pwd':$('#login_pwd').val()}, function(result) {
				is_click_login = false;
				if (result) {
					if (result.error == 0 && result.is_bind == 0 && openid) {
						  layer.open({
							title:['提示：','background-color:#FF658E;color:#fff;'],
							content:'系统检测到您是在微信中访问的，是否需要绑定微信号，下次访问可以免登录！',
							btn: ['是', '否'],
							shadeClose: false,
							yes: function(){
								$.post("/wap.php?g=Wap&c=Workerstaff&a=freeLogin",function(ret){
									if(!ret.error){
										layer.open({title:['成功提示：','background-color:#FF658E;color:#fff;'],content:'恭喜您绑定成功！',btn: ['确定'],end:function(){window.parent.location = store_index;}});
									}else{
										layer.open({
											title:['错误提示：','background-color:#FF658E;color:#fff;'],
											content:ret.msg,
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
					} else if(result.error == 0){
						setTimeout(function(){
							window.parent.location = store_index;
						},1000);
					} else {
						$('#login_'+result.dom_id).focus();
						layer.open({content: result.msg, skin: 'msg', time: 2});
					}
				} else {
					layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'登录出现异常，请重试~',btn: ['确定'],end:function(){}});
				}
			},'JSON');
		}
		return false;
	});
});
$("body").css({"height":$(window).height()});
$(".Land_end input").focus(function(){
	$(this).siblings("a").show();
});
$(".Land_end a").click(function(){
	$(this).hide();
	$(this).siblings("input").val("");
});     
</script>   
</html>