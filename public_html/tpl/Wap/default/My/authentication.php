<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>实名认证</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<style>
#upload_list .upload_action, #upload_list .upload_item {
	width: 25%;
	float: left;
	position: relative;
	display: -webkit-box;
	-webkit-box-pack: center;
	-webkit-box-align: center;
	border: solid 5px #fff;
	-webkit-box-sizing: border-box;
	overflow: hidden;
}
#upload_list .upload_action img {
	display: block;
	width: 100%;
	border: 0;
}
.upload_box .upload_action #fileImage {
	opacity: 0;
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
}
.clearfix:after {
	content: '.';
	font-size: 0;
	height: 0;
	visibility: hidden;
	display: block;
	clear: both;
}
.upload_box .upload_list .upload_item .upload_delete {
	position: absolute;
	top: 0;
	right: 0;
	width: 20px;
	height: 20px;
	line-height: 20px;
	background: url(./tpl/Wap/default/static/bbs/img/upimg_x.png) no-repeat;
	background-size: cover;
}
.upload_box .upload_item img {
	max-width: 100%;
	max-height: 100%;
	display: none;
}
.tips_img {
    margin-left: 5px;
    margin-right: 5px;
    vertical-align: middle;
    margin-top: -4px;
}
</style>
</head>
<body id="index">
        <if condition="$error">
        	<div id="tips" class="tips tips-err" style="display:block;">{pigcms{$error}</div>
        <else/>
        	<div id="tips" class="tips"></div>
        </if>
        <if condition="$real_name neq 0 AND $real_name neq 3">
	        <div style="text-align:left;margin-top:10px;">
				<div style="margin-left:10px;"><span>真实姓名：</span>{pigcms{$user_auth['user_truename']}</div>
				<div style="margin-top:10px;margin-left:10px;"><span>身份证号：</span>{pigcms{$user_auth['user_id_number']}</div>
				<div style="margin-top:10px;margin-left:10px;">身份证图：</div>
				<div style="margin-top:10px;text-align:center;"><img style="width:90%;" src="{pigcms{$user_auth['authentication_img']}" /></div>
			</div>
        <else />
	        <form method="post" action="{pigcms{:U('My/authentication')}" id="form">
			    <dl class="list">
		    		<dd>
		    			<dl>
			        		<dd class="dd-padding"><input class="input-weak" placeholder="请输入您的真实姓名" type="text" id="username" name="username" autocomplete="off"></dd>
					        <dd class="dd-padding"><input class="input-weak" placeholder="请输入真实身份证号码" type="text" id="idnumber" name="idnumber" autocomplete="off"></dd>
					    </dl>
		    		</dd>
					<dl>
						<dd class="item">
							<div class="upload_box">
								<ul class="upload_list clearfix" id="upload_list">
									<li class="upload_action">
										<img src="./tpl/Wap/default/static/bbs/img/xiangji.png" />
										<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage" name="" />
									</li>
								</ul>
							</div>
						</dd>
					</dl>
					<dd style="text-align:center">
			    		<img style="width:90%;" src="./tpl/Wap/default/static/images/authentication.png" />
		    		</dd>
			    </dl>
			    <div class="btn-wrapper">
					<button type="submit" class="btn btn-block btn-larger">确认提交</button>
			    </div>
			</form>
		</if>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}classify/exif.js"></script>
		<script src="{pigcms{$static_path}classify/imgUpload.js"></script>
		<script>
			$('#form').submit(function(){
				$('#tips').removeClass('tips-err').hide();
			    var user = $("#username");
			    var number = $("#idnumber");
			    if(user.val().length == 0){
			        $('#tips').html('请输入真实姓名').addClass('tips-err').show();
			        return false;
				}
			    if(number.val().length != 18 && number.val().length != 15){
			        $('#tips').html('请输入正确的15位或18位身份证号码').addClass('tips-err').show();
				    return false;
				}
			});
			$(function() {
				$('#fileImage').click(function(){
					var len = $('.upload_item').length;
					if(len>=1){
						return false;
					}
				})
				if($(".upload_list").length){
			        var imgUpload = new ImgUpload({
			            fileInput: "#fileImage",
			            container: "#upload_list",
			            countNum: 1,
						url:"{pigcms{:U('authenticationUpload',array('ml'=>authentication))}",
					})
				}
			});
		</script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>