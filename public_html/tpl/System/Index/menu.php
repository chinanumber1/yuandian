<include file="Public:header"/>
	<form method="post" id="myform" action="{pigcms{:U('Index/savemenu')}" frame="true" refresh="true" data-call_fun="1">
		<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
		<input type="hidden" name="admin_id" value="{pigcms{$admin.id}"/>
		<tr>
			<th width="100px">
				<label><input type="checkbox" class="menu_9999 father_menu" value="9999" name="menus[]"  <if condition="in_array(9999, $admin['menus'])">checked</if> />概况</label>
			</th>
			<td>管理员在没有授权该权限时，只有系统管理员和区域管理员才能使用该功能</td>
		</tr>
		<volist name="menus" id="rowset">
			<tr>
				<th width="100px">
					<label><input type="checkbox" class="menu_{pigcms{$rowset['id']} father_menu" value="{pigcms{$rowset['id']}" name="menus[]" <if condition="in_array($rowset['id'], $admin['menus'])">checked</if>/>　{pigcms{$rowset['name']}</label>
				</th>
				<td>
					<volist name="rowset['lists']" id="row">
					<label><input type="checkbox" class="child_menu_{pigcms{$row['fid']} child_menu" value="{pigcms{$row['id']}"  name="menus[]" data-fid="{pigcms{$row['fid']}"  <if condition="in_array($row['id'], $admin['menus'])">checked</if> />　{pigcms{$row['name']}</label>　
					</volist>
				</td>
			</tr>
		</volist>
		<tr><td colspan="2"><label><input type="checkbox" id="all"/> 全选</label></td></tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#all').click(function(){
		if ($(this).attr('checked')) {
			$('.father_menu, .child_menu').attr('checked', true);
		} else {
			$('.father_menu, .child_menu').attr('checked', false);
		}
	});
	$('.father_menu').click(function(){
		var fid = $(this).val();
		if ($(this).attr('checked')) {
			$('.child_menu_' + fid).attr('checked', true);
		} else {
			$('.child_menu_' + fid).attr('checked', false);
		}
	});
	$('.child_menu').click(function(){
		var fid = $(this).attr('data-fid');
		if ($(this).attr('checked')) {
			$('.menu_' + fid).attr('checked', true);
		} else {
			var flag = false;
			$('.child_menu_' + fid).each(function(){
				if ($(this).attr('checked')) {
					flag = true;
				}
			});
			$('.menu_' + fid).attr('checked', flag);
		}
	});
});

function submitCallBack(info){
		window.top.msg(1,info,true);
	  top.art.dialog({id:"menu"}).close();
}
</script>
<include file="Public:header"/>