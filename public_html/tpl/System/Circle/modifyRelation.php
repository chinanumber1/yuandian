<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Circle/modifyRelation')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$res.id}"/>
		
		
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$res['status'] eq 0">selected</if>"><span>启用</span><input type="radio" name="status" value="0"  <if condition="$res['status'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$res['status'] eq 1">selected</if>"><span>禁用</span><input type="radio" name="status" value="1"  <if condition="$res['status'] eq 1">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>

		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>