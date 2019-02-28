<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Goods/banner_modify')}" enctype="multipart/form-data">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

			<tr>
				<th width="80">轮播图片</th>
				<td><input type="file" class="input fl" name="image" style="width:200px;" placeholder="请上传图片" validate="required:true" tips="建议尺寸1200*420"/></td>
			</tr>
			<tr>
				<th width="80">链接地址</th>
				<td>
					<input type="text" class="input fl" name="url" id="url" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,url:true"/>
					<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url')" data-toggle="modal">从功能库选择</a>
				</td>
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
function addLink(domid){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=LinkPC&a=insert',{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>