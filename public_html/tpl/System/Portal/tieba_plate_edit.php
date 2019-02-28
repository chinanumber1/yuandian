<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Portal/tieba_plate_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="plate_id" value="{pigcms{$tieba_plate['plate_id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">板块名称</th>
				<td><input type="text" class="input fl" name="plate_name" id="plate_name" value="{pigcms{$tieba_plate.plate_name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>

			<tr>
				<th width="80">板块排序</th>
				<td><input type="text" class="input fl" name="sort" value="{pigcms{$tieba_plate.sort}" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>

			<tr>
				<th width="80">板块状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$tieba_plate['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1"  <if condition="$tieba_plate['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$tieba_plate['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0"  <if condition="$tieba_plate['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>