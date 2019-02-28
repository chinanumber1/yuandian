<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Portal/activity_add')}" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">活动名称</th>
				<td><input type="text" class="input fl" name="title" id="title" size="25" placeholder="" validate="maxlength:100,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">广告图片</th>
				<td><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="请上传图片" validate="required:true"/></td>
			</tr>
			<tr>
				<th width="80">活动时间</th>
				<td><input type="text" class="input fl" name="time" id="time" size="25" placeholder="" validate="maxlength:50,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动地址</th>
				<td><input type="text" class="input fl" name="place" id="place" size="25" placeholder="" validate="maxlength:100,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动价格</th>
				<td><input type="text" class="input fl" name="price" value="" id="price" size="25" placeholder="" validate="maxlength:100,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动人数</th>
				<td><input type="text" class="input fl" name="number" id="number" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">报名截至时间</th>
				<td><input type="text" class="input-text" name="enroll_time" id="d233" value="{pigcms{$_GET.begin_time}" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" validate="maxlength:100,required:true"/></td>
			</tr>
			<tr>
				<th width="80">所属分类</th>
				<td>
					<select name="cid" id="cid">
						<volist id="catList" name="catList">
							<option value="{pigcms{$catList.cid}">{pigcms{$catList.cat_name}</option>
						</volist>
					</select>
				</td>
			</tr>
			<tr>
				<th width="80">负责人</th>
				<td><input type="text" class="input fl" name="leader" id="leader" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动状态</th>
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
