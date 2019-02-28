<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/deliverList')}" class="on">配送列表</a>|<a href="{pigcms{:U('Deliver/desk')}" target="_blank">调度控制台</a>
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
							<span class="mar_l_10">订单来源：
                                <select id="order_from" name="order_from">
                                    <option value="-1" <if condition="$order_from eq -1">selected</if> >全部</option>
                                    <option value="0" <if condition="$order_from eq 0">selected</if> >{pigcms{$config.shop_alias_name}</option>
                                    <option value="1" <if condition="$order_from eq 1">selected</if> >饿了么</option>
                                    <option value="2" <if condition="$order_from eq 2">selected</if> >美团</option>
                                    <option value="3" <if condition="$order_from eq 3">selected</if> >帮送</option>
                                    <option value="4" <if condition="$order_from eq 4">selected</if> >帮买</option>
                                </select>
							</span>
                            <span class="mar_l_10">配送状态：<select id="status" name="deliver">
                                        <option value="-1" <if condition="$status eq -1">selected</if> >全部</option>
                                        <option value="1" <if condition="$status eq 1">selected</if> >等待接单</option>
                                        <option value="2" <if condition="$status eq 2">selected</if> >已接单</option>
                                        <option value="3" <if condition="$status eq 3">selected</if> >已取货</option>
                                        <option value="4" <if condition="$status eq 4">selected</if> >开始配送</option>
                                        <option value="5" <if condition="$status eq 5">selected</if> >已完成</option>
                                        <option value="6" <if condition="$status eq 6">selected</if> >已评价</option>
                                        <option value="0" <if condition="$status eq 0">selected</if> >已失效</option>
                                    </select>
                            </span>
                            <span class="mar_l_10"><select id="timetype" name="timetype">
                                        <option value="0" <if condition="$timetype eq 0">selected</if> >店员接单多少分钟以上</option>
                                        <option value="1" <if condition="$timetype eq 1">selected</if> >配送员接单多少分钟以上</option>
                                        <option value="2" <if condition="$timetype eq 2">selected</if> >配送时长多少分钟以上</option>
                                    </select>
                                    <input type="text" id="time" name="time" <if condition="$time">value="{pigcms{$time}" </if> placeholder="单位：分钟">
                            </span>
							<span class="mar_l_10">用户手机号：<input type="text" id="phone" name="phone" <if condition="$phone">value="{pigcms{$phone}" </if>></span>
							<span>开始时间筛选：</span>
							<div style="display:inline-block;">
								<select class='custom-date' id="time_value" name='select'>
									<option value='1' <if condition="$day eq 1">selected</if>>今天</option>
									<option value='7' <if condition="$day eq 7">selected</if>>7天</option>
									<option value='30' <if condition="$day eq 30">selected</if>>30天</option>
									<option value='180' <if condition="$day eq 180">selected</if>>180天</option>
									<option value='365' <if condition="$day eq 365">selected</if>>365天</option>
									<option value='custom' <if condition="$period">selected</if>>{pigcms{$period|default='自定义'}</option>
								</select>
							</div>
							<span class="mar_l_10"><button id="search" class="button">搜索</button></span>
						</div>
					</td>
                    <td>
                        <a href="{pigcms{:U('Deliver/export', array('status' => $status, 'timetype' => $timetype, 'time' => $time, 'day' => $day, 'phone'=> $phone, 'period' => $period, 'order_from' => $order_from))}" class="button" style="float:right;margin-right: 10px;">导出订单</a>
                    </td>
				</tr>
                <tr>
                    <td>
                    <b>该筛选条件下总记录数：{pigcms{$res_count.0.count}</b>　
                    <b>总配送费：￥{pigcms{$res_count.0.total_freight|default='0.00'}</b>　
                    <b>订单总价格：￥{pigcms{$res_count.0.total_money|default='0.00'}</b>　
                    <b>小费总额：￥{pigcms{$res_count.0.tip_price|default='0.00'}</b>　
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
								
								<th>客户信息</th>
								<!--th>支付方式</th-->
								<th>支付状态</th>
								<th>订单价格</th>
								<th>应收现金</th>
								<th>配送状态</th>
								<th>配送员信息</th>
								<th>开始时间</th>
								<th>送达时间</th>
								<th>店员接单时长</th>
								<th>配送员接单时长</th>
								<th>配送时长</th>
								<th>接单类型</th>
								<th>分配配送员</th>
								<th>订单详情</th>
								<th>操作</th>
								<!--th>创建时间</th-->
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($supply_info)">
								<volist name="supply_info"  id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td width="50">{pigcms{$vo.supply_id}</td>
										<td width="50"><if condition="$vo['order_from'] eq 0">{pigcms{$config.shop_alias_name}<elseif condition="$vo['order_from'] eq 1" />饿了么<elseif condition="$vo['order_from'] eq 2" />美团<elseif condition="$vo['order_from'] eq 3" />帮我送<elseif condition="$vo['order_from'] eq 4 OR $vo['order_from'] eq 5" />帮我买</if></td>
										<!--td width="50">{pigcms{$vo.group}</td-->
										<td width="80"><if condition="$vo['item'] eq 3">{pigcms{$vo.from_site}<else/>{pigcms{$vo.storename}</if></td>
										<td width="150">{pigcms{$vo.username}<br/>{pigcms{$vo.userphone}<br/>{pigcms{$vo.aim_site}</td>
										<!--td width="50">{pigcms{$vo.pay_type}</td-->
										<td width="50">{pigcms{$vo.paid}</td>
										<td width="50">{pigcms{$vo.money|floatval}</td>
										<td width="50">{pigcms{$vo.deliver_cash|floatval}</td>
										<td width="50">{pigcms{$vo.order_status}</td>
										<td width="50">{pigcms{$vo.name}<br/>{pigcms{$vo.phone}</td>
										<td width="80">{pigcms{$vo.start_time}</td>
										<td width="80">{pigcms{$vo.end_time}</td>
										<td width="80">{pigcms{$vo.confirm_time}</td>
										<td width="80">{pigcms{$vo.grab_time}</td>
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
								
										<if condition="$vo['status'] eq 0" >
										<font color="red">订单失效</font>
										<elseif condition="$vo['status'] eq 1" />
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/appoint_deliver',array('supply_id' => $vo['supply_id']))}','指派配送员',480,380,true,false,false,editbtn,'edit',true);">指派配送员</a>
										<elseif condition="$vo['status'] lt 5" />
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/appoint_deliver',array('supply_id' => $vo['supply_id']))}','更换配送员',480,380,true,false,false,editbtn,'edit',true);" style="color:red">更换配送员</a>
                                        <elseif condition="$vo['status'] eq 7" />
                                        <font color="red">退款失败</font>
                                        <else />
										<font color="green">配送完成</font>
										</if>
										</td>
										<td width="80">
										<if condition="$vo['item'] eq 3">
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Service/offer_info',array('publish_id' => $vo['order_id'],'frame_show'=>true))}','查看订单信息',600,600,true,false,false,false,'detail',true);">查看</a>
										<elseif condition="$vo['item'] eq 2" />
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','查看{pigcms{$config.shop_alias_name}订单详情',720,520,true,false,false,false,'detail',true);">查看</a>
										</if>
										</td>
                                        <td width="80">
                                        <if condition="!in_array($vo['status'], array(2,3,4))">
                                        ---
                                        <else />
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/edit_supply_status',array('supply_id'=>$vo['supply_id']))}','修改配送状态',500,200,true,false,false,false,'edit',true);" style="color:green" data-supply="{pigcms{$vo['supply_id']}" class="change">修改配送状态</a>
                                        </if>
                                        </td>
										<!--td width="50">{pigcms{$vo.create_time}</td-->
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="19">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="19">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
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
			var phone = $("#phone").val(), status = $('#status').val(), timetype = $('#timetype').val(), time = $('#time').val(), order_from = $('#order_from').val();
			var day = '', period = '';
			if($('#time_value option:selected').attr('value')=='custom'){
				period = $('#time_value option:selected').html();
			}else{
				day = $('#time_value option:selected').attr('value');
			}
			location.href = "{pigcms{:U('Merchant/Deliver/deliverList')}"+"&period="+period+"&phone="+phone+"&day="+day+"&status="+status+"&order_from="+order_from+"&timetype="+timetype+"&time="+time;
		}
		// $('.change').click(function(){
		// 	var supply_id = $(this).attr('data-supply'), obj = $(this);
		// 	window.top.art.dialog({
		// 		lock: true,
		// 		content: '您确定要将该配送订单修改成配送完成吗？修改后相应的订单状态变成已消费！',
		// 		ok: function(){
		// 			$.get("{pigcms{:U('Deliver/change')}", {supply_id:supply_id}, function(response){
		// 				if (response.error_code) {
		// 					window.top.msg(0, response.msg);
		// 				} else {
		// 					window.top.msg(1, response.msg,true);
		// 					location.reload();
		// 				}
		// 			}, 'json');
		// 		},
		// 		cancel: true
		// 	});
		// });
	});
</script>
<style>
.drp-popup{top:90px !important}
.deliver_search input{height: 20px;}
.deliver_search select{height: 24px;}
.deliver_search .mar_l_10{margin-left: 10px;}
.deliver_search .btn{height: 23px;line-height: 16px; padding: 0px 12px;}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/date-picker/index.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/date-picker/index.css" />
<include file="Public:footer"/>