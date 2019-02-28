<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('House/sms_add_all')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="100">增加条数</th>
				<td><input type="text" class="input fl" name="sms_number" size="30" placeholder="请输入短信条数" validate="maxlength:20,required:true"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<include file="Public:footer"/>