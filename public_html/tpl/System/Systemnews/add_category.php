<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Systemnews/add_category')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">标题</th>
				<td><input type="text" class="input fl" name="name" size="75" placeholder="快报分类" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">排序</th>
				<td><input type="text" class="input fl" name="sort" value="0" size="10" placeholder="排序" validate="maxlength:20,required:true,digits:true" /></td>
			</tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1"  checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable" ><span>禁止</span><input type="radio" name="status" value="0"  /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	
<include file="Public:footer"/>