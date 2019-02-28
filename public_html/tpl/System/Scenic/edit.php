<include file="Public:header"/>
	<form id="add" method="post" action="{pigcms{:U('edit')}" enctype="multipart/form-data" refresh="true">
		<input type="hidden" name="company_id" value="{pigcms{$find.company_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">帐号</th>
				<td><input type="text" class="input fl" name="account" id="account" size="25" value="{pigcms{$find.account}" placeholder="请输入帐号" validate="maxlength:30,required:true"/><em for="account" generated="true" class="error tips">不允许更改</em></td>
			</tr>
			<tr>
				<th width="80">密码</th>
				<td><input type="text" class="input fl" name="pwd" id="pwd" size="25" placeholder="请输入密码" validate="maxlength:30,required:true"/><em for="pwd" generated="true" class="error tips">必填项</em></td>
			</tr>
			<tr>
				<th width="80">公司名</th>
				<td><input type="text" class="input fl" name="company_name" id="company_name" value="{pigcms{$find.company_name}" size="25" placeholder="请输入公司名" validate="maxlength:30,required:true"/><em for="company_name" generated="true" class="error tips">不允许更改</em></td>
			</tr>
			<tr>
				<th width="80">手机号</th>
				<td><input type="text" class="input fl" name="company_phone" id="company_phone" value="{pigcms{$find.company_phone}" size="25" placeholder="请输入手机" validate="maxlength:30,required:true"/><em for="company_phone" generated="true" class="error tips">必填项</em></td>
			</tr>
			<tr>
				<th width="80">地址</th>
				<td><input type="text" class="input fl" name="company_address" id="company_address" value="{pigcms{$find.company_address}" size="25" placeholder="请输入地址" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
				<th width="80">邮箱</th>
				<td><input type="text" class="input fl" name="company_email" id="company_email" size="25" value="{pigcms{$find.company_email}" placeholder="请输入邮箱" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
			<tr>
				<td width="80">状态：</td>
				<td><select name="status">
				<option value="1" <if condition="$find['status'] eq 1">selected="selected" </if>>审核通过</option>
				<option value="3" <if condition="$find['status'] eq 3">selected="selected" </if>>审核不通过</option>
				<option value="4" <if condition="$find['status'] eq 4">selected="selected" </if>>禁止登陆</option>
				</select></td>
			</tr>
			<tr>
				<th width="80">审核备注</th>
				<td><textarea style="width:200px;height:100px;" type="text" class="input fl" name="remarks" id="remarks" size="25" placeholder="请输入审核备注">{pigcms{$list.remarks}</textarea></td></tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>