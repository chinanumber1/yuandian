<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Area/circle_category_add')}" frame="true" refresh="true">
		<input type="hidden" name="category_id" value="{pigcms{$_GET['category_id']}"/>
		<input type="hidden" name="type" value="hotel"/>
		<input type="hidden" name="level" value="1"/>
		<input type="hidden" name="addtime" value="{pigcms{:time()}"/>
	
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">名称</th>
				<td><input type="text" class="input fl" name="name" id="area_name" size="20" placeholder="请输入名称" value="{pigcms{$res.name}" validate="maxlength:30,required:true"/></td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>

<include file="Public:footer"/>