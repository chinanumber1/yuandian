<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Slider/cat_modify')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">分类名称</th>
				<td><input type="text" class="input fl" name="cat_name" size="20" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">分类标识</th>
				<td><input type="text" class="input fl" name="cat_key" size="20" placeholder="分类标识" validate="maxlength:20,required:true,english:true"/></td>
			</tr>
			<tr>
				<th width="80">分类类型</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>wap站</span><input type="radio" name="cat_type" value="0" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>pc站</span><input type="radio" name="cat_type" value="1" /></label></span>
				</td>
			</tr>
			<tr>
				<th colspan="2">添加分类，并不会直接展现在页面中。需要修改系统源代码中方可显示。</th>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>