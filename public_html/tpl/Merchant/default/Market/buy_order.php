<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Market/buy_order')}">进销存</a>
			</li>
			<li class="active">进货订单</li>
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
									<th width="100" class="button-column">卖家信息</th>
									<th width="80" class="button-column">订单状态</th>
									<th width="200" class="button-column">操作</th>
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
											                 店铺名:{pigcms{$vo.store_name}<br/>
											                 店铺电话:<span style="color:green">{pigcms{$vo.merchant_phone}</span><br/>
											</td>
											<td class="button-column">{pigcms{$vo.status_txt}</td>
											<td class="">
											    <if condition="$vo['status'] eq 10">
                                                <php>if ($vo['fid']) {</php>
                                                <a title="去支付" class="green" style="padding-right:8px;" href="{pigcms{:U('Market/cart',array('fid'=>$vo['fid']))}">支付订单</a>|
                                                <php> } else { </php>
												<a title="去支付" class="green" style="padding-right:8px;" href="{pigcms{:U('Market/buy',array('goods_id'=>$vo['goods_id'], 'order_id'=>$vo['order_id']))}">支付订单</a>|
                                                <php>} </php>
												<a title="删除" class="green" onclick="artconcancel({pigcms{$vo.order_id});" href="javascript:void(0);">删除订单</a>|
											    <elseif condition="$vo['status'] eq 1" />
                                                <a title="删除" class="green" onclick="artconcancel({pigcms{$vo.order_id});" href="javascript:void(0);">删除订单</a>|
											    <elseif condition="$vo['status'] eq 2" />
											    <a class="btn btn-success" onclick="artconfirms({pigcms{$vo.order_id});">收货</a>  
											    <elseif condition="$vo['status'] eq 3" />
                                                    <if condition="$vo['store_count'] eq 1">
                                                    <a title="上架" class="green" style="padding-right:8px;" href="{pigcms{:U('Market/add_to_store',array('order_id' => $vo['order_id'], 'store_id' => $vo['to_store_id']))}">上架</a>
                                                    <elseif condition="$vo['store_count'] gt 1" />
                                                    <a title="上架" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Market/select_store', array('order_id' => $vo['order_id']))}">上架</a>
                                                    </if>
											    </if>
											    <a title="操作订单" class="green handle_btn" href="{pigcms{:U('Market/order_detail', array('order_id' => $vo['order_id'], 'type' => 'buy'))}">查看详情</a>
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
function artconfirms(order_id) {
	artDialog({	
			content:'您确认您已经收到货了，确认后您的钱就付给对方，不能返回了！',
			lock:true,
			style:'succeed noClose'
		},
		function(){
			$.post("{pigcms{:U('pull')}", {'order_id':order_id}, function(response){
			     if (response.error_code) {
			    	 artDialog({content:response.msg});
			     } else {
			    	 window.location.reload();
			     }
			}, 'json');
		}
	);
}
function artconcancel(order_id) {
    artDialog({ 
            content:'您确定要删除该订单吗？',
            lock:true,
            style:'succeed noClose'
        },
        function(){
            $.post("{pigcms{:U('cancel')}", {'order_id':order_id}, function(response){
                 if (response.status == 0) {
                     artDialog({content:response.info});
                 } else {
                     window.location.reload();
                 }
            }, 'json');
        }
    );
}
</script>
<include file="Public:footer"/>