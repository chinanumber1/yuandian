<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Meal/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li>{pigcms{$now_store['name']}</li>
			<li class="active">订单列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<label class="col-sm-1">订单状态筛选：</label>
						<select id="status" name="status">
							<volist name="status_list" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
							</volist>
						</select>
					</div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="50">订单号</th>
									<th id="shopList_c1" width="50">下单人姓名</th>
									<th id="shopList_c0" width="80">下单人电话</th>
									<th id="shopList_c0" width="80">下单人地址</th>
									<th id="shopList_c5" width="50" >餐台信息</th>
									<th id="shopList_c5" width="50" >使用人数</th>
									<th id="shopList_c5" width="50" >消费类型</th>
									<th id="shopList_c3" width="80">下单时间</th>
									<!--th id="shopList_c3" width="80">预计消费（送达）时间</th-->
									<th id="shopList_c3" width="50">实际总价</th>
									<th id="shopList_c3" width="50">优惠金额</th>
									<th id="shopList_c3" width="100">
									
									<if condition="$type eq 'price'">
										<if condition="$sort eq 'ASC'">
											<a href="{pigcms{:U('Meal/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">应收总价<i class="menu-icon fa fa-sort-desc"></i></a>
										<elseif condition="$sort eq 'DESC'" />
											<a href="{pigcms{:U('Meal/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'ASC', 'status' => $status))}">应收总价<i class="menu-icon fa fa-sort-asc"></i></a>
										<else />
											<a href="{pigcms{:U('Meal/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">应收总价<i class="menu-icon fa fa-sort"></i></a>
										</if>
									<else />
										<a href="{pigcms{:U('Meal/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">应收总价<i class="menu-icon fa fa-sort"></i></a>
									</if>
									</th>
									
									<th id="shopList_c4" width="50">平台余额支付金额</th>
									<th id="shopList_c4" width="50">商家会员卡余额支付金额</th>
									<th id="shopList_c4" width="50">在线支付金额</th>
									<th id="shopList_c4" width="50">平台优惠券金额</th>
									<th id="shopList_c4" width="50">商户优惠券金额</th>
									<th id="shopList_c4" width="50">{pigcms{$config['score_name']}抵扣金额</th>
									<th id="shopList_c4" width="50">店员需收现</th>
									
									<th id="shopList_c4" width="100">验证消费</th>
									<th id="shopList_c4" width="70">支付状态</th>
									<th id="shopList_c4" width="70">订单状态</th>
									<th id="shopList_c5" width="130" >菜单详情</th>
									<th id="shopList_c5" width="120" >顾客留言</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$order_list">
									<volist name="order_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><div class="tagDiv">{pigcms{$vo.order_id}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.name}</div></td>
											<td><div class="shopNameDiv">{pigcms{$vo.phone}</div></td>
											<td>{pigcms{$vo.address}</td>
											<td>{pigcms{$vo.tablename}</td>
											<td>{pigcms{$vo.num}</td>
											<td>
											<if condition="$vo['meal_type'] eq 0">预定
											<elseif condition="$vo['meal_type'] eq 1" />外卖
											<elseif condition="$vo['meal_type'] eq 2" />iPad点餐
											<elseif condition="$vo['meal_type'] eq 3" />堂内点餐
											</if>
											</td>
											<td>{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</td>
											<!--td><if condition="$vo['arrive_time']">{pigcms{$vo.arrive_time|date="Y-m-d H:i:s",###}</if></td-->
											<td><if condition="$vo['total_price'] gt 0">{pigcms{$vo['total_price']|floatval}<else />{pigcms{$vo.price|floatval}</if></td>
											<td>{pigcms{$vo['minus_price']|floatval}</td>
											<td>{pigcms{$vo['price']|floatval}</td>
											
											<td>{pigcms{$vo['balance_pay']|floatval}</td>
											<td>{pigcms{$vo['merchant_balance']|floatval}</td>
											<td>{pigcms{$vo['payment_money']|floatval}</td>
											<td>{pigcms{$vo['coupon_price']|floatval}</td>
											<td>{pigcms{$vo['card_price']|floatval}</td>
											<td>{pigcms{$vo['score_deducte']|floatval}</td>
											<td>
											<if condition="$vo['total_price'] gt 0">
											<strong style="color: red">{pigcms{$vo['total_price']-$vo['minus_price']-$vo['balance_pay']-$vo['merchant_balance']-$vo['payment_money']-$vo['coupon_price']-$vo['card_price']-$vo['score_deducte']|floatval}</strong>
											<else />
											<strong style="color: red">{pigcms{$vo['price']-$vo['balance_pay']-$vo['merchant_balance']-$vo['payment_money']-$vo['coupon_price']-$vo['card_price']-$vo['score_deducte']|floatval}</strong>
											</if>
											</td>
											
											<td><if  condition="!empty($vo['last_staff'])">
											操作人员：<span class="red">{pigcms{$vo['last_staff']}</span><br/>消费时间：<br/>{pigcms{$vo.use_time|date="Y-m-d H:i",###}
											<else/>
											<span class="red">未验证消费</span>
										   </if>
										</td>
											<td>
												<if condition="$vo['paid'] eq 0">未支付
												<elseif condition="$vo['pay_type'] eq 'offline' AND empty($vo['third_id'])" />
												<span class="red">线下支付　未付款</span>
												<elseif condition="$vo['paid'] eq 2"/>已付<span class="red">{pigcms{$vo.pay_money}</span>
												<elseif condition="$vo['paid'] eq 1"/><span class="green">全额支付</span>
												</if>
											</td>
											<td>
												<if condition="$vo['status'] eq 0"><span style="color: red">未使用</span>
												<elseif condition="$vo['status'] eq 1" /><span style="color: green">已使用</span>
												<elseif condition="$vo['status'] eq 2" /><span style="color: green">已评价</span>
												<elseif condition="$vo['status'] eq 3" /><span style="color: red">已退款</span>
												<else /><span style="color: red">已取消</span>
												</if>
											</td>
											<td>
											<volist name="vo['info']" id="menu">
											{pigcms{$menu['name']}:{pigcms{$menu['price']}*{pigcms{$menu['num']}</br>
											</volist>
											<a title="操作订单" class="green handle_btn" style="float:right" href="{pigcms{:U('Meal/order_detail',array('order_id'=>$vo['order_id']))}">
												<i class="ace-icon fa fa-search bigger-130"></i>
											</a>
											</td>
											<td>{pigcms{$vo.note}</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="18" >您的店铺暂时还没有订单。</td></tr>
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
	$('#status').change(function(){
		location.href = "{pigcms{:U('Meal/order', array('store_id' => $now_store['store_id'], 'type' => $type, 'sort' => $sort))}&status=" + $(this).val();
	});
});
</script>
<include file="Public:footer"/>
