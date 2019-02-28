<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('aguide_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="guide_id" value="{pigcms{$list.guide_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">导游ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.guide_id}</div></td>
				<th width="15%">用户ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.user_id}</div></td>
			<tr/>
			<tr>
				<th width="15%">省份</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.province_id}</div></td>
				<th width="15%">城市</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.city_id}</div></td>
			<tr/>
			<tr>
				<th width="15%">导游名</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.guide_name}</div></td>
				<th width="15%">昵称</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.guide_nickname}</div></td>
			<tr/>
			<tr>
				<th width="15%">手机号</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.guide_phone}</div></td>
				<th width="15%">从业年限</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.guide_life}</div></td>
			</tr>
			<tr>
				<th width="15%">单价</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.guide_price}</div></td>
				<th width="15%">总评分</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.score_all}</div></td>
			</tr>
			<tr>
				<th width="15%">评分总数</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.reply_count}</div></td>
				<th width="15%">平均分</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.score_mean}</div></td>
			</tr>
			<tr>
				<th width="15%">导游类型</th>
				<td width="35%"><div style="height:24px;line-height:24px;"><if condition="$list['guide_type'] eq 1">咨询服务类<elseif condition="$list['guide_type'] eq 2"/>导游讲解类</if></div></td>
				<th width="15%">入驻时间</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.create_time|date='Y-m-d H:i:s',###}</div></td>
			</tr>
			<tr>
				<th width="15%">更新时间</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.update_time|date='Y-m-d H:i:s',###}</div></td>
				<th width="15%">景内导游</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$list['guide_status'] eq 1">selected</if>"><span>正常</span><input type="radio" name="guide_status" value="1"  <if condition="$list['guide_status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$list['guide_status'] eq 3">selected</if>"><span>关闭</span><input type="radio" name="guide_status" value="3" <if condition="$list['guide_status'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">导游证号</th>
				<td width="85%" colspan="3"><div>{pigcms{$list.guide_card}</div></td>
			</tr>
			<tr>
				<th width="15%">个人签名</th>
				<td width="85%" colspan="3"><div>{pigcms{$list.guide_autograph}</div></td>
			</tr>
			<tr>
				<th width="15%">导游证图片</th>
				<td width="85%" colspan="3"><img src="{pigcms{$list.guide_card_img}" style="width:90px; height: 80px;"></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>