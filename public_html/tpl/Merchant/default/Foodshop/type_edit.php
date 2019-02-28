<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.fooshop_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Foodshop/table',array('store_id'=>$now_store['store_id']))}">{pigcms{$now_store.name}</a></li>
			<li class="active">桌台分类管理</li>
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
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post">
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">分类名称</label></label>
									<input class="col-sm-1" size="20" name="name" id="name" type="text" value="{pigcms{$now_type.name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">容纳最少人数</label></label>
									<input class="col-sm-1" name="min_people" id="min_people" type="text" value="{pigcms{$now_type.min_people}"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">容纳最多人数</label></label>
									<input class="col-sm-1" name="max_people" id="max_people" type="text" value="{pigcms{$now_type.max_people}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">预订金</label></label>
									<input class="col-sm-1" name="deposit" type="text" value="{pigcms{$now_type.deposit|floatval}"/>
									<span class="form_tips" style="color:red;">（单位：元）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">排号前缀</label></label>
									<input class="col-sm-1" name="number_prefix" type="text" value="{pigcms{$now_type.number_prefix}"/>
									<span class="form_tips" style="color:red;">在排号时区分桌台类型（如大桌用：D,小桌用S,等，得到的排号D1、D2；S1、S2等）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort_name">使用时间</label></label>
									<input class="col-sm-1" name="use_time" type="text" value="{pigcms{$now_type.use_time|default=60}"/>
									<span class="form_tips" style="color:red;">该类型下的桌台每次使用时间大约是多长时间，如一个小时，那么下一桌大约就要60分钟后才能使用（单位：分钟）</span>
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