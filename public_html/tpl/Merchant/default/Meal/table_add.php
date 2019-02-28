<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Meal/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Meal/table',array('store_id'=>$now_store['store_id']))}">{pigcms{$now_store.name}</a></li>
			<li class="active">添加餐台分类</li>
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
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">				
							<li class="active">
								<a href="{pigcms{:U('Meal/table_add',array('store_id'=>$now_store['store_id']))}">添加桌台</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">桌台名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$now_table.name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">容纳人数</label></label>
									<input class="col-sm-2" size="20" name="num" id="num" type="text" value="{pigcms{$now_table.num}"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="canrqnums">桌台使用状态</label></label>
									<div class="radio">
										<label>
											<input name="status" value="1" type="radio" <if condition="$now_table['status'] eq 1" >checked="checked"</if>/>
											<span class="lbl" style="z-index: 1">使用中</span>
										</label>
										<label>
											<input name="status" value="0" type="radio" <if condition="$now_table['status'] eq 0">checked="checked"</if>/>
											<span class="lbl" style="z-index: 1">空闲</span>
										</label>
									</div>										
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
