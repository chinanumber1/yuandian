<include file="Public:header"/>
	<style>
		.frame_form td{line-height:24px;}
	</style>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="15%">提现编号</th>
			<td colspan="3" width="85%">{pigcms{$withdraw.id}</td>
		</tr>
		
		<tr>
			<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息</b></td>
		</tr>
		<tr>
			<th width="15%">提现金额</th>
			<td width="85%" colspan="3">{pigcms{$withdraw['money']/100}元</td>
		
		</tr>
		<tr>
			<th width="15%">订单状态</th>
			<td width="85%" colspan="3">
				<if condition="$withdraw['status'] eq 0">
					<font color="red">未审核</font>
				<elseif condition="$withdraw['status'] eq 1" />
					<font color="green">通过审核</font>
				<elseif condition="$withdraw['status'] eq 2" />
					<font color="red">被驳回</font>
				</if>
			</td>
		</tr>
		
	
		<tr>
			<th width="15%">提现时间</th>
			<td width="85%" colspan="3">{pigcms{$withdraw.withdraw_time|date='Y-m-d H:i:s',###}</td>
		
		</tr>
		
		<tr>
			<th width="15%"><if condition="$withdraw['status'] eq 2">驳回理由<else />备注</if></td>
			<td width="85%" colspan="3" <if condition="$withdraw['status'] eq 2">style="color:red;"</if>>{pigcms{$withdraw.remark}</td>
		</tr>
		
		<tr>
			<th width="15%">景区ID</th>
			<td width="35%">{pigcms{$now_merchant.mer_id}</td>
			<th width="15%">景区名称</th>
			<td width="35%">{pigcms{$now_merchant.name}</td>
		</tr>
		<tr>
			<th width="15%">景区手机号</th>
			<td width="85%" colspan="3">{pigcms{$now_merchant.phone}</td>
			
		</tr>
		
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
<include file="Public:footer"/