<include file="Public:header"/>
	<form id="myform" method="post" action="__SELF__" enctype="multipart/form-data">
		<input type="hidden" name="cat_fid" value="{pigcms{$_GET.cat_fid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
				<tr>
					<th width="80">分类图片</th>
					<td><input type="file" class="input fl" name="cat_pic" style="width:200px;" placeholder="分类图片" validate="required:true" tips="分类图片，尺寸为298*198的图标" /></td>
				</tr>
            
			<tr>
            
				<th width="80">分类排序</th>
				<td><input type="text" class="input fl" name="cat_sort" value="0" size="10" placeholder="分类排序" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>


			<if condition='!empty($_GET["cat_fid"])'>
				<tr>
					<th width="80">是否热门</th>
					<td>
						<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="is_hot" value="1" /></label></span>
						<span class="cb-disable"><label class="cb-disable selected"><span>否</span><input type="radio" name="is_hot" value="0" checked="checked" /></label></span>
						<em class="notice_tips" tips="如果选择热门，颜色会有变化"></em>
					</td>
				</tr>
			</if>
			<tr>
				<th width="80">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="cat_status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="cat_status" value="0" /></label></span>
				</td>
			</tr>
            
            <tr>
                    <th width="80">描述</th>
                    <td><textarea name="desc" cols="35" rows="4"></textarea><em class="notice_tips" tips="微信回复、电脑网站介绍时需要用到，换行符表示换行。最多支持12个字。"></em></td>
				</tr>
            
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>