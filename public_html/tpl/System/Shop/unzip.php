<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/unzip')}" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th colspan="2"><span style="color:red">先导入商品再导入图片</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;服务器限制上传文件大小为：{pigcms{:ini_get('upload_max_filesize')}</th>
			</tr>
			<tr>
				<th width="80">图片zip压缩包</th>
				<td><input type="file" class="input fl" name="file" style="width:200px;" placeholder="请上传图片zip压缩包" validate="required:true"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>