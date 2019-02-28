<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('save_status')}" enctype="multipart/form-data">
		<input type="hidden" name="guide_id" value="{pigcms{$list.guide_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">编号</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.ride_id}</div></td>
				<th width="15%">用户ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.user_id}</div></td>
			<tr/>
			<tr>
				<th width="15%">司机姓名</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.owner_name}</div></td>
				<th width="15%">司机电话</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.owner_phone}</div></td>
			<tr/>
			<tr>
				<th width="15%">城市</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.area_name}</div></td>
				<th width="15%">出发时间</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.start_time|date='Y-m-d H:i',###}</div></td>
			</tr>
			<tr>
				<th width="15%">出发地</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.departure_place}</div></td>
				<th width="15%">目的地</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.destination}</div></td>
			<tr/>
			<tr>
				<th width="15%">标题</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.ride_title}</div></td>
				<th width="15%">发布天数</th>
				<td width="35%"><div style="height:24px;line-height:24px;"><if condition="$list['ride_date_number'] eq 1">当天<else/>全天</if></div></td>
			</tr>
			<tr>
				<th width="15%">违约时间</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list['cancel_time']}分钟</div></td>
				<th width="15%">违约金</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.penalty}元</div></td>
			</tr>
			<tr>
				<th width="15%">座位数</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list['seat_number']}位</div></td>
				<th width="15%">已坐数量</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.sit_number}位</div></td>
			</tr>
			<tr>
				<th width="15%">剩下座位</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list['remain_number']}位</div></td>
				<th width="15%">单价</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$list.ride_price}元</div></td>
			</tr>
			<tr>
				<th width="15%">状态</th>
				<td width="35%"><div style="height:24px;line-height:24px;">
					<if condition="$list['status'] eq 1">启用
						<elseif condition="$list['status'] eq 2"/>过期
						<elseif condition="$list['status'] eq 3"/>人满
						<elseif condition="$list['status'] eq 4"/>司机停止
						<elseif condition="$list['status'] eq 5"/>司机暂停
						<elseif condition="$list['status'] eq 6"/>后台关闭
					</if>
				</div></td>
				
				<if condition="$list['status'] eq 1 OR $list['status'] eq 6">
				<th width="15%">操作</th>
				<td width="35%">
					<div style="height:24px;line-height:24px;">
						<span class="cb-enable">
							<label class="cb-enable <if condition="$list['status'] eq 1">selected</if> ">
								<span>启用</span>
								<input type="radio" name="status" value="1"  <if condition="$list['status'] eq 1">checked="checked"</if> />
							</label>
						</span>
						<span class="cb-disable">
							<label class="cb-disable <if condition="$list['status'] eq 6">selected</if> ">
								<span>关闭</span>
								<input type="radio" name="status" value="6"  <if condition="$list['status'] eq 6">checked="checked"</if> />
							</label>
						</span>
					</div>
				</td>
				</if>

			</tr>


		<input type="hidden" name="ride_id" value="{pigcms{$list.ride_id}"/>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>