<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-cny"></i>
			<li class="active"><a href="{pigcms{:U('Merchant_money/discount_detail')}">商家账单</a></li>
			<li class="active">商家统一折扣记录</li>

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
	button, .button{
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
	
	#myform div{
		margin-top:10px;
	}
	</style>
    <div style="margin:10px 10px 3px 10px;">
		<form id="myform" method="post" action="{pigcms{:U('Merchant_money/discount_detail')}" style="display:inline;">
			<div>
				订&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;单：<input type="text" name="order_id" value="{pigcms{$order_id}" placeholder="输入订单号" >
			</div>
			<input type="hidden" name="mer_id" value="{pigcms{$mer_id}">
			<div>
			<div style="float:left"><font color="#000">时间筛选 ：</font></div>
			<input type="text" class="input fl" name="begin_time" id="d4311"  value="{pigcms{$begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
			<input type="text" class="input fl" name="end_time" id="d4312" value="{pigcms{$end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
			<input type="submit" value="查询"> 　　　　　
            <a href="{pigcms{:U('Merchant_money/exportDiscount', array('order_id' => $order_id, 'begin_time' => $begin_time, 'end_time' => $end_time))}" class="button">导出订单</a>
			</div>
            <span style="font-size:18px;color:red;">商家统一折扣优惠总金额：{pigcms{$now_merchant.discount_price}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;商家统一折扣优惠总单数：{pigcms{$now_merchant.discount_num}</span>
		</form>
    </div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<div class="tabbable" >
								<div class="row">					
									<div class="col-xs-13">		
										<div class="grid-view">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
                            							<th>店铺名称</th>
                            							<th>订单号</th>
                            							<th>订单总额</th>
                            							<th>商家统一折扣比例</th>
                            							<th>商家统一折扣优惠总金额</th>
                            							<th>支付时间</th>
                            							<th>操作</th>
													</tr>
												</thead>
												<tbody>
                                                    <if condition="$order_list">
                                                    <volist name="order_list" id="vo">
                                                        <tr>
                                                            <td>{pigcms{$vo.name}</td>
                                                            <td>{pigcms{$vo.real_orderid}</td>
                                                            <td>{pigcms{$vo.price|floatval}</td>
                                                            <td>{pigcms{:floatval($vo['discount']/10)} 折</td>
                                                            <td>{pigcms{$vo.discount_money|floatval}</td>
                                                            <td>{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</td>
                                                            <td>
                                                                <php> if ($vo['order_from'] == 1) {</php>
                                                                    <a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Merchant/Foodshop/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
                                                                        <i class="ace-icon fa fa-search bigger-130">查看详情</i>
                                                                    </a>
                                                                <php> } elseif ($vo['order_from'] == 0) {</php>
                                                                    <a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
                                                                        <i class="ace-icon fa fa-search bigger-130">查看详情</i>
                                                                    </a>
                                                                <php> } </php>
                                                            </td>
                                                        </tr>
                                                    </volist>
                                                        <tr><td class="textcenter pagebar" colspan="12">{pigcms{$pagebar}</td></tr> 
                                                    <else/>
                                                        <tr class="odd"><td class="textcenter red" colspan="12" >暂时还没记录。</td></tr>
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
        var thiswidth = $(this).data('width');
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'操作订单',
			padding: 0,
			width: thiswidth,
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

// function exports(){
	// var order_type = $('select[name="order_type"]').val();
	// var order_id = $('input[name="order_id"]').val();
	// var begin_time = $('input[name="begin_time"]').val();
	// var end_time = $('input[name="end_time"]').val();
	// var store_id = $('#store_id').val();
	// if(order_type=='all'&&order_id!=''){
		// alert('该分类下没有不能填订单ID');
	// }else{
		// var export_url ="{pigcms{:U('Bill/export',array('mer_id'=>$mer_id, 'type' => 'income'))}&order_type="+order_type+'&order_id='+order_id+'&begin_time='+begin_time+'&end_time='+end_time+'&store_id='+store_id;
		// window.location.href = export_url;
	// }
// }

		 var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Bill/export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>
