<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('Unit/preferential_list')}">缴费优惠列表</a>
			</li>
			<li class="active">编辑缴费优惠</li>
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
									<label class="col-sm-1"><label for="property_month_num">物业缴费周期</label></label>
									<select name="property_month_num">
										<option value='0'>请选择</option>
										<option value='1' <if condition='$detail["property_month_num"] eq 1'>selected="selected"</if>>1个月</option>
										<option value='3' <if condition='$detail["property_month_num"] eq 3'>selected="selected"</if>>3个月</option>
										<option value='6' <if condition='$detail["property_month_num"] eq 6'>selected="selected"</if>>6个月</option>
										<option value='12' <if condition='$detail["property_month_num"] eq 12'>selected="selected"</if>>12个月</option>
										<option value='24' <if condition='$detail["property_month_num"] eq 24'>selected="selected"</if>>24个月</option>
										<option value='0' <if condition='!in_array($detail["property_month_num"],array(1,3,6,12,24))'>selected="selected"</if>>自定义</option>
									</select>
									
									<label <if condition='in_array($detail["property_month_num"],array(1,3,6,12,24))'>style="display:none"</if>>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="6" name="diy_property_month_num" <if condition='!in_array($detail["property_month_num"],array(1,3,6,12,24))'>value="{pigcms{$detail["property_month_num"]}"</if> />&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">单位：月</span></label>
								</div>
								
								<div class="form-group diy_property_type">
									<label class="col-sm-1"><label for="presented_property_month_num">自定义类型</label></label>
									<label style="padding-left:0px;padding-right:20px;"><input type="radio" class="ace" value="0" name="diy_type" <if condition='$detail["diy_type"] eq 0'>checked="checked"</if>><span style="z-index: 1" class="lbl">月份</span></label>
									<label style="padding-left:0px;padding-right:20px;"><input type="radio" class="ace" value="1" name="diy_type" <if condition='$detail["diy_type"] eq 1'>checked="checked"</if>><span style="z-index: 1" class="lbl">文本</span></label>
								</div>
                                
								<div class="form-group diy_property_content" <if condition='$detail["diy_type"] eq 1'>style="display:none"</if>>
									<label class="col-sm-1"><label for="presented_property_month_num">赠送物业时间</label></label>
									<select name="presented_property_month_num">
										<option value='0'>请选择</option>
										<option value='1' <if condition='$detail["presented_property_month_num"] eq 1'>selected="selected"</if>>1个月</option>
										<option value='3' <if condition='$detail["presented_property_month_num"] eq 3'>selected="selected"</if>>3个月</option>
										<option value='6' <if condition='$detail["presented_property_month_num"] eq 6'>selected="selected"</if>>6个月</option>
										<option value='0' <if condition='!in_array($detail["presented_property_month_num"],array(1,3,6))'>selected="selected"</if>>自定义</option>
									</select>
									
									<label <if condition='in_array($detail["presented_property_month_num"],array(1,3,6))'>style="display:none"</if>>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="6" name="diy_presented_property_month_num" <if condition='!in_array($detail["presented_property_month_num"],array(1,3,6))'>value="{pigcms{$detail["presented_property_month_num"]}"</if>/>&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">单位：月</span></label>
									&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">*可不填写</span>
								</div>
								
								
								<div class="form-group diy_property_content" <if condition='$detail["diy_type"] eq 0'>style="display:none"</if>>
									<label class="col-sm-1"><label>自定义内容</label></label>
									<label><textarea name="diy_content">{pigcms{$detail['diy_content']}</textarea></label>
								</div>
							</div>
                            
                            <div class="form-group">
									<label class="col-sm-1">状态</label>
									
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition='$detail["status"] eq 1'>checked="checked"</if> class="ace" value="1" name="status"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" value="0" <if condition='$detail["status"] eq 0'>checked="checked"</if> name="status"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
            						<if condition="in_array(30,$house_session['menus'])">
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
	if(!$('select[name="property_month_num"]').val()){
		if(!$('input[name="diy_property_month_num"]').val()){
			alert('物业缴费周期不能为空！');
			return false;
		}
	}
}

$('select').change(function(){
	if($(this).val() == 0){
		$(this).next('label').show();
	}else{
		$(this).next('label').hide();
	}
});

$('input[name="diy_type"]').each(function(i){
	$(this).click(function(){
		$('.diy_property_content').each(function(){
			$(this).hide();
		});
		$('.diy_property_content').eq(i).show();
	});
	
});
</script>

<include file="Public:footer"/>