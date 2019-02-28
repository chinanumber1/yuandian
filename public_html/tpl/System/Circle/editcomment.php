<include file="Public:header"/>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<form id="myform" method="post" action="{pigcms{:U('Circle/doeditcomment')}" enctype="multipart/form-data">
  <input type="hidden" name="id" value="{pigcms{$res.id}"/>
  <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr  >
            <th width="80" >评论详情</th>
            <td>
                <textarea name="content" id="content">{pigcms{$res.content}</textarea>
            </td>
        </tr>
        <tr>
			<th width="80">显示状态</th>
				<td>
					<span class="cb-enable">
					<label class="cb-enable <if condition="$res['status'] eq 0">selected</if>"><span>显示</span><input type="radio" name="status" value="0"  <if condition="$res['status'] eq 1">checked="checked"</if> /></label>
					</span>
					<span class="cb-disable">
					<label class="cb-disable <if condition="$res['status'] eq 1">selected</if>"><span>隐藏</span><input type="radio" name="status" value="1"  <if condition="$res['status'] eq 1">checked="checked"</if> /></label>
					</span>
				</td>
			</tr>
    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
        <input type="reset" value="取消" class="button" />
    </div>
</form>
<script type="text/javascript">
    KindEditor.ready(function(K){
        kind_editor = K.create("#content",{
            width:'350px',
            height:'150px',
            resizeType : 1,
            allowPreviewEmoticons:false,
            allowImageUpload : true,
            filterMode: true,
            items : [
                'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons',  'link'
            ],
            emoticonsPath : './static/emoticons/',
            uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=Circle/editor"
        });
    });
</script>
<include file="Public:footer"/>