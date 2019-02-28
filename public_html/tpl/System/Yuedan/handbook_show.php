<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Yuedan/examine_edit')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<div style="margin:13px 4%;">{pigcms{$handbook_info['content']|htmlspecialchars_decode}</div>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="审核" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
