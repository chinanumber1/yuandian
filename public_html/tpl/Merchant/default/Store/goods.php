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
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">{pigcms{$config.shop_alias_name}</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink" data-url="{pigcms{:U('shop_list')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink cur" data-url="{pigcms{:U('goods')}">
						<div class="icon list"></div>
						<div class="text">待发商品清单</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('goods_sale')}">
						<div class="icon list"></div>
						<div class="text">商品销售统计</div>
					</li>
                    <if condition="$config['eleme_app_key'] OR $config['meituan_sign_key']">
                    <li class="urlLink" data-url="{pigcms{:U('shop_order_report_form')}">
                        <div class="icon list"></div>
                        <div class="text">各平台订单统计</div>
                    </li>
                    </if>
					<if condition="$config['pay_in_store']">
					<li class="urlLink" data-url="{pigcms{:U('market')}">
						<div class="icon list"></div>
						<div class="text">线下零售</div>
					</li>
					</if>
				</ul>
			</div>
			<div class="rightMain">
				<div class="alert waring" style="background-color: #f9cdcd;border-color: #f9cdcd;color: #8c2a2a;display:none;">
					<i class="ice-icon fa fa-volume-up bigger-130"></i>
					<p>您有部分商品库存小于10,请及时 <a title="库存报警商品例表"  data-title="库存报警商品例表" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('shop_goods_stock')}">查看</a>！</p>
				</div>
				<div class="alert alert-block alert-success">
					<p>
						待发商品清单：指定是用户已经下单，等待您确认发货的商品统计。
					</p>
				</div>
				<div class="form-group" style="margin-top:15px;">
					<form id="myform" method="post" action="{pigcms{:U('Store/goods')}" >
						<label class="col-sm-1" style="margin-top: 8px;">开始结束时间 ：</label>
						<input type="text" class="col-sm-2" name="begin_time" style="width:180px;margin-right: 10px;" value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly/>
						<input type="text" class="col-sm-2" name="end_time" style="width:180px;margin-left: 10px;" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly/>&nbsp;&nbsp;&nbsp;
						<input type="submit" class="btn btn-sm btn-success" style="padding:4px 12px;"/>
					</form>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>商品名</th>
								<th>商品属性</th>
								<th>数量</th>
								<th>总数量</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$list">
								<volist name="list" id="vo">
									<tr>
										<td <if condition="$vo['row'] gt 1">rowspan="{pigcms{$vo['row']}"</if>>{pigcms{$vo.name}</td>
										<volist name="vo['list']" id="row" key="j">
										<if condition="$j gt 1">
										<tr>
										</if>
										<td>{pigcms{$row.spec}</td>
										<td>{pigcms{$row.total}</td>
										<if condition="$j eq 1 && $vo['row'] gt 1">
											<td rowspan="{pigcms{$vo['row']}">{pigcms{$vo.count}</td>
										</if>
										<if condition="$j lt $vo['row']">
										</tr>
										</if>
										</volist>
										<if condition="$vo['row'] eq 1">
										<td>{pigcms{$vo.count}</td>
										</if>
									</tr>
								</volist>
							<else/>
								<tr class="odd"><td class="button-column" colspan="4" >您的店铺暂时还没有发货清单。</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
<script>
function check_shop_goods_stock()
{
	$.get("{pigcms{:U('Store/check_shop_goods_stock')}", function(result){
		if(result.status == 1){
			$('.waring').show();
		} else {
			$('.waring').hide();
		}
		setTimeout(function(){
			check_shop_goods_stock();
		},6000);
	}, 'json');
}
$(document).ready(function(){
	check_shop_goods_stock();
});
</script>
</html>