<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			.repair_pic img{max-width:100%;}
		</style>
	</head>
	<body>
		<table>
			
			<tr>
				<th width="15%">业主姓名</th>
				<td width="35%">{pigcms{$repair.name}</td>
				<th width="15%">业主编号</th>
				<td width="35%">{pigcms{$repair.usernum}</td>
			</tr>
			<tr>
				<th width="15%">上报时间</th>
				<td width="35%">{pigcms{$repair.time|date='Y-m-d H:i:s',###}</td>
				<th width="15%">上报地址</th>
				<td width="35%">{pigcms{$repair.address}</td>
			</tr>
			<tr>
				<th width="15%">联系方式</th>
				<td width="85%" colspan="3">{pigcms{$repair.phone}</td>
			</tr>
			<tr>
				<th width="15%">上报内容</th>
				<td width="85%" colspan="3">{pigcms{$repair.content}</td>
			</tr>
			<if condition="repair.pic">
				<tr>
					<th width="15%">上报图例</th>
					<td width="85%" colspan="3" class="repair_pic">
						<volist name="repair.pic" id="p">
							<img src="{pigcms{$p}"/><br/>
						</volist>
					</td>
				</tr>
			</if>
			
			<if condition="$repair['r_status'] eq 0">
				<tr>
					<th width="15%">分发给工作人员</th>
					<td width="85%"  colspan="3">
						<select id="worker_id">
							<option>请选择</option>
							<volist name="workers" id="worker">
							<option value="{pigcms{$worker['wid']}">{pigcms{$worker['name']}</option>
							</volist>
						</select>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="4">
					<button id="suggent_submit" type="button" class="btn btn-info"><i class="ace-icon fa fa-check bigger-110"></i>提交</button>
					</td>
				</tr>
			<elseif condition='$repair["r_status"] eq 1'/>
				<tr>
					<th width="15%">状态</th>
					<td width="85%" colspan="3"><font color="green">已指派</font></td>
				</tr>
				<tr>
					<th width="15%">处理人员</th>
					<td width="85%" colspan="3"><font color="green">{pigcms{$worker['name']}, {pigcms{$worker['phone']}</font></td>
				</tr>
			<elseif condition='$repair["r_status"] eq 2'/>
				<tr>
					<th width="15%">状态</th>
					<td width="85%" colspan="3"><font color="green">已受理</font></td>
				</tr>
				<tr>
					<th width="15%">处理人员</th>
					<td width="85%" colspan="3"><font color="green">{pigcms{$worker['name']}, {pigcms{$worker['phone']}</font></td>
				</tr>
				<tr>
					<th width="15%">接单留言</th>
					<td width="85%" colspan="3"><font>{pigcms{$repair['msg']}</font></td>
				</tr>
				<tr>
					<if condition="$follow">
						<th width="15%">接单跟进</th>
						<td width="85%" colspan="3">
						<volist name="follow" id="vo">
							<p>{pigcms{$vo['time']} - {pigcms{$vo['content']}</p>
						</volist>
						</td>
					</if>
				</tr>
			<elseif condition='$repair["r_status"] eq 3'/>
				<tr>
					<th width="15%">状态</th>
					<td width="85%" colspan="3"><font color="green">已处理</font></td>
				</tr>
				<if condition="$worker">
				<tr>
					<th width="15%">处理人员</th>
					<td width="85%" colspan="3"><font color="green">{pigcms{$worker['name']}, {pigcms{$worker['phone']}</font></td>
				</tr>
				<tr>
					<th width="15%">接单留言</th>
					<td width="85%" colspan="3"><font>{pigcms{$repair['msg']}</font></td>
				</tr>
				<tr>
					<th width="15%">处理时间</th>
					<td width="85%" colspan="3">{pigcms{$repair['reply_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				<tr>
					<th width="15%">处理意见</th>
					<td width="85%" colspan="3">{pigcms{$repair.reply_content}</td>
				</tr>
				</if>
				<if condition="repair.reply_pic">
					<tr>
						<th width="15%">处理图例</th>
						<td width="85%" colspan="3" class="repair_pic">
							<volist name="repair.reply_pic" id="p">
								<img src="{pigcms{$p}"/><br/>
							</volist>
						</td>
					</tr>
				</if>
			<elseif condition='$repair["r_status"] eq 4'/>
				<tr>
					<th width="15%">状态</th>
					<td width="85%" colspan="3"><font color="green">业主已评价</font></td>
				</tr>
				<if condition="$worker">
				<tr>
					<th width="15%">处理人员</th>
					<td width="85%" colspan="3"><font color="green">{pigcms{$worker['name']}, {pigcms{$worker['phone']}</font></td>
				</tr>
				<tr>
					<th width="15%">接单留言</th>
					<td width="85%" colspan="3"><font>{pigcms{$repair['msg']}</font></td>
				</tr>
				<tr>
					<th width="15%">处理时间</th>
					<td width="85%" colspan="3">{pigcms{$repair['reply_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				</if>
				<tr>
					<th width="15%">处理意见</th>
					<td width="85%" colspan="3">{pigcms{$repair.reply_content}</td>
				</tr>
				<if condition="repair.reply_pic">
					<tr>
						<th width="15%">处理图例</th>
						<td width="85%" colspan="3" class="repair_pic">
							<volist name="repair.reply_pic" id="p">
								<img src="{pigcms{$p}"/><br/>
							</volist>
						</td>
					</tr>
				</if>
				<tr>
					<th width="15%">评论时间</th>
					<td width="85%" colspan="3">{pigcms{$repair['comment_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				<tr>
					<th width="15%">评分</th>
					<td width="85%" colspan="3"><font color="red">{pigcms{$repair.score}分</font></td>
				</tr>
				<tr>
					<th width="15%">评论内容</th>
					<td width="85%" colspan="3">{pigcms{$repair.comment}</td>
				</tr>
				<if condition="repair.comment_pic">
					<tr>
						<th width="15%">评论图例</th>
						<td width="85%" colspan="3" class="repair_pic">
							<volist name="repair.comment_pic" id="p">
								<img src="{pigcms{$p}"/><br/>
							</volist>
						</td>
					</tr>
				</if>
			</if>
		</table>
		
		
		
		<script type="text/javascript">
			$('#suggest_select').change(function(){
				$('#content').text('');
				if($(this).val()!='请选择'){
					$('#content').text($(this).val());
				}
			});
			
			$('#suggent_submit').click(function(){
				var url = "{pigcms{:U('ajax_suggest_reply')}";
				var pigcms_id = "{pigcms{$_GET['pid']}";
				var worker_id = $('#worker_id').val();
				$.post(url, {'worker_id':worker_id,'pigcms_id':pigcms_id}, function(data){
					alert(data.msg)
					if(data.status){
						location.reload();
					}
				},'json')
			});
			
// 			$('#suggent_submit').click(function(){
// 				if(confirm('提交后，将无法修改，确认提交？')){
// 					var url = "{pigcms{:U('ajax_suggest_reply')}";
// 					var pigcms_id = "{pigcms{$_GET['pid']}";
// 					var reply_content = $('#content').val();
// 					$.post(url,{'reply_content':reply_content,'pigcms_id':pigcms_id},function(data){
// 						alert(data.msg)
// 						if(data.status){
// 							location.reload();
// 						}
// 					},'json')
// 				}
				
// 			});
		</script>
	</body>
</html>