<include file="Public:header"/>
<style>
	.station{width: 80px; height: 40px; float: left;}
</style>
<form id="myform" method="post" action="{pigcms{:U('Metro/saveStation')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

		<tr>
			<th width="80">城市</th>
			<td id="choose_cityarea" circle_id="-1" area_id="-1"></td>
		</tr>

		<tr>
			<td width="80">站点名称</td>
			<td>
				<input type="text" class="input fl" name="name" value="" placeholder="请填写一个站点名" validate="required:true"></td>
		</tr>

		<tr>
			<td width="80">站点名称首字母</td>
			<td>
				<input type="text" class="input fl" name="first_word" value="" placeholder="" tips="用于筛选" validate="required:true"></td>
		</tr>


		<tr>
			<th width="80">坐标经纬度</th>
			<td id="choose_map"></td>
		</tr>

		<tr>
			<td width="80">状态</td>
			<td class="radio_box">
				<span class="cb-enable">
					<label class="cb-enable selected">
						<span>正常</span>
						<input type="radio" name="status" value="1" checked="checked"></label>
				</span>

				<span class="cb-disable">
					<label class="cb-disable">
						<span>禁止</span>
						<input type="radio" name="status" value="0"></label>
				</span>
			</td>
		</tr>



	</table>


	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>