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
				<a href="{pigcms{:U('Store/goods_stock_export')}" class="btn btn-success down_excel">导出表格</a>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>									
								<th id="shopList_c1" width="50">商品编号</th>
								<th id="shopList_c1" width="50">商品名称</th>
								<th id="shopList_c0" width="80">商品价格</th>
								<th id="shopList_c0" width="80">剩余库存</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$goods_list">
								<volist name="goods_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									
										<td><div class="tagDiv">{pigcms{$vo.number}</div></td>
										<td><div class="tagDiv">{pigcms{$vo.name}</div></td>
										<td><div class="shopNameDiv">{pigcms{$vo.price|floatval}</div></td>
										<td><b style="color:red">{pigcms{$vo.stock_num}</b></td>
									</tr>
								</volist>
							<else/>
								<tr class="odd"><td class="button-column" colspan="30" >您的店铺暂时还没有订单。</td></tr>
							</if>
						</tbody>
					</table>
					{pigcms{$pagebar}
				</div>
			</div>
		</div>
	</body>
</html>