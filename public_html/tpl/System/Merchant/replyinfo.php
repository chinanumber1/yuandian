<include file="Public:header"/>
	<form id="myform" method="post" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">评论ID</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" value="{pigcms{$reply.pigcms_id}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">商户名称</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.m_name}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="80">店铺名称</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.s_name}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="80">用户姓名</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.nickname}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="80">用户电话</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.phone}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="80">评论类型</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.type_name}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="80">评论时间</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.add_time|date='Y-m-d H:i:s',###}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="80">评分</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.score}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="80">评论内容</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.comment}" size="25" placeholder="英文或数字" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			
			<if condition="!empty($reply['pics'])">
				<tr>
					<th width="80">分类现图</th>
					<td>
					<volist name="reply['pics']" id="pic">
					<img src="{pigcms{$pic['image']}" style="width:50px;height:50px;" class="view_msg"/>　
					</volist>
					</td>
				</tr>
			</if>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>