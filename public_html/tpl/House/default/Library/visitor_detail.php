<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 社区管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
    <style type="text/css">
    .green{ color:green}
    </style>
	<body>
		<table>
        	<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>访客信息：</b></td>
			</tr>
			<tr>
				<th width="15%">访客姓名</th>
				<td width="35%"><if condition='$detail["visitor_name"]'>{pigcms{$detail.visitor_name}<else/>未填写</if></td>
				<th width="15%">访客手机号</th>
				<td width="35%">{pigcms{$detail.visitor_phone}</td>
			</tr>
            
            <tr>
				<th width="15%">访客类型</th>
				<td width="35%">{pigcms{$detail.visitor_type}</td>
				<th width="15%">状态</th>
				<td width="35%">
                	<if condition='$detail["status"] eq 0'>
                        <span class="red">未放行</span>
						<if condition="in_array(217,$house_session['menus'])">
                        <a href="javascript:void(0)" class="chk_visitor_info" data-id="{pigcms{$detail['id']}">确认放行</a>
                    	</if>
                    <elseif condition='$detail["status"] eq 1' />
                        <span class="green">已放行（业主）</span>
                    <else/>
                        <span class="green">已放行（社区）</span>
                    </if>
                </td>
			</tr>
            
            <tr>
				<th width="15%">添加时间</th>
				<td width="35%"><if condition='$detail["add_time"]'>{pigcms{$detail.add_time|date='Y-m-d H:i:s',###}</if></td>
				<th width="15%">放行时间</th>
				<td width="35%"><if condition='$detail["pass_time"]'>{pigcms{$detail.pass_time|date='Y-m-d H:i:s',###}</if></td>
			</tr>
            <tr>
				<th width="15%">备注</th>
				<td width="85%" colspan="3">{pigcms{$detail.memo}</td>
			</tr>
            
            <tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>业主信息：</b></td>
			</tr>
			<tr>
				<th width="15%">业主姓名</th>
				<td width="35%">{pigcms{$detail.owner_name}</td>
				<th width="15%">业主手机号</th>
				<td width="35%">{pigcms{$detail.owner_phone}</td>
			</tr>
			
			<tr>
				<th width="15%">业主住址</th>
				<td width="85%" colspan="3">{pigcms{$detail.owner_address}</td>
			</tr>
            
            
            <if condition='$_GET["flag"]'>
                <tr>
                    <td colspan="4"><button style=" margin:0 auto; display:block" onClick="history.go(-1);">返回</button></td>
                </tr>
            </if>
		</table>
		<script type="text/javascript">
			$(function(){
				$('#merchant_remark_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						$('#merchant_remark_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				$('#store_id_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/order_store_id',array('order_id'=>$now_order['order_id']))}",{store_id:$('#order_store_id').val()},function(result){
						$('#store_id_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				
				$('.chk_visitor_info').click(function(){
					var chk_visitor_info_url ="{pigcms{:U('chk_visitor_info')}";
					var id = $(this).data('id');
					var status = 2;
					$.post(chk_visitor_info_url,{'id':id,'status': status},function(data){
						if(data['status']){
							alert(data['msg']);
							location.reload();
						}else{
							alert(data['msg']);
						}
					},'json')
				});
			});
		</script>
	</body>
</html>