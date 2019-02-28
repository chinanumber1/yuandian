<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/goods_import')}" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">示例表格</th>
				<td><a target="_blank" href="{pigcms{$static_public}file/sys_goods.xlsx" style="margin-left:0px;">点击下载</a></td>
			</tr>
			<tr>
				<th width="80">Excel导入</th>
				<td><input type="file" class="input fl" name="file" style="width:200px;" placeholder="请上传Excel数据表" validate="required:true"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>