<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
	<title>{pigcms{$config.site_name} - 店员管理中心</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
</head>
	<body>
		<div class="mainBox">
			<div class="rightMain">
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="50">订单编号</th>
								<th width="80">客户姓名</th>
								<th width="80">客户电话</th>
								<th width="80" class="button-column">预订金</th>
								<th width="80">预订时间</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$list">
							<volist name="list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td>{pigcms{$vo.real_orderid}</td>
									<td>{pigcms{$vo.name}</td>
									<td>{pigcms{$vo.phone}</td>
									<td class="button-column">{pigcms{$vo.book_price|floatval}</td>
									<if condition="$vo['book_time']">
									<td>{pigcms{$vo.book_time|date='Y-m-d H:i:s',###}</td>
									<else />
									<td>--</td>
									</if>
								</tr>
							</volist>
							<else />
							<tr>
								<td colspan="5" style="text-align: center;">该餐台暂无预定</th>
							</tr>
							</if>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>

</html>