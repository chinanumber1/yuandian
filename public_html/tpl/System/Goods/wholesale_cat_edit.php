<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Goods/wholesale_cat_amend')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_category.id}"/>
		<input type="hidden" name="fid" value="{pigcms{$parentid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="100">批发市场分类名称</th>
				<td><input type="text" class="input fl" name="name" id="name" value="{pigcms{$now_category.name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="100">批发市场分类排序</th>
				<td><input type="text" class="input fl" name="sort" id="sort" value="{pigcms{$now_category.sort}" placeholder="" validate="maxlength:20,number:true" tips="分类排序（数值越大排在前面）"/></td>
			</tr>
			<tr>
				<th width="100">批发市场分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1"  <if condition="$now_category['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['status'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="status" value="0"  <if condition="$now_category['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
            <!-- <tr>
                <th width="100">是否热门</th>
                <td>
                    <span class="cb-enable"><label class="cb-enable <if condition="$now_category['is_hot'] eq 1">selected</if>"><span>是</span><input type="radio" name="is_hot" value="1" <if condition="$now_category['is_hot'] eq 1">checked="checked"</if> /></label></span>
                    <span class="cb-disable"><label class="cb-disable <if condition="$now_category['is_hot'] eq 0">selected</if>"><span>否</span><input type="radio" name="is_hot" value="0" <if condition="$now_category['is_hot'] eq 0">checked="checked"</if> /></label></span>
                </td>
            </tr> -->
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>