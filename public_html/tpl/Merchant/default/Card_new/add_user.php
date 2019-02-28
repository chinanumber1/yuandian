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
	<form id="myform" method="post" action="{pigcms{:U('Card_new/add_user')}" frame="true" refresh="true" autocomplete="off">
		<table>
			<tr> 
				<th width="20%">实体卡号</th>
				<td width="80%" colspan="3"><input type="text" class="input"  name="physical_id" value="{pigcms{$card.physical_id}"></td>
			</tr>
			<if condition="$config.merchant_card_recharge_offline eq 1">
			<tr>
				<th width="20%">会员卡余额初始值</th>
				<td width="80%" colspan="3"><input type="text" class="input" name="card_money_give" size="10" validate="number:true" tips="会员卡余额初始值"/></td>
			</tr>
			</if>
			<tr>
				<th width="20%">会员卡{pigcms{$config['score_name']}初始值</th>
				<td width="80%" colspan="3"><input type="text" class="input" name="card_score" size="10" validate="number:true" tips="会员卡{pigcms{$config['score_name']}初始值"/></td>
			</tr>
			<tr>
				<th width="20%">状态</th>
				<td width="80%" colspan="3">
					<select name="status">
						<option value="1" selected="selected">正常</option>
						<option value="0">禁止</option>
					</select>
				</td>
			</tr>
			
			
		</table>
		
	
		<div class="btn">
			<button type="submit">确定</button>
			<button type="reset">取消</button>
		</div>
		</form>
		<script>
			$(function(){
				$('.handle_btn').live('click',function(){
					art.dialog.open($(this).attr('href'),{
						init: function(){
							var iframe = this.iframe.contentWindow;
							window.top.art.dialog.data('iframe_handle',iframe);
						},
						id: 'handle',
						title:'编辑',
						padding: 0,
						width: 720,
						height: 520,
						lock: true,
						resize: false,
						background:'black',
						button: null,
						fixed: false,
						close: null,
						left: '50%',
						top: '38.2%',
						opacity:'0.4'
					});
					return false;
				});
				
				$('#group_id').change(function(){
					$('#frmselect').submit();
				});
			});
		</script>
	</body>
</html>