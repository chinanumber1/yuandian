<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Slider/cat_amend')}" frame="true" refresh="true">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="cat_name" value="{pigcms{$now_category.cat_name}" size="20" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">分类标识</th>
				<td><input type="text" class="input fl" name="cat_key" value="{pigcms{$now_category.cat_key}" size="20" placeholder="分类标识" validate="maxlength:20,required:true,english:true"/></td>
			</tr>
			<tr>
				<th width="80">建议尺寸</th>
				<td><textarea name="size_info" rows="4" cols="30" placeholder="导航的建议尺寸" validate="maxlength:50,required:true">{pigcms{$now_category.size_info}</textarea></td>
			</tr>
			<tr>
				<th width="80">分类类型</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['cat_type'] eq 0">selected</if>"><span>wap站</span><input type="radio" name="cat_type" value="0" <if condition="$now_category['cat_type'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['cat_type'] eq 1">selected</if>"><span>pc站</span><input type="radio" name="cat_type" value="1" <if condition="$now_category['cat_type'] eq 1">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>