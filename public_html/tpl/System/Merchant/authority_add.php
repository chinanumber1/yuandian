<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/authority_add')}" frame="true" refresh="true">
			<input type="hidden" name="id" value="{pigcms{$_GET.id}"/>
			<input type="hidden" name="menus[]" value="1"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr >
			<th >
				<label>名称：</label><input type="text" name="name"  class="input fr" value="{pigcms{$group.name}"/>
				<label>价格：</label><input type="text" name="price"  class="input fr" value="{pigcms{$group.price}"/>元
			</th>
			
		</tr>
		<volist name="menus" id="rowset">
			<tr>
				<th width="20%" style=" font-weight:bold; font-size:14px">
					<label><input type="hidden"value="{pigcms{$rowset['id']}" name="menus[]">&nbsp;{pigcms{$rowset['name']}</label>
				</th>
			</tr>
			<if condition="$rowset['menu_list']">
				<volist name="rowset['menu_list']" id="row">
				<tr style="font-weight:bold;">
					<td>
							<label><input type="checkbox" class="menu_{pigcms{$row['id']} father_menu" value="{pigcms{$row['id']}"  name="menus[]" data-fid="{pigcms{$row['fid']}"  <if condition="in_array($row['id'], $merchant['menus'])">checked</if> />&nbsp;{pigcms{$row['name']}</label>
							<if condition='$row["menu_list"]'>
								<tr style=" background:#fff; ">
									<td style=" padding-left:35px;">
										<volist name="row['menu_list']" id="srow">
											<label><input type="checkbox" class="child_menu_{pigcms{$srow['fid']} child_menu" value="{pigcms{$srow['id']}"  name="menus[]" data-fid="{pigcms{$srow['fid']}"  <if condition="in_array($srow['id'], $merchant['menus'])">checked</if> />&nbsp;{pigcms{$srow['name']}</label>
										</volist>
									</td>
								</tr>
							</if>
					</td>
				</tr>
				</volist>
			</if>
		</volist>
		<tr><td colspan="2"><label><input type="checkbox" id="all"/> 全选</label></td></tr>
		</table>
		<div class="btn">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		</div>
	</form>
<style>
.frame_form td label{
	margin: 5px;
    display: inline-block;
}
</style>
<!--script type="text/javascript">
$(document).ready(function(){
	$('#all').click(function(){
		if ($(this).attr('checked')) {
			$('.father_menu, .child_menu, .s_child_menu').attr('checked', true);
		} else {
			$('.father_menu, .child_menu, .s_child_menu').attr('checked', false);
		}
	});
	$('.father_menu').click(function(){
		var fid = $(this).val();
		
		if ($(this).attr('checked')) {
			$('.child_menu_' + fid).attr('checked', true);
			$('.child_menu_' + fid).each(function(){
				var id = $(this).val();
				$('.s_child_menu_' + id).attr('checked', true);
			});
		} else {
			$('.child_menu_' + fid).attr('checked', false);
			$('.child_menu_' + fid).each(function(){
				var id = $(this).val();
				$('.s_child_menu_' + id).attr('checked', false);
			});
		}
	});
	$('.child_menu').click(function(){
		var fid = $(this).attr('data-fid');
		var id = $(this).val();
		if ($(this).attr('checked')) {
			$('.menu_' + fid).attr('checked', true);
			$('.s_child_menu_' + id).attr('checked', true);
		} else {
			var flag = false;
			$('.child_menu_' + fid).each(function(){
				if ($(this).attr('checked')) {
					flag = true;
				}
			});
			$('.menu_' + fid).attr('checked', flag);
			$('.s_child_menu_' + id).attr('checked', flag);
		}
	});
	
	
	$('.s_child_menu').click(function(){
		var fid= $(this).data('fid');
		var id = $(this).val();

		if($(this).attr('checked')){
			$('.child_menu').each(function(){
				if($(this).val()==fid){
					$(this).attr('checked',true);
					var pid = $(this).data('fid');
					$('.menu_'+pid).attr('checked',true);
				}
			});
		}else{
			var flag = false;
			$('.s_child_menu_' + fid).each(function(){
				if ($(this).attr('checked')) {
					flag = true;
				}
			});
			
			
			$('.child_menu').each(function(){
				if($(this).val()==fid){
					$(this).attr('checked',flag);
					var pid = $(this).data('fid');
					$('.menu_'+pid).attr('checked',flag);
				}
			});
		}
		
	});
});
</script-->

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
</script>
<include file="Public:footer"/>