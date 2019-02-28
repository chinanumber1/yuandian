<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Meal/label_amend')}" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{pigcms{$label['id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">标签名称</th>
				<td><input type="text" class="input fl" name="name" value="{pigcms{$label['name']}" size="20" placeholder="请输入名称" validate="maxlength:20,required:true" tips="最多4个汉字"/></td>
			</tr>
			<!--tr>
				<th width="80">标签现图</th>
				<td><img src="{pigcms{$config.site_url}/{pigcms{$label['icon']}" style="width:50px;height:50px;" class="view_msg"/></td>
			</tr>
			<tr>
				<th width="80">标签图片</th>
				<td><input type="file" class="input fl" name="icon" style="width:200px;" placeholder="请上传图片" tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
			</tr-->
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>