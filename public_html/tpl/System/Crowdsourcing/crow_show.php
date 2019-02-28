<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('aguide_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="guide_id" value="{pigcms{$list.guide_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">编号</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.package_id}</div></td>
				<th width="15%">用户ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.user_id}</div></td>
			<tr/>
			<tr>
				<th width="15%">联系人</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.user_name}</div></td>
				<th width="15%">联系电话</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.user_phone}</div></td>
			<tr/>
			<tr>
				<th width="15%">出发地</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.package_start}</div></td>
				<th width="15%">目的地</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.package_end}</div></td>
			<tr/>
			<tr>
				<th width="15%">物品名</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.package_title}</div></td>
				<th width="15%">实名认证</th>
				<td width="35%"><div style="height:24px;line-height:24px;"><if condition="$list['is_authentication'] eq 1">需认证<else/>不需认证</if></div></td>
			</tr>
			<tr>
				<th width="15%">运费</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.package_money}</div></td>
				<th width="15%">押金</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.package_deposit}</div></td>
			</tr>
			<tr>
				<th width="15%">车型</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list['category_name']}</div></td>
				<th width="15%">状态</th>
				<td width="35%"><div style="height:24px;line-height:24px;"><if condition="$list['package_status'] eq 1">启用<elseif condition="$list['package_status'] eq 2"/>关闭<else/>进行中</if></div></td>
			</tr>
			<tr>
				<th width="15%">描述</th>
				<td width="85%" colspan="3"><div>{pigcms{$list.package_remarks}</div></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>