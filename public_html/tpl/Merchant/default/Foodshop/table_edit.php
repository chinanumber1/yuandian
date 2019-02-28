<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.fooshop_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Meal/table',array('store_id'=>$now_store['store_id']))}">{pigcms{$now_store.name}</a></li>
			<li class="active">桌台管理</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">桌台名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$now_table.name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>桌台所属分类</label></label>
									<select name="tid">
									<volist name="types" id="type">
									<option value="{pigcms{$type['id']}" <if condition="$now_table['tid'] eq $type['id']">selected</if>>{pigcms{$type['name']}</option>
									</volist>
									</select>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label>所属店员</label></label>
                                    <select name="staff_id">
                                    <option value="0" <if condition="$now_table['staff_id'] eq 0">selected</if>>不绑定店员</option>
                                    <volist name="staffs" id="staff">
                                    <option value="{pigcms{$staff['id']}" <if condition="$now_table['staff_id'] eq $staff['id']">selected</if>>{pigcms{$staff['name']}</option>
                                    </volist>
                                    </select>
                                </div>
								
								<if condition="$ok_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:blue;">{pigcms{$ok_tips}</span>				
									</div>
								</if>
								<if condition="$error_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:red;">{pigcms{$error_tips}</span>				
									</div>
								</if>
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit">
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
	</div>
</div>
<include file="Public:footer"/>