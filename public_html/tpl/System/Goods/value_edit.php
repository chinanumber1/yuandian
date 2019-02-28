<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Goods/value_modify')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_value.id}"/>
		<input type="hidden" name="pid" value="{pigcms{$now_properties.id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">属性名称</th>
				<td>{pigcms{$now_properties['name']}</td>
			</tr>
			<tr>
				<th width="90">属性值</th>
				<td><input type="text" class="input fl" name="name" id="name" value="{pigcms{$now_value.name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>