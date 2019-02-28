<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('User/check')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">用户ID：</td>
				<td>
					<label>{pigcms{$userAuth['uid']}</label>
					<input type="hidden" name="authentication_id" value="{pigcms{$userAuth['authentication_id']}">
					<input type="hidden" name="uid" value="{pigcms{$userAuth['uid']}">
				</td>
			</tr>
			<tr>
				<td width="80">真实姓名：</td>
				<td>
					<label>{pigcms{$userAuth['user_truename']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">身份证号：</td>
				<td>
					<label>{pigcms{$userAuth['user_id_number']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">身份证图片：</td>
				<td>
					<a href="{pigcms{$userAuth['authentication_img']}" target="_blank" title="新窗口打开原图">
						<img style="width:400px;" src="{pigcms{$userAuth['authentication_img']}" />
					</a>
					<br/>
				</td>
			</tr>
			<tr>
				<td width="80">背面图片：</td>
				<td>
					<a href="{pigcms{$userAuth['authentication_back_img']}" target="_blank" title="新窗口打开原图">
						<img style="width:400px;" src="{pigcms{$userAuth['authentication_back_img']}" />
					</a>
					<br/>
				</td>
			</tr>
			<tr>
				<td width="80">手持证件：</td>
				<td>
					<a href="{pigcms{$userAuth['hand_authentication']}" target="_blank" title="新窗口打开原图">
						<img style="width:400px;" src="{pigcms{$userAuth['hand_authentication']}" />
					</a>
					<br/>
				</td>
			</tr>
			<if condition="$status eq 1">
				<tr>
					<td width="80">审核时间：</td>
					<td>{pigcms{$userAuth.authentication_time|date='Y-m-d H:i:s',###}</td>
				</tr>
			<else/>
				<tr>
					<td width="80">审核备注：</td>
					<td>
						<select name="examine_remarks">
							<option value="0" selected="selected">审核通过</option>
							<option value="1">您上传的身份证照片模糊，请重新上传审核！</option>
							<option value="2">您上传的身份证照片与真实姓名不符，请重新上传审核！</option>
							<option value="3">经审核，您上传的身份证已经过期，请重新上传审核！</option>
						</select>
					</td>
				</tr>
			</if>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="审核" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
