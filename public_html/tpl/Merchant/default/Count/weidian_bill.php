<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active">商家账单</li>
			<li class="active"><a href="{pigcms{:U('Count/weidian_store', array('store_id' => $fid))}">微店店铺的分销商</a></li>
			<li class="active">分销商的账单列表</li>
		</ul>
	</div>

	
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<div class="tabbable" style="margin-top:20px;">
								<div class="row">					
									<div class="col-xs-12">		
										<div class="widget-body">　
											<input type="hidden" id="store_id" value="{pigcms{$store_id}" />
											<button class="btn btn-success" style="margin-left: -16px;">确定对账</button>					
										</div>
										<div class="grid-view">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th><input type="checkbox" id="all_select"/></th>
														<th>编号号</th>
														<th>订单号</th>
														<th>交易号</th>
														<th>下单时间</th>
														<th>支付时间</th>
														<th>订单总金额</th>
														<th>获得利润</th>
														<th>对账状态</th>
													</tr>
												</thead>
												<tbody>
													<if condition="$bill_list">
														<volist name="bill_list" id="vo">
															<tr>
																<td><if condition="$vo['is_pay_bill'] eq 0"><input type="checkbox" value="{pigcms{$vo.order_id}" class="select" data-price="{pigcms{$vo.check_amount}"/></if></td>
																<td>{pigcms{$vo.order_id}</td>
																<td>{pigcms{$vo.order_no}</td>
																<td>{pigcms{$vo.trade_no}</td>
																<td>{pigcms{$vo.add_time}</td>
																<td>{pigcms{$vo.paid_time}</td>
																<td>{pigcms{$vo.total}</td>
																<td>{pigcms{$vo.check_amount}</td>
																<td><if condition="$vo['is_pay_bill'] eq 0"><strong style="color: red">未对账</strong><else /><strong style="color: green"><strong type="color:green">已对账</strong></if></td>
															</tr>
														</volist>
														<tr class="even">
															<td colspan="9">
																本页总金额：<strong style="color: green">{pigcms{$total_price}</strong>　
																本页总利润：<strong style="color: green">{pigcms{$total_profit}</strong>　
																本页已对账利润：<strong style="color: red">{pigcms{$finish_profit}</strong>
															</td>
														</tr>
														<tr class="odd">
															<td colspan="9" id="show_count"></td>
														</tr>
														<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
														<!--tr><td class="textcenter pagebar" colspan="3">{pigcms{$pagebar}</td></tr-->	
													<else/>
														<tr class="odd"><td class="textcenter red" colspan="9" style="text-align: center;">该分销商还没有订单记录。</td></tr>
													</if>
												</tbody>
											</table>
										</div>						
									</div>
									<!--div class="col-xs-2" style="margin-top: 15px;">
										<a class="btn btn-success" href="#">导出成excel</a>
									</div-->
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#all_select').click(function(){
		if ($(this).attr('checked')){
			$('.select').attr('checked', true);
		} else {
			$('.select').attr('checked', false);
		}
		total_price();
	});
	$('.select').click(function(){total_price();});
	
	$('.btn-success').click(function(){
		var strids = '';
		var pre = ''
		$('.select').each(function(){
			if ($(this).attr('checked')) {
				strids += pre + $(this).val();
				pre = ',';
			}
		});
		if (strids.length > 0) {
			$.get("{pigcms{:U('Count/save_bill')}", {'strids':strids, 'store_id':$('#store_id').val()}, function(data){
				if (data.error_code == 0) {
					location.reload();
				}
			}, 'json');
		}
	});
});


function total_price()
{
	var total = 0;
	$('.select').each(function(){
		if ($(this).attr('checked')) {
			total += parseFloat($(this).attr('data-price'));
		}
	});
	total = Math.round(total * 100)/100;
	var percent = $('#percent').val();
	if (total > 0) {
		$('#show_count').html('对账总金额：<strong style=\'color:red\'>￥' + total + '</strong>');
	} else {
		$('#show_count').html('');
	}
}
</script>
<include file="Public:footer"/>
