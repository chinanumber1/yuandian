<include file="Public:header"/>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr style="font-size: 15px; color: #333;">
			<td width="120">留言时间</td>
			<td width="100">留言人</td>
			<td> 内容 </td>
		</tr>
		<if condition="is_array($orderList)">
			<volist name="orderList" id="vo">
				<tr>
					<td width="120">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</td>
					<td width="100">{pigcms{$vo.nickname}</td>
					<td>{pigcms{$vo.message}</td>
				</tr>
			</volist>
		<else/>
			<tr ><td colspan="3" style="text-align: center; color: red; font-size: 12px;">列表为空！</td></tr>
		</if>
		
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="审核" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>

<include file="Public:footer"/>
