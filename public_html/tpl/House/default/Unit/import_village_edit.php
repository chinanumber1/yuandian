<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('import_village')}">物业管理</a>
			</li>
			<li class="active">修改信息</li>
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
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="usernum">物业编号</label></label>
									<input class="col-sm-2" size="20" name="usernum" id="usernum" type="text" value="{pigcms{$detail.usernum}" readonly="readonly"/>
								</div>


								<div class="form-group">
									<label class="col-sm-1"><label for="floor_name">单元名称</label></label>
									<input class="col-sm-2" size="20" name="floor_name" id="floor_name" type="text" value="{pigcms{$detail.floor_name}" readonly="readonly"/>
								</div>

                                <div class="form-group">
									<label class="col-sm-1"><label for="floor_layer">单元楼号</label></label>
									<input class="col-sm-2" size="20" name="floor_layer" id="floor_layer" type="text" value="{pigcms{$detail.layer_name}" readonly="readonly"/>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="layer">层号</label></label>
									<input class="col-sm-2" size="20" name="layer" id="layer" type="text" value="{pigcms{$detail.layer}" <if condition='$detail["status"] gt 1'>readonly="readonly"</if>/>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="room">房间号</label></label>
									<input class="col-sm-2" size="20" name="room" id="room" type="text" value="{pigcms{$detail.room}" <if condition='$detail["status"] gt 1'>readonly="readonly"</if>/>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="housesize">房屋面积</label></label>
									<input class="col-sm-2" size="20" name="housesize" id="housesize" type="text" value="{pigcms{$detail.housesize}" />
								</div>

							</div>


                            <div class="form-group">
									<label class="col-sm-1">状态</label>

									<label style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition='$detail["status"] egt 1'>checked="checked"</if> class="ace" value="1" name="status"><span style="z-index: 1" class="lbl">开启</span></label>
									<label style="padding-left:0px;"><input type="radio" class="ace" value="0" <if condition='$detail["status"] eq 0'>checked="checked"</if> name="status"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
            						<if condition="in_array(39,$house_session['menus'])">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
									<else/>
									<button class="btn btn-info" type="submit" disabled="disabled">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
									</if>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
function check_submit(){
	if($('#floor_name').val() == ''){
		alert('单元名称不能为空！');
		return false;
	}

	if($('#floor_layer').val() == ''){
		alert('单元楼号不能为空！');
		return false;
	}

	if(parseFloat($('#housesize').val()) <= 0 ){
		alert('房屋面积不能为空！');
		return false;
	}


/* 	if($('select[name="property_month_num"]').val() == 0){
		if($('#property_month_num').val() == ''){
			alert('请填写物业缴费周期');
			return false;
		}
	}


	if($('select[name="presented_property_month_num"]').val() == 0){
		if($('#presented_property_month_num').val() == ''){
			alert('请填写赠送物业时间');
			return false;
		}
	} */
}

$('select').change(function(){
	if($(this).val() == 0){
		$(this).next('label').show();
	}else{
		$(this).next('label').hide();
	}
});
</script>

<include file="Public:footer"/>