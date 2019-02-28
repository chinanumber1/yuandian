<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('House/village_import')}" enctype="multipart/form-data">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">示例表格</th>
				<td><a target="_blank" href="{pigcms{$static_public}file/village_sample.xls" class="button" style="margin-left:0px;">点击下载</a></td>
			</tr>
			<tr>
				<th width="80">Excel导入</th>
				<td><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="请上传excel表格" validate="required:true"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>