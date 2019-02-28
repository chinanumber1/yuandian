<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-desktop"></i>
			<li class="active"><a href="{pigcms{:U('Deliver/user')}">配送管理</a></li>
			<!-- <li>{pigcms{$now_group.appoint_name}</li> -->
			<li class="active">配送员列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<!-- <form id="frmselect" method="get" action="" style="margin-bottom:0;">
					<input type="hidden" name="c" value="Group"/>
					<input type="hidden" name="a" value="order_list"/>
					<select id="group_id" name="group_id">
						<volist name="group_list" id="vo">
							<option value="{pigcms{$vo.group_id}" <if condition="$_GET['group_id'] eq $vo['group_id']">selected="selected"</if>>{pigcms{$vo.s_name}</option>
						</volist>
					</select>
				</form> -->
				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<style>
						.deliver_search input{height: 24px;}
						.deliver_search select{height: 24px;}
						.deliver_search .mar_l_10{margin-left: 10px;}
						.deliver_search .btn{height: 25px;line-height: 16px; padding: 0px 12px;}
					</style>
					<div class="deliver_search">
						<span>
							店铺名称：
								<select id="store" name="store">
									<option value="0">全部</option>
									<volist name="stores" id="store">
										<option <if condition="$selectStoreId eq $store['store_id']">selected</if> value="{pigcms{$store['store_id']}">{pigcms{$store['name']}</option>
					    			</volist>
					    		</select>
					    </span>
					    <span class="mar_l_10">
					    	配送员：<select id="deliver" name="deliver">
					    			<option value="0">全部</option>
					    			<volist name="delivers" id="user">
										<option <if condition="$selectUserId eq $user['uid']">selected</if> value="{pigcms{$user['uid']}">{pigcms{$user['name']}</option>
					    			</volist>
					    		</select>
					    </span>
<!-- 					    <span class="mar_l_10">订单号：<input type="text" id="order_number" name="order_number" <if condition="$orderNum">value="{pigcms{$orderNum}"></if></span> -->
					    <span class="mar_l_10">客户手机号：<input type="text" id="phone" name="phone" <if condition="$phone">value="{pigcms{$phone}"></if></span>
					    <span class="mar_l_10"><button id="search" class="btn btn-success">搜索</button></span>
					</div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>店铺名称</th>
									<th>客户昵称</th>
									<th>客户手机号</th>
									<th>客户地址</th>
									<th>订单价格</th>
									<th>应收现金</th>
<!-- 									<th>支付方式</th> -->
									<th>支付状态</th>
<!-- 									<th>订单状态</th> -->
									<th>配送员昵称</th>
									<th>配送员手机号</th>
<!-- 									<th>配送员类型</th> -->
									<th>开始时间</th>
									<th>结束时间</th>
									<th>创建时间</th>
									<th>配送状态</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($supply_info)): ?>
								<volist name="supply_info"  id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td>{pigcms{$vo.storename}</td>
										<td>{pigcms{$vo.username}</td>
										<td>{pigcms{$vo.userphone}</td>
										<td>{pigcms{$vo.aim_site}</td>
										<td>{pigcms{$vo.money|floatval}</td>
										<td style="color:red">{pigcms{$vo.deliver_cash|floatval}</td>
										
<!-- 										<td>{pigcms{$vo.pay_type}</td> -->
										<td>{pigcms{$vo.paid}</td>
<!-- 										<td>{pigcms{$vo.status}</td> -->
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.phone}</td>
<!-- 										<td width="100">{pigcms{$vo.group}</td> -->
										<td>{pigcms{$vo.start_time}</td>
										<td>{pigcms{$vo.end_time}</td>
										<td>{pigcms{$vo.create_time}</td>
										<td>{pigcms{$vo.order_status}</td>
									</tr>
								</volist>
								<?php else : ?>
									<tr><td colspan="16" style="color:red;text-align:center;">暂无配送记录...</td></tr>
								<?php endif; ?>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
	var selectStoreId = {pigcms{:$selectStoreId? $selectStoreId: 0};
	var selectUserId = {pigcms{:$selectUserId? $selectUserId: 0};
	$(function(){
		$("#store").change(function(){
			selectStoreId = $("#store").val();
			selectUserId = 0;
			search();
		});
		$("#deliver").change(function(){
			selectStoreId = 0;
			selectUserId = $("#deliver").val();
			search();
		});
		$("#order_number").focus(function(){
			$("#phone").val("");
		});
		$("#phone").focus(function(){
			$("#order_number").val("");
		});
		$("#search").click(function(){
			var orderNum = $("#order_number").val();
			var phone = $("#phone").val();
			search(orderNum, phone)
		});
		function search(orderNum, phone) {
			var orderNum =  orderNum || 0;
			var phone = phone || 0;
			location.href = "{pigcms{:U('Merchant/Deliver/deliverList')}"+"&orderNum="+orderNum+"&phone="+phone+"&selectStoreId="+selectStoreId+"&selectUserId="+selectUserId;
		}
	});
</script>
<include file="Public:footer"/>
