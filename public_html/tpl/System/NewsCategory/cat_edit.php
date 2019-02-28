<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('NewsCategory/cat_amend')}" frame="true" refresh="true">
		<input type="hidden" name="category_id" value="{pigcms{$now_category.category_id}"/>
		<input type="hidden" name="category_pid" value="{pigcms{$parentid}"/>
		<input type="hidden" name="level" value="{pigcms{$level}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">分类名称</th>
				<td><input type="text" class="input fl" name="name" id="name" value="{pigcms{$now_category.name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="90">短标记(url)</th>
				<td><input type="text" class="input fl" name="flag" id="flag" value="{pigcms{$now_category.flag}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="90">分类排序</th>
				<td><input type="text" class="input fl" name="order" value="{pigcms{$now_category.order}" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>
			<tr>
				<th width="90">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['is_display'] eq 1">selected</if>"><span>启用</span><input type="radio" name="is_display" value="1"  <if condition="$now_category['is_display'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['is_display'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="is_display" value="0"  <if condition="$now_category['is_display'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
			<!--<tr>
				<th width="90">分类下店铺显示</th>
				<td>
					<select name="show_method" class="valid">
					<option value="0" <if condition="$now_category['show_method'] eq 0">selected</if>>不营业不显示</option>
					<option value="1" <if condition="$now_category['show_method'] eq 1">selected</if>>不营业正常显示</option>
					<option value="2" <if condition="$now_category['show_method'] eq 2">selected</if>>不营业靠后显示</option>
					</select>
				</td>
			</tr>-->
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>