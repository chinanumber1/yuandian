<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Group/cue_field_amend')}" frame="true" refresh="true">
		<input type="hidden" name="cat_id" value="{pigcms{$_GET.cat_id}"/>
		<input type="hidden" name="id" value="{pigcms{$_GET.id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">名称</th>
				<td><input type="text" class="input fl" name="name" id="name" size="10" placeholder="" validate="maxlength:20,required:true" tips="" value="{pigcms{$now_cue.name}"/></td>
			</tr>
			<tr>
				<th width="80">显示排序</th>
				<td><input type="text" class="input fl" name="sort" size="10" placeholder="显示排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。" value="{pigcms{$now_cue.sort}"/></td>
			</tr>
			<tr>
				<th width="80">字段类型</th>
				<td>
					<select name="type">
						<option value="0" <if condition="$now_cue['type'] eq 0">selected="selected"</if>>单行文本 input[text]</option>
						<option value="1" <if condition="$now_cue['type'] eq 1">selected="selected"</if>>多行文本 textarea</option>
					</select>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>