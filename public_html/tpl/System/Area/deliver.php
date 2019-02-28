<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Area/deliverAmend')}" frame="true" refresh="true">
		<input type="hidden" name="area_id" value="{pigcms{$area_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			
			<tr>
				<th width="90">开启配送设置</th>
				<td>
					<select name="status" class="valid" tips="是否开启平台对店铺的配送费单独设置，如果开启，下面的设置才有用，如果关闭那么采用平台的默认设置">
					<option value="0" <if condition="$deliverSet['status'] eq 0">selected</if>>关闭</option>
					<option value="1" <if condition="$deliverSet['status'] eq 1">selected</if>>开启</option>
					</select>
				</td>
			</tr>
			<tr class="open_own">
				<th colspan="2" style="color:red">配送时间段一的设置</th>
			</tr>
            <tr class="open_own">
                <th width="160">配送时间段：</th>
                <td>
                    <input type="text" class="input-text valid" name="delivertime_start" value="{pigcms{$deliverSet.delivertime_start}" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:'HH:mm'})">&nbsp;-&nbsp;&nbsp;
                    <input type="text" class="input-text" name="delivertime_stop" value="{pigcms{$deliverSet.delivertime_stop}" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:'HH:mm'})">
                </td>
            </tr>
            <tr class="open_own">
                <th width="90">时段一起送价</th>
                <td><input type="text" class="input fl" name="basic_price1" value="{pigcms{$deliverSet.basic_price1|floatval}" size="10" tips="每单达到这个价格才给予配送"/></td>
            </tr>
			<tr class="open_own" >
				<th width="90">免配送费设置</th>
				<td>
					<select name="freetype" class="valid" tips="订单金额超过下面的[订单满]免配送费">
					<option value="0" <if condition="$deliverSet['freetype'] eq 0">selected</if>>免配送费</option>
					<option value="1" <if condition="$deliverSet['freetype'] eq 1">selected</if>>不免配送费</option>
					<option value="2" <if condition="$deliverSet['freetype'] eq 2">selected</if>>订单金额达条件免</option>
					</select>
				</td>
			</tr>
			<tr class="open_own free_type full_money">
				<th width="90">订单满</th>
				<td><input type="text" class="input fl" name="full_money" value="{pigcms{$deliverSet.full_money|floatval}" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">起步配送费</th>
				<td><input type="text" class="input fl" name="base_fee" value="{pigcms{$deliverSet.base_fee|floatval}" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">起步配送距离</th>
				<td><input type="text" class="input fl" name="base_distance" value="{pigcms{$deliverSet.base_distance|floatval}" size="10" tips="每单在起步距离（单位:公里）"/></td>
			</tr>
			<tr class="open_own free_type">
				<th width="90">每公里的配送费</th>
				<td><input type="text" class="input fl" name="per_km_price" value="{pigcms{$deliverSet.per_km_price|floatval}" size="10" tips="超出起步距离的路程每公里的单价，如果超出部分不是整数的情况下舍去零头取整数，距离是按直线距离算的（单位:元）"/></td>
			</tr>
			<tr class="open_own" >
				<th colspan="2" style="color:red">配送时间段二的设置</th>
			</tr>
            <tr class="open_own">
                <th width="160">配送时间段：</th>
                <td>
                    <input type="text" class="input-text valid" name="delivertime_start2" value="{pigcms{$deliverSet.delivertime_start2}" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:'HH:mm'})">&nbsp;-&nbsp;&nbsp;
                    <input type="text" class="input-text" name="delivertime_stop2" value="{pigcms{$deliverSet.delivertime_stop2}" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:'HH:mm'})">
                </td>
            </tr>
            <tr class="open_own">
                <th width="90">时段二起送价</th>
                <td><input type="text" class="input fl" name="basic_price2" value="{pigcms{$deliverSet.basic_price2|floatval}" size="10" tips="每单达到这个价格才给予配送"/></td>
            </tr>
			<tr class="open_own" >
				<th width="90">免配送费设置</th>
				<td>
					<select name="freetype2" class="valid" tips="订单金额超过下面的[订单满]免配送费">
					<option value="0" <if condition="$deliverSet['freetype2'] eq 0">selected</if>>免配送费</option>
					<option value="1" <if condition="$deliverSet['freetype2'] eq 1">selected</if>>不免配送费</option>
					<option value="2" <if condition="$deliverSet['freetype2'] eq 2">selected</if>>订单金额达条件免</option>
					</select>
				</td>
			</tr>
			<tr class="open_own free_type2 full_money2">
				<th width="90">订单满</th>
				<td><input type="text" class="input fl" name="full_money2" value="{pigcms{$deliverSet.full_money2|floatval}" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">起步配送费</th>
				<td><input type="text" class="input fl" name="base_fee2" value="{pigcms{$deliverSet.base_fee2|floatval}" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">起步配送距离</th>
				<td><input type="text" class="input fl" name="base_distance2" value="{pigcms{$deliverSet.base_distance2|floatval}" size="10" tips="每单在起步距离（单位:公里）"/></td>
			</tr>
			<tr class="open_own free_type2">
				<th width="90">每公里的配送费</th>
				<td><input type="text" class="input fl" name="per_km_price2" value="{pigcms{$deliverSet.per_km_price2|floatval}" size="10" tips="超出起步距离的路程每公里的单价，如果超出部分不是整数的情况下舍去零头取整数，距离是按直线距离算的（单位:元）"/></td>
			</tr>
            <tr class="open_own" >
                <th colspan="2" style="color:red">配送时间段三的设置</th>
            </tr>
            <tr class="open_own">
                <th width="160">配送时间段：</th>
                <td>
                    <input type="text" class="input-text valid" name="delivertime_start3" value="{pigcms{$deliverSet.delivertime_start3}" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:'HH:mm'})">&nbsp;-&nbsp;&nbsp;
                    <input type="text" class="input-text" name="delivertime_stop3" value="{pigcms{$deliverSet.delivertime_stop3}" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:'HH:mm'})">
                </td>
            </tr>
            <tr class="open_own">
                <th width="90">时段三起送价</th>
                <td><input type="text" class="input fl" name="basic_price3" value="{pigcms{$deliverSet.basic_price3|floatval}" size="10" tips="每单达到这个价格才给予配送"/></td>
            </tr>
            <tr class="open_own" >
                <th width="90">免配送费设置</th>
                <td>
                    <select name="freetype3" class="valid" tips="订单金额超过下面的[订单满]免配送费">
                    <option value="0" <if condition="$deliverSet['freetype3'] eq 0">selected</if>>免配送费</option>
                    <option value="1" <if condition="$deliverSet['freetype3'] eq 1">selected</if>>不免配送费</option>
                    <option value="2" <if condition="$deliverSet['freetype3'] eq 2">selected</if>>订单金额达条件免</option>
                    </select>
                </td>
            </tr>
            <tr class="open_own free_type3 full_money3">
                <th width="90">订单满</th>
                <td><input type="text" class="input fl" name="full_money3" value="{pigcms{$deliverSet.full_money3|floatval}" size="10" tips="（单位:元）上面一项选择了满免后，当订单达到该项指定金额免配送费"/></td>
            </tr>
            <tr class="open_own free_type3">
                <th width="90">起步配送费</th>
                <td><input type="text" class="input fl" name="base_fee3" value="{pigcms{$deliverSet.base_fee3|floatval}" size="10" tips="在起步距离范围内的配送费（单位:元）"/></td>
            </tr>
            <tr class="open_own free_type3">
                <th width="90">起步配送距离</th>
                <td><input type="text" class="input fl" name="base_distance3" value="{pigcms{$deliverSet.base_distance3|floatval}" size="10" tips="每单在起步距离（单位:公里）"/></td>
            </tr>
            <tr class="open_own free_type3">
                <th width="90">每公里的配送费</th>
                <td><input type="text" class="input fl" name="per_km_price3" value="{pigcms{$deliverSet.per_km_price3|floatval}" size="10" tips="超出起步距离的路程每公里的单价，如果超出部分不是整数的情况下舍去零头取整数，距离是按直线距离算的（单位:元）"/></td>
            </tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script>
var is_have_two_time = 1;
$(document).ready(function(){
	var s_is_open_own = $('select[name=status]').val(), freetype = $('select[name=freetype]').val();
	if (s_is_open_own == 1) {
		$('.open_own').show();
		if (freetype == 0) {
			$('.free_type').hide();
		} else if (freetype == 1) {
			$('.free_type').show();
			$('.full_money').hide();
		} else if (freetype == 2) {
			$('.free_type').show();
		}
		var freetype2 = $('select[name=freetype2]').val();
		if (freetype2 == 0) {
			$('.free_type2').hide();
		} else if (freetype2 == 1) {
			$('.free_type2').show();
			$('.full_money2').hide();
		} else if (freetype2 == 2) {
			$('.free_type2').show();
		}
        var freetype3 = $('select[name=freetype3]').val();
        if (freetype3 == 0) {
            $('.free_type3').hide();
        } else if (freetype3 == 1) {
            $('.free_type3').show();
            $('.full_money3').hide();
        } else if (freetype3 == 2) {
            $('.free_type3').show();
        }
	} else {
		$('.open_own').hide();
	}
	$('select[name=status]').change(function(){
		if ($(this).val() == 1) {
			$('.open_own').show();
			freetype = $('select[name=freetype]').val();
			if (freetype == 0) {
				$('.free_type').hide();
			} else if (freetype == 1) {
				$('.free_type').show();
				$('.full_money').hide();
			} else if (freetype == 2) {
				$('.free_type').show();
			}
			freetype2 = $('select[name=freetype2]').val();
			if (freetype2 == 0) {
				$('.free_type2').hide();
			} else if (freetype2 == 1) {
				$('.free_type2').show();
				$('.full_money2').hide();
			} else if (freetype2 == 2) {
				$('.free_type2').show();
			}
            freetype3 = $('select[name=freetype3]').val();
            if (freetype3 == 0) {
                $('.free_type3').hide();
            } else if (freetype3 == 1) {
                $('.free_type3').show();
                $('.full_money3').hide();
            } else if (freetype3 == 2) {
                $('.free_type3').show();
            }
		} else {
			$('.open_own').hide();
		}
	});
	$('select[name=freetype]').change(function(){
		if ($(this).val() == 0) {
			$('.free_type').hide();
		} else if ($(this).val() == 1) {
			$('.free_type').show();
			$('.full_money').hide();
		} else if ($(this).val() == 2) {
			$('.free_type').show();
		}
	});
	
	$('select[name=freetype2]').change(function(){
		if ($(this).val() == 0) {
			$('.free_type2').hide();
		} else if ($(this).val() == 1) {
			$('.free_type2').show();
			$('.full_money2').hide();
		} else if ($(this).val() == 2) {
			$('.free_type2').show();
		}
	});
    
    $('select[name=freetype3]').change(function(){
        if ($(this).val() == 0) {
            $('.free_type3').hide();
        } else if ($(this).val() == 1) {
            $('.free_type3').show();
            $('.full_money3').hide();
        } else if ($(this).val() == 2) {
            $('.free_type3').show();
        }
    });
});
</script>
<include file="Public:footer"/>