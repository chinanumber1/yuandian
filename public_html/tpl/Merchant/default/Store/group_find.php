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
			<div class="txt">{pigcms{$config.group_alias_name}</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink" data-url="{pigcms{:U('group_list')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink cur" data-url="{pigcms{:U('group_find')}">
						<div class="icon search"></div>
						<div class="text">查找订单</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
					<form id="find-form" method="post">
						<select name="find_type" id="find_type" class="col-sm-2" style="margin-right:10px;height:42px;">
							<optgroup label="{pigcms{$config.group_alias_name}">
								<option value="1">消费密码</option>
							</optgroup>
							<optgroup label="实物">
								<option value="2">快递单号</option>
							</optgroup>
							<optgroup label="通用">
								<option value="3">订单编号</option>
								<option value="4">{pigcms{$config.group_alias_name}ID</option>
								<option value="5">用户ID</option>
								<option value="6">用户昵称</option>
								<option value="7">手机号码</option>
							</optgroup>
						</select>
						<input class="col-sm-4" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:30px;"/>
						<button class="btn btn-success" type="submit" id="find_submit">查找订单</button>
					</form>
				</div>
				<div id="order_list" class="grid-view" style="display:none;">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>订单编号</th>
								<th>{pigcms{$config.group_alias_name}名称</th>
								<th>订单信息</th>
								<th>订单类型</th>
								<th>验证消费</th>
								<th>用户信息</th>
								<th>订单状态</th>
								<th class="button-column">操作</th>
							</tr>
						</thead>
						<tbody id="order_html">
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<script id="find_html" type="text/html">
			{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
				<tr class="{{# if(i%2==1){ }}odd{{# }else{ }}even{{# } }}">
					<td width="100">{{ d.list[i].real_orderid }}</td>
					<td width="200"><a href="{pigcms{$config.site_url}/index.php?g=Group&c=Detail&group_id={{ d.list[i].group_id }}" target="_blank">{{ d.list[i].s_name }}</a></td>
					<td width="150">
						数量：{{ d.list[i].num }}<br/>
						总价：{{ d.list[i].total_money }}<br/>
					</td>
					<td width="80">
						{{# if(d.list[i].tuan_type == '0'){ }}
							{pigcms{$config.group_alias_name}券
						{{# }else if(d.list[i].tuan_type == '1'){ }}
							代金券
						{{# }else if(d.list[i].tuan_type == '2'){ }}
							实物
						{{# } }}
						
						<!--{!{# if(d.list[i].tuan_type != '2'){ }!}
							<br/><br/>消费密码：{{ d.list[i].group_pass }!}
						{!{# }else if(d.list[i].express_id != ''){ }}
							<br/><br/>快递单号：{{ d.list[i].express_id }!}
						{!{# } }!}-->
					</td>
					<td width="150">
					  {{# if(d.list[i].last_staff !=''){ }}
						操作店员：{{ d.list[i].last_staff }}<br/>
						消费时间：{{ d.list[i].use_time }}<br/>
						{{# }else{ }}
						 <span class="red">未验证消费</span>
						{{# } }}
					</td>
					<td width="180">
						{{# if(d.list[i].paid == '1'){ }}
							用户ID：{{ d.list[i].uid }}<br/>
							用户名：{{ d.list[i].nickname }}<br/>
							订单手机号：{{ d.list[i].group_phone }}<br/>
						{{# }else{ }}
							未付款用户无法查看
						{{# } }}
					</td>
					<td width="200">
						{{# if(d.list[i].paid == '1'){ }}
							{{# if(d.list[i].pay_type == 'offline' && d.list[i].third_id ==0 && d.list[i].status != 3){ }}
								<font color="red">线下未付款</font>
								
								{{# if(d.list[i].pass_array && d.list[i].num>1){ }}
									<a  class="green handle_btn" data-title="查看消费码详情"  href="{pigcms{:U('Store/group_pass_array')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">查看消费码详情</a>
								{{# }else{ }}
									<a href="{pigcms{:U('Store/group_verify')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">验证付款</a>
								{{# } }}
								
							{{# }else if(d.list[i].status == '0'){ }}
								<font color="green">已付款</font>
								
								{{# if(d.list[i].tuan_type != '2'){ }}
									<font color="red">未消费</font>
									{{# if(d.list[i].pass_array && d.list[i].num>1){ }}
										<a  class="green handle_btn" data-title="查看消费码详情"  href="{pigcms{:U('Store/group_pass_array')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">查看消费码详情</a>
									{{# }else{ }}
										<a href="{pigcms{:U('Store/group_verify')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">验证消费</a>
									{{# } }}
								{{# }else{ }}
									{{# if(d.list[i]['is_pick_in_store']){ }}
										<font color="red">未取货</font>
									{{# }else{ }}
										<font color="red">未发货</font>
									{{# } }}
								{{# } }}
							{{# }else if(d.list[i].status == '1'){ }}
								<font color="green">已消费</font>
								<font color="red">待评价</font>
								{{# if(d.list[i].pass_array && d.list[i].num>1){ }}
									<a  class="green handle_btn" data-title="查看消费码详情"  href="{pigcms{:U('Store/group_pass_array')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">查看消费码详情</a>
								{{# } }}
							{{# }else if(d.list[i].status == '3'){ }}
								<font color="red">已退款</font>
								{{# if(d.list[i].pass_array && d.list[i].num>1 ){ }}
									<a  class="green handle_btn" data-title="查看消费码详情"  href="{pigcms{:U('Store/group_pass_array')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">查看消费码详情</a>
								{{# } }}
							{{# }else if(d.list[i].status == '4'){ }}
								<font color="red">用户已取消</font>
								{{# if(d.list[i].pass_array && d.list[i].num>1){ }}
									<a  class="green handle_btn" data-title="查看消费码详情"  href="{pigcms{:U('Store/group_pass_array')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">查看消费码详情</a>
								{{# } }}
							{{# }else{ }}
								<font color="green">已完成</font>
								{{# if(d.list[i].pass_array && d.list[i].num>1){ }}
									<a  class="green handle_btn" data-title="查看消费码详情"  href="{pigcms{:U('Store/group_pass_array')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">查看消费码详情</a>
								{{# } }}
							{{# } }}
						{{# }else{ }}
							<font color="red">未付款</font>
							{{# if(d.list[i].pass_array && d.list[i].num>1){ }}
								<a  class="green handle_btn"  href="{pigcms{:U('Store/group_pass_array')}&order_id={{ d.list[i].order_id }}" class="group_verify_btn">查看消费码详情</a>
							{{# } }}
					{{# } }}
						<br/>
						下单时间：{{ d.list[i].add_time }}<br/>
						{{# if(d.list[i].paid == '1'){ }}
							付款时间：{{ d.list[i].pay_time }}
						{{# } }}
					</td>
					<td class="button-column" width="40">
						<a title="查看订单详情"  data-title="订单详情" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Store/group_edit')}&order_id={{ d.list[i].order_id }}">
							<i class="shortBtn">查看详情</i>
						</a>
					</td>
				</tr>
			{{# } }}
		</script>
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
		<script type="text/javascript">
			$('#find_type').change(function(){
				if($(this).val() != '1'){
					$('#find_value').val($('#find_value').val().replace(/\s+/g,""));
				}else{
					$('#find_value').val($('#find_value').val().replace(/\s+/g,"").replace(/(\d{4})/g,'$1 '));
				}
			});
			$('#find_value').focus().keyup(function(){
				if($('#find_type').val() == '1'){
					if($(this).val().substr(-1) == ' '){
						$(this).val($(this).val().substr(0,($(this).val().length-1)));
					}else{
						$(this).val($(this).val().replace(/\s+/g,"").replace(/(\d{4})/g,'$1 '));
					}
				}
			});
			$('#find-form').submit(function(){
				var find_value = $('#find_value');
				find_value.val($.trim(find_value.val()));
				if(find_value.val().length < 1){
					alert('请输入查找内容！');
					find_value.focus();
					return false;
				}
				
				var post_type = $('#find_type').val();
				var post_value = $('#find_value').val().replace(/\s+/g,"");
				$('#find_submit').removeClass('btn-success').addClass('btn-error').prop('disabled',true).html('请求中...');
				$('#order_html').empty();
				$('#order_list').hide();
				$.post("{pigcms{:U('Store/group_find')}",{find_type:post_type,find_value:post_value},function(result){
					$('#find_submit').removeClass('btn-error').addClass('btn-success').prop('disabled',false).html('查找订单');
					data = $.parseJSON(result);
					if(data.row_count > 0){
						laytpl(document.getElementById('find_html').innerHTML).render(data, function(html){
							document.getElementById('order_html').innerHTML = html;
							$('#order_list').show();
						});
					}else{
						alert('未查找到内容！');
					}
				});
				
				return false;
			});

			$('.group_verify_btn').live('click',function(){
				var verify_btn = $(this);
				layer.confirm('是否验证?', {
					  btn: ['是','否'] //按钮
					}, function(){
					  	verify_btn.html('验证中..');
						$.get(verify_btn.attr('href'),function(result){
							alert(result.info);
							if(result.status == 1){
								window.location.href = window.location.href;
							}else{
								//verify_btn.html('验证消费');
								layer.msg('验证成功', {icon: 1});
							}
						});
					}, function(){
					 	layer.msg('已取消验证', {icon: 2});
				});
				return false;
			});
		</script>
	</body>
</html>