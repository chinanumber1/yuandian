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
		<div class="mainBox">
			<div class="rightMain">
					
				<div class="alert alert-block alert-success" style="margin:10px 0;">
					<b>{pigcms{$stime}至{pigcms{$etime}</b>　
					<b>{pigcms{$pay_type_title}：{pigcms{$total_money|floatval}</b>
					<a href="{pigcms{$report_export}" class="button down_excel" style="float:right;margin-right: 10px;">下载报表</a>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th id="shopList_c1" width="50">订单号</th>
								<th id="shopList_c1" width="50">用户姓名</th>
								<th id="shopList_c0" width="80">用户电话</th>
								<th id="shopList_c5" width="50">订单总价</th>
								<th id="shopList_c4" width="50">优惠金额</th>
								<th id="shopList_c4" width="50">实付金额</th>
								<th id="shopList_c4" width="50">在线支付金额</th>
								<th id="shopList_c4" width="50">线下支付金额</th>
								<th id="shopList_c3" width="80">支付时间</th>
								<th id="shopList_c4" width="70">支付类型</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$order_list">
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td><div class="tagDiv">{pigcms{$vo.real_orderid}</div></td>
										<td><div class="tagDiv">{pigcms{$vo.username}</div></td>
										<td><div class="shopNameDiv">{pigcms{$vo.userphone}</div></td>
										<td>{pigcms{$vo.total_price|floatval}</td>
										<td>{pigcms{$vo.discount_price|floatval}</td>
										<td>{pigcms{$vo.price|floatval}</td>
										<td>{pigcms{$vo.online_price|floatval}</td>
										<td>{pigcms{$vo.offline_price|floatval}</td>
										<td>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</td>
										<td>{pigcms{$vo.pay_type}</td>
									</tr>
								</volist>
							<else/>
								<tr class="odd"><td class="button-column" colspan="12" >暂无记录。</td></tr>
							</if>
						</tbody>
					</table>
					{pigcms{$pagebar}
				</div>
			</div>
		</div>
	</body>
</html>