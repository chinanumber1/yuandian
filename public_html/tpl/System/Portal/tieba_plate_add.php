<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Portal/tieba_plate_add')}" frame="true" refresh="true">
		<input type="hidden" name="pid" value="{pigcms{$pid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">板块名称</th>
				<td><input type="text" class="input fl" name="plate_name" id="plate_name" size="25"  placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">板块排序</th>
				<td><input type="text" class="input fl" name="sort" value="0" size="10" placeholder="板块排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>
			<tr>
				<th width="80">板块状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
