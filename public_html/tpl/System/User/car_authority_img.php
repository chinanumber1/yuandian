<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('User/car_check')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			
			<tr >
				<td width="80">{pigcms{$config.ride_upload_pic_name_1}：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['drivers_license']}" /></td>
			</tr >
			<tr >
				<td width="80">{pigcms{$config.ride_upload_pic_name_2}：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['driving_license']}" /></td>
			</tr>
			<tr >
				<td width="80">{pigcms{$config.ride_upload_pic_name_3}：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['authentication_img']}" /></td>
			</tr>
			<tr >
				<td width="80">{pigcms{$config.ride_upload_pic_name_4}：</td>
				<td><img style="width:400px;" src="{pigcms{$userAuth['authentication_back_img']}" /></td>
			</tr>
		
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="审核" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
