<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-user"></i>
				<a href="{pigcms{:U('User/audit_index')}">业主审核管理</a>
			</li>
			<li class="active">审核业主设置</li>
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
					<form  class="form-horizontal" method="post" id="edit_form" action="__SELF__" onsubmit="return chk_submit()">
						<input  name="pigcms_id" type="hidden"  value="{pigcms{$info.pigcms_id}"/>
						<input  name="usernum" type="hidden"  value="{pigcms{$info.usernum}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								
								<div class="form-group">
									<label class="col-sm-1"><label for="usernum">用户编号</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$info.usernum}" type="text" style="border:none;background:white!important;" readonly="readonly">
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="user_name">姓名</label></label>
									<input class="col-sm-2" size="20" name="user_name" id="user_name" type="text" value="{pigcms{$info.name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">联系方式</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value="{pigcms{$info.phone}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="address">住址</label></label>
									<input class="col-sm-2" size="20" name="address" id="address" type="text" style="border:none;background:white!important;" readonly="readonly" value="{pigcms{$info.layer_name}{pigcms{$info.floor_name}{pigcms{$info.layer}{pigcms{$info.room}" />
								</div>

                                <div class="form-group">
                                    <label class="col-sm-1"><label for="housesize">房屋面积</label></label>
                                    <input class="col-sm-2" size="20" name="housesize" id="housesize" type="text" value="{pigcms{$info.housesize}" <if condition='$info["status"] eq 3'>readonly="readonly"</if>/>
                                </div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="memo">备注</label></label>
									<textarea class="col-sm-2" size="10" name="memo" id="memo" />{pigcms{$info.memo}</textarea>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="unittype">住宅类型</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$info.floor_type_name}" type="text" style="border:none;background:white!important;" readonly="readonly">
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="unittype">关系</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$info.relation_val}" type="text" style="border:none;background:white!important;" readonly="readonly">
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" for="status">状态</label>
									
									<label style="padding-left:0px;padding-right:20px;"><input type="radio" name="status" value="1" class="ace" checked="checked"><span class="lbl" style="z-index: 1">通过</span></label>
									<label style="padding-left:0px;"><input type="radio" name="status" value="0" class="ace"><span class="lbl" style="z-index: 1" >禁止</span></label>
								</div>
							</div>
						</div>
						<input type="hidden" name="floor_id" value="{pigcms{$info.floor_id}" />
						<input type="hidden" name="layer_num" value="{pigcms{$info.layer}" />
						<input type="hidden" name="room_num" value="{pigcms{$info.room}" />
						<input type="hidden" name="type" value="{pigcms{$info.type}" />
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(102,$house_session['menus'])">disabled="disabled"</if>>
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
<script type="text/javascript" language="javascript">
function chk_submit(){
	if(!confirm('确认进行修改?')){
		return false;
	}
}
</script>
<include file="Public:footer"/>