<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('groom_edit')}" frame="true" refresh="true" autocomplete="off">
		<input type="hidden" name="cat_id" value="{pigcms{$list['cat_id']}" />
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名</th>
				<td><input type="text" value="{pigcms{$list['cat_name']}" class="input fl" name="cat_name" id="cat_name" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
				<th width="80">排序</th>
				<td><input type="number" value="{pigcms{$list['cat_sort']}" class="input fl" name="cat_sort" id="cat_sort" size="20" placeholder="" tips="值越大越靠前"/></td>
			</tr>
			<if condition="$many_city eq 1">
				<tr>
					<th width="15%">通用广告</th>
					<td width="35%" class="radio_box">
						<span class="cb-enable"><label class="cb-enable <if condition="$list['city_id'] eq 0">selected</if>"><span>通用</span><input id="yes" type="radio" name="currency" value="1" <if condition="$list['city_id'] eq 0">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$list['city_id'] neq 0">selected</if>"><span>不通用</span><input id="no" type="radio" name="currency" value="2" <if condition="$list['city_id'] neq 0">checked="checked"</if> /></label></span>
					</td>
				</tr>
				<tr id="adver_region" <if condition="$list['city_id'] eq 0">style="display:none;"</if>>
					<th width="15%">所在区域</th>
					<td width="85%" colspan="3" id="choose_cityareass" province_idss="{pigcms{$list['province_id']}" city_idss="{pigcms{$list['city_id']}"></td>
				</tr>
			</if>
			<tr>
				<th width="15%">图片</th>
				<td width="85%" colspan="3">
				    <input type="hidden" name="cat_img" value="{pigcms{$list['cat_img']}"/>
					<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage">上传图片</a>
				    <img src="" width="50px" />&nbsp;&nbsp;图片建议：宽度640px,高度230px
				</td>
			</tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$list['status'] eq 1">selected</if>"><span>开启</span><input type="radio" name="status" value="1" <if condition="$list['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$list['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$list['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">补齐</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$list['complete'] eq 1">selected</if>"><span>是</span><input type="radio" name="complete" value="1" <if condition="$list['complete'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$list['complete'] eq 0">selected</if>"><span>否</span><input type="radio" name="complete" value="0" <if condition="$list['complete'] eq 0">checked="checked"</if> /></label></span>
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
$("#yes").click(function(){
	$("#adver_region").hide();
})
$("#no").click(function(){
	$("#adver_region").show();
})
</script>