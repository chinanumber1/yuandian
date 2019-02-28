<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('NewsCategory/create_data')}" frame="true" refresh="true">
		<input type="hidden" name="category_pid" id="category_pid" value="{pigcms{$parentid}"/>
		<input type="hidden" name="level" id="level" value="{pigcms{$level}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">分类名称</th>
				<td><input type="text" class="input fl" name="name" id="name" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="90">短标记(url)</th>
				<td><input type="text" class="input fl" name="flag" id="flag" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="90">分类排序</th>
				<td><input type="text" class="input fl" name="order" value="0" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>
			<tr>
				<th width="90">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="is_display" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="is_display" value="0" /></label></span>
				</td>
			</tr>
			<!--<tr>
				<th width="90">分类下店铺显示</th>
				<td>
					<select name="show_method" class="valid">
					<option value="0" selected="selected">不营业不显示</option>
					<option value="1">不营业正常显示</option>
					<option value="2">不营业靠后显示</option>
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