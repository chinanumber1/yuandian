<include file="Public:header"/>
	<form method="post" action="{pigcms{:U('Portal/activity_edit')}" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<input type="hidden" name="a_id" value="{pigcms{$activityInfo.a_id}">
			<tr>
				<th width="80">活动名称</th>
				<td><input type="text" class="input fl" name="title" value="{pigcms{$activityInfo.title}" id="title" size="25" placeholder="" validate="maxlength:100,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动现图</th>
				<td><img src="{pigcms{$config.site_url}/upload/portal/{pigcms{$activityInfo.pic}" style="width:260px;height:80px;" class="view_msg"/></td>
			</tr>
			<tr>
				<th width="80">活动图片</th>
				<td><input type="file" class="input fl" name="pic" value="{pigcms{$activityInfo.pic}" style="width:200px;" placeholder="请上传图片" tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
			</tr>
			<tr>
				<th width="80">活动时间</th>
				<td><input type="text" class="input fl" name="time" value="{pigcms{$activityInfo.time}" id="time" size="25" placeholder="" validate="maxlength:50,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动地址</th>
				<td><input type="text" class="input fl" name="place" value="{pigcms{$activityInfo.place}" id="place" size="25" placeholder="" validate="maxlength:100,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动价格</th>
				<td><input type="text" class="input fl" name="price" value="{pigcms{$activityInfo.price}" value="" id="price" size="25" placeholder="" validate="maxlength:100,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动人数</th>
				<td><input type="text" class="input fl" name="number" value="{pigcms{$activityInfo.number}" id="number" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">报名截至时间</th>
				<td><input type="text" class="input-text" name="enroll_time" id="d233" value="{pigcms{$activityInfo.enroll_time|date="Y-m-d H:i:s",###}" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" validate="maxlength:100,required:true"/></td>
			</tr>
			<tr>
				<th width="80">所属分类</th>
				<td>
					<select name="cid" id="cid">
						<volist id="catList" name="catList">
							<option <if condition="$activityInfo['cid'] eq $catList['cid']">selected="selected"</if> value="{pigcms{$catList.cid}">{pigcms{$catList.cat_name}</option>
						</volist>
					</select>
				</td>
			</tr>
			<tr>
				<th width="80">负责人</th>
				<td><input type="text" class="input fl" name="leader" value="{pigcms{$activityInfo.leader}" id="leader" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">活动状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$activityInfo['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$activityInfo['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$activityInfo['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$activityInfo['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
