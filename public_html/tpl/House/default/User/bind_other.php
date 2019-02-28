<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 社区管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
    <style type="text/css">
    .green{ color:green}
    </style>
	<body>
		<form id="myform" action="{pigcms{:U('bind_other')}" method='post'>
			<input type="hidden" name="pigcms_id" value="{pigcms{$_GET['pigcms_id']}" >
			<input type="hidden" name="edit" value="{pigcms{$_GET['edit']}" >
			<table>
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>绑定家属/租客</b></td>
				</tr>
				<tr>
					<th width="15%">姓名</th>
					<td width="85%">
						<input type="text" name="name" value="{pigcms{$bind_info.name}" style="    line-height: 20px;" >
						
					</td>
				</tr>
				<tr>
					<th width="15%">电话</th>
					<td width="85%">
						<input type="text" name="phone" value="{pigcms{$bind_info.phone}" style="    line-height: 20px;" placeholder="手机号码">
					<font color="red">*不能填写和业主相同的号码</font>
					</td>
				</tr>
				
				<tr>
					<th width="15%">关系</th>
					<td width="85%">
						<label style="padding-left:0px;padding-right:20px;">
							<input type="radio" class="ace" value="1" name="type" <if condition="$bind_info.type eq 1">checked="checked"</if>>
							<span style="z-index: 1" class="lbl">家属</span>
						</label>
						<label style="padding-left:0px;">
							<input type="radio" class="ace" value="2" name="type" <if condition="$bind_info.type eq 2">checked="checked"</if>>
							<span style="z-index: 1" class="lbl">租客</span>
						</label>
					</td>
				</tr>
				
				<tr>
					<th width="15%">备注</th>
					<td width="85%">
						<textarea name="memo" placeholder="备注">{pigcms{$bind_info.memo}</textarea>
					</td>
				</tr>

				<tr>
					<td colspan="4"><button class="chk_express" style=" margin:0 auto; display:block;float:right" <if condition="!in_array(116,$house_session['menus']) && !in_array(104,$house_session['menus'])">disabled="disabled"</if>>保存</button></td>
				</tr>
			  
			 
			</table>
		</form>
	</body>
	<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
</html>