<include file="Public:header"/>
	<form id="myform" method="post" action="__SELF__" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">菜单名称</th>
				<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">菜单图片</th>
				<td><input type="file" class="input fl" name="pic_path" style="width:180px;" placeholder="请上传图片" tips="可不上传"/></td>
			</tr>
            
            <tr>
				<th width="80">菜单选中图片</th>
				<td><input type="file" class="input fl" name="hover_pic_path" style="width:180px;" placeholder="请上传图片" tips="可不上传"/></td>
			</tr>
            
			<tr>
				<th width="80">链接地址</th>
				<td>
				<input type="text" class="input fl" name="url" id="url" style="width:180px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
                <a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0)" data-toggle="modal">从功能库选择</a>
				</td>
			</tr>
			<tr>
				<th width="80">菜单排序</th>
				<td><input type="text" class="input fl" name="sort" style="width:80px;" value="0" validate="maxlength:10,required:true,number:true"/></td>
			</tr>
			<tr>
				<th width="80">菜单状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
        	<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
function addLink(domid,iskeyword, type){
	art.dialog.data('domid', domid);
	if (type == 1) {
		art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	} else {
		art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	}
}
</script>
<include file="Public:footer"/>