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
		
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">{pigcms{$config.appoint_alias_name}</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink cur" data-url="{pigcms{:U('appoint_list')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('appoint_find')}">
						<div class="icon search"></div>
						<div class="text">查找订单</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('allot_appoint_list')}">
						<div class="icon merchant"></div>
						<div class="text">商家派发订单</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<form action="{pigcms{:U('Store/appoint_list')}" method="get">
					<input type="hidden" name="c" value="Store"/>
					<input type="hidden" name="a" value="appoint_list"/>
					<input type="hidden" name="appoint_id" value="{pigcms{$_GET.appoint_id}"/>
					
					搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					<select name="searchtype">
						<option value="order_id" <if condition="$_GET['searchtype'] eq 'order_id'">selected="selected"</if>>订单编号</option>
						<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
						<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
						<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
						<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
					</select>
					<font color="#000">日期筛选：</font>
					<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
					<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
			
					支付方式筛选: 
					<select id="pay_type" name="pay_type">
							<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
						<volist name="pay_method" id="vo">
							<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
						</volist>
							<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
					</select>
					<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　
					<a href="javascript:void(0)" onclick="exports()" class="btn btn-success" style="float:right;margin-right: 10px;">导出订单</a>
				</form>
				<p>&nbsp;</p>此页面只列出已归属到此店铺的订单。若想验证新订单或查找订单，请点击上面按钮。
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>订单编号</th>
								<th>预约名称</th>
								<th>订单信息</th>
								<th>服务状态</th>
								<th>用户信息</th>
								<th>订单状态</th>
								<th class="button-column">操作</th>
							</tr>
						</thead>
						<tbody>
						<if condition="!empty($order_list)">
							<volist name="order_list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td width="100">{pigcms{$vo.order_id}</td>
									<td width="200"><a href="{pigcms{$config.site_url}/index.php?g=Appoint&c=Detail&appoint_id={pigcms{$vo.appoint_id}" target="_blank">{pigcms{$vo.appoint_name}</a></td>
									<td width="150">
										定金：{pigcms{:floatval($vo['payment_money'])}元<br/>
										总价：{pigcms{:floatval($vo['appoint_price'])}元<br/>
										<if condition='$vo["type"] != 2'>预约时间：{pigcms{$vo.appoint_date}&nbsp;{pigcms{$vo.appoint_time}</if>
										
									</td>
									<td width="150">
										<if condition="empty($vo['last_staff']) OR $vo['service_status'] eq 0">
											<span class="red">未验证服务</span>
										<else/>
											操作店员：{pigcms{$vo['last_staff']}<br/>
											<php>if($vo['last_time']>0){</php>消费时间：{pigcms{$vo['last_time']|date='Y-m-d H:i:s',###}<br/><php>}</php>
										</if>
									</td>
									<td width="180">
										用户ID：{pigcms{$vo.uid}<br/>
										用户名：{pigcms{$vo.nickname}<br/>
										用户手机号：{pigcms{$vo.phone}<br/>
									</td>
									<td width="200">
									<if condition="$vo['paid'] == 0" >
										<font color="red">未支付</font>
										<if condition="$vo['service_status'] == 0">
											<font color="red">未服务</font>
											<if condition='($vo["is_del"] eq 0) && ($vo["payment_status"] eq 0)'><a href="{pigcms{:U('Store/appoint_verify',array('order_id'=>$vo['order_id']))}" class="group_verify_btn">验证服务</a></if>
										<elseif condition="$vo['service_status'] == 1" />
											<font color="green">已服务</font>
										<elseif condition="$vo['service_status'] == 2" />
											<font color="green">已评价</font>
										</if>
									<elseif condition="$vo['paid'] == 1" />
										<font color="green">订金已支付</font>
										<if condition='$vo["complete_source"] eq 2'>
							<if condition='$vo["service_status"] eq 1'>
								<font color="green">已服务</font>
								<else />
											<font color="red">技师已服务，用户未付余款</font>
							</if>

							<if condition='$vo["service_status"] eq 0'><a href="{pigcms{:U('Store/appoint_verify',array('order_id'=>$vo['order_id']))}" class="group_verify_btn">验证服务</a></if>
										<elseif condition="$vo['service_status'] == 0" />
											<font color="red">未服务</font>
											<if condition='$vo["is_del"] eq 0'><a href="{pigcms{:U('Store/appoint_verify',array('order_id'=>$vo['order_id']))}" class="group_verify_btn">验证服务</a></if>
										<elseif condition="$vo['service_status'] == 1" />
											<font color="green">已服务</font>
										<elseif condition="$vo['service_status'] == 2" />
											<font color="green">已评价</font>
										</if>
									<elseif condition="$vo['paid'] == 2" />
										<font color="red">已退款</font>
									<elseif condition="$vo['paid'] == 3" />
										<font color="orange">用户已取消</font>
									<else/>
										<font color="red">订单异常</font>
									</if><br/>
										下单时间：{pigcms{$vo['order_time']|date='Y-m-d H:i:s',###}<br/>
										<if condition="$vo['pay_time']">付款时间：{pigcms{$vo['pay_time']|date='Y-m-d H:i:s',###}<br/></if>
										<if condition='$vo["is_del"] neq 0'>
											<font color="red">
												<switch name='vo["is_del"]'>
													<case value="1">已取消【用户】【PC端】</case>
													<case value="2">已取消【平台】</case>
													<case value="3">已取消【商家】</case>
													<case value="4">已取消【店员】</case>
													<case value="5">已取消【用户】【WAP端】</case>
												</switch>
											</font>
										</if>
									</td>
									<td class="button-column" width="40">
										<a title="查看订单详情"  data-title="订单详情" class="green handle_btn" style="padding-right:8px;" 
										href="{pigcms{:U('appoint_detail',array('order_id'=>$vo['order_id']))}">
											<i class="shortBtn">查看详情</i>
										</a>
									   <if condition='($vo["is_del"] eq 0) && ($vo["paid"] eq 0)'>
											<a href="javascript:void(0)" data-order-id="{pigcms{$vo['order_id']}" class="appoint_del" style="margin-top:20px;display:block;" class="red" title="取消订单">
												<i class="shortBtn">取消订单</i>
											</a>
									   </if>
									</td>
								</tr>
							</volist>
								<tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="11">列表为空！</td></tr>
							</if>
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
 var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('appoint_export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
</html>