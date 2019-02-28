<include file="Public:header"/>
	<form id="add" method="post" action="{pigcms{:U('add')}" enctype="multipart/form-data" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">帐号</th>
				<td><input type="text" class="input fl" name="account" id="account" size="25" placeholder="请输入帐号" validate="maxlength:30,required:true"/><em for="account" generated="true" class="error tips">必填项</em></td>
			</tr>
			<tr>
				<th width="80">密码</th>
				<td><input type="text" class="input fl" name="pwd" id="pwd" size="25" placeholder="请输入密码" validate="maxlength:30,required:true"/><em for="pwd" generated="true" class="error tips">必填项</em></td>
			</tr>
			<tr>
				<th width="80">公司名</th>
				<td><input type="text" class="input fl" name="company_name" id="company_name" size="25" placeholder="请输入公司名" validate="maxlength:30,required:true"/><em for="company_name" generated="true" class="error tips">必填项</em></td>
			</tr>
			<tr>
				<th width="80">手机号</th>
				<td><input type="text" class="input fl" name="company_phone" id="company_phone" size="25" placeholder="请输入手机" validate="maxlength:30,required:true"/><em for="company_phone" generated="true" class="error tips">必填项</em></td>
			</tr>
			<tr>
				<th width="80">地址</th>
				<td><input type="text" class="input fl" name="company_address" id="company_address" size="25" placeholder="请输入地址" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
				<th width="80">邮箱</th>
				<td><input type="text" class="input fl" name="company_email" id="company_email" size="25" placeholder="请输入邮箱" validate="maxlength:30,required:true"/></td>
			</tr>
			<tr>
			<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>未用</span><input type="radio" name="status" value="4" /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>