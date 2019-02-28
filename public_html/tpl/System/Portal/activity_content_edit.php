<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Portal/activity_content_edit')}" frame="true" refresh="true">
		<input type="hidden" name="a_id" value="{pigcms{$content.a_id}">
		<input type="hidden" name="key" value="{pigcms{$content.key}">
		<textarea name="content" id="content">{pigcms{$content.content}</textarea>
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script type="text/javascript">
			KindEditor.ready(function(K){
				kind_editor = K.create("#content",{
					width:'796px',
					height:'590px',
					resizeType : 1,
					allowPreviewEmoticons:false,
					allowImageUpload : true,
					filterMode: true,
					items : [
						'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link'
					],
					emoticonsPath : './static/emoticons/',
					uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=portal/activity"
				});
			});
		</script>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>