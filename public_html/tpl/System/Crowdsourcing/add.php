<include file="Public:header"/>
	<form id="add" method="post" action="{pigcms{:U('add')}" enctype="multipart/form-data" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="category_name" id="category_name" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/><em for="category_name" generated="true" class="error tips">必填项</em></td>
			</tr>
			<tr>
				<th width="80">分类排序</th>
				<td><input type="text" class="input fl" name="category_sort" size="10" value="0" validate="required:true,number:true,maxlength:6" tips="数值越大，排序越前"/></td>
			</tr>
			<tr>
				<th width="80">活动图片</th>
				<td><input type="file" class="input fl" name="category_img" style="width:200px;" placeholder="请上传图片" validate="required:true"  tips="请上传图片的尺寸控制在60*60之内"/></td></tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="category_status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>未用</span><input type="radio" name="category_status" value="0" /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>