<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-desktop"></i>
				<a href="{pigcms{:U('Deliver/user')}">配送管理</a>
			</li>
			<li class="active">添加配送员</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本设置</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">姓名</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text"/>
								</div>
								<if condition="$config.international_phone eq 1">
									<div class="form-group">
										<label class="col-sm-1"><label for="password">区号</label></label>
										
										<select name="phone_country_type" id="phone_country_type" style="height:34px;float:left;margin-right:5px;">
										<option value="86"  <if condition="$config.qcloud_sms_default_country eq 86">selected</if>>+86 中国 China</option>
										<option value="1"  <if condition="$config.qcloud_sms_default_country eq 1">selected</if>>+1 加拿大 Canada</option>
										</select>
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">联系电话</label></label>
									
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="qq">密码</label></label>
									<input class="col-sm-2" size="20" name="pwd" id="pwd" type="text"/>
								</div>
								<!--div class="form-group" style="display:none;">
									<label class="col-sm-1"><label for="qq">配送范围</label></label>
									<input class="col-sm-2" size="20" name="range" id="range" type="text" value="5"/>
									<span class="form_tips">（公里）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="long_lat">店铺经纬度</label></label>
									<input class="col-sm-2" size="10" name="long_lat" id="long_lat" type="text" readonly="readonly"/>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" id="show_map_frame" data-toggle="modal">点击选取经纬度</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>配送员常驻地</label></label>
									<fieldset id="choose_cityarea"></fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="adress">店铺地址</label></label>
									<input class="col-sm-2" size="20" name="adress" id="adress" type="text"/>
									<span class="form_tips">地址不能带有上面所在地选择的省/区/商圈信息。</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1" for="have_meal">状态</label>
									<select name="status" id="status">
										<option value="1" selected="selected">正常</option>
										<option value="0">禁止</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="have_meal">选择店铺</label>
									<select name="store_id" id="store_id">
										<volist name="waimai_store" id='waimai'>
										<option value="{pigcms{$waimai.store_id}" selected="selected">{pigcms{$waimai.name}</option>
										</volist>
									</select>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
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
	$('#edit_form').submit(function(){
		$('#edit_form button[type="submit"]').prop('disabled',true).html('保存中...');
		$.post("{pigcms{:U('Deliver/user_add')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Deliver/user')}";
			}else{
				$('#edit_form button[type="submit"]').prop('disabled',false).html('<i class="ace-icon fa fa-check bigger-110"></i>保存');
				alert(result.info);
			}
		})
		return false;
	}); 
</script>
<include file="Public:footer"/>
