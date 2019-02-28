<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('scenic_add')}" frame="true" refresh="true" autocomplete="off">
		<input type="hidden" name="company_id" value="{pigcms{$company.company_id}"/>
		<input type="hidden" name="company_name" value="{pigcms{$company.company_name}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">景点ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">添加后自动生成</div></td>
				<th width="15%">公司ID</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$company.company_id}</div></td>
			<tr/>
			<tr>
				<th width="15%">景点名</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_title" size="20" validate="maxlength:50,required:true"/></td>
				<th width="15%">公司名</th>
				<td width="35%"><div style="height:24px;line-height:24px;">{pigcms{$company.company_name}</div></td>
			<tr/>
			<tr>
				<th width="15%">帐号</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_account" size="20" validate="maxlength:50,required:true"/></td>
				<th width="15%">管理员</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_name" size="20" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="15%">密码</th>
				<td width="35%"><input type="password" class="input fl" name="scenic_pwd" size="20" autocomplete="off" validate="required:true"/></td>
				<th width="15%">管理员手机</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_phone" size="20" autocomplete="off" validate="mobile:true,required:true"/></td>
			</tr>
			<tr>
				<th width="15%">微信号</th>
				<td width="35%"><input type="text" class="input fl" name="scenic_wchant" size="20" validate="maxlength:100"/></td>
				<th width="15%">景点星级</th>
				<td width="35%"><input type="text" class="input fl" name="level" size="20" validate="maxlength:11,required:true"/></td>
			</tr>
			<tr>
				<th width="15%">景点状态</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="scenic_status" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="scenic_status" value="2"/></label></span>
				</td>
				<th width="15%">车位</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="is_parking" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="is_parking" value="2"/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">开启地图</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="panorama_map" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="panorama_map" value="2"/></label></span>
				</td>
				<th width="15%">景内导游</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="is_guide" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="is_guide" value="2"/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">热门景区</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="is_hot" value="1"/></label></span>
					<span class="cb-disable"><label class="cb-disable selected"><span>否</span><input type="radio" name="is_hot" value="2" checked="checked"/></label></span>
				</td>
			</tr>
			<tr>
				<th width="15%">所在区域</th>
				<td width="85%" colspan="3" id="choose_cityareas" province_ids="" city_ids="" area_ids=""></td>
			</tr>
			<tr>
				<th width="15%">详细地址</th>
				<td width="85%" colspan="3"><input type="text" class="input fl" name="scenic_address" size="20" validate="maxlength:100,required:true"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>