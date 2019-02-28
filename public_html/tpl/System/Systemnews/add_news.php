<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Systemnews/add_news')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">标题</th>
				<td><input type="text" class="input fl" name="title" size="75" placeholder="快报标题" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">分类</th>
				<if condition="$category">
				<td>
					<select name="category_id">
						<volist name="category" id="vo">
							<option value="{pigcms{$vo.id}" <if condition="$vo['id'] eq $_GET['category_id']">selected="selected"</if>>{pigcms{$vo.name}</option>
						</volist>
					</select>
				</td>
				</if>
			</tr>
			<tr>
				<th width="80">排序</th>
			
				<td><input type="text" class="input fl" name="sort" value="0"  placeholder="快报标题" validate="maxlength:50,required:true,digits:true"/></td>
			</tr>
			<tr>
				<th width="80">内容</th>
				<td>
					<textarea name="content" id="content"></textarea>
				</td>
			</tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable "><span>禁用</span><input type="radio" name="status" value="0" /></label></span>
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