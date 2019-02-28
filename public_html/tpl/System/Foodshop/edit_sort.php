<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Foodshop/edit_amend')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_shop.store_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">店铺名称</th>
				<td>{pigcms{$now_shop.name}</td>
			</tr>
			<tr class="delivery_range_type0">
				<th width="90">排序</th>
				<td><input type="text" class="input fl" name="sort" value="{pigcms{$now_shop.sort}" id="sort" size="10" tips="数值越大排在越前"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>