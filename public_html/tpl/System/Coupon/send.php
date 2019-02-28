<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 派发优惠券</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			a:hover,a:visited{color:#666;}
		</style>
	</head>
	<body>
		<table style="overflow:auto">
			<thead>
				<tr>
					<if condition="$_GET['all'] eq 1"><th>用户ID</th><elseif condition="$user_list " /><th>等级名称</th></if>
					<th>用户名</th>
					<th>派送状态</th>
				</tr>

			</thead>
			<tbody>
				<if condition="$user_list">
					<if condition="$_GET['all'] eq 1">
					
					
						
						<volist name="user_list" id="vv" key="k">
						<tr>
							<td >{pigcms{$vv.uid}</td>
							<td class="send_name" data-id = "{pigcms{$vv.uid}"><if condition="$vv.nickname neq ''">{pigcms{$vv.nickname}<else />{pigcms{$vv.phone}</if></td>
							<td class="content"></td>
						</tr>
						</volist>
				
					<else />
					<volist name="user_list" id="vo">
						<tr><td class="group_name" rowspan="<php>echo 1+count($vo['userlist']);</php>">{pigcms{$vo.group_name}</td></tr>
						
							<volist name="vo['userlist']" id="vv" key="k">
							<tr>
								<td class="send_name" data-id = "{pigcms{$vv.uid}">{pigcms{$vv.nickname}{pigcms{$vv.uid}</td>
								<td class="content"></td>
							</tr>
							</volist>
						
					</volist>
					</if>
				<elseif condition="$user"/>
					<tr>
						<td class="send_name" data-id = "{pigcms{$user.uid}">{pigcms{$user.nickname}</td>
						<td class="content"></td>
					</tr>
				</if>
			</tbody>
		</table>
		
	
		<!--<div class="btn">
			<button id="submit" type="submit">确定</button>
			<button id="reset" type="reset">取消</button>
		</div>-->

		<script>
			var coupon_list = "{pigcms{$coupon_list}";
			$(function(){
				$.each($('.send_name'), function(index, val) {
					var uid = $(this).attr('data-id');
					var content = $(this);
					$.ajax({
						url: "{pigcms{:U('ajax_send')}",
						type: "post",
						dataType:"json",
						data: {coupon_list:coupon_list,uid:uid},
						beforeSend: function () {
							content.next('.content').html('正在派发请等待。。。');
						},
						success: function (data) {
							if(!data.error_code){
								content.next('.content').empty();
									var str = '<ul>';
								$.each(data.return, function(index, val) {
									str += '<li>优惠券(<font color="blue">'+val.coupon_name+'</font>)';
									if(val.send_code!=0){
										str+='<font color="red">派送失败</font>,'+val.error_msg+'</li>';
									}else{
										str+='<font color="green">派送成功</font></li>';
									}
									
									
								});
								str += '</ul>';
								content.next('.content').append(str);
							}
							
						},
						error: function (data) {
							content.next('.content').html('网络通信失败！');
						}
					});
					
				});
				
				$('#group_id').change(function(){
					$('#frmselect').submit();
				});
				
				
				$('#reset').click(function(){
				 parent.location.reload();   

				});
			});
		</script>
	</body>
</html>