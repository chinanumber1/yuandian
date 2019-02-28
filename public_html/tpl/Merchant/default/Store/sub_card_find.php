<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>免单套餐 - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">查找消费码</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink" data-url="{pigcms{:U('sub_card')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink cur" data-url="{pigcms{:U('sub_card_find')}">
						<div class="icon search"></div>
						<div class="text">查找订单</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
					<form id="find-form" method="post">
						<select name="find_type" id="find_type" class="col-sm-2" style="margin-right:10px;height:42px;">
							
								<option value="1">消费密码</option>
						
						</select>
						<input class="col-sm-4" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:30px;"/>
						<button class="btn btn-success" type="submit" id="find_submit">查找订单</button>
					</form>
				</div>
				<div id="order_list" class="grid-view" style="display:none;">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>编号</th>
								<th>套餐名称</th>
								<th>消费码</th>
								<th>套餐总价</th>
								<th>套餐描述</th>
								<th>购买时间</th>
								<th>套餐状态</th>
								<th>用户信息</th>
								<th>订单状态</th>
							
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
					<td width="100">{{ d.list[i].id }}</td>
					<td width="150">{{ d.sub_card.name }}</td>
					<td width="150">{{ d.list[i].pass }}</td>
					<td width="50">
						{{ d.sub_card.price }}
					</td>
					<td width="150">
						{{ d.sub_card.desc }}
			
					</td>
					<td width="150">
						{{ d.list[i].add_time }}
			
					</td>
					<td width="80">
						{{# if(d.list[i].effective_days <0){ }}
						<font color="red">已过期无法验证消费</font>
					  {{# }else if(d.list[i].last_staff !=''){ }}
						操作店员：{{ d.list[i].last_staff }}<br/>
						消费时间：{{ d.list[i].use_time }}<br/>
						{{# }else{ }}
						 <span class="red">未验证消费</span>
						 <a href="{pigcms{:U('Store/sub_card_verify')}&pass={{ d.list[i].pass }}" class="group_verify_btn">验证消费</a>
						{{# } }}
					</td>
					<td width="100">
					
						用户ID：{{ d.list[i].uid }}<br/>
						用户名：{{ d.list[i].nickname }}<br/>
						用户手机号：{{ d.list[i].phone }}<br/>
					
					</td>
					<td width="100" >
					 {{# if(d.list[i].status ==1){ }}  <font color="blue">已消费</font>	{{# }else{ }} <font color="red">未消费</font>		{{# } }}
	
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
				$.post("{pigcms{:U('Store/sub_card_find')}",{find_type:post_type,find_value:post_value},function(result){
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
				verify_btn.html('验证中..');
				$.get(verify_btn.attr('href'),function(result){
					alert(result.info);
					if(result.status == 1){
						window.location.href = window.location.href;
					}else{
						verify_btn.html('验证消费');
					}
				});
				return false;
			});
		</script>
	</body>
</html>