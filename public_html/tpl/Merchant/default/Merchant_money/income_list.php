<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Merchant_money/index')}">商家余额</a></li>
			<li class="active">收入记录</li>

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
	
	#myform div{
		margin-top:10px;
	}
	</style>
        

        <div style="margin:10px;">
				<div class="info" style="font-size:16px;font-family: 'Arial Negreta','Arial';font-weight: 700;">收入记录  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="exports();"><button>导出EXCEL</button></a>
				<if condition="$config.open_extra_price eq 1"><a href="{pigcms{:U('Merchant_money/score_log')}" ><button>线下{pigcms{$config.score_name}记录</button></a></if></div> 
				<form id="myform" method="post" action="{pigcms{:U('Merchant_money/income_list')}" style="display:inline;">
					<div>
						业务类型：
						<select name="order_type">
							<volist name="alias_name" id="vo">
								<option value="{pigcms{$key}" <if condition="$order_type eq $key">selected=selected</if>>{pigcms{$vo}</option>
							</volist>
						</select>
					</div>
					<div>
						店&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;铺：<select id="store_id" name="store_id" onchange="javascript:frmselect.submit()">
								<option value="">选择店铺</option>
								<volist name="store_list" id="vo">
									<option value="{pigcms{$vo.store_id}" <if condition="$store_id eq $vo['store_id']" >selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
					<div>
						订&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;单：<input type="text" name="order_id" value="{pigcms{$order_id}" placeholder="输入订单号" >
					</div>
					<input type="hidden" name="mer_id" value="{pigcms{$mer_id}">
					<div>
					<div style="float:left"><font color="#000">时间筛选 ：</font></div>
					<input type="text" class="input fl" name="begin_time" id="d4311"  value="{pigcms{$begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
					<input type="text" class="input fl" name="end_time" id="d4312" value="{pigcms{$end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
					<input type="submit" value="查询"> <span style="font-size:18px;color:red;">总计 ：{pigcms{$total}<div style="font-size:12px;display:inline">(消费收入:{pigcms{$income_total|floatval}<if condition="$recharge_total gt 0">，充值总额为：{pigcms{$recharge_total|floatval}</if>)</div> <if condition="$config.open_extra_price eq 1">&nbsp;&nbsp;&nbsp;送出{pigcms{$config.score_name}总计 ：{pigcms{$total_score|floatval} 个{pigcms{$config.score_name}</if><div style="font-size:12px;display:inline"></div></span>
					</div>
				</form>
           
        </div>

	
	<!-- 内容头部 -->
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
														<th>订单类型</th>
														<th>订单详情</th>
														<th>数量</th>
														<th>总额</th>
														<if condition="$config.open_extra_price eq 1"><th>送出{pigcms{$config.score_name}数</th>
														<th>用户消费{pigcms{$config.score_name}数</th>
														</if>
														<th>平台佣金<font color="red" size="1">(提现代表手续费)</font></th>
														<th>支付时间</th>
														<th>当前商家余额</th>
														<th>操作</th>
													</tr>
												</thead>
												<tbody>
													<if condition="$income_list">
														<volist name="income_list" id="vo">
															<tr>
																<if condition="$vo.type eq 'weidian'"><td>微店</td><elseif condition='$vo.is_autotrophic eq 1' /><td>平台自营</td><else /><td><if condition="$vo.store_id gt 0">{pigcms{$vo.store_name}</if></td></if>
															
															
																<td><if condition="$vo.type eq 'withdraw'">w_{pigcms{$vo.id}<else />{pigcms{$vo.order_id}</if></td>
																<td><?php echo $alias_name[$vo['type']];?></td>
																<td>
																
																{pigcms{:msubstr($vo['desc'],0,50,true,'utf-8')}
																</td>
																<td>{pigcms{$vo.num}</td>
																<td><if condition="$vo.income eq 1"><font color="#2bb8aa">+{pigcms{$vo.money|floatval}</font><elseif condition="$vo.income eq 2" /><font color="#f76120">-{pigcms{$vo.money|floatval}</font></if></td>
																<if condition="$config.open_extra_price eq 1">
																<td>{pigcms{$vo.score|intval}</td>
																<td>{pigcms{$vo.score_count|floatval}</td>
																</if>
																<td><font color="#5167de">{pigcms{$vo.system_take} <if condition="$vo['type'] eq 'withdraw' AND $vo['percent'] gt 0"> （提现手续费）<elseif condition="$vo['system_take'] gt 0" />（抽成比例 {pigcms{$vo.percent} %）</if></font></td>
																<td><if condition="$vo['use_time'] gt 0">{pigcms{$vo.use_time|date="Y-m-d H:i:s",###}</if></td>
																<td><font color="#5167de">{pigcms{$vo.now_mer_money}</font></td>
																<td style="font-size:12px;">
																	<if condition="$vo.type eq 'group'">
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Merchant/Group/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'meal'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Merchant/Foodshop/order_detail',array('order_id'=>$vo['order_id'],'store_id'=>$vo['store_id']))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'appoint'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Appoint/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'weidian'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Orderdetail/weidain_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'waimai'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Waimai/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'wxapp'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Orderdetail/wxapp_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'shop' OR $vo.type eq 'shop_offline'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'store' OR $vo.type eq 'cash'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Orderdetail/store_detail',array('order_id'=>$vo['order_id'],'from'=>'bill'))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
												
																	<elseif condition="$vo.type eq 'withdraw'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Merchant_money/withdraw_order_info',array('id'=>$vo['order_id']))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'coupon'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Orderdetail/coupon_detail',array('order_id'=>$vo['order_id'],'mer_id'=>$mer_id))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'yydb'" />
																		<a title="操作订单" class="green handle_btn" data-width="720" style="padding-right:8px;" href="{pigcms{:U('Orderdetail/yydb_detail',array('order_id'=>$vo['order_id']))}">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
																	<elseif condition="$vo.type eq 'market'" />
																		<a title="操作订单" class="green handle_btn" data-width="1500" style="padding-right:8px;" href="{pigcms{:U('Market/order_detail',array('order_id'=>$vo['order_id']))}&type=<if condition="$vo['income'] eq 1">sell<else />buy</if>">
																			<i class="ace-icon fa fa-search bigger-130">查看详情</i>
																		</a>
                                                                    <elseif condition="$vo.type eq 'marketmulti'" />
                                                                        <a title="操作订单" class="green handle_btn" data-width="1500" style="padding-right:8px;" href="{pigcms{:U('Market/order_detail',array('fid'=>$vo['order_id']))}&type=<if condition="$vo['income'] eq 1">sell<else />buy</if>">
                                                                            <i class="ace-icon fa fa-search bigger-130">查看详情</i>
                                                                        </a>
                                                                    </if>
																</td>
															</tr>
														</volist>
												
														
														<tr class="odd">
															<td colspan="13" id="show_count"></td>
														</tr>
														<tr><td class="textcenter pagebar" colspan="13">{pigcms{$pagebar}</td></tr>	
													<else />
														<tr class="odd"><td class="textcenter red" colspan="13" >暂时还没有收入记录</td></tr>
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
