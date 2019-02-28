<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Market/sell_order')}">进销存</a>
			</li>
			<li class="active">销售订单</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50" class="button-column">订单号</th>
									<th width="50" class="button-column">商品图片</th>
									<th class="button-column">商品名称</th>
									<th width="100" class="button-column">单价 (元)</th>
									<th width="100" class="button-column">批发数量</th>
									<th width="80" class="button-column">总价 (元)</th>
									<th width="100" class="button-column">优惠明细</th>
									<th width="100" class="button-column">买家信息</th>
									<th width="80" class="button-column">订单状态</th>
									<th width="100" class="button-column">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$orders">
									<volist name="orders" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td class="button-column">{pigcms{$vo.order_id}</td>
											<td class="button-column"><img src="{pigcms{$vo.pic}" width="50" height="50"></td>
											<td class="button-column">{pigcms{$vo.name}</td>
											<td class="button-column">{pigcms{$vo.price|floatval}</td>
											<td class="button-column">{pigcms{$vo.num}({pigcms{$vo.unit})</td>
											<td class="button-column">{pigcms{$vo.money|floatval}</td>
											<td>{pigcms{$vo.discount_info_txt}</td>
											<td>
											                 商家名:{pigcms{$vo.merchant_name}<br/>
											                 商家电话:<span style="color:green">{pigcms{$vo.merchant_phone}</span><br/>
											                 联系人:{pigcms{$vo.username}<br/>
											                 联系电话:<span style="color:green">{pigcms{$vo.userphone}</span><br/>
											                 收货地址:{pigcms{$vo.address}<br/>
											</td>
											<td class="button-column">{pigcms{$vo.status_txt}</td>
											<td class="button-column">
											    <if condition="$vo['status'] eq 1 OR $vo['status'] eq 2">
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Market/push',array('goods_id'=>$vo['goods_id'], 'order_id'=>$vo['order_id']))}">发货</a>
											    </if>
											    <a title="操作订单" class="green handle_btn" href="{pigcms{:U('Market/order_detail',array('order_id'=>$vo['order_id'], 'type' => 'sell'))}">
												<i class="ace-icon fa fa-search bigger-130"></i>
										        </a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="10" >暂无订单信息</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
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
			width: 1500,
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