<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
	<header class="pigcms-header mm-slideout">
		<a href="javascript:history.go(-1);" id="pigcms-header-left"><i class="iconfont icon-left"></i></a>
		<p id="pigcms-header-title">编辑店员</p>
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
			<p class="pigcms-form-title">员工帐号(<span style='color:red'>不允许修改</span>)</p>
			<input type="text" class="pigcms-input-block" style='background:white'  readonly="readonly" value="{pigcms{$staff['username']}">
		</div>
		<div class="pigcms-container">
			<p class="pigcms-form-title">员工手机号</p>
			<input type="text" class="pigcms-input-block" name='staff_phone' value="{pigcms{$staff.tel}">
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>员工姓名</p>
			<input type="text" class="pigcms-input-block" name="staff_name" value='{pigcms{$staff.name}'>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>初始密码</p>
			<input type="password" class="pigcms-input-block" name="staff_password" placeholder="请填写员工初始密码">
		</div>
		<div class="pigcms-container">
			<p class="pigcms-form-title">选择店铺(<span style='color:red'>不允许修改</span>)</p>
			<select name="store_id" id="inputstore_id" class="pigcms-input-block">
			<volist name='store' id='store'>
				<option value="{pigcms{$store.store_id}" <if condition="$store['store_id'] eq $staff['store_id']">selected</if>>{pigcms{$store.name}</option>
			</volist>
			</select>
		</div>
		<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit" value="确定">确定</button>
		<input type="hidden" name="type_id" value="staff_edit" />
		<input type="hidden" name="staff_id" value="{pigcms{$staff.id}" />
	</form>
	
	</div>
	
</body>
	<include file="Public:footer"/>
</html>
