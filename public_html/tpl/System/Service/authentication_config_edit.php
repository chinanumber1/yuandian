<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Service/authentication_config_edit')}" frame="true" refresh="true">
		<input type="hidden" name="acid" value="{pigcms{$info['acid']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">认证名称</th>
				<td><input type="text" class="input fl" name="title" id="title" size="25" value="{pigcms{$info['title']}" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>

			<tr>
				<th width="80">唯一标识</th>
				<td><input type="text" class="input fl" name="key" id="key" size="25" value="{pigcms{$info['key']}" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			

			<tr>
				<th width="80">认证类型</th>
				<td>
					<span class="cb-enable">
						<label class="cb-enable <if condition="$info['type'] eq 1">selected</if> ">
							<span>文本</span>
							<input type="radio" name="type" value="1"  <if condition="$info['type'] eq 1">checked="checked"</if> />
						</label>
					</span>

					<span class="cb-disable">
						<label class="cb-disable <if condition="$info['type'] eq 2">selected</if> ">
							<span>图片</span>
							<input type="radio" name="type" value="2"  <if condition="$info['type'] eq 2">checked="checked"</if> />
						</label>
					</span>
				</td>
			</tr>
		</table>
	
		<div class="btn hidden">
			<input type="submit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>