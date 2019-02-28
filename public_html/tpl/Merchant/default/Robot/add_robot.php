<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 添加机器人</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
		<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
		<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
		<style>
			a:hover,a:visited{color:#666;}
		</style>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('add_robot')}"  refresh="true" autocomplete="off">
		<table>
			<tr> 
				<th width="20%">名称</th>
				<td width="80%" colspan="3"><input type="text" class="input robot_name"  name="robot_name"  readonly>
				<button type="button" onclick="rand_name()">随机姓名</button>
				</td>
			</tr>
		
			<tr>
				<th width="20%">头像</th>
				<td width="80%" colspan="2"><input type="text" class="input avator" name="avatar"  readonly />
				<a href="javascript:void(0)" class="btn" id="J_selectImage"><button type="button">上传图片</button></a>
				</td>
			</tr>
		</table>
		<div class="btn">
			<button type="submit">确定</button>
			
		</div>
	</form>
		<script>
			KindEditor.ready(function(K){
				var site_url = "{pigcms{$config.site_url}";
				var editor = K.editor({
					allowFileManager : true
				});
				$('#J_selectImage').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic',array('path'=>'robot_avator'))}";
					editor.loadPlugin('image', function(){
						editor.plugin.imageDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.avator').val(site_url+url);
								upload_file_btn.siblings('img').remove();
								
								upload_file_btn.siblings('.avator').after('<img style="width:40px;height:40px;margin-left: 12px;vertical-align: middle;" src="'+site_url+url+'">')
								editor.hideDialog();
							}
						});
					});
				});

			});
			
			function rand_name(){
				$.post('{pigcms{:U('ajax_get_user_name')}', '', function(data, textStatus, xhr) {
					data = eval('('+data+')')
					$('.robot_name').val(data.name);
				});
			}
		</script>
	</body>
</html>