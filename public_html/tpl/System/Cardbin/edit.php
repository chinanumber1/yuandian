<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('amend')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$nowBank.id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">银行名称</th>
				<td><input type="text" class="input fl" name="bankname" size="30" placeholder="请输入银行名称" validate="maxlength:50,required:true" value="{pigcms{$nowBank.bankname}"/></td>
			</tr>
			<tr>
				<th width="80">银行卡名称</th>
				<td><input type="text" class="input fl" name="cardname" size="30" placeholder="请输入银行卡名称" validate="maxlength:50,required:true" value="{pigcms{$nowBank.cardname}"/></td>
			</tr>
			<tr>
				<th width="80">银行卡BIN</th>
				<td><input type="text" class="input fl" name="cardbin" size="30" placeholder="请输入银行卡BIN" validate="maxlength:6,required:true,number:true" value="{pigcms{$nowBank.cardbin}"/></td>
			</tr>
			<tr>
				<th width="80">银行卡类型</th>
				<td>
					<select name="cardtype">
						<option value="1" <if condition="$nowBank['cardtype'] eq 1">selected="selected"</if>>储蓄卡</option>
						<option value="2" <if condition="$nowBank['cardtype'] eq 2">selected="selected"</if>>信用卡</option>
					</select>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>