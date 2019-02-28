<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active">未对账订单列表</li>

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
        <div id="nav" class="mainnav_title">
                <ul>
					<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'meal'))}" <if condition="$type eq 'meal'">class="on"</if>>{pigcms{$config.meal_alias_name}账单<span id="meal"></span></a>
					<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'group'))}" <if condition="$type eq 'group'">class="on"</if>>{pigcms{$config.group_alias_name}账单<span id="group"></span></a>
					<if condition="$config['appoint_page_row'] gt 0">
					<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'appoint'))}" <if condition="$type eq 'appoint'">class="on"</if>>{pigcms{$config.appoint_alias_name}账单<span id="appoint"></span></a>
					</if>
					<if condition="$config['waimai_alias_name']">
						<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'waimai'))}" <if condition="$type eq 'waimai'">class="on"</if>>{pigcms{$config.waimai_alias_name}账单<span id="waimai"></span></a>
					</if>
					<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'shop'))}" <if condition="$type eq 'shop'">class="on"</if>>{pigcms{$config.shop_alias_name}账单<span id="shop"></span></a>
					<if condition="$config['is_cashier'] OR $config['pay_in_store']">
					<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'store'))}" <if condition="$type eq 'store'">class="on"</if>>到店付账单<span id="store"></span></a>
					</if>
					<if condition="$config['is_open_weidian']">
						<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'weidian'))}" <if condition="$type eq 'weidian'">class="on"</if>>微店账单<span id="weidian"></span></a>
					</if>
					<if condition="$config['wxapp_url']">
						<a href="{pigcms{:U('Bill/order',array('mer_id'=>$mer_id, 'type' => 'wxapp'))}" <if condition="$type eq 'wxapp'">class="on"</if>>营销账单<span id="wxapp"></span></a>
					</if>
				</ul>
        </div>
	
        <style type="text/css">
            .mainnav_title {line-height:40px;/* height:40px; */border-bottom:1px solid #eee;color:#31708f;}
            .mainnav_title a {color:#004499;margin:0 5px;padding:4px 7px;background:#d9edf7;}
            .mainnav_title a:hover ,.mainnav_title a.on{background:#498CD0;color:#fff;text-decoration: none;}
        </style>
      
        <div class="alert alert-info" style="margin:10px;">
            <div class="info" style="font-size:16px;font-family: 'Arial Negreta','Arial';font-weight: 700;">餐饮对账单明细  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{pigcms{:U('Bill/export',array('mer_id'=>$mer_id, 'type' => $type))}" class="on"><button>导出未对账的账单</button></a></div> 
			<div class="info">上一次对账时间：<if condition="$bill_time">{pigcms{$bill_time|date="Y/m/d H:i",###}<else />无</if></div>
			<div class="info">对账关系：<font color="#C3BEBE">平台>{pigcms{$now_merchant['name']}</font></div>
			<div class="info">待对账订单总数：<font color="red">{pigcms{$un_bill_count}</font></div>
			<div class="info">待对账总额：<font id = "all_bill_money" color="red"></font></div>
            <!--时间筛选-->
            <form id="myform" method="post" action="{pigcms{:U('Bill/order')}" >
                <input type="hidden" name="mer_id" value="{pigcms{$mer_id}">
                <input type="hidden" name="type" value="{pigcms{$type}">
                <div style="float:left"><font color="#000">时间筛选 ：</font></div>
                <input type="text" class="input fl" name="begin_time" style="width:120px;height:32px;" id="d4311"  value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
                <input type="text" class="input fl" name="end_time" style="width:120px;height:32px;" id="d4311" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
                <input type="submit">
            </form>
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
														<th><input type="checkbox" id="all_select"/></th>
												
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
													<if condition="$order_list">
														<volist name="order_list" id="vo">
															<tr>
																<td><if condition="$vo['is_pay_bill'] eq 0"><input type="checkbox" value="{pigcms{$vo.name}_{pigcms{$vo.order_id}" class="select" data-price="{pigcms{:sprintf("%.2f",$vo['order_price'])}" system_pay="{pigcms{:sprintf("%.2f",$vo['score_deducte']+$vo['coupon_price'],2)}" <if condition="$type eq 'group'">refund-fee="{pigcms{$vo.refund_fee}" refund-money="{pigcms{$vo.refund_money}"</if>  payment_money="{pigcms{$vo.payment_money}" balance_pay="{pigcms{$vo.balance_pay}"/></if></td>
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
																		<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Merchant/Meal/order_detial',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
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
	
	$('#group_id').change(function(){
		$('#frmselect').submit();
	});
	 $.ajax({
		url:'{pigcms{:U('Bill/get_un_bill_count')}',
		type:"post",
		dataType:"JSON",
		data: {mer_id:{pigcms{$mer_id}},
		beforeSend: function(){
			$('.mainnav_title a.on div').html('(数据加载中)');
		},
		success:function(date){
			$.each(date.un_bill_count, function(index, val) {
				$('#'+index).html('(待对账：'+val+')');
			});
			
			$('#all_bill_money').html(date.all_bill_money+' 元');

		}
	 });
	
	
	$('.mainnav_title a').hover(function(){
		$(".mainnav_title .on div").css('color','#7EBAEF');
		$(this).children('span').css('color','#EDEDED');
	}, function(event){
		$(this).children('span').css('color','#7EBAEF');
	});
});



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
});


function total_price()
{
	var total = 0;
	var system_pay = 0;
	$('.select').each(function(){
		if ($(this).attr('checked')) {
			if(parseFloat($(this).attr('refund-fee'))>0||parseFloat($(this).attr('refund-money'))>0){
				total += parseFloat($(this).attr('data-price'))-parseFloat($(this).attr('refund-money'));
				system_pay +=parseFloat($(this).attr('system_pay')) + parseFloat($(this).attr('balance_pay')) + parseFloat($(this).attr('payment_money'))-parseFloat($(this).attr('refund-money'));
			}else{
				total += parseFloat($(this).attr('data-price'));
				system_pay += parseFloat($(this).attr('system_pay')) + parseFloat($(this).attr('balance_pay')) + parseFloat($(this).attr('payment_money'));
			}
		}
	});
	total = Math.round(total * 100)/100;
	system_pay = Math.round(system_pay * 100)/100;
	var percent = $('#percent').val();

	if (total > 0) {
		$('#show_count').html('账单总计金额：<strong style=\'color:red\'>￥' + total + '</strong>, 平台对该商家的抽成比例是：<strong style=\'color:green\'>' + percent + '%</strong>, 平台抽成金额：<strong style=\'color:green\'>￥' + Math.round(system_pay * percent) /100 + '</strong>,平台支付金额：<strong style=\'color:green\'>￥' + Math.round(system_pay*100)/100 + '</strong>,商家应得金额:<strong style=\'color:red\'>￥' + Math.round((system_pay - Math.round(system_pay * percent) /100) * 100)/100 + '</strong>');
	} else {
		$('#show_count').html('');
	}
}


</script>
<include file="Public:footer"/>
