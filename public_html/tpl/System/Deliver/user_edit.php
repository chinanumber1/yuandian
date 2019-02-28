<include file="Public:header"/>
<style>

</style>
	<form id="myform" method="post" action="{pigcms{:U('Deliver/user_edit')}" frame="true" refresh="true">
		<input type="hidden" name="uid" value="{pigcms{$now_user.uid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="15%">姓名</th>
				<td width="35%"><input type="text" class="input fl" name="name" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.name}"/></td>
				<th width="15%">手机号</th>
				<td width="35%">
				<if condition="$config.international_phone eq 1">
					<select name="phone_country_type" id="phone_country_type" style="float:left;margin-right:5px;">
					<option value="86" <if condition="$now_user.phone_country_type eq 86">selected</if>>+86 中国 China</option>
					<option value="1" <if condition="$now_user.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
					</select>
				</if>
				<input type="text" class="input fl" name="phone" size="20" validate="mobile:true,required:true" value="{pigcms{$now_user.phone}"/></td>
			</tr>
			<tr>
				<th width="15%">密码</th>
				<td width="35%"><input type="text" class="input fl" name="pwd" size="20" value="" tips="不修改则不填写" /></td>
				<th width="15%">状态</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_user['status'] eq 1">selected</if>"><span>正常</span><input type="radio" name="status" value="1"  <if condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_user['status'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="status" value="0"  <if condition="$now_user['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
            <tr>
                <th width="15%">是否开启扔回功能</th>
                <td width="35%" class="radio_box">
                    <span class="cb-enable"><label class="cb-enable <if condition="$now_user['is_cancel_order'] eq 1">selected</if>"><span>开启</span><input type="radio" name="is_cancel_order" value="1"  <if condition="$now_user['is_cancel_order'] eq 1">checked="checked"</if>/></label></span>
                    <span class="cb-disable"><label class="cb-disable <if condition="$now_user['is_cancel_order'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="is_cancel_order" value="0"  <if condition="$now_user['is_cancel_order'] eq 0">checked="checked"</if>/></label></span>
                </td>
            </tr>
			<tr>
				<th width="15%">配送员手中待配送完成的订单数小于多少单后才可继续抢单</th>
				<td width="35%"><input type="number" class="input fl" name="max_num" size="20" value="{pigcms{$now_user.max_num}" tips="只可填写大于等于0的整数，0表示不限制" /></td>
                <th width="15%">排序</th>
                <td width="35%"><input type="text" class="input fl" name="sort" size="20" value="{pigcms{$now_user.sort}" tips="正整数，数字越大排在越前" /></td>
			</tr>
			<tr>
			 	<th width="15%">所在地</th>
				<td id="choose_cityarea" colspan=3  province_id="{pigcms{$now_user.province_id}" city_id="{pigcms{$now_user.city_id}" area_id="{pigcms{$now_user.area_id}" circle_id="{pigcms{$now_user.circle_id}"></td>
			<tr>
			<tr>
				<th width="15%">配送范围</th>
				<td>
                    <input type="hidden" id="delivery_range_polygon" name="delivery_range_polygon" value="{pigcms{$now_user.delivery_range_polygon}">
                    <select id="delivery_range_type" name="delivery_range_type" >
                    <option value="0" <if condition="$now_user['delivery_range_type'] eq 0">selected</if>>半径范围</option>
                    <option value="1" <if condition="$now_user['delivery_range_type'] eq 1">selected</if>>自定义范围</option>
                    </select>
                </td>
                <th width="15%" class="range" <if condition="$now_user['delivery_range_type'] eq 1">style="display:none"</if>>半径距离</th>
                <td class="range" <if condition="$now_user['delivery_range_type'] eq 1">style="display:none"</if>>
                    <input type="text" class="input fl" name="range" size="20" validate="required:true" value="{pigcms{$now_user.range}"/>公里
                </td>
			</tr>
			<tr>
				<th width="15%">配送员常驻地区</th>
				<td width="35%"><input type="text" class="input fl" readonly="readonly" name="adress" id="adress" validate="required:true" value="{pigcms{$now_user.site}"/></td>
				<th width="15%">配送员经纬度</th>
				<td width="35%" class="radio_box"><input class="input fl" size="20" name="long_lat" id="long_lat" type="text" readonly="readonly" validate="required:true" value="{pigcms{$now_user.lng},{pigcms{$now_user.lat}"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<div class="modal-content" style="width:100%;">
		<div class="modal-header no-padding" style="width:100%;">
			<div class="table-header" style="padding-left:10px;">拖动红色图标，经纬度框内将自动填充经纬度。</div>
		</div>
		<div class="modal-body no-padding" style="width:100%;">
			<form id="map-search" style="margin:10px;">
				<input id="map-keyword" type="textbox" style="width:300px;border:1px solid #ccc;height:24px;line-height:24px;padding-left:6px;" placeholder="尽量填写城市、区域、街道名" value="{pigcms{$now_user.site}"/>
				<input type="submit" value="搜索" class="button" style="margin-left:0px;"/>
			</form>
			<div style="width:750px;height:400px;margin-left:10px;" id="cmmap"></div>
		</div>
	</div>
	<script type="text/javascript">
	var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",merchant_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}";
	</script>
<if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places,drawing&key={pigcms{$config.google_map_ak}"></script>
    <script type="text/javascript">var polygonMap = 1;</script>
    <script type="text/javascript" src="{pigcms{$static_path}js/map_google.js?t=1111"></script>
    <else />
	<!--<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>-->
	<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
    <script type="text/javascript" src="https://api.map.baidu.com/library/DrawingManager/1.4/src/DrawingManager_min.js"></script>
    <script type="text/javascript">var polygonMap = 1;</script>
    <script type="text/javascript" src="{pigcms{$static_path}js/map.js?t=2"></script>
</if>
<include file="Public:footer"/>