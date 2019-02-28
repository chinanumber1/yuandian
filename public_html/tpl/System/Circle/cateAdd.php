<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Circle/cateAdd')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="0"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

			<tr>
				<th width="80">名称</th>
				<td><input type="text" class="input fl" name="name" value="" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/></td>
			</tr>

			<tr>
				<th width="80">父类</th>
				<td>
				<select name="fid" validate="required:true">
				<option value='0'>一级分类</option>
				<!--<volist name="cateList" id="vo">
				<option value='{pigcms{$vo.id}'>{pigcms{$vo.name}</option>
				</volist>-->
				</select></td>
			</tr>

			<tr>
				<th width="80">状态</th>
				<td>
				<select name="status">
				<option value="0">启用</option>
				<option value="1">不启用</option>
				</select>
				</td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript">
	get_first_word('area_name','area_url','first_pinyin');
</script>	
<include file="Public:footer"/>