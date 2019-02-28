<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Yuedan/agreement_add')}" frame="true" refresh="true">
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="80">协议标题</th>
			<td>
				<input type="text" class="input fl" name="title" id="title" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/>
			</td>
		</tr>
		<tr>
			<th width="80">协议标识</th>
			<td>
				<input type="text" class="input fl" name="key" id="key" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/>
			</td>
		</tr>
		<tr>
			<th width="80">协议内容</th>
			<td>
				<textarea style="width: 400px; height: 200px;" name="content"></textarea>
			</td>
		</tr>
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>
