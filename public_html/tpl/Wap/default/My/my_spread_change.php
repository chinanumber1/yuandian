<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>佣金过户</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<style>
		img{width:80%;text-align:center;}
		#qrcode{height:100%;}
		.tip{margin-top:15px; display:block}
		.ticket_pic{ text-align:center; margin-top:10px}
	</style>
</head>
<body id="index">
        <if condition="$error">
        	<div id="tips" class="tips tips-err" style="display:block;">{pigcms{$error}</div>
        <else/>
        	<div id="tips" class="tips"></div>
        </if>
		<if condition="$now_user['spread_change_uid'] eq 0">
			<form id="form" method="post" >
				<input type="hidden" name="label" value="{pigcms{$_GET.label}"/>
				<dl class="list">
					<dd class="dd-padding">
						<input id="user_phone" placeholder="请输入过户的用户手机号码" class="input-weak" type="text" name="user_phone" value="" />
					</dd>
				</dl>
				<dl>
					<dd class="dd-padding" id="title" style="display:none;">
						<label class="mt"><span class="pay-wrapper">查找到的用户</span></label>
					</dd>
				<dl class="list" style="display:none;" id="userlist">
				
				</dl>
				<div class="btn-wrapper"><button type="button" class="btn btn-block btn-larger">佣金过户</button></div>
			</form>
		<else />
			<dl class="list">
				<dd class="dd-padding" style="line-height: 20px;">
					您的用户推广佣金已经过户给了{pigcms{$change_user['nickname']}{pigcms{$change_user['phone']}，如果需要解绑，请联系管理员
				</dd>
			</dl>
		</if>
		
		
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			var international_phone = {pigcms{$config.international_phone|intval=###};
			var tip='';
			$(function(){
				// $("#url").change(function(event) {
				$('button').click(function(event){
					var user_phone = $("input[name='change_user']:checked").val();
				
					if(typeof(user_phone)=='undefined'){
						$('#tips').html('请选择手机号码。').show();
						return false;
					}
					if(!international_phone && !/^[0-9]{11}$/.test(user_phone)){
						$('#tips').html('手机号码错误').show();
						return false;
					}
					 layer.open({
						content: '佣金过户后您所有的佣金将给过户的用户，并且过户关系不能解除'
						,btn: ['确定', '取消']
						,skin: 'footer'
						,yes: function(index){
							 $.ajax({
								url: '{pigcms{:U('My/my_spread_change')}',
								type: 'POST',
								dataType: 'json',
								data: $('#form').serialize(),
								
								success:function(data){
									if(!data.error_code){
										 layer.open({
											content: data.msg
										,btn: ['确定']
										,yes:function(index){
											
											  window.location.reload();
										}
									  });
									
									}else{
										layer.open({
										content: data.msg
										,btn: ['确定']
									  });
									}
								}
							});
						}
					  });
					
				});
				
			
				$('#user_phone').bind('input propertychange', function(){
					 var user_phone = $('#user_phone').val();
					if(!/^[0-9]{11}$/.test(user_phone)&&!/^[0-9]{10}$/.test(user_phone)){
						$('#userlist').hide();
						$('#title').hide();
					}else{
						$.post('{pigcms{:U('My/ajax_search_user')}', {key:'phone',value:user_phone }, function(data, textStatus, xhr) {
							if(!data.error_code){
								$('#userlist').empty();
								$('#title').show();
								$('#userlist').show();
								var str = '';
								$.each(data.msg,function(index,val){
								
									str+='<dd class="dd-padding" ><label class="mt"><span class="pay-wrapper">'+val.nickname+' '+val.phone+'<input type="radio" class="mt" value="'+val.phone+'"  name="change_user" style="float:right;"><p style="display:block;float:right;"></p></span></label></dd>';
								});
								$('#userlist').html(str);
							}else{
								layer.open({
									content: data.msg
									,btn: ['确定']
								});
							}
						});
					}
				});
			});
			
			
		</script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>