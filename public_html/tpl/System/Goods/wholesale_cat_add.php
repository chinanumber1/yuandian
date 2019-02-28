<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Goods/wholesale_cat_modify')}" frame="true" refresh="true">
		<input type="hidden" name="fid" id="fid" value="{pigcms{$parentid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="100">批发市场分类名称</th>
				<td><input type="text" class="input fl" name="name" id="name" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="100">批发市场分类排序</th>
				<td><input type="text" class="input fl" name="sort" id="sort" value="0" placeholder="" validate="maxlength:20,number:true" tips="分类排序（数值越大排在前面）"/></td>
			</tr>
			<tr>
				<th width="100">批发市场分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
            <!-- <tr>
                <th width="100">是否热门</th>
                <td>
                    <span class="cb-enable"><label class="cb-enable "><span>是</span><input type="radio" name="is_hot" value="1" /></label></span>
                    <span class="cb-disable"><label class="cb-disable selected"><span>否</span><input type="radio" name="is_hot" value="0" checked="checked" /></label></span>
                </td>
            </tr> -->
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>