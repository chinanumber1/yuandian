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
			<div class="txt">{pigcms{$config.group_alias_name}</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink cur" data-url="{pigcms{:U('group_list')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('group_find')}">
						<div class="icon search"></div>
						<div class="text">查找订单</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<div class="form-group clearfix">
					<form action="{pigcms{:U('Stroe/group_list')}" method="get">
						<input type="hidden" name="c" value="Store"/>
						<input type="hidden" name="a" value="group_list"/>
						<input type="hidden" name="appoint_id" value="{pigcms{$_GET.appoint_id}"/>
						
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<select name="searchtype">
								<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>
								<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>支付流水号</option>
								<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
								<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{$config.group_alias_name}名称</option>
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
							</select>
							<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" style="width:200px;"/>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;-&nbsp;			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<font color="#000">订单状态筛选：</font>
							<select id="status" name="status" >
								
								<volist name="status_list" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<font color="#000">支付方式筛选：</font>
							<select id="pay_type" name="pay_type">
									<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
								<volist name="pay_method" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
									<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
							</select>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　
							<a href="javascript:void(0)" onclick="exports()" class="down_excel" style="float:right;padding:8px 14px;border:1px solid #629b58;color:#629b58;">导出订单</a>
						</div>
					</form>
				</div>
				<p>&nbsp;此页面只列出已归属到此店铺的订单。若想验证新订单或查找订单，请点击"查找订单"按钮。</p>
				<div class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>订单编号</th>
									<th>{pigcms{$config.group_alias_name}名称</th>
									<th>订单信息</th>
									<th>验证消费</th>
									<th>订单状态</th>
									<th class="button-column">操作</th>
								</tr>
							</thead>
							<tbody>
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td width="100">{pigcms{$vo.real_orderid}</td>
										<td width="200"><a href="{pigcms{$config.site_url}/index.php?g=Group&c=Detail&group_id={pigcms{$vo.group_id}" target="_blank">{pigcms{$vo.s_name}</a></td>
										<td width="150">
											数量：{pigcms{$vo.num}<br/>
										总价：￥{pigcms{$vo.total_money|floatval=###}<br/>
										</td>
										<td width="150">
										 <if condition="empty($vo['last_staff']) OR empty($vo['use_time'])">
										    <span class="red">未验证消费</span>
											<else/>
											操作店员：{pigcms{$vo['last_staff']}<br/>
											消费时间：{pigcms{$vo['use_time']|date='Y-m-d H:i:s',###}<br/>
											</if>
										</td>
										<td width="200">
											<if condition="$vo['status'] == 3">
										   <font color="red">已取消</font>
											<elseif condition="$vo['paid']" />
												<if condition="$vo['third_id'] eq '0' AND $vo['pay_type'] eq 'offline' AND $vo['status'] eq 0">
													<font color="red">线下未付款</font>
												<elseif condition="$vo['status'] eq 0" />
												<font color="green">已付款</font>&nbsp;&nbsp;
													<php>if($vo['tuan_type'] != 2){</php>
														<font color="red">未消费</font>
												
														<php>if(!$vo['pass_array'] && ($vo['group_start_status']==1 || $vo['group_start_status']==3 )){</php>
														<a href="{pigcms{:U('Store/group_verify',array('order_id'=>$vo['order_id']))}" class="group_verify_btn">验证消费</a>
														<php>
															}
															
														</php>
													
													<php>}else{</php>
														<php>if($vo['is_pick_in_store']){</php>
															<font color="red">未取货</font>
														<php>}else{</php>
																<php>if($vo['express_id']){</php>	
																	<font color="red">未确认收货</font>
																<php>}else{</php>
																	<font color="red">未发货</font>
																<php>}</php>
														<php>}</php>
													<php>}</php>
												<elseif condition="$vo['status'] eq 1"/>
													<php>if($vo['tuan_type'] != 2){</php>
														<font color="green">已消费</font>
													<php>}else{</php>
														<php>if($vo['is_pick_in_store']){</php>
															<font color="green">已取货</font>
														<php>}else{</php>
															<font color="green">已收货</font>
														<php>}</php>
													<php>}</php>
												&nbsp;&nbsp;<font color="red">待评价</font>
												<else/>
													<font color="green">已完成</font>
												</if>
											<else/>
												<font color="red">未付款</font>
											</if>
										<if condition="$vo.pass_array && count($vo['pass_array'] gt 1 && ($vo['group_start_status']==1 ||  $vo['group_start_status']==3))">
											&nbsp;&nbsp;<a class="green handle_btn" data-title="消费码详情" href="{pigcms{:U('Store/group_pass_array',array('order_id'=>$vo['order_id']))}">查看消费码详情</a>
											</if>
											<br/>
											下单时间：{pigcms{$vo['add_time']|date='Y-m-d H:i:s',###}<br/>
											<if condition="$vo['paid']">付款时间：{pigcms{$vo['pay_time']|date='Y-m-d H:i:s',###}</if>
										</td>
										<td class="button-column" width="40">
											<a title="查看订单详情"  data-title="订单详情" data-box_width="800px" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Store/group_edit',array('order_id'=>$vo['order_id']))}">
												<i class="shortBtn">查看详情</i>
											</a>
										</td>
									</tr>
								</volist>
							</tbody>
						</table>
					{pigcms{$pagebar}
					</div>
				</div>
			</div>
	</body>
	<script>
	 var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('group_export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
</html>