<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Goods/property_amend')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_property.id}"/>
		<input type="hidden" name="cat_id" value="{pigcms{$now_property.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">属性名称</th>
				<td><input type="text" class="input fl" name="name" id="name" value="{pigcms{$now_property.name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="90">属性状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_property['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1"  <if condition="$now_property['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_property['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0"  <if condition="$now_property['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>