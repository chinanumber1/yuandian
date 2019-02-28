<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 会员卡编辑</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			a:hover,a:visited{color:#666;}
		</style>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('shop_fitment_color')}" autocomplete="off">
		<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>
		<table>
			<tr>
				<th width="20%">选择主题色</th>
				<td width="80%" colspan="3">
					<select name="shop_fitment_color" id="shop_fitment_color" style="width:150px;">
						<option value="06C1AE" <if condition="$now_store['shop_fitment_color'] eq '06C1AE'">selected="selected"</if>>绿色</option>
						<option value="D32C0C" <if condition="$now_store['shop_fitment_color'] eq 'D32C0C'">selected="selected"</if>>红色</option>
						<option value="F57224" <if condition="$now_store['shop_fitment_color'] eq 'F57224'">selected="selected"</if>>黄色</option>
						<option value="0E6CD8" <if condition="$now_store['shop_fitment_color'] eq '0E6CD8'">selected="selected"</if>>蓝色</option>
						<option value="6F31D7" <if condition="$now_store['shop_fitment_color'] eq '6F31D7'">selected="selected"</if>>紫色</option>
						<option value="C0956A" <if condition="$now_store['shop_fitment_color'] eq 'C0956A'">selected="selected"</if>>棕色</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="20%">颜色预览</th>
				<td width="80%" colspan="3">
					<div id="color_preview" style="width:150px;height:28px;display:inline-block;background:#{pigcms{$now_store.shop_fitment_color|default='06C1AE'};"></div>
				</td>
			</tr>
		</table>
		<div class="btn">
			<button type="submit" id="submit">保存</button>
		</div>
		</form>
		<script>
			$(function(){
				$('#shop_fitment_color').change(function(){
					$('#color_preview').css('background','#' + $(this).val());
					console.log($('#fitment_preview .fitment_header',parent.document));
					$('#fitment_preview .fitment_header',parent.document).css('background-color','#' + $(this).val());
				});
				$('#myform').submit(function(){
					$('#submit').prop('disabled',true).html('保存中...');
					$.post($('#myform').attr('action'),$('#myform').serialize(),function(result){
						if(result.status == 1){
							parent.fitment_header_bgcolor = $('#shop_fitment_color').val();
							parent.layer.alert(result.info,{
								end:function(){
									window.parent.layer.closeAll();
								}
							});
						}else{
							parent.layer.alert(result.info);
							$('#submit').prop('disabled',false).html('保存');
						}
					});
					return false;
				});
			});
		</script>
	</body>
</html>