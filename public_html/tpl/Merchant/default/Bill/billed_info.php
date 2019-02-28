<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Bill/billed_list')}">已对账列表</a></li>
			<li class="active">已对账详情</li>

		</ul>
	</div>
	<style>
	.mainnav_title{
		margin-top:20px;
	}
	.mainnav_title ul a {
		padding: 15px 20px;
	}
	ul, ol {
		margin-bottom: 15px;
	}
	.mainnav_title span{
		color:#7EBAEF;
		
	}
	.mainnav_title a.on div{
		color:#C1BEBE;
	}
	.all{
		border-collapse:collapse;
		border:none;
	}
	.all td{
		border:solid #000 1px;
		border-color:"#cccc99";
		height: 20px;
		text-align: center;
	}
	.all th{
		border:solid #000 1px;
		border-color:"#cccc99";
		height: 20px;
	}
	button{
		padding: 6px;
		background-color: rgb(241, 235, 235);;
		box-sizing: border-box;
		border-width: 1px;
		border-style: solid;
		border-color: rgba(121, 121, 121, 1);
		border-radius: 2px;
		-moz-box-shadow: none;
		-webkit-box-shadow: none;
		box-shadow: none;
		font-size: 14px;
		color: #666666;
		cursor: pointer;

	}
	</style>
        

      
        <div class="alert alert-info" style="margin:10px;">
				<div class="info" style="font-size:16px;font-family: 'Arial Negreta','Arial';font-weight: 700;">餐饮对账单明细  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{pigcms{:U('Bill/export',array('mer_id'=>$mer_id, 'type' => $type,'bill_id'=>$bill_info['id']))}" class="on"><button>导出EXCEL</button></a></div> 
				<div class="info">对账时间：{pigcms{$bill_info.bill_time|date="Y/m/d H:i",###}</div>
				<div class="info">对账关系：<font color="#C3BEBE">平台>{pigcms{$now_merchant['name']}</font></div>
				<div class="info">已对账订单总数:{pigcms{$bill_info.count}</div>
            <!--时间筛选-->
			<table class="all">
				<thead>
					<tr>
						<th>平台总支付金额</th>
						<th>平台抽成商家比例</th>
						<th>平台抽成金额</th>
						<th>商家应得金额</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{pigcms{$order_list.total}元</td>
						<td>{pigcms{$percent}%</td>
						<td>{pigcms{$order_list['total']*$percent/100}</td>
						<td>{pigcms{$bill_info['money']/100}元</td>
					</tr>
				</tbody>
			</table>
           
        </div>

	
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<div class="tabbable" >
								<div class="row">					
									<div class="col-xs-12">		
										<div class="grid-view">
										<div><span style="color:#000;">未对账订单数量：<font color="red">{pigcms{$un_bill_count}</font></span></div>
											<table class="table table-striped table-bordered table-hover">
												<thead>
													
													<tr>
												
														<th>店铺名称</th>
														<th>订单号</th>
														<th>订单详情</th>
														<th>订单数量</th>
														<th>总额</th>
														<th>应对帐金额</th>
														<if condition="($type eq 'group')">
															<th>已退款金额</th>
															<th>退款手续费</th>
														</if>
														
														<th>支付时间</th>
													
                                                        <if condition="($type eq 'meal') or ($type eq 'group')"><th>状态</th></if>
														<th>操作</th>
													</tr>
												</thead>
												<tbody>
													<if condition="$order_list.order_list">
														<volist name="order_list.order_list" id="vo">
															<tr>
																<td>{pigcms{$vo.store_name}</td>
																<td>{pigcms{$vo.order_id}</td>
																<td>
																
																<if condition="$type eq 'meal'">
																	<volist name="vo['order_name']" id="menu">
																	{pigcms{$menu['name']}:{pigcms{$menu['price']}*{pigcms{$menu['num']}</br>
																	</volist>
																<elseif condition="$vo['name'] eq 3" />
																<a title="操作订单" class="green handle_btn" href="{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id']))}">
																	<i class="ace-icon fa fa-search bigger-130"></i>
																</a>
																<else />
																{pigcms{$vo.order_name}
																</if>
																</td>
																<td>{pigcms{$vo.total}</td>
																<td>{pigcms{$vo['order_price']|floatval}</td>
																<if condition="$type eq 'group'">
																<td>{pigcms{$vo['score_deducte']+$vo['coupon_price']+$vo['balance_pay']+$vo['payment_money']-$vo['refund_money']+$vo['refund_fee']}</td>
																<else />
																<td>{pigcms{$vo['score_deducte']+$vo['coupon_price']+$vo['balance_pay']+$vo['payment_money']}</td>
																</if>
																<if condition="$type eq 'group'">
																	<td>{pigcms{$vo.refund_money}</td>
																	<td>{pigcms{$vo.refund_fee}</td>
																</if>
																<td><if condition="$vo['pay_time'] gt 0">{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</if></td>
																<if condition="($type eq 'meal') or ($type eq 'group')">
                                                                                                                                    <td>
																	<if condition="$vo['paid'] eq 0">
																		未付款
																	<elseif condition="$vo['status'] eq 6" />
																		部分退款
																	<else />
																		<if condition="$vo['pay_type'] eq 'offline' AND empty($vo['third_id'])">线下未支付
																		<elseif condition="$vo['status'] eq 0" />未消费
																		<elseif condition="$vo['status'] eq 1" />未评价
																		<elseif condition="$vo['status'] eq 2" />已完成
																		</if>
																	</if>
                                                                                                                                    </td>
																</if>
																<td>
																	<if condition="$type eq 'group'">
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Merchant/Group/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	<elseif condition="$type eq 'meal'" />
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Merchant/Meal/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	<elseif condition="$type eq 'appoint'" />
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Appoint/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	<elseif condition="$type eq 'weidian'" />
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Weidian/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	<elseif condition="$type eq 'waimai'" />
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Waimai/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	<elseif condition="$type eq 'wxapp'" />
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Wxapp/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	<elseif condition="$type eq 'shop'" />
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	<elseif condition="$type eq 'store'" />
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Store/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130"></i>
																		</a>
																	</if>
																</td>
															</tr>
														</volist>
														<input type="hidden" id="percent" value="{pigcms{$percent}" />
														
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
$(function(){
	$('.handle_btn').live('click',function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'操作订单',
			padding: 0,
			width: 720,
			height: 520,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
	});
	

});




</script>
<include file="Public:footer"/>
