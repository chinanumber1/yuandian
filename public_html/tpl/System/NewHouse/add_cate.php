<include file="Public:header"/>
<style>
	.station{width: 80px; height: 40px; float: left;}
</style>
<form id="myform" method="post" action="{pigcms{:U('NewHouse/save_cate')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<input type="hidden" name="cate_id" value="{pigcms{$cate.id}"/>
		<!--<tr>
			<th width="80">城市</th>
			<td id="choose_cityarea" circle_id="-1" area_id="-1"></td>
		</tr>-->

		<tr>
			<td width="80">分类名称</td>
			<td><input type="text" class="input fl" name="name" value="{pigcms{$cate.name}" ></td>
		</tr>

		<tr>
			<td width="80">URL</td>
			<td><input type="text" class="input fl" name="url" value="{pigcms{$cate.url}" ></td>
		</tr>

		<tr>
			<td width="80">状态</td>
			<td class="radio_box">
				<span class="cb-enable">
					<label class="cb-enable <?php if($cate['status'] == 0){echo 'selected';}?>">
						<span>正常</span>
						<input type="radio" name="status" value="0" <?php if($cate['status']==0){echo 'checked="checked"';}?>></label>
				</span>

				<span class="cb-disable">
					<label class="cb-disable <?php if($cate['status'] == 1){echo 'selected';}?>">
						<span>禁止</span>
						<input type="radio" name="status" value="1" <?php if($cate['status']==1){echo 'checked="checked"';}?>></label>
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