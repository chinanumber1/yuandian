<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/desk', array('area_id' => $_GET['area_id']))}">待指派</a>|<a href="{pigcms{:U('Deliver/order', array('area_id' => $_GET['area_id']))}" class="on">配送中</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<div class="deliver_search">
							<!-- <span>
								店铺名称：
									<select id="store" name="store">
										<option value="0">全部</option>
										<volist name="stores" id="store">
											<option <if condition="$selectStoreId eq $store['store_id']">selected</if> value="{pigcms{$store['store_id']}">{pigcms{$store['name']}</option>
										</volist>
									</select>
							</span> -->
<!-- 							<span class="mar_l_10"> -->
<!-- 								配送状态：<select id="status" name="deliver"> -->
<!-- 										<option value="0" <if condition="$status eq 0">selected</if> >全部</option> -->
<!-- 										<option value="1" <if condition="$status eq 1">selected</if> >等待接单</option> -->
<!-- 										<option value="2" <if condition="$status eq 2">selected</if> >已接单</option> -->
<!-- 										<option value="3" <if condition="$status eq 3">selected</if> >已取货</option> -->
<!-- 										<option value="4" <if condition="$status eq 4">selected</if> >开始配送</option> -->
<!-- 										<option value="5" <if condition="$status eq 5">selected</if> >已完成</option> -->
<!-- 									</select> -->
<!-- 							</span> -->
<!-- 							<span class="mar_l_10">用户手机号：<input type="text" id="phone" name="phone" <if condition="$phone">value="{pigcms{$phone}"></if></span> -->
<!-- 							<span>开始时间筛选：</span> -->
							<!--div style="display:inline-block;"-->
<!-- 								<select class='custom-date' id="time_value" name='select'> -->
<!-- 									<option value='1' <if condition="$day eq 1">selected</if>>今天</option> -->
<!-- 									<option value='7' <if condition="$day eq 7">selected</if>>7天</option> -->
<!-- 									<option value='30' <if condition="$day eq 30">selected</if>>30天</option> -->
<!-- 									<option value='180' <if condition="$day eq 180">selected</if>>180天</option> -->
<!-- 									<option value='365' <if condition="$day eq 365">selected</if>>365天</option> -->
<!-- 									<option value='custom' <if condition="$period">selected</if>>{pigcms{$period|default='自定义'}</option> -->
<!-- 								</select> -->
<!-- 							</div> -->
<!-- 							<span class="mar_l_10"><button id="search" class="btn btn-success">搜索</button></span> -->
<!--                             &nbsp;&nbsp;&nbsp;该筛选条件下总记录数：{pigcms{$res_count.0.count}&nbsp;&nbsp;&nbsp;总配送费：￥{pigcms{$res_count.0.total_freight|default='0.00'}&nbsp;&nbsp;&nbsp;订单总价格：￥{pigcms{$res_count.0.total_money|default='0.00'}&nbsp;&nbsp;&nbsp;小费总额：￥{pigcms{$res_count.0.tip_price|default='0.00'} -->
							<!--a href="{pigcms{:U('Deliver/export', array('status' => $status, 'day' => $day, 'phone'=> $phone, 'period' => $period))}" class="button" style="float:right;margin-right: 10px;">导出订单</a-->
						</div>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
			
						<thead>
							<tr>
								<th>配送ID</th>
								<th>订单来源</th>
								<!--th>配送员类型</th-->
								<th>店铺名称</th>
								
								<th>客户昵称</th>
								<th>客户手机</th>
								<th>客户地址</th>
								<!--th>支付方式</th-->
								<th>支付状态</th>
								<th>订单价格</th>
								<th>应收现金</th>
								<th>配送状态</th>
								<th>配送员昵称</th>
								<th>配送员手机号</th>
								<th>开始时间</th>
								<th>送达时间</th>
								<th>配送时长</th>
								<th>接单类型</th>
								<th>分配配送员</th>
								<th>操作</th>
								<!--th>创建时间</th-->
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($supply_info)">
								<volist name="supply_info"  id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td width="30">{pigcms{$vo.supply_id}</td>
										<td width="40"><if condition="$vo['order_from'] eq 0">{pigcms{$config.shop_alias_name}<elseif condition="$vo['order_from'] eq 1" />饿了么<elseif condition="$vo['order_from'] eq 2" />美团<elseif condition="$vo['order_from'] eq 3" />帮我送<elseif condition="$vo['order_from'] eq 4 OR $vo['order_from'] eq 5" />帮我买</if></td>
										<!--td width="50">{pigcms{$vo.group}</td-->
										<td width="80"><if condition="$vo['item'] eq 3">--<else/>{pigcms{$vo.storename}</if></td>
										<td width="30">{pigcms{$vo.username}</td>
										<td width="50">{pigcms{$vo.userphone}</td>
										<td width="150">{pigcms{$vo.aim_site}</td>
										<!--td width="50">{pigcms{$vo.pay_type}</td-->
										<td width="50">{pigcms{$vo.paid}</td>
										<td width="30">{pigcms{$vo.money|floatval}</td>
										<td width="30">{pigcms{$vo.deliver_cash|floatval}</td>
										<td width="50">{pigcms{$vo.order_status}</td>
										<td width="50">{pigcms{$vo.name}</td>
										<td width="80">{pigcms{$vo.phone}</td>
										<td width="80">{pigcms{$vo.start_time}</td>
										<td width="80">{pigcms{$vo.end_time}</td>
										<td width="80">{pigcms{$vo.deliver_use_time}</td>
										
										<td width="80">
										<if condition="$vo['status'] lt 2">
										---
										<elseif condition="$vo['get_type'] eq 0" />
										抢单
										<else />
										系统派单
										</if>
										</td>
										
										<td width="80">
										<if condition="$vo['item'] eq 3">
										<php>if ($vo['status'] == 0) {</php>
										<font color="red">订单失效</font>
										<php> } elseif ($vo['status'] == 5) {</php>
										<font color="green">配送完成</font>
										<php>} else { </php>
										---
										<php>}</php>
										<elseif condition="$vo['status'] eq 0" />
										<font color="red">订单失效</font>
										<elseif condition="$vo['status'] eq 1" />
										<a href="javascript:void(0);" onclick="artiframe('{pigcms{:U('Deliver/appoint_deliver',array('supply_id' => $vo['supply_id']))}','指派配送员(配送距离{pigcms{$vo['distance']})',480,380,true,false,false,editbtn,'edit',true);">指派配送员</a>
										<elseif condition="$vo['status'] lt 5" />
										<a href="javascript:void(0);" onclick="artiframe('{pigcms{:U('Deliver/appoint_deliver',array('supply_id' => $vo['supply_id']))}','更换配送员(配送距离{pigcms{$vo['distance']})',480,380,true,false,false,editbtn,'edit',true);" style="color:red">更换配送员</a>
										<else />
										<font color="green">配送完成</font>
										</if>
										</td>
										<td width="80">
										<if condition="$vo['status'] eq 0 OR $vo['status'] eq 5 OR $vo['status'] eq 1 OR $vo['item'] eq 3">
										---
										<else />
										<a href="javascript:void(0);" style="color:green" data-supply="{pigcms{$vo['supply_id']}" class="change">修改成配送完成</a>
										</if>
										</td>
										<!--td width="50">{pigcms{$vo.create_time}</td-->
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="18">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="18">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
        <script type="text/javascript" src="{pigcms{$static_public}js/screenfull.min.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
        <script type="text/javascript">if(self!=top){window.top.location.href = "{pigcms{:U('Index/index')}";}var selected_module="{pigcms{:strval($_GET['module'])}",selected_action="{pigcms{:strval($_GET['action'])}",selected_url="{pigcms{:urldecode(strval(htmlspecialchars_decode($_GET['url'])))}";</script>
        <script type="text/javascript" src="{pigcms{$static_path}js/index.js"></script>
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
			search();
		});
		function search(orderNum, phone) {
			var phone = $("#phone").val(), status = $('#status').val();
			var day = '', period = '';
			if($('#time_value option:selected').attr('value')=='custom'){
				period = $('#time_value option:selected').html();
			}else{
				day = $('#time_value option:selected').attr('value');
			}
			location.href = "{pigcms{:U('Merchant/Deliver/deliverList')}"+"&period="+period+"&phone="+phone+"&day="+day+"&status="+status;
		}
		$('.change').click(function(){
			var supply_id = $(this).attr('data-supply'), obj = $(this);
			window.top.art.dialog({
				lock: true,
				content: '您确定要将该配送订单修改成配送完成吗？修改后相应的订单状态变成已消费！',
				ok: function(){
					$.get("{pigcms{:U('Deliver/change')}", {supply_id:supply_id}, function(response){
						if (response.error_code) {
							window.top.msg(0, response.msg);
						} else {
							window.top.msg(1, response.msg,true);
							obj.remove();
						}
					}, 'json');
				},
				cancel: true
			});
		});
	});
</script>
<style>
.drp-popup{top:90px !important}
.deliver_search input{height: 20px;}
.deliver_search select{height: 20px;}
.deliver_search .mar_l_10{margin-left: 10px;}
.deliver_search .btn{height: 23px;line-height: 16px; padding: 0px 12px;}
</style>
<include file="Public:footer"/>