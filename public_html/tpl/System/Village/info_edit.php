<include file="Public:header"/>
<style>
	.station{margin-right:5px; height: 30px;line-height: 30px; float: left;}
</style>
<form id="myform" method="post" action="{pigcms{:U('Village/info_edit_data')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">


		<tr>
			<th width="80">基本信息</th>
			<td>
				<textarea name="details" id="details">{pigcms{$info.details}</textarea>
			</td>
		</tr>
		<tr>
			<th width="80">配套设施</th>
			<td>
				<textarea name="facilities" id="facilities">{pigcms{$info.facilities}</textarea>
			</td>
		</tr>
		<tr>
			<th width="80">小区简介</th>
			<td>
				<textarea name="synopsis" id="synopsis">{pigcms{$info.synopsis}</textarea>
			</td>
		</tr>
		<tr>
			<th width="80">交通状况</th>
			<td>
				<textarea name="traffic" id="traffic">{pigcms{$info.traffic}</textarea>
			</td>
		</tr>
		<tr>
			<th width="80">周边信息</th>
			<td>
				<textarea name="ambient" id="ambient">{pigcms{$info.ambient}</textarea>
			</td>
		</tr>
	</table>
	<div class="btn hidden">
		<input type="hidden" name="village_id" value="{pigcms{$info.village_id}">
		<input type="hidden" name="info_id" value="{pigcms{$info.info_id}">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>

<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			kind_editor = K.create("#details",{
				width:'350px',
				height:'150px',
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});


			kind_editor = K.create("#facilities",{
				width:'402px',
				height:'150px',
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});


			kind_editor = K.create("#synopsis",{
				width:'402px',
				height:'150px',
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});


			kind_editor = K.create("#traffic",{
				width:'402px',
				height:'150px',
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});

			kind_editor = K.create("#ambient",{
				width:'402px',
				height:'150px',
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});


            var editor = K.editor({
                allowFileManager : true
            });
            K('#image3').click(function() {
                editor.uploadJson = "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school";
                editor.loadPlugin('image', function() {
                    editor.plugin.imageDialog({
                        showRemote : false,
                        imageUrl : K('#url3').val(),
                        clickFn : function(url, title, width, height, border, align) {
                            // var img = K('#houseImg');
                            // img.attr("src",url);
                            K('#list_img').val(url);
                            K('#list_img_msg').html(url);
                            
                            editor.hideDialog();
                        }
                    });
                });
            });


		});
	</script>


<include file="Public:footer"/>