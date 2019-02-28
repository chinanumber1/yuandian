<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	
	<style>

.chk_1 { 
    display: none; 
} 

.chk_1 + label { 
    /*background-color: #FFF; 
    border: 1px solid #C1CACA; 
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05); 
    padding: 9px; 
    border-radius: 5px; 
    display: inline-block; 
    position: relative; 
    margin-right: 30px; */
} 
.chk_1 + label:active { 
    box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1); 
} 
 
.chk_1:checked + label { 
   /* background-color: #ECF2F7; 
    border: 1px solid #92A1AC; 
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1); 
    color: #243441; */
} 
 
.chk_1:checked + label:after { 
    content: '\2714'; //勾选符号 
    position: absolute; 
    top: 0px; 
    left: 0px; 
    color: #758794; 
    width: 100%; 
    text-align: center; 
    font-size: 1.4em; 
    padding: 1px 0 0 0; 
    vertical-align: text-top; 
} 
 
</style>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/savemenu')}" frame="true" refresh="true">
		<input type="hidden" name="mer_id" value="{pigcms{$merchant.mer_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<volist name="menus" id="rowset">
			<tr>
				<th width="20%" style=" font-weight:bold; font-size:14px">
					<label><!--input type="checkbox" class="menu_{pigcms{$rowset['id']} father_menu" value="{pigcms{$rowset['id']}" name="menus[]" <if condition="in_array($rowset['id'], $merchant['menus'])">checked</if>/-->&nbsp;{pigcms{$rowset['name']}</label>
				</th>
			</tr>
			<if condition="$rowset['menu_list']">
				<volist name="rowset['menu_list']" id="row">
				
				<tr style="font-weight:bold;">
					<td>
							<label <if condition="!in_array($row['id'], $menu_group)">style="color:#ccc"</if>>
							
							<input type="checkbox" class="menu_{pigcms{$row['id']} father_menu chk_1" id="menu_{pigcms{$row['id']}" value="{pigcms{$row['id']}"  name="menus[]" data-fid="{pigcms{$row['fid']}"  <if condition="in_array($row['id'], $menu_group)"> checked</if>   disabled/>
							
							
							 <if condition="in_array($row['id'], $menu_group)">
								<label for="menu_{pigcms{$row['id']}"></label> 
							</if>
							
							{pigcms{$row['name']}
							</label>
							<if condition='$row["menu_list"]'>
							
								<tr style=" background:#fff; ">
									<td style=" padding-left:35px;">
										<volist name="row['menu_list']" id="srow">
											
												<label <if condition="!in_array($srow['id'], $menu_group)">style="color:#ccc"</if>><input type="checkbox" class="child_menu_{pigcms{$srow['fid']} child_menu chk_1" id="menu_{pigcms{$srow['id']}" value="{pigcms{$srow['id']}"  name="menus[]" data-fid="{pigcms{$srow['fid']}" <if condition="in_array($srow['id'], $menu_group)"> checked</if>  disabled/>&nbsp;
											
											
												<if condition="in_array($srow['id'], $menu_group)">
													<label for="menu_{pigcms{$srow['id']}"></label> 
												</if>
												
												 {pigcms{$srow['name']}
												</label>
										</volist>
									</td>
								</tr>
							</if>
											
							
					</td>
				</tr>
				</volist>
			</if>
		</volist>
		
		</table>
		
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
</script>
</body>
</html>