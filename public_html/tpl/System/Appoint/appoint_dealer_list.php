<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Appoint/product_list')}">预约列表</a>
					<a href="{pigcms{:U('Appoint/appoint_dealer_list')}" class="on">订单列表</a>
				</ul>
			</div>

			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<!--<th>订单信息</th>-->
								<th>订单用户</th>
								<th>查看用户信息</th>
								<th>订单状态</th>
								<th>服务状态</th>
								<!--<th>支付详情</th>-->
								<!--<th>时间</th>-->
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($order_list)">
								<volist name="order_list" id="vo">
									<tr>
										<td>{pigcms{$vo.order_id}</td>
										<!--<td>
											定金：￥ {pigcms{$vo.payment_money}<br/>
											总价：￥ {pigcms{$vo.appoint_price}<br>
										</td>-->
										<td>
											用户昵称：{pigcms{$vo.nickname}<br/>
											手机号：{pigcms{$vo.phone}
										</td>
										<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','查看服务详情',680,560,true,false,false,false,'detail',true);">查看用户信息</a></td>
										<td>
											<if condition="$vo['paid'] eq 0"><span style="color:red">未支付</span>
											<elseif condition="$vo['paid'] eq 1" /><span style="color:green">已支付</span>
											<elseif condition="$vo['paid'] eq 2" /><span style="color:blue">已退款</span>
											</if>
											&nbsp;|&nbsp;
											<if condition="$vo['is_del'] eq 0"><span style="color:green">未取消</span>
										<elseif condition="($vo['is_del'] eq 1) || ($vo['is_del'] eq 5)" /><span style="color:red">已取消【用户】</span>
											<elseif condition="$vo['is_del'] eq 2" /><span style="color:red">已取消【平台】</span>
											<elseif condition="$vo['is_del'] eq 3" /><span style="color:red">已取消【商家】</span>
											<elseif condition="$vo['is_del'] eq 4" /><span style="color:red">已取消【店员】</span>
											</if>
										</td>
										<td>
											<if condition="$vo['service_status'] eq 0"><span style="color:red">未服务</span>
											<elseif condition="($vo['service_status'] eq 1) || ($vo['service_status'] eq 2)" /><span style="color:green">已服务</span>
											</if>
										</td>
										<!--<td>
											平台余额支付：{pigcms{$vo.balance_pay} <br>
									 		商家会员卡余额支付：{pigcms{$vo.merchant_balance}<br>
									 		在线支付金额：<if condition="$vo['paid'] == 1" >{pigcms{$vo['pay_money']}<else/>0.00</if><br> 
										</td>-->
										<!--<td>
											下单时间：{pigcms{$vo.order_time|date="Y-m-d H:i:s",###}<br/>
											付款时间：{pigcms{$vo.order_time|date="Y-m-d H:i:s",###}
										</td>-->
										<td class="textcenter">
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','查看服务详情',660,490,true,false,false,false,'detail',true);">查看详情</a>
											<if condition='!$vo["is_del"]'>
											&nbsp;|&nbsp;<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/allot_merchant',array('order_id'=>$vo['order_id']))}','分配订单',660,200,true,false,false,editbtn,'add',true);">分配订单</a>
											</if>
									  		<!-- <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/product_detail',array('appoint_id'=>$vo['appoint_id']))}','编辑预约信息',480,<if condition="$vo['appoint_id']">240<else/>340</if>,true,false,false,editbtn,'edit',true);">编辑</a> -->
									  	</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="8">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>