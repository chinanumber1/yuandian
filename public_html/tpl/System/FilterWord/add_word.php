<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('FilterWord/add_word')}" frame="true" refresh="true">
		
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<font color="red">*敏感词不可添加数字 、 html代码等</font>
			<tr>
				<td width="80">敏感词：</td>
				<td>
					<input type="text" class="input fl" name="word" value=""  autocomplete="off" validate="required:true">
				</td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>

