<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
	<header class="pigcms-header mm-slideout">
		<a href="javascript:history.go(-1);" id="pigcms-header-left"><i class="iconfont icon-left"></i></a>
	</header>
	<div class="container container-fill" style='padding-top:50px'>
		<!--左侧菜单-->
		<include file="Public:leftMenu"/>
		<!--左侧菜单结束-->
	<form class="pigcms-form" method="post" action="" onsubmit="return checkSubmit2();return false">
		<div class="pigcms-container">
			<p class='pigcms-form-title'>打印机状态</p>
			<p class='pigcms-form-title' id="printer-status" style="color: #838383;">
				<i class="iconfont icon-printer" style="font-size: 50px!important;margin-right: 20px;"></i>
				<!--<span class="pigcms-span pigcms-span-info">未绑定</span>-->
			</p>
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>终端号<span class="pigcms-help-text">(终端号, 密钥在打印机底部)</span></p>
			<input type="text" class="pigcms-input-block" placeholder="请输入终端号" name="mcode" value="{pigcms{$Orderprinter['mcode']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>绑定账号<span class="pigcms-help-text">(绑定手机号和绑定账号只能填写一个)</span></p>
			<input type="text" class="pigcms-input-block" placeholder="请输入绑定账号" name="username" value="{pigcms{$Orderprinter['username']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>密钥</p>
			<input type="text" class="pigcms-input-block" placeholder="请输入密钥" name="mkey" value="{pigcms{$Orderprinter['mkey']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>终端手机号</p>
			<input type="text" class="pigcms-input-block" placeholder="请输入终端手机号" name="mp" value="{pigcms{$Orderprinter['mp']}" >
		</div>
		<div class="pigcms-container">
			<p class='pigcms-form-title'>打印份数</p>
			<input type="text" class="pigcms-input-block" placeholder="请输入打印份数" name="count" value="{pigcms{$Orderprinter['count']}" >
		</div>
		<div class="pigcms-container">
			<p class="pigcms-form-title">选择店铺</p>
			<select name="store_id" class="pigcms-input-block">
			<volist name='store' id='store'>
				<option value="{pigcms{$store.store_id}" <if condition="$Orderprinter['store_id'] eq $store['store_id']"> selected="selected"</if>>{pigcms{$store.name}</option>
			</volist>
			</select>
		</div>
		<div class="pigcms-container">
			<label><input type="radio" class='ios-switch green tinyswitch' value="1" name="paid" <if condition="$Orderprinter['paid'] eq 1"> checked="checked"</if>>只打印付过款的 </label>
			<label><input type="radio" value="0" name="paid" <if condition="$Orderprinter['paid'] eq 0"> checked="checked"</if> >无论是否付款都打印</label>
		</div>
		<button type="submit" class="pigcms-btn-block pigcms-btn-block-info"	 value="保存">保存</button>
			<input  name="pigcms_id"  type="hidden" value="{pigcms{$pigcms_id}"/>	
		</form>

	</div>
	<script>
		function checkSubmit2(){
			//alert($("input[name='count']").val());
		var message = "请正确填写以下信息:<br>";

		if($("input[name='mcode']")[0] && $("input[name='mcode']").val() == ''){
			message += '终端号<br>';
		}
		if($("input[name='username']")[0] && $("input[name='username']").val() == ''){
			message += '绑定账号<br>';
		}
		if($("input[name='mkey']")[0] && $("input[name='mkey']").val() == ''){
			message += '密钥<br>';
		}
		if($("input[name='mp']")[0] && ($("input[name='mp']").val() == '' || !checkMobile($("input[name='mp']").val()))){
			message += '终端手机号<br>';
		}
		if(message != "请正确填写以下信息:<br>"){
			alert_open(message);
			return false;
		}else{
			hardware_add();
			return false;
		}
	}
	</script>
</body>
	<include file="Public:footer"/>
</html>
