<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('FilterWord/mutil_add')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<font color="red">*敏感词不可添加数字 、 html代码等</font>
			<tr>
				<td width="80">敏感词集：</td>
				<td>
					<textarea name="words" form="myform" autocomplete="off"  style="height:500px;width:400px" validate="required:true"></textarea> 
				</td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
