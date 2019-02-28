<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Meal/label_modify')}" enctype="multipart/form-data">
<!-- 		<input type="hidden" name="id" value="{pigcms{$label.id}"/> -->
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">标签名称</th>
				<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入名称" validate="maxlength:20,required:true" tips="最多4个汉字"/></td>
			</tr>
			<!--tr>
				<th width="80">标签图片</th>
				<td><input type="file" class="input fl" name="icon" style="width:200px;" placeholder="请上传图片" validate="required:true"  tips="请上传图片的尺寸控制在50*50之内"/></td>
			</tr-->
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>