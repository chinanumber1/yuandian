<include file="Store:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cutlery"></i>
				{pigcms{$config.meal_alias_name}订单列表
			</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<span class="red">温馨提示：您有<b id='js-order-num'>0</b> 个订单未处理，是否要刷新页面？</span><button onclick="reload();">刷新</button>
					<div class="alert alert-block alert-success">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>
						</button>	
						<p>
							注意:在每行的输入框里可以通过输入您想要搜索的订单的关键词<br/>
							在对应的标题下输入对应的关键词后按【Enter】即可搜索
						</p>
					</div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="50">订单号</th>
									<th id="shopList_c1" width="50">下单人姓名</th>
									<th id="shopList_c0" width="80">下单人电话</th>
									<th width="80">消费码</th>
									<th id="shopList_c0" width="100">下单人地址</th>
									<th id="shopList_c3" width="80">下单时间</th>
									<th id="shopList_c3" width="80">订单总价</th>
									<th id="shopList_c3" width="50">优惠后金额</th>
									<th id="shopList_c3" width="50">应收</th>
									<th id="shopList_c4" width="80">支付状态</th>
									<th id="shopList_c4" width="80">订单状态</th>
									<th id="shopList_c4" width="50">余额支付金额</th>
									<th id="shopList_c4" width="50">在线支付金额</th>
									<th id="shopList_c4" width="50">使用商家会员卡余额</th>
									<th id="shopList_c5" width="130" >菜单详情</th>
									<th id="shopList_c5" width="120" >顾客留言</th>
									<th class="button-column">操作</th>
<!-- 									<th class="button-column" width="70">操作</th> -->
								</tr>
							</thead>
							<tbody>
								<tr class="filters">
									<form method="post" action="" id="queryForList">
									<td><input id="order_id" name="order_id" type="text" maxlength="20" value="{pigcms{$order_id}"/></td>
									<td><input id="name" name="name" type="text" maxlength="20"  value="{pigcms{$name}"/></td>
									<td><input id="phone" name="phone" type="text" maxlength="20"  value="{pigcms{$phone}"/></td>
									<td><input id="meal_pass" name="meal_pass" type="text" maxlength="20"  value="{pigcms{$meal_pass}"/></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
<!-- 									<td>&nbsp;</td> -->
									</form>
								</tr>
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td><div class="tagDiv">{pigcms{$vo.order_id}</div></td>
										<td><div class="tagDiv">{pigcms{$vo.address_info.name}</div></td>
										<td><div class="shopNameDiv">{pigcms{$vo.address_info.phone}</div></td>
										<td><div class="shopNameDiv">{pigcms{$vo.code}</div></td>
										<td>{pigcms{$vo.address_info.address} {pigcms{$vo.address_info.detail}</td>
										<td>{pigcms{$vo.create_time|date="Y-m-d H:i:s",###}</td>
										<td>{pigcms{$vo.price}</td>
										<td>{pigcms{$vo.discount_price}</td>
										<td>{pigcms{$vo.discount_price}</td>
										<td>
											<if condition="$vo['paid'] eq 0">未支付
											<elseif condition="$vo['pay_type'] eq 'offline' AND empty($vo['third_id'])" />
											<span class="red">线下支付　未付款</span>
											<elseif condition="$vo['paid'] eq 1"/>已付<span class="red">{pigcms{$vo.pay_money}</span>
											<elseif condition="$vo['paid'] eq 1"/><span class="green">全额支付</span>
											</if>
										</td>
										<td>
											<if condition="$vo['order_status'] eq 0">订单失效
											<elseif condition="$vo['order_status'] eq 1"/>订单完成
											<elseif condition="$vo['order_status'] eq 2"/>未确认
											<elseif condition="$vo['order_status'] eq 3"/>已确认
											<elseif condition="$vo['order_status'] eq 6"/>退单
											<elseif condition="$vo['order_status'] eq 7"/>已取消
											</if> 
										</td>
										<td>{pigcms{$vo.balance_pay}</td>
										<td>{pigcms{$vo.online_pay}</td>
										<td>{pigcms{$vo.merchant_balance}</td>
										<td>
										<volist name="vo['order_info']" id="menu">
										{pigcms{$menu['goods_info']['name']}:{pigcms{$menu['price']}*{pigcms{$menu['num']}</br>
										</volist>
										</td>
										<td>{pigcms{$vo.desc}</td>
										<td class="button-column" width="50">
											<if condition="$vo['order_status'] eq 3" >
												<a title="已接单" class="green edit_btn" style="padding-right:8px;" href="javascript:;" >已接单</a>
											<elseif condition="$vo['order_status'] neq 7"  />
												<if condition="$vo['order_status'] eq 1">
													订单完成<br/>
													<a title="操作订单" class="green edit_btn js-add-order js-add-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">重新接单</a>
												<else/>
													<a title="操作订单" class="green edit_btn js-add-order js-add-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">接单</a>
												</if>
											</if>
										</td>
<!-- 										<td class="button-column" width="60"> 
											<if condition="$vo['order_status'] neq 7" > 
											<a title="取消订单" class="green edit_btn js-cancel-order js-cancel-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">取消订单</a>
											<elseif condition="$vo['order_status'] eq 7"  /> 
											<a title="订单已经取消" class="green edit_btn" style="padding-right:8px;" href="javascript:;">订单已取消</a>
											</if> 
										</td> -->
									</tr>
								</volist>
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
<script type="text/javascript">
$(function(){
	showOrderNum();
	setInterval ("showOrderNum()", 60000);
	jQuery(document).on('click','.js-cancel-order',function(){
        if(!confirm('确定要取消这个订单吗?不可恢复。')) return false;
         
        	var order_id = $(this).attr('js-order');
        	$.post("{pigcms{:U('Store/waimai_add')}",{order_id:order_id,status:2},function(result){
        		if(result.status == 1){
        			$('.js-cancel-order-'+order_id).html('取消成功');
        			$('.js-cancel-order').removeClass('js-cancel-order');
        		}else{
        			alert('取消失败');
        			window.location.reload();
        		}
        	})
        })
})
function showOrderNum()
{
	$.post("{pigcms{:U('Store/waimai_num')}",{},function(result){
		if(result.status == 1){
			$('#js-order-num').html(result.info);
		}
	})
}
document.onkeydown = function(event_e) {
	if(window.event) event_e = window.event;  
	var int_keycode = event_e.charCode||event_e.keyCode;  
	if(int_keycode ==13 && ($('#order_id').val() != '' || $('#phone').val() != '' || $('#name').val() != '' || $('#meal_pass').val() != '')) $('#queryForList').submit();
}
$('.js-add-order').click(function(){
	var order_id = $(this).attr('js-order');
	$('.js-add-order-'+order_id).html('处理中');
	$.post("{pigcms{:U('Store/waimai_add')}",{order_id:order_id,status:1},function(result){
		if(result.status == 1){
			$('.js-add-order-'+order_id).html(result.info);
			$('.js-add-order').removeClass('js-add-order');
			showOrderNum();
		}else{
			alert(result.info);
			window.location.reload();
		}
	})
})
function reload(){
	window.location.href="{pigcms{:U('Store/waimai')}";
}
</script>
<include file="Public:footer"/>
