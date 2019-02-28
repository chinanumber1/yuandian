<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
	<header class="pigcms-header mm-slideout">
		<a href="{pigcms{:U('Index/login')}" id="pigcms-header-left"><i class="iconfont icon-left"></i></a>
		<p id="pigcms-header-title">注册商家</p>
		<a  id="pigcms-header-right"></a>
	</header>
	<div class="container container-fill" style='padding:50px 0;'>
	<link rel="stylesheet" href="{pigcms{$static_path}/css/shop_staff.css">
	<script type="text/javascript" src="{pigcms{$static_path}/js/iscroll.js"></script>
	<style>
		.pigcms-container{
			background: none;
			padding: 0;
		}
		.form_tips{color:red;}
		#choose_area{margin-right: 30px;width: 35%;padding-left: 20px;}
		#choose_circle{width: 45%;padding-left: 20px;}
	</style>
	<form class="pigcms-form" method="post" action="" onsubmit="return checkSubmit();return false;">
		<div class="pigcms-container">
			<p class='pigcms-form-title'>帐 号：</p>
			<input type="text" class="pigcms-input-block" name="account" placeholder="长度为6~16位字符">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>密 码：</p>
			<input type="password" class="pigcms-input-block" name="pwd" placeholder="长度为大于6位字符">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>商家名称：</p>
			<input type="text" class="pigcms-input-block" name="mername" placeholder="请填写商家名称">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>所在区域：</p>
			<div id="choose_cityarea" style="margin-left: 18px;margin-bottom: 12px;"></div>
		</div>
		<div class="pigcms-container">
			<p class="pigcms-form-title">邮 箱：</p>
			<input type="text" class="pigcms-input-block" name="email" placeholder="必填">
		</div>
		<div class="pigcms-container">
			<p class="pigcms-form-title">手机号：</p>
			<input type="text" class="pigcms-input-block" name="phone" placeholder="必填">
		</div>
	  <div class="pigcms-container">
			<p class="pigcms-form-title">验证码：</p>
			<input class="pigcms-input-block" type="text" id="reg_verify" style="width:70px;display: inline;" maxlength="4" name="verify"/>&nbsp;&nbsp;
			<span class="verify_box">
				<img src="{pigcms{:U('Index/verify',array('type'=>'reg'))}" id="reg_verifyImg" onclick="reg_fleshVerify('{pigcms{:U('Index/verify',array('type'=>'reg'))}')" title="刷新验证码" alt="刷新验证码" style="vertical-align: middle;"/>&nbsp;
				<a href="javascript:reg_fleshVerify('{pigcms{:U('Index/verify',array('type'=>'reg'))}')">刷新验证码</a>
			</span>
		</div>

		<button type="submit" class="pigcms-btn-block pigcms-btn-block-info">注 册</button>
		<input type="hidden" name="type_id" value="mer_reg" />
	</form>
		<div style="font-size:12px;margin-left:20px;">
		<p class="form_tips">注册成功后需要管理员审核！</p>
		<if condition="$config['site_phone']"><p>客服电话 ：<a href="tel:{pigcms{$config.site_phone}">{pigcms{$config.site_phone}</a></p></if>
	</div>
	</div>
	
</body>

<script type="text/javascript">
   var static_public="{pigcms{$static_public}",choose_province="/merchant.php?g=Merchant&c=Area&a=ajax_province",choose_city="/merchant.php?g=Merchant&c=Area&a=ajax_city",choose_area="/merchant.php?g=Merchant&c=Area&a=ajax_area",choose_circle="merchant.php?g=Merchant&c=Area&a=ajax_circle";

function reg_fleshVerify(url){
	var time = new Date().getTime();
	$('#reg_verifyImg').attr('src',url+"&time="+time);
}
 </script>
 <script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>
	<include file="Public:footer"/>
</html>
