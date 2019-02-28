<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
	<header class="pigcms-header mm-slideout">
		<a href="javascript:history.go(-1);" id="pigcms-header-left"><i class="iconfont icon-left"></i></a>
		<p id="pigcms-header-title">添加店员</p>
		<a  id="pigcms-header-right"></a>
	</header>
	<div class="container container-fill" style='padding-top:50px'>
	<link rel="stylesheet" href="{pigcms{$static_path}/css/shop_staff.css">
	<script type="text/javascript" src="{pigcms{$static_path}/js/iscroll.js"></script>
	<style>
		.pigcms-container{
			background: none;
			padding: 0;
		}
	</style>
	<form class="pigcms-form" method="post" action="" onsubmit="return checkSubmit();return false">
		<div class="pigcms-container">
			<p class='pigcms-form-title'>员工手机号</p>
			<input type="text" class="pigcms-input-block" name="staff_phone" placeholder="请填写员工手机号">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>员工姓名</p>
			<input type="text" class="pigcms-input-block" name="staff_name" placeholder="请填写员工姓名">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>员工帐号</p>
			<input type="text" class="pigcms-input-block" name="staff_username" placeholder="请填写员工帐号">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>初始密码</p>
			<input type="password" class="pigcms-input-block" name="staff_password" placeholder="请填写员工初始密码">
		</div>
		<div class="pigcms-container">
			<p class="pigcms-form-title">选择店铺</p>
			<select name="store_id" class="pigcms-input-block">
			<volist name='store' id='store'>
				<option value="{pigcms{$store.store_id}">{pigcms{$store.name}</option>
			</volist>
			</select>
		
		
		</div>
		<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit" value="确定">确定</button>
		<input type="hidden" name="type_id" value="staff_add" />
	</form>
	
	</div>
	
</body>
	<include file="Public:footer"/>
</html>
