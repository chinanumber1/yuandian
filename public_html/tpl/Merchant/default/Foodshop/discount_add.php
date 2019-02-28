<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Foodshop/discount',array('store_id'=>$now_store['store_id']))}">{pigcms{$now_store.name}</a></li>
			<li class="active">添加店铺优惠</li>
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
									<label class="col-sm-1"><label for="sort_name">优惠条件</label></label>
									<input class="col-sm-1" size="10" name="full_money" id="full_money" type="text" value="{pigcms{$now_discount.full_money|default='100'}"/>
									<span class="form_tips" style="color: red">满足条件的金额</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">优惠的金额</label></label>
									<input class="col-sm-1" size="10" name="reduce_money" id="reduce_money" type="text" value="{pigcms{$now_discount.reduce_money|default='10'}"/>
									<span class="form_tips" style="color: red">可优惠的金额</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="type">优惠类型</label>
									<select name="type" id="type">
										<option value="0" <if condition="$now_discount['type'] eq 0">selected="selected"</if>>新单</option>
										<option value="1" <if condition="$now_discount['type'] eq 1">selected="selected"</if>>满减</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>使用状态</label></label>
									<span><label><input name="status" value="0" type="radio">&nbsp;<span>停用</span>&nbsp;</label></span>
									<span><label><input name="status" checked="checked" value="1" type="radio" >&nbsp;<span>开启</span></label></span>
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