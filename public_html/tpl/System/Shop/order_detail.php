<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th colspan="1">订单编号</th>
		<th colspan="6">{pigcms{$order['real_orderid']}</th>
	</tr>
	<if condition="$order.orderid neq 0">
	<tr>
		<th colspan="1">流水号</th>
		<th colspan="6"><if condition="$order['pay_type'] neq 'baidu'">shop_</if>{pigcms{$order['orderid']}</th>
	</tr>
	</if>
	<if condition="$order['shop_pass']">
	<tr>
		<th colspan="1">消费码</th>
		<th colspan="6">{pigcms{$order['shop_pass']}</th>
	</tr>
	</if>
	<tr>
		<th width="180">商品名称</th>
		<th><strong>原价</strong></th>
		<th><strong>优惠类型</strong></th>
		<th><strong>优惠率</strong></th>
		<th><strong>优惠价</strong></th>
		<th>数量</th>
		<th>规格属性详情</th>
	</tr>
	<volist name="order['info']" id="rowset">
	<if condition="!empty($rowset['name'])">
	<tr><td colspan="7">{pigcms{$rowset['name']}</td></tr>
	</if>
	<volist name="rowset['list']" id="vo">
	<tr>
		<th width="180">{pigcms{$vo['name']}</th>
    	<th style="color:#9F0050">{pigcms{$vo['price']|floatval}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo.extra_price}{pigcms{$config.extra_price_alias_name}</if></th>
    	<th style="color:#9F0050"><php>if ($vo['discount_type'] == 1 || $vo['discount_type'] == 4) {echo '店铺折扣';} elseif ($vo['discount_type'] == 2 || $vo['discount_type'] == 5) {echo '分类折扣';} elseif ($vo['discount_type'] == 3) {echo 'VIP折扣';}</php></th>
    	<th style="color:#9F0050"><strong><php>if ($vo['discount_rate']) echo $vo['discount_rate']/10;</php></strong></th>
    	<th style="color:#9F0050"><strong><php>if ($vo['discount_price']) { echo floatval($vo['discount_price']); } else { echo floatval($vo['price']); }</php></strong></th>
    	<th style="color:#9F0050"><strong>{pigcms{$vo['num']}</strong> ({pigcms{$vo['unit']})</th>
		<th>{pigcms{$vo['spec']}</th>
	</tr>
	</volist>
	</volist>
	<if condition="($order.status eq 0 OR $order.status eq 5) AND $order.paid eq 1">
	<tr>
		<th colspan="7"><a href="javascript:void(0)" onclick="refund_confirm();"><font color="blue">手动退款</font></a></th>
	</tr>
	</if>
    <if condition="$order['username']">
	<tr>
		<th colspan="7">客户姓名：{pigcms{$order['username']}</th>
	</tr>
    </if>
    <if condition="$order['userphone']">
	<tr>
		<th colspan="7">客户手机：{pigcms{$order['userphone']}</th>
	</tr>
    </if>
    
	<if condition="$order['register_phone']">
	<tr>
		<th colspan="7" style="color:red">客户注册手机：{pigcms{$order['register_phone']}</th>
	</tr>
	</if>
    <php>if ($order['order_from'] != 6) {</php>
	<if condition="$order['is_pick_in_store'] eq 2">
	<tr>
		<th colspan="7">自提地址：{pigcms{$order['address']}</th>
	</tr>
	<else />
	<tr>
		<th colspan="7">客户地址：{pigcms{$order['address']}</th>
	</tr>
	</if>
	<tr>
		<th colspan="7">配送方式：{pigcms{$order['deliver_str']}</th>
	</tr>
	<tr>
		<th colspan="7">配送状态：{pigcms{$order['deliver_status_str']}</th>
	</tr>
	<if condition="$order['is_pick_in_store'] eq 3 AND $order['express_id']">
	<tr>
		<th colspan="7">快递公司：{pigcms{$order['express_name']}</th>
	</tr>
	<tr>
		<th colspan="7">快递单号：{pigcms{$order['express_number']}</th>
	</tr>
	</if>
	<if condition="$order['deliver_user_info']">
	<tr>
		<th colspan="7">配送员姓名：{pigcms{$order['deliver_user_info']['name']}</th>
	</tr>
	<tr>
		<th colspan="7">配送员电话：{pigcms{$order['deliver_user_info']['phone']}</th>
	</tr>
	</if>
    <php>} else {</php>
    <tr>
        <th colspan="7">订单来源：线下零售 </th>
    </tr>
    <php>}</php>
	<tr>
		<th colspan="7">下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	<if condition="$order['pay_time']">
	<tr>
		<th colspan="7">支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	</if>
	<if condition="$order['expect_use_time']">
	<tr>
		<th colspan="7">期望到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
	<if condition="$order['use_time']">
	<tr>
		<th colspan="7">送达时间：{pigcms{$order['use_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
    <if condition="$supplys['confirm_time']">
        <tr>
            <th colspan="7">店员接单时长：{pigcms{$supplys['confirm_time']}</th>
        </tr>
    </if>
    <if condition="$supplys['order_time']">
        <tr>
            <th colspan="7">店员接单时间：{pigcms{$supplys['order_time']|date="Y-m-d H:i:s",###}</th>
        </tr>
    </if>
    <if condition="$supplys['grab_time']">
        <tr>
            <th colspan="7">配送员接单时长：{pigcms{$supplys['grab_time']}</th>
        </tr>
    </if>
    <if condition="$supplys['start_time']">
        <tr>
            <th colspan="7">配送员接单时间：{pigcms{$supplys['start_time']|date="Y-m-d H:i:s",###}</th>
        </tr>
    </if>
    <if condition="$supplys['deliver_use_time']">
        <tr>
            <th colspan="7">配送时长：{pigcms{$supplys['deliver_use_time']}</th>
        </tr>
    </if>
<!--    <if condition="$supplys['end_time']">-->
<!--        <tr>-->
<!--            <th colspan="7">配送结束时间：{pigcms{$supplys['end_time']|date="Y-m-d H:i:s",###}</th>-->
<!--        </tr>-->
<!--    </if>-->
    <!-- deliver_time_info -->
    <if condition="$delivery_times">
        <volist name="delivery_times" id = "time">
            <tr>
                <th colspan="7">
                    <if condition="$time['status'] eq 3">配送员接单时间：{pigcms{$time['dateline']|date="Y-m-d H:i:s",###}
                        <elseif condition="$time['status'] eq 4" />配送员取货时间：{pigcms{$time['dateline']|date="Y-m-d H:i:s",###}
                        <elseif condition="$time['status'] eq 5" />配送员开始配送时间：{pigcms{$time['dateline']|date="Y-m-d H:i:s",###}
                        <elseif condition="$time['status'] eq 6" />配送员送达时间：{pigcms{$time['dateline']|date="Y-m-d H:i:s",###}
                    </if>
                </th>
            </tr>
        </volist>
    </if>
    <!-- end -->
	<tr>
		<th colspan="7">商品总价：￥{pigcms{$order['goods_price']|floatval} 元<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<if condition="$order['goods_price'] neq $order['discount_price']">折扣后：￥{pigcms{$order['discount_price']|floatval} 元</if></th>
	</tr>
	<if condition="$order['packing_charge'] gt 0">
	<tr>
		<th colspan="7">{pigcms{$store['pack_alias']|default='打包费'}：￥{pigcms{$order['packing_charge']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['freight_charge'] gt 0">
	<tr>
		<th colspan="7">{pigcms{$store['freight_alias']|default='配送费用'}：￥{pigcms{$order['freight_charge']|floatval} 元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<if condition="$order['distance'] gt 0">（配送距离：<font color="red">{pigcms{:round($order['distance']/1000,2)} 公里</font>）【若设置为骑行距离、则是由地图接口提供，会根据当时路况等变化】</if></th>
	</tr>
	</if>
	<if condition="$order['other_money'] gt 0">
	<tr>
		<th colspan="7">加价送的金额：￥{pigcms{$order['other_money']|floatval} 元</th>
	</tr>
	</if>
	<tr>
		<th colspan="7">订单总价：￥{pigcms{$order['total_price']|floatval} 元<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if></th>
	</tr>
    
    <php>if (!empty($order['discount_detail'])) {</php>
    <volist name="order['discount_detail']" id="detail">
    <tr>
        <th colspan="7">优惠信息：
        <if condition="$detail['discount_type'] eq 1">平台首单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) .'</b>)';}</php>
        <elseif condition="$detail['discount_type'] eq 2" />平台满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) . '</b>)';}</php>
        <elseif condition="$detail['discount_type'] eq 3" />商家首单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
        <elseif condition="$detail['discount_type'] eq 4" />商家满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
        <elseif condition="$detail['discount_type'] eq 5" />平台配送订单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) . '</b>)';}</php>
        <elseif condition="$detail['discount_type'] eq 6" />商家配送订单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
        </if>
        </th>
    </tr>
    </volist>
    <php>} else {</php>
	<if condition="$order['merchant_reduce'] gt 0">
	<tr>
		<th colspan="7">店铺优惠：￥{pigcms{$order['merchant_reduce']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['balance_reduce'] gt 0">
	<tr>
		<th colspan="7">平台优惠：￥{pigcms{$order['balance_reduce']|floatval} 元</th>
	</tr>
	</if>
    <php>}</php>
	<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
	<tr>
		<th colspan="7">会员卡：{pigcms{$order['card_discount']|floatval} 折优惠</th>
	</tr>
	</if>
	<tr>
		<th colspan="7">实付金额：￥{pigcms{$order['price']|floatval} 元</th>
	</tr>
	<if condition="$order['score_used_count']">
	<tr>
		<th colspan="7">使用{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </th>
	</tr>
	<tr>
		<th colspan="7">{pigcms{$config.score_name}抵现：￥{pigcms{$order['score_deducte']|floatval} 元</th>
	</tr>
	</if>
			
	<if condition="$order['card_give_money'] gt 0">
	<tr>
		<th colspan="7">会员卡余额：￥{pigcms{$order['card_give_money']|floatval} 元</th>
	</tr>
	</if>
	
	<if condition="$order['merchant_balance'] gt 0">
	<tr>
		<th colspan="7">商家余额：￥{pigcms{$order['merchant_balance']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['balance_pay'] gt 0">
	<tr>
		<th colspan="7">平台余额：￥{pigcms{$order['balance_pay']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['payment_money'] gt 0">
	<tr>
		<th colspan="7">在线支付：￥{pigcms{$order['payment_money']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['card_id']">
	<tr>
		<th colspan="7">店铺优惠券金额：￥{pigcms{$order['card_price']} 元</th>
	</tr>
	</if>
	<if condition="$order['coupon_id']">
	<tr>
		<th colspan="7">平台优惠券金额：￥{pigcms{$order['coupon_price']} 元</th>
	</tr>
	</if>
	<if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])">
	<tr>
		<th colspan="7">线下需支付：￥{pigcms{$order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-$order['coupon_price']|floatval}元</th>
	</tr>
	</if>
	<tr>
		<th colspan="7">支付状态：{pigcms{$order['pay_status']}</th>
	</tr>
	<tr>
		<th colspan="7">支付方式：{pigcms{$order['pay_type_str']}</th>
	</tr>
	<tr>
		<th colspan="7">订单状态：{pigcms{$order['status_str']}<if condition="$order['status'] eq 4">&nbsp;&nbsp;&nbsp;&nbsp;退款时间:{pigcms{$order['last_time']|date="Y-m-d H:i:s",###}</if></th>
	</tr>
    <?php if(!empty($refund_list)){ ?>
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
            <tr>
                <th colspan="4"><strong>退货信息</strong></th>
            </tr>
            <volist name="refund_list" id="rowset">
            <tr>
                <th colspan="4"><strong>退货商品</strong></th>
            </tr>
            <tr>
                <th><strong>商品名称</strong></th>
                <th><strong>单价</strong></th>
                <th><strong>数量</strong></th>
                <th><strong>规格属性详情</strong></th>
            </tr>
            <volist name="rowset['goodsList']" id="vo">
                <tr>
                    <th style="color:#9F0050">{pigcms{$vo['name']}</th>
                    <th style="color:#9F0050">{pigcms{$vo['price']|floatval}</th>
                    <th style="color:#9F0050"><strong>{pigcms{$vo['num']}</strong><php>if ($vo['unit']) {</php> ({pigcms{$vo['unit']})<php>}</php></th>
                    <th style="color:#9F0050">{pigcms{$vo['spec']}</th>
                </tr>
            </volist>

            <tr>
                <th colspan="4">退款备注: <strong style="color:red">{pigcms{$rowset['reason']}</strong></th>
            </tr>

            <tr>
                <th colspan="4">退款金额: <strong style="color:red">￥{pigcms{$rowset['price']} 不含配送费、打包费、优惠金额</strong></th>
            </tr>
            <tr>
                <th colspan="4">退款凭证:
                    <ul class="imgs" style="display:inline">
                        <php>foreach ($rowset['image'] as $image) {</php>
                        <li style="display:inline">
                            <img src="{pigcms{$image}" height="50" class="refundImg"/>
                        </li>
                        <php>}</php>
                    </ul>
                </th>
            </tr>

            <tr>
                <th colspan="4">退款状态:
                    {pigcms{$rowset.showStatus}
                    <if condition="$rowset.reply_content neq ''">
                        &nbsp;&nbsp;&nbsp;&nbsp;理由：<span style="color: red;font-weight: bold;">{pigcms{$rowset.reply_content}</span>
                    </if>
                    &nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$rowset.reply_time|date="Y-m-d H:i",###}
                </th>
            </tr>
            </volist>
        </table>
    <?php } ?>
	<tr>
		<th colspan="7">备注:{pigcms{$order['desc']|default="无"}</th>
	</tr>
	<if condition="$order['invoice_head']">
		<tr>
			<th colspan="7">发票抬头:{pigcms{$order['invoice_head']}</th>
		</tr>
	</if>
	<if condition="$order['cue_field']">
		<tr>
			<th colspan="7">&nbsp;</th>
		</tr>
		<tr>
			<th colspan="7"><strong>分类填写字段</strong></th>
		</tr>
		<volist name="order['cue_field']" id="vo">
			<tr>
				<th colspan="1">{pigcms{$vo.title}</th>
				<th colspan="6">{pigcms{$vo.txt}</th>
			</tr>
		</volist>
	</if>
	<tr>
		<th colspan="7">
			<input type="button" value="登录商家后台" class="button" onclick="window.open('/admin.php?c=Merchant&a=merchant_login&mer_id={pigcms{$order.mer_id}')"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" value="登录店员后台" class="button" onclick="window.open('/admin.php?c=Merchant&a=storestaff_login&store_id={pigcms{$order.store_id}')"/>
		</th>
	</tr>
</table>

<script>
	function refund_confirm(){
		layer.confirm('确认后订单状态改为已退款，金额请通过其他渠道手动退款给客户！', {
			btn: ['确定','取消'] //按钮
		}, function(){
			window.location.href='{pigcms{:U('Shop/refund_update',array('order_id'=>$order['order_id']))}';
		});
		//
	}

    $('.refundImg').click(function(){
        var obj = $(this);
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            skin: 'layui-layer-nobg', //没有背景色
            shadeClose: true,
            content: '<img src="' + obj.attr('src') + '" style="height:100%;width:100%"/>'
        });
    });
</script>
<include file="Public:footer"/>