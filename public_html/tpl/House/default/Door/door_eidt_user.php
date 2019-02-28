<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('door_list')}">社区论坛</a>
			</li>
			<li><a href="{pigcms{:U('door_user',array('door_id'=>$door_id))}">用户列表</a></li>
			<li class="active">修改用户</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" id="edit_form" action="{pigcms{:U('door_eidt_user',array('pigcms_id'=>$aFind['pigcms_id'],'door_id'=>$door_id))}" enctype="multipart/form-data" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="pigcms_id">ID</label></label>
									<input class="col-xs-4 col-sm-4 col-md-2" size="20" readonly name="pigcms_id" id="pigcms_id" type="text" value="{pigcms{$aFind.pigcms_id}"/>
								</div>
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="start_time">开始时间</label></label>
									<input class="col-xs-4 col-sm-4 col-md-2" size="20" name="start_time" id="start_time" type="date" />
								</div>
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="end_time">结束时间</label></label>
									<input class="col-xs-4 col-sm-4 col-md-2" size="20" name="end_time" id="end_time" type="date" />
								</div>
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="status">状态</label></label>
									<label><input name="status" type="radio" value="1" <if condition="$aFind.status eq 1">checked</if> />&nbsp;&nbsp;启用</label>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input name="status" type="radio" value="2" <if condition="$aFind.status eq 2">checked</if> />&nbsp;&nbsp;禁用</label>
								</div>
							</div>
						</div>
						<div class="space"></div>
						<div class="clearfix form-actions">
							<div class="col-md-offset-3 col-md-9">
								<button class="btn btn-info" type="submit" <if condition="!in_array(230,$house_session['menus'])">disabled="disabled"</if>>
									<i class="ace-icon fa fa-check bigger-110"></i>
									保存
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
.form-group>label{font-size:12px;line-height:24px;}
</style>
<script>
function check_submit(){
	if($('#door_device_id').val() == ''){
		alert('设备ID不能为空');
		return false;
	}else if($('#door_name').val() == ''){
		alert('设备名不能为空');
		return false;
	}else if($('#floor_id').val() == ''){
		alert('楼名不能为空');
		return false;
	}
	window.location.href = "{pigcms{:U('door_eidt',array('door_id'=>$door_id))}";
}
</script>
<include file="Public:footer"/>