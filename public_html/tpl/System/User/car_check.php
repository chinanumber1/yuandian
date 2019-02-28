<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('User/car_check')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">用户ID：</td>
				<td>
					<label>{pigcms{$userAuth['uid']}</label>
					<input type="hidden" name="car_id" value="{pigcms{$userAuth['car_id']}">
					<input type="hidden" name="uid" value="{pigcms{$userAuth['uid']}">
				</td>
			</tr>
			<tr>
				<td width="80">真实姓名：</td>
				<td>
					<label>{pigcms{$userAuth['name']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">车牌号：</td>
				<td>
					<label>{pigcms{$userAuth['front']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">身份证号：</td>
				<td>
					<label>{pigcms{$userAuth['user_id_number']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">车辆车型：</td>
				<td>
					<label>{pigcms{$userAuth['models']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">驾龄：</td>
				<td>
					<label>{pigcms{$userAuth['driving']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">车龄：</td>
				<td>
					<label>{pigcms{$userAuth['age']}</label>
				</td>
			</tr>
			<tr>
				<td width="80">车价：</td>
				<td>
					<label>{pigcms{$userAuth['car_price']}</label>
				</td>
			</tr>
			<tr style="display:none">
				<td width="80">身份证图片：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['authentication_img']}" /></td>
			</tr >
			<tr style="display:none">
				<td width="80">背面图片：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['authentication_back_img']}" /></td>
			</tr>
			<tr>
				<td width="80">{pigcms{$config.ride_upload_pic_name_1}：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['drivers_license']}" /></td>
			</tr>
			<tr>
				<td width="80">{pigcms{$config.ride_upload_pic_name_2}：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['driving_license']}" /></td>
			</tr>
			<if condition="$statuss eq 1">
				<tr>
					<td width="80">审核时间：</td>
					<td>{pigcms{$userAuth.add_time|date='Y-m-d H:i:s',###}</td>
				</tr>
				<tr>
					<td width="80">审核备注：</td>
					<td><if condition="$userAuth['status'] eq 1">审核通过<elseif condition="$userAuth['status'] eq 2"/>审核不通过</if></td>
				</tr>
				<tr>
					<td width="80">审核备注：</td>
					<td>{pigcms{$userAuth.examine_remarks}</td>
				</tr>
			<else/>
				<tr>
					<td width="80">审核：</td>
					<td>
						<select name="status">
							<option value="1" selected="selected">审核通过</option>
							<option value="2">审核不通过</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="80">审核备注：</td>
					<td>
						<textarea name="examine_remarks"></textarea>
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
