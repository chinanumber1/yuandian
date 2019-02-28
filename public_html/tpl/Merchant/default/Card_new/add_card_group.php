<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 会员卡分组</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			a:hover,a:visited{color:#666;}
		</style>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Card_new/add_card_group')}" frame="true" refresh="true" autocomplete="off" onsubmit="return false;" >
		<table>
			<tr>
				<th width="15%">分组名称</th>
				<td width="85%" colspan="3"><input type="text" class="input" name="name" value="{pigcms{$now_group.name}"/></td>
				<input type="hidden" name="gid" value="{pigcms{$now_group.id}">
			</tr>
			<tr>
				<th width="15%">分组注释</th>
				<td width="85%" colspan="3"><input type="text" class="input" name="des" value="{pigcms{$now_group.des}"/></td>
				
			</tr>
		</table>
		
	
		<div class="btn">
			<button id="submit" type="submit">确定</button>
			<button id="reset" type="reset">取消</button>
		</div>
		</form>
		<script>
			$(function(){
				
				
				$('#group_id').change(function(){
					$('#frmselect').submit();
				});
				$('#submit').click(function(){
					$.ajax({
						url: '{pigcms{:U('Card_new/add_card_group')}',
						type: 'POST',
						dataType: 'json',
						data: $('#myform').serialize(),
						success:function(date){
							if(date.status){
								alert(date.info);
								parent.location.reload();   
							}else{
								alert(date.info);
							}
						}
					});
				});
				
				$('#reset').click(function(){
				 parent.location.reload();   

				});
			});
		</script>
	</body>
</html>