<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('scenic_edit')}" frame="true" refresh="true" autocomplete="off">
		<input type="hidden" name="scenic_id" value="{pigcms{$find.scenic_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">景点ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$find.scenic_id}</div></td>
				<th width="15%">商家ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$find.company_id}</div></td>
			<tr/>
			<tr>
				<th width="15%">景点名</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_title" size="20" validate="maxlength:50,required:true" value="{pigcms{$find.scenic_title}"/></td>
				<th width="15%">商家名</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$company.name}</div></td>
			<tr/>
			<tr>
				<th width="15%">管理员</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_name" size="20" validate="maxlength:20,required:true" value="{pigcms{$find.scenic_name}"/></td>
				<th width="15%">管理员手机</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_phone" size="20" value="{pigcms{$find.scenic_phone}" autocomplete="off" validate="mobile:true"/></td>
			</tr>
			<tr>
				<th width="15%">景点星级</th>
				<td width="35%"><input type="text" class="input fl" name="level" size="20" validate="maxlength:11" value="{pigcms{$find.level}"/></td>
			</tr>
			<tr>
				<th width="15%">景点状态</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$find['scenic_status'] eq 1">selected</if>"><span>开启</span><input type="radio" name="scenic_status" value="1"  <if condition="$find['scenic_status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$find['scenic_status'] eq 2">selected</if>"><span>关闭</span><input type="radio" name="scenic_status" value="2" <if condition="$find['scenic_status'] eq 2">checked="checked"</if>/></label></span>
				</td>
				<th width="15%">车位</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$find['is_parking'] eq 1">selected</if>"><span>开启</span><input type="radio" name="is_parking" value="1"  <if condition="$find['is_parking'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$find['is_parking'] eq 2">selected</if>"><span>关闭</span><input type="radio" name="is_parking" value="2" <if condition="$find['is_parking'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">开启地图</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$find['panorama_map'] eq 1">selected</if>"><span>开启</span><input type="radio" name="panorama_map" value="1"  <if condition="$find['panorama_map'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$find['panorama_map'] eq 2">selected</if>"><span>关闭</span><input type="radio" name="panorama_map" value="2" <if condition="$find['panorama_map'] eq 2">checked="checked"</if>/></label></span>
				</td>
				<th width="15%">景内导游</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$find['is_guide'] eq 1">selected</if>"><span>开启</span><input type="radio" name="is_guide" value="1"  <if condition="$find['is_guide'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$find['is_guide'] eq 2">selected</if>"><span>关闭</span><input type="radio" name="is_guide" value="2" <if condition="$find['is_guide'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">热门景区</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$find['is_hot'] eq 1">selected</if>"><span>是</span><input type="radio" name="is_hot" value="1"  <if condition="$find['is_hot'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$find['is_hot'] eq 2">selected</if>"><span>否</span><input type="radio" name="is_hot" value="2" <if condition="$find['is_hot'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">佣金比例</th>
				<td width="35%"><input type="text" class="input fl" name="spread_rate" size="20" value="{pigcms{$find.spread_rate}" tips="填写5，代表每单平台提成5%" autocomplete="off"/></td>
				<th width="15%">余额</th>
				<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">￥{pigcms{$find.now_money|floatval=###}</td>
			</tr>
			<tr>
				<th width="15%">所在区域</th>
				<td width="85%" colspan="3" id="choose_cityareass" province_ids="{pigcms{$find.province_id}" city_ids="{pigcms{$find.city_id}" area_ids="{pigcms{$find.area_id}"></td>
			</tr>
			<tr>
				<th width="15%">详细地址</th>
				<td width="85%" colspan="3"><input type="text" class="input fl" name="scenic_address" size="20" validate="maxlength:100,required:true" value="{pigcms{$find.scenic_address}"/></td>
			</tr>
			<!--<tr>
				<th width="15%">记录表</th>
				<td width="85%" colspan="3">
					<div style="height:30px;line-height:24px;">
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('money_list',array("scenic_id"=>$find["scenic_id"]))}','余额记录列表',680,560,true,false,false,null,'money_list',true);">余额记录</a>
					</div>
				</td>
			</tr>-->
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>