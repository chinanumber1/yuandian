<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('com_add')}" frame="true" refresh="true" autocomplete="off">
		<input type="hidden" name="cat_id" value="{pigcms{$_GET['cat_id']}" />
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">商品名</th>
				<td><input type="text" class="input fl" name="com_name" id="com_name" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
				<th width="80">简单描述</th>
				<td><input type="text" class="input fl" name="com_title" id="com_title" size="20" placeholder="请输入名称" validate="maxlength:200,required:true"/></td>
			</tr>
			<tr>
				<th width="80">排序</th>
				<td><input type="number" class="input fl" name="sort" id="sort" size="20" placeholder="" tips="值越大越靠前"/></td>
			</tr>
			<tr>
				<th width="80">价格</th>
				<td><input type="text" class="input fl" name="price" id="price" size="20" placeholder="" tips="值越大越靠前" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
				<th width="15%">图片</th>
				<td width="85%" colspan="3">
				    <input type="hidden" name="com_img" value=""/>
					<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage">上传图片</a>
				    <img src="" width="50px" />&nbsp;&nbsp;图片建议：宽度640px,高度230px
				</td>
			</tr>
			<tr>
				<th width="80">链接地址</th>
				<td>
					<input type="text" class="input fl" name="url" id="url" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
						<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0)" data-toggle="modal">从功能库选择</a>
					</if>
				</td>
			</tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	 //var islock=false;
	K('.J_selectImage').click(function(){
		var obj=$(this);
		editor.uploadJson = "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/image";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					obj.siblings('input').val(url);
					editor.hideDialog();
					obj.siblings('img').attr('src',url).show();
					//window.location.reload();
				}
			});
		});

	});

	kind_editor = K.create("#description",{
		width:'480px',
		height:'380px',
		minWidth:'480px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		],
		emoticonsPath : './static/emoticons/',
		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/image"
	});
});
function addLink(domid, iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>