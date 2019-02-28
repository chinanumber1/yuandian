<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('edit_advert')}" frame="true" refresh="true" autocomplete="off">
		<input type="hidden" name="advert_id" value="{pigcms{$_GET['advert_id']}"/>
		<input type="hidden" name="cat_id" value="{pigcms{$_GET['cat_id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">标题</th>
				<td width="85%" colspan="3"><input type="text" value="{pigcms{$scenic_advert['advert_title']}" class="input fl" name="advert_title" size="40" validate="maxlength:50,required:true"/></td>
			<tr/>
			<tr>
				<th width="15%">排序</th>
				<td width="85%" colspan="3"><input type="text" value="{pigcms{$scenic_advert['sort']}" class="input fl" name="sort" size="10" value="0" validate="maxlength:50,required:true"/>&nbsp;&nbsp;值越大，越靠前</td>
			<tr/>
			<tr>
				<th width="15%">通用广告</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$scenic_advert['city_id'] eq 0">selected</if>"><span>通用</span><input id="yes" type="radio" name="currency" value="1" <if condition="$scenic_advert['city_id'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$scenic_advert['city_id'] neq 0">selected</if>"><span>不通用</span><input id="no" type="radio" name="currency" value="2" <if condition="$scenic_advert['city_id'] neq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
			<tr id="adver_region" <if condition="$scenic_advert['city_id'] eq 0">style="display:none;"</if>>
				<th width="15%">所在区域</th>
				<td width="85%" colspan="3" id="choose_cityareass" province_idss="{pigcms{$scenic_advert['province_id']}" city_idss="{pigcms{$scenic_advert['city_id']}"></td>
			</tr>
			<tr>
				<th width="15%">状态</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label <if condition="$scenic_advert['advert_status'] eq 1">class="cb-enable selected"<else/>class="cb-disable"</if>><span>开启</span><input type="radio" name="advert_status" value="1" <if condition="$scenic_advert['advert_status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label <if condition="$scenic_advert['advert_status'] eq 2">class="cb-enable selected"<else/>class="cb-disable"</if>><span>关闭</span><input type="radio" name="advert_status" value="2" <if condition="$scenic_advert['advert_status'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">图片</th>
				<td width="85%" colspan="3">
				    <input type="hidden" name="advert_img" value="{pigcms{$scenic_advert['advert_img']}"/>
					<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage">上传图片</a>
				    <img src="{pigcms{$scenic_advert['url_advert_img']}" width="50px" />&nbsp;&nbsp;图片建议：宽度640px,高度230px
				</td>
			</tr>
			<tr>
				<th width="80">链接地址</th>
				<td>
					<input type="text" class="input fl" name="advert_url" id="advert_url" value="{pigcms{$scenic_advert['advert_url']}" style="width:200px;" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
						<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('advert_url', 0)" data-toggle="modal">从功能库选择</a>
					</if>
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
$("#yes").click(function(){
	$("#adver_region").hide();
})
$("#no").click(function(){
	$("#adver_region").show();
})
</script>