<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">优惠券</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink cur" data-url="{pigcms{:U('coupon_list')}">
						<div class="icon order"></div>
						<div class="text">优惠券列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('coupon_find')}">
						<div class="icon search"></div>
						<div class="text">查找优惠券</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<p>&nbsp;</p>此页面只列出已归属到此店铺的优惠券。若想验证新优惠券或查找优惠券，请点击查找按钮。
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>编号</th>
								<th>优惠券名称</th>
								<th>优惠券密码</th>
								<th>用户信息</th>
								<th>订单状态</th>
							</tr>
						</thead>
						<tbody>
							<volist name="order_list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td width="100">{pigcms{$vo.pigcms_id}</td>
									<td width="200"><a href="{pigcms{$config.site_url}/activity/{pigcms{$vo.activity_id}.html" target="_blank">{pigcms{$vo.name}</a></td>
									<td width="200">{pigcms{$vo.number}</td>
									<td width="200">
										用户ID：{pigcms{$vo.uid}<br/>
										用户名：{pigcms{$vo.nickname}<br/>
										手机号：{pigcms{$vo.phone}
									</td>
									<td width="200">
										兑换时间：{pigcms{$vo['time']|date='Y-m-d H:i:s',###}<br/>
										验证时间：{pigcms{$vo['check_time']|date='Y-m-d H:i:s',###}
									</td>
								</tr>
							</volist>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
	<script>
	$(function(){
		$('.group_verify_btn').live('click',function(){
			var verify_btn = $(this);
			verify_btn.html('验证中..');
			$.get(verify_btn.attr('href'),function(result){
				if(result.status == 1){
					var icon = 'succeed';
					var button = [{
									name:'确定',
									callback:function () {  
										window.location.href = window.location.href;
									},
									focus:true
								}];
				}else{
					var icon = 'error';
					var button = [{name:'关闭'}];
					verify_btn.html('验证服务');
				}
				var content = result.info;
				alert(content);
			});
			return false;
		});
	});
	$('.appoint_del').click(function(){
		var url ="{pigcms{:U('ajax_staff_del')}";
		var order_id = $(this).data('order-id');
		if(confirm('取消后，将无法恢复，是否确认取消？')){
			$.post(url,{'order_id':order_id},function(data){
				alert(data.msg);
				if(data.status){
					location.reload();
				}
			},'json')
		}
		
	});
</script>
</html>