<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-cloud"></i>
			<li class="active">微硬件</li>
			<li class="active"><a href="{pigcms{:U('Dizwifi/index')}">微信链接WIFI</a></li>
			<li class="active">新增设备</li>
		</ul>
	</div>
	<div class="alert alert-info" style="margin:10px;">
		<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>温馨提示：<br/>
		1.进入无线路由器的管理后台，修改待添加设备的无线网络名称和密码。无线路由器的无线网名称和密码必须和下面填写的值一致，否则会连接失败<br/>
		2.请确保您设备的无线网名称以大写字母WX开头，下面填写无线网名称时WX会自动加上无需手动输入<br/>
		3.为保证门店下多台设备无缝漫游，同一个门店下的设备必须使用相同的无线网络名称和密码<br/>
		4.微信WIFI功能需要结合门店，如果您还未添加门店请点击‘添加门店’进行添加，审核中的门店也可以添加微信wifi
		
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1" for="shop_id">选择门店</label>
                                    <php>if (!empty($shop_list)) { </php>
									<select name="shop_id" id="shop_id">
										<volist name="shop_list" id="shop">
											<option value="{pigcms{$shop.shop_id}">{pigcms{$shop.shop_name}</option>
										</volist>
									</select>
                                    <php> } else { </php>
                                    <label class="col-sm-1">暂无可选店铺</label>
                                    <php>}</php>
									<input type="hidden" name="shop_name" id="shop_name" value="" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>无线网名称</label></label>
									<input class="col-sm-2" size="20" name="ssid" value="" id="ssid" type="text"/>
									<span class="form_tips">28字符以内。为避免乱码请尽量不要使用中文。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="password">无线网密码</label></label>
									<input class="col-sm-2" size="20" name="password" value="" id="password" type="text"/>
									<span class="form_tips">大于8个字符，不能包含中文字符</span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="bssid">无线网mac地址</label></label>
									<input class="col-sm-2" size="20" name="bssid" value="" id="bssid" type="text"/>
									<span class="form_tips">字符长度17个，并且字母小写，例如：00:1f:7a:ad:5c:a8</span>
								</div-->
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="button">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$(".btn-info").click(function(){
		var ssid = $("#ssid").val();
		var password = $("#password").val();
// 		var bssid = $("#bssid").val();
		var shop_name = $("#shop_id").find("option:selected").text();
		$("#shop_name").val(shop_name);
		var reg= /^[A-Fa-f0-9]{2}(:[A-Fa-f0-9]{2}){5}$/;
		var msg = '';
		if(ssid == ''){
			msg += '无线网名称不能为空\n';
		}else if(ssid.length > 28){
			msg += '无线网名称必须28个字符以内\n';
		}
		if(password == ''){
			msg += '无线网密码不能为空\n';
		}else if(password.length < 8){
			msg += '无线网密码必须大于8个字符\n';
		}
// 		if(bssid == ''){
// 			msg += '无线网mac地址不能为空';
// 		}else if(!reg.test(bssid)){
// 			msg += '无线网MAC地址格式错误';
// 		}
		if(msg != ''){
			alert(msg);
			return false;
		}
		$("#edit_form").submit();
	});
});
</script>
<include file="Public:footer"/>
