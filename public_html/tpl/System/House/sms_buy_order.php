<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('House/sms_buy_order')}" class="on">小区列表</a>
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
							<col/>
							<col/>
							<col/>
						</colgroup>
						<thead>
							<tr>
								<th>订单ID</th>
								<th>社区</th>
								<th>订单编号</th>
								<th>购买人</th>
								<th>付款时间</th>
								<th>支付金额</th>
								<th>购买数量（条）</th>
								<th>类型</th>
								<th>支付方式</th>
								<th>当前短信剩余条数</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($sms_buy_orde_list)">
								<volist name="sms_buy_orde_list" id="vo">
									<tr>
										<td>{pigcms{$vo.order_id}</td>
										<td>{pigcms{$vo.village_name}</td>
										<td>{pigcms{$vo.orderid}</td>
										<td>{pigcms{$vo.type_id}</td>
										<td>{pigcms{$vo.pay_time|date='Y-m-d H:i:s',###}</td>
										<td>{pigcms{$vo.payment_money}</td>
										<td>{pigcms{$vo.sms_number}</td>
										<td><if condition="$vo['pay_type'] eq weixin">微信<elseif condition="$vo['pay_type'] eq 'yue'"/>余额<else/>管理员操作</if></td>
										<td><if condition="$vo['pay_type'] eq weixin">微信<elseif condition="$vo['pay_type'] eq 'yue'"/>余额<else/>管理员操作</if></td>
										<td>{pigcms{$vo.current_number}</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="10">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>
<include file="Public:footer"/>