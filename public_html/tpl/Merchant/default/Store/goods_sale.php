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
					<li class="urlLink" data-url="{pigcms{:U('goods')}">
						<div class="icon list"></div>
						<div class="text">待发商品清单</div>
					</li>
					<li class="urlLink cur" data-url="{pigcms{:U('goods_sale')}">
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
						商品销售统计：指的是您指定的时段内用户已消费的商品总计。
						<br/>
					</p>
				</div>
				<div class="form-group" style="margin-top:15px;">
					<form id="myform" method="post" action="{pigcms{:U('Store/goods_sale')}" >
                        <div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
						<label style="margin-top: 8px;">订单来源:</label>
						<select name="orderFrom">
                            <option value="0" <if condition="0 eq $orderFrom">selected="selected"</if>>全部</option>
                            <option value="1" <if condition="1 eq $orderFrom">selected="selected"</if>>商城</option>
                            <option value="2" <if condition="2 eq $orderFrom">selected="selected"</if>>{pigcms{$config.shop_alias_name}</option>
                            <option value="3" <if condition="3 eq $orderFrom">selected="selected"</if>>线下零售</option>
						</select>
                        <label style="margin-top: 8px;">商品分类:</label>
                        <select name="sort_id">
                            <option value="0" <if condition="0 eq $sortId">selected="selected"</if>>全部</option>
                            <volist name="sort_list" id="sort">
                            <option value="{pigcms{$sort['sort_id']}" <if condition="$sort['sort_id'] eq $sortId">selected="selected"</if>>{pigcms{$sort['sort_name']}</option>
                            </volist>
                        </select>
                        </div>
                        <div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
                        <label style="float:left;margin-top: 8px;">开始结束时间 ：</label>
                        <input type="text" class="col-sm-2" name="begin_time" style="width:180px;margin-right: 10px;" value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly/>
                        <input type="text" class="col-sm-2" name="end_time" style="width:180px;margin-left: 10px;" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly/>&nbsp;&nbsp;&nbsp;
                        <input type="submit" class="btn btn-sm btn-success" style="padding:4px 12px;"/>
                        </div>
                        <a href="{pigcms{:U('Store/sort_export', array('sort_id' => $sortId, 'stime' => $begin_time, 'etime' => $end_time, 'orderFrom' => $orderFrom))}" class="btn btn-success" style="float:right;margin-right: 10px;">导出数据</a>
					</form>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>订单来源</th>
								<th>商品名</th>
								<th>商品属性</th>
								<th>数量</th>
								<th>单价</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$list">
								<volist name="list" id="vo">
									<tr>
										<!-- <td <if condition="$vo['row'] gt 1">rowspan="{pigcms{$vo['row']}"</if>>{pigcms{$vo.name}</td>
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
										</if> -->
                                        <td><php>if ($vo['order_from'] == 1) { </php>商城<php>}elseif ($vo['order_from'] == 6) { </php>线下零售<php>}else{ </php>{pigcms{$config['shop_alias_name']}<php>}</php></td>
                                        <td>{pigcms{$vo.name}</td>
                                        <td>{pigcms{$vo.spec}</td>
                                        <td>{pigcms{$vo.num}</td>
                                        <td><if condition="$vo['discount_price'] gt 0">{pigcms{$vo['discount_price']|floatval}<else/>{pigcms{$vo['price']|floatval}</if></td>
                                        
									</tr>
								</volist>
							<else/>
								<tr class="odd"><td class="button-column" colspan="5" >暂无该筛选条件的商品销售统计。</td></tr>
							</if>
						</tbody>
					</table>
                    {pigcms{$pagebar}
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