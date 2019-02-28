<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
        <script src="{pigcms{$static_public}js/layer/layer.js"></script>
		<title>{pigcms{$config.site_name} - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
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
		<th><strong>数量</strong></th>
		<th><strong>规格属性详情</strong></th>
	</tr>
	<volist name="order['info']" id="rowset">
	<if condition="!empty($rowset['name'])">
	<tr><td colspan="7">{pigcms{$rowset['name']}</td></tr>
	</if>
	<volist name="rowset['list']" id="vo">
	<tr>
		<th style="color:#9F0050">{pigcms{$vo['name']}</th>
		<th style="color:#9F0050">{pigcms{$vo['price']|floatval}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo.extra_price}{pigcms{$config.extra_price_alias_name}</if></th>
		<th style="color:#9F0050"><php>if ($vo['discount_type'] == 1 || $vo['discount_type'] == 4) {echo '店铺折扣';} elseif ($vo['discount_type'] == 2 || $vo['discount_type'] == 5) {echo '分类折扣';} elseif ($vo['discount_type'] == 3) {echo 'VIP折扣';}</php></th>
		<th style="color:#9F0050"><strong><php>if ($vo['discount_rate']) echo $vo['discount_rate']/10;</php></strong></th>
		<th style="color:#9F0050"><strong><php>if ($vo['discount_price']) { echo floatval($vo['discount_price']); } else { echo floatval($vo['price']); }</php></strong></th>
		<th style="color:#9F0050"><strong>{pigcms{$vo['num']}</strong><php>if ($vo['unit']) {</php> ({pigcms{$vo['unit']})<php>}</php></th>
		<th style="color:#9F0050">{pigcms{$vo['spec']}</th>
	</tr>
	</volist>
	</volist>
	<tr>
		<th colspan="7">备注:<span style="color:red">{pigcms{$order['desc']|default="无"}</span></th>
	</tr>
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
		<th colspan="7">客户地址：{pigcms{$order['user_adress']['province_txt']} &nbsp;{pigcms{$order['user_adress']['city_txt']}  &nbsp;{pigcms{$order['user_adress']['area_txt']}  &nbsp;{pigcms{$order['address']}</th>
	</tr>
	</if>
    <if condition="$order['platform'] eq 0">
	<tr>
		<th colspan="7">配送方式：{pigcms{$order['deliver_str']}</th>
	</tr>
	<tr>
		<th colspan="7">配送状态：{pigcms{$order['deliver_status_str']}</th>
	</tr>
    </if>
    
	<if condition="$order['is_pick_in_store'] eq 0 AND $order['no_bill_money'] gt 0">
	<tr>
		<th colspan="7">支付平台费用：{pigcms{$order['no_bill_money']|floatval}</th>
	</tr>
	</if>
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
		<th colspan="7">期望送达时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
  <if condition="$order['use_time']">
	<tr>
		<th colspan="7">送达时间：{pigcms{$order['use_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
    <if condition="$order['goods_price'] gt 0">
	<tr>
		<th colspan="7">商品总价：￥{pigcms{$order['discount_price']|floatval} 元</th>
	</tr>
    </if>
	<if condition="$order['packing_charge'] gt 0">
	<tr>
		<th colspan="7">{pigcms{$store['pack_alias']|default='打包费'}：￥{pigcms{$order['packing_charge']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['freight_charge'] gt 0">
	<tr>
		<th colspan="7">{pigcms{$store['freight_alias']|default='配送费用'}：￥{pigcms{$order['freight_charge']|floatval} 元</th>
	</tr>
	</if>
    <if condition="$order['other_money'] gt 0">
    <tr>
        <th colspan="7">加价送的金额：￥{pigcms{$order['other_money']|floatval} 元</th>
    </tr>
    </if>
	<tr>
		<th colspan="7">订单总价：￥{pigcms{$order['discount_price'] + $order['packing_charge'] + $order['freight_charge'] + $order['other_money']|floatval} 元</th>
	</tr>
    
    <php>if (!empty($order['discount_detail'])) {</php>
    <volist name="order['discount_detail']" id="detail">
    <tr>
        <th colspan="7">优惠信息：
        <php>if ($detail['discount_type'] == 1) {</php>平台首单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) .'</b>)';}</php>
        <php>} elseif ($detail['discount_type'] == 2) {</php>平台满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) . '</b>)';}</php>
        <php>} elseif ($detail['discount_type'] == 3) {</php>商家首单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
        <php>} elseif ($detail['discount_type'] == 4) {</php>商家满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
        <php>} elseif ($detail['discount_type'] == 5) {</php>平台配送订单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) . '</b>)';}</php>
        <php>} elseif ($detail['discount_type'] == 6) {</php>商家配送订单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
        <php>}</php>
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
	<if condition="$order['score_used_count'] gt 0">
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
    <if condition="$order['platform'] eq 0">
	<tr>
		<th colspan="7">支付方式：{pigcms{$order['pay_type_str']}</th>
	</tr>
    </if>
	<tr>
		<th colspan="7">订单状态：{pigcms{$order['status_str']}</th>
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
</table>
<script type="text/javascript">
			$(function(){
				$('#merchant_remark_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						$('#merchant_remark_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				$('#store_id_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/order_store_id',array('order_id'=>$now_order['order_id']))}",{store_id:$('#order_store_id').val()},function(result){
						$('#store_id_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
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
			});
		</script>
	</body>
</html>