<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Cardedit/add_card')}" frame="true" refresh="true">
		<input type="hidden" name="lid" value="{pigcms{$leveldata['id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">前缀：</td>
				<td>
					<input type="text" class="input fl" name="head_number" value=""  autocomplete="off" validate="required:true,digits:true">
				</td>
			</tr>
			<tr>
				<td width="80">卡号范围：</td>
				<td>
				<input type="text" class="input fl" name="start_number" value=""  autocomplete="off" validate="required:true,digits:true">
				</td>
				<td>
				<input type="text" class="input fl" name="end_number" value=""  autocomplete="off" validate="required:true,digits:true">&nbsp;&nbsp;&nbsp;
				<span class="red">如：0-99999</span>
				</td>
			</tr>
			<tr>
				<td width="80">绑定商家ID</td>
				<td>
				<input type="text" class="input fl" name="merid" value=""  autocomplete="off" validate="digits:true">
				</td>
			</tr>
			<tr>
				<td width="80">默认余额：</td>
				<td>
				<input type="text" class="input fl" name="default_money" value=""  autocomplete="off" validate="digits:true">
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>

