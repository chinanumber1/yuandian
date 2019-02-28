<include file="Public:header"/>
	<form id="myform" method="post" action="" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">配送员姓名</th>
				<th width="90">电话</th>
<!-- 				<th width="90">常驻地址距离</th> -->
                <if condition="$supply['item'] eq 3">
				<th width="90">配送员距离取货地址</th>
                <else />
                <th width="90">配送员距离店铺</th>
                </if>
				<th width="90">选择</th>
			</tr>
			<volist name="users" id="row">
			<tr>
				<th width="90">{pigcms{$row['name']}</th>
				<th width="90">{pigcms{$row['phone']}</th>
<!-- 				<th width="90">{pigcms{$row['range']}</th> -->
				<th width="90">{pigcms{$row['now_range']}</th>
				<td><input type="radio" name="uid" value="{pigcms{$row['uid']}" /></td>
			</tr>
			</volist>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>