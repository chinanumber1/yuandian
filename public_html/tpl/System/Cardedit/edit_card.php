<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Cardedit/edit_card')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">实体卡卡号：</td>
				<td>
					<label>{pigcms{$physical_card['cardid']}</label>
					<input type="hidden" name="cardid" value="{pigcms{$physical_card['cardid']}">
				</td>
			</tr>
			<tr>
				<td width="80">绑定用户ID：</td>
				<td>
				<if condition="$physical_card['uid'] gt 0"> {pigcms{$physical_card['uid']}<else /> <input type="text" class="input fl" name="uid" value="{pigcms{$physical_card['uid']}" tips="不修改则不填写" autocomplete="off" validate="digits:true" ></if>
				</td>
			</tr>
			<tr>
				<td width="80">绑定商户ID：</td>
				<td>
				<if condition="$physical_card['merid'] gt 0"> {pigcms{$physical_card['merid']}<else /><input type="text" class="input fl" name="merid" value="{pigcms{$physical_card['merid']}" tips="不修改则不填写" autocomplete="off" validate="digits:true" ></if>
				</td>
			</tr>
			<tr>
				<td width="80">余额：</td>
				<td>
				<if condition="$physical_card['balance_money'] gt 0"> {pigcms{$physical_card['balance_money']}<input type="hidden"  name="balance_money" value="{pigcms{$physical_card['balance_money']}" ><else /> <input type="text" class="input fl" name="balance_money" value="{pigcms{$physical_card['balance_money']}" autocomplete="off" validate="digits:true"  ></if>
				</td>
			</tr>
			
			<tr>
				<td width="80">状态：</td>
				<td>
				
				<select name="status">
				<option value="0" <if condition="$physical_card['status'] eq 0">selected="selected" </if>>禁止</option>
				<option value="1" <if condition="$physical_card['status'] eq 1">selected="selected" </if>>正常</option>
				<option value="2" <if condition="$physical_card['status'] eq 2">selected="selected" </if>>未审核</option>
				</select>
				
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
