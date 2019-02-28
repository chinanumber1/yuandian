<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">配送员管理</a>|
					<a href="{pigcms{:U('Deliver/log_list', array('uid'=>$user['uid']))}" class="on">【{pigcms{$user['name']}】的配送记录</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Deliver/log_list')}" method="get">
							<input type="hidden" name="c" value="Deliver"/>
							<input type="hidden" name="a" value="log_list"/>
							<input type="hidden" name="uid" value="{pigcms{$user['uid']}"/>
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
							配送完成时间: <!--input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>用户ID</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>昵称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>手机号</option>
							</select-->
							<input type="text" class="input-text" name="begin_time" style="width:160px;" value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>&nbsp;&nbsp;&nbsp;到&nbsp;&nbsp;&nbsp;
							<input type="text" class="input-text" name="end_time" style="width:160px;" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>&nbsp;&nbsp;&nbsp;
							<input type="submit" value="查询" class="button"/>
							&nbsp;&nbsp;&nbsp;该筛选条件下总记录数：{pigcms{$res_count.0.count}&nbsp;&nbsp;&nbsp;总配送距离：{pigcms{$res_count.0.total_distance|default='0.00'}公里&nbsp;&nbsp;&nbsp;总配送费：￥{pigcms{$res_count.0.total_freight|default='0.00'}&nbsp;&nbsp;&nbsp;订单总价格：￥{pigcms{$res_count.0.total_money|default='0.00'}&nbsp;&nbsp;&nbsp;小费总额：￥{pigcms{$res_count.0.tip_price|default='0.00'}
							<a href="{pigcms{:U('Deliver/export_user', array('begin_time' => $begin_time, 'end_time' => $end_time, 'uid' => $user['uid']))}" class="button" style="float:right;margin-right: 10px;">导出订单</a>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
<!-- 								<th>订单ID</th> -->
								<th>订单来源</th>
								<!--th>配送员类型</th-->
								<th>店铺名称</th>
								<th>客户昵称</th>
								<th>客户手机</th>
								<th>客户地址</th>
								<!--th>支付方式</th-->
								<th>支付状态</th>
								<th>订单价格</th>
								<th>配送费</th>
								<th>小费</th>
								<th>配送状态</th>
								<th>开始时间</th>
								<th>结束时间</th>
								<th>配送时长</th>
								<th>应收现金</th>
								<!--th>创建时间</th-->
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($supply_info)">
								<volist name="supply_info"  id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
<!-- 										<td width="30">{pigcms{$vo.order_id}</td> -->
										<td><if condition="$vo['item'] eq 0">{pigcms{$config.meal_alias_name}<elseif condition="$vo['item'] eq 1" />外送系统<elseif condition="$vo['item'] eq 2" />{pigcms{$config.shop_alias_name}<elseif condition="$vo['item'] eq 3" /><php>if ($vo['server_type'] == 1) { echo '帮我送'; }else{echo '帮我买'; }</php></if></td>
										<!--td width="50">{pigcms{$vo.group}</td-->
										<td>{pigcms{$vo.storename}</td>
										<td>{pigcms{$vo.username}</td>
										<td>{pigcms{$vo.userphone}</td>
										<td>{pigcms{$vo.aim_site}</td>
										<!--td width="50">{pigcms{$vo.pay_type}</td-->
										<td>{pigcms{$vo.paid}</td>
										<td>{pigcms{$vo.money|floatval}</td>
										<td>{pigcms{$vo.freight_charge|floatval}</td>
										<td>{pigcms{$vo.tip_price|floatval}</td>
										<td>{pigcms{$vo.order_status}</td>
										<td>{pigcms{$vo.start_time}</td>
										<td>{pigcms{$vo.end_time}</td>
										<td>{pigcms{$vo.deliver_use_time}</td>
										<td style="color:red">{pigcms{$vo.deliver_cash|floatval}</td>
<!-- 										<td width="80">{pigcms{$vo.end_time}</td> -->
										<!--td width="50">{pigcms{$vo.create_time}</td-->
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="17">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="17">列表为空！</td></tr>
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