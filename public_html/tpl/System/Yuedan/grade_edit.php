<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Yuedan/grade_edit')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<td width="80">等级</td>
			<td><input type="text" class="input fl" value="{pigcms{$gradeInfo['grade']}" placeholder="请填写用户等级" disabled="true" validate="number:true,required:true">
			<input type="hidden"  name="grade_id" value="{pigcms{$gradeInfo['grade_id']}"></td>
		</tr>
		<tr>
			<td width="80">购买金额</td>
			<td><input type="text" class="input fl" name="money" value="{pigcms{$gradeInfo['money']}" onBlur="if(this.value <= {pigcms{$gradeInfo.money}){this.value =''}" onmousedown="" placeholder="所需金额" validate="number:true,required:true"></td>
		</tr>
		<tr>
			<td width="80">抽成比例</td>
			<td><input type="text" class="input fl" name="precent" value="{pigcms{$gradeInfo['precent']}" placeholder="抽成比例" validate="number:true,required:true"></td>
		</tr>

	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<include file="Public:footer"/>