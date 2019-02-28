<include file="Public:header"/>


<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active">账单</li>
			<li class="active">商家与店员对账</li>
		</ul>
	</div>
        <style type="text/css">
            .mainnav_title {line-height:40px;/* height:40px; */border-bottom:1px solid #eee;color:#31708f;}
            .mainnav_title a {color:#004499;margin:0 5px;padding:4px 7px;background:#d9edf7;}
            .mainnav_title a:hover ,.mainnav_title a.on{background:#498CD0;color:#fff;text-decoration: none;}
        </style>
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Count/staff_bill',array('mer_id'=>$mer_id, 'type' => 'meal', 'staffid' => $staffid))}" <if condition="$type eq 'meal'">class="on"</if>>{pigcms{$config.meal_alias_name}账单</a>
			<a href="{pigcms{:U('Count/staff_bill',array('mer_id'=>$mer_id, 'type' => 'group', 'staffid' => $staffid))}" <if condition="$type eq 'group'">class="on"</if>>{pigcms{$config.group_alias_name}账单</a>
			<a href="{pigcms{:U('Count/staff_bill',array('mer_id'=>$mer_id, 'type' => 'shop', 'staffid' => $staffid))}" <if condition="$type eq 'shop'">class="on"</if>>{pigcms{$config.shop_alias_name}账单</a>
		</ul>
	</div>
	<div class="alert alert-info" style="margin:10px;">
		<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>只统计已消费的订单
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<div class="tabbable" >
								<div class="row">					
									<div class="col-xs-12">
										<div class="widget-body">
											<label for="name">店员选择：</label>
											<select name="staff_id" id="staff_id">
											<option value="0"  <if condition="$staffid eq 0">selected</if>>全部成员</option>
											<volist name="staffs" id="vo">
											<option value="{pigcms{$vo['id']}" <if condition="$staffid eq $vo['id']">selected</if>>{pigcms{$vo['name']}</option>
											</volist>
											</select>　　
											<button class="btn btn-success">确定对账</button>					
										</div>
										<div class="grid-view">
										<div><span style="color:#000;">未对账订单数量：<font color="red">{pigcms{$uncount}</font></span></div>
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th><input type="checkbox" id="all_select"/></th>
														<th>订单号</th>
														<th>总价</th>
														<th>在线支付金额</th>
														<th>平台余额支付金额</th>
														<th>商家会员卡余额支付金额</th>
														<th>平台优惠券抵现</th>
														<th>商户优惠券抵现</th>
														<th>{pigcms{$config['score_name']}抵现</th>
														<th>店员收取现金</th>
														<th>下单时间</th>
														<th>支付时间</th>
														<th>对账状态</th>
													</tr>
												</thead>
												<tbody>
													<if condition="$order_list">
														<volist name="order_list" id="vo">
															<tr>
																<td><if condition="$vo['is_pay_bill'] eq 0"><input type="checkbox" value="{pigcms{$vo.name}_{pigcms{$vo.order_id}" class="select" data-price="{pigcms{$vo.cash}"/></if></td>
																<td>{pigcms{$vo.real_orderid}</td>
																<td>{pigcms{$vo.price|floatval}</td>
																<td>{pigcms{$vo['payment_money']|floatval}</td>
																<td>{pigcms{$vo.balance_pay|floatval}</td>
																<td>{pigcms{$vo.merchant_balance|floatval}</td>
																<td>{pigcms{$vo['coupon_price']|floatval}</td>
																<td>{pigcms{$vo['card_price']|floatval}</td>
																<td>{pigcms{$vo['score_deducte']|floatval}</td>
																<td>{pigcms{$vo['cash']|floatval}</td>
																<td>{pigcms{$vo.create_time|date="Y-m-d H:i:s",###}</td>
																<td>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</td>
																<td><if condition="$vo['is_pay_bill'] eq 0"><strong style="color: red">未对账</strong><else /><strong style="color: green"><strong type="color:green">已对账</strong></if></td>
															</tr>
														</volist>
														<input type="hidden" id="percent" value="{pigcms{$percent}" />
														<tr class="even" style="display:none">
															<td colspan="16">
															<if condition="$percent">
															平台的抽成比例：<strong style="color: green">{pigcms{$percent}%</strong> <br/>
															本页总金额：<strong style="color: green">{pigcms{$total}</strong>　本页已出账金额：<strong style="color: red">{pigcms{$finshtotal} * {pigcms{$percent}%</strong><br/> 
															总金额：<strong style="color: green">{pigcms{$alltotal+$alltotalfinsh}</strong>　总已出账金额：<strong style="color: red">{pigcms{$alltotalfinsh} * {pigcms{$percent}%</strong><br/>
															<strong>本页平台应获取的抽成金额：</strong><strong style="color: green">{pigcms{$total*$percent/100}</strong><br/>
															<strong>平台应获取的抽成金额：</strong><strong style="color: green">{pigcms{$alltotal+$alltotalfinsh-$all_total_percent}</strong><br/>
															<strong>本页应获取的金额：</strong><strong style="color: green">{pigcms{$total_percent}</strong><br/>
															<strong>应获取的总金额：</strong><strong style="color: red">{pigcms{$all_total_percent}</strong><br/>
															<else />
																本页总金额：<strong style="color: green">{pigcms{$total}</strong>　本页已出账金额：<strong style="color: red">{pigcms{$finshtotal}</strong><br/> 
																总金额：<strong style="color: green">{pigcms{$alltotal+$alltotalfinsh}</strong>　总已出账金额：<strong style="color: red">{pigcms{$alltotalfinsh}</strong><br/>
															</if>
															</td>
														</tr>
														<tr class="odd">
															<td colspan="19" id="show_count"></td>
														</tr>
														<tr><td class="textcenter pagebar" colspan="19">{pigcms{$pagebar}</td></tr>	
													<else/>
														<tr class="odd"><td class="textcenter red" colspan="19" >该的店铺暂时还没有订单。</td></tr>
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
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
	$('#staff_id').change(function(){
		location.href = "{pigcms{:U('Count/staff_bill', array('type' => $type))}&staffid=" + $(this).val();
	});
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
			$.get("{pigcms{:U('Count/change')}", {strids:strids}, function(data){
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
	if (total > 0) {
		$('#show_count').html('账单总计金额：<strong style=\'color:red\'>￥' + total.toFixed(2) + '</strong>');
	} else {
		$('#show_count').html('');
	}
}
</script>
<include file="Public:footer"/>
