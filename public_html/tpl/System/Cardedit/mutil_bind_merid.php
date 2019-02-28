<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Cardedit/mutil_bind_merid')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">实体卡卡号集：</td>
				<td>
					<textarea name="cardids" form="myform" autocomplete="off"  style="height:180px;width:400px" validate="required:true"></textarea> 
					
				</td>
			</tr>
			<tr>
				<td width="80">绑定商户ID：</td>
				<td>
				<input type="text" class="input fl" name="merid" value=""  autocomplete="off" validate="required:true,digits:true" >
				</td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
