<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('aguide_verify')}" enctype="multipart/form-data" refresh="true">
		<input type="hidden" name="guide_id" value="{pigcms{$list.guide_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">向导ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.guide_id}</div></td>
			</tr>
			<tr>
				<th width="15%">状态</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable selected"><span>审核通过</span><input type="radio" name="guide_status" value="1"  checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>审核不通过</span><input type="radio" name="guide_status" value="4"/></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">审核备注</th>
				<td><textarea style="width:200px;height:100px;" type="text" class="input fl" name="guide_remarks" id="guide_remarks" size="25" placeholder="请输入审核备注"></textarea></td></tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>