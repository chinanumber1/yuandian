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
	<form id="myform" method="post" action="{pigcms{:U('Card_new/card_detail')}" frame="true" refresh="true" autocomplete="off" onsubmit="return false;" >
		<table>
			<tr>
				<th width="15%">会员名称</th>
				<td width="18%">{pigcms{$card.nickname}</td>
				<th width="15%">会员卡卡号</th>
				<td width="18%">{pigcms{$card.id}</td>
				<th width="15%">会员手机号</th>
				<td width="18%">{pigcms{$card.phone}</td>
				<input type="hidden" name="id" value="{pigcms{$card.id}">
				<input type="hidden" name="uid" value="{pigcms{$card.uid}">
			</tr>
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>会员卡信息</b></td>
			</tr>
				
			<tr>
				<th width="15%">实体卡号</th>
				<td width="85%" colspan="3"><input type="text" class="input"  name="physical_id" value="{pigcms{$card.physical_id}"></td>
			</tr>
			<tr>
				<th width="15%">会员卡领取时间</th>
				<td width="85%" colspan="3"><if condition="$card.add_time neq 0">{pigcms{$card.add_time|date="Y-m-d H:i:s",###}</if></td>
			</tr>
			<if condition="$config.merchant_card_recharge_offline eq 1">
			<tr>
				<th width="15%">余额</th>
				<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">现在余额：￥{pigcms{$card['card_money']+$card['card_money_give']|floatval=###} &nbsp;&nbsp;&nbsp;&nbsp;<select name="set_money_type"><option value="1">增加</option><option value="2">减少</option></select>&nbsp;&nbsp;<input type="text" class="input" name="set_money" size="10" validate="number:true" tips="此处填写增加或减少的额度，不是将余额变为此处填写的值"/></div>(<font color="red">会员卡赠送余额：{pigcms{$card.card_money_give} 元</font>)</td>
			</tr>
		
			</if>
			<tr>
				<th width="15%">{pigcms{$config['score_name']}</th>
				<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">现在{pigcms{$config['score_name']}：{pigcms{$card.card_score} &nbsp;&nbsp;&nbsp;&nbsp;<select name="set_score_type"><option value="1">增加</option><option value="2">减少</option></select>&nbsp;&nbsp;<input type="text" class="input" name="set_score" size="10" validate="number:true" tips="此处填写增加或减少的{pigcms{$config['score_name']}，不是将{pigcms{$config['score_name']}变为此处填写的值"/></div></td>
			</tr>
			<if condition="$card_group">
				<tr>
					<th width="15%">用户分组</th>
					<td width="85%" colspan="3">
						<select name="gid">
							<volist name="card_group" id="vo">
							<option value="{pigcms{$vo.id}" <if condition="$card['gid'] eq $vo['id']">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
						</select>
					</td>
				</tr>
			</if>
			<tr>
				<th width="15%">状态</th>
				<td width="85%" colspan="3">
					<select name="status"><option value="1" <if condition="$card.status eq 1">selected="selected"</if>>正常</option><option value="0" <if condition="$card.status eq 0">selected="selected"</if>>禁止</option></select>
				</td>
			</tr>
			<tr>
				<th width="15%">记录表</th>
				<td width="85%" colspan="3">
					<div style="height:30px;line-height:24px;">
						<a href="{pigcms{:U('Card_new/consume_record',array('id'=>$card['id']))}" >消费记录</a>
						
					</div>
				</td>
			</tr>
			
		</table>
		
	
		<div class="btn">
			<button id="submit" type="submit">确定</button>
			<button id="reset" type="reset">取消</button>
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
						width: 800,
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
				$('#submit').click(function(){
					$.ajax({
						url: '{pigcms{:U('Card_new/card_detail')}',
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