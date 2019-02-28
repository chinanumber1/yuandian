<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Systemnews/edit_news')}" frame="true" refresh="true">
		
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">标题</th>
				<input type="hidden" name="id" value="{pigcms{$news.id}" />
				<td><input type="text" class="input fl" name="title" value="{pigcms{$news.title}" size="75" placeholder="快报标题" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">分类</th>
				<if condition="$category">
				<td>
					<select name="category_id">
						<volist name="category" id="vo">
							<option value="{pigcms{$vo.id}"<if condition="$vo['id'] eq $news['category_id']">selected="selected"</if>>{pigcms{$vo.name}</option>
						</volist>
					</select>
				</td>
				</if>
			</tr>
			<tr>
				<th width="80">排序</th>
			
				<td><input type="text" class="input fl" name="sort" value="{pigcms{$news.sort}"  placeholder="快报标题" validate="maxlength:50,digits:true,required:true"/></td>
			</tr>
			<tr>
				<th width="80">内容</th>
				<td>
					<textarea name="content" id="content" >{pigcms{$news.content|htmlspecialchars}</textarea>
				</td>
			</tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$news['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1"  <if condition="$news['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$news['status'] eq 0">selected</if>"><span>禁用</span><input type="radio" name="status" value="0"  <if condition="$news['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			
			kind_editor = K.create("#content",{
				width:'402px',
				height:'320px',
				resizeType : 1,
				<if condition="$_GET['frame_show']">readonlyMode : true,</if>
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/news"
			});
			
		});
	</script>
<include file="Public:footer"/>