<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Store/shop_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="order_id" value="{pigcms{$order.order_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<if condition="!in_array($order['status'], array(2,3,4,5)) AND $order['fetch_number']">
			<tr>
				<th colspan="1">取单编号</th>
				<th colspan="3">{pigcms{$order['fetch_number']}</th>
			</tr>
			</if>
			<tr>
				<th colspan="1">订单编号</th>
				<th colspan="2">{pigcms{$order['real_orderid']}</th>
				<th><button type="button" id="print">打印订单</button></th>
			</tr>
			<if condition="$order.orderid neq 0">
			<tr>
				<th colspan="1">订单流水号</th>
				<th colspan="3"><if condition="$order['pay_type'] neq 'baidu'">shop_</if>{pigcms{$order['orderid']}</th>
			</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th><strong>商品名称</strong></th>
				<th><strong>原价</strong></th>
				<th><strong>优惠类型</strong></th>
				<th><strong>优惠率</strong></th>
				<th><strong>优惠价</strong></th>
				<th><strong>数量</strong></th>
				<th><strong>规格属性详情</strong></th>
			</tr>
			<volist name="order['info']" id="rowset">
			<if condition="!empty($rowset['name'])">
			<tr><td colspan="4">{pigcms{$rowset['name']}</td></tr>
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
            <php>if ($order['platform'] == 0) {</php>
			<tr >
				<th><strong>总价</strong></th>
				<th>{pigcms{$order['goods_price']|floatval}</th>
				<th colspan="5">{pigcms{$order['num']}</th>
			</tr>
            <php>}</php>
		</table>
        <if condition="$finishRefunds">
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
            <tr>
                <th colspan="4"><strong>已退商品</strong></th>
            </tr>
            <tr>
                <th><strong>商品名称</strong></th>
                <th><strong>退款单价</strong></th>
                <th><strong>退款数量</strong></th>
                <th><strong>规格属性详情</strong></th>
            </tr>
            <volist name="finishRefunds" id="finish">
            <tr>
                <th style="color:#9F0050">{pigcms{$finish['name']}</th>
                <th style="color:#9F0050">{pigcms{$finish['price']|floatval}</th>
                <th style="color:#9F0050"><strong>{pigcms{$finish['num']}</strong><php>if ($finish['unit']) {</php> ({pigcms{$finish['unit']})<php>}</php></th>
                <th style="color:#9F0050">{pigcms{$finish['spec']}</th>
            </tr>
            </volist>
        </table>
        </if>
        <volist name="refund_list" id="rowset">
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
            <tr>
                <th colspan="4"><strong>退货信息</strong></th>
            </tr>
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
                    <volist name="rowset['logList']" id="vo">
                        <if condition="$vo['status'] eq 0"><span>商家正在审核中 <php>if (!empty($vo['note'])) { </php>,理由：<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></span> 
                        <elseif condition="$vo['status'] eq 1"/><span>商家审核通过 <php>if (!empty($vo['note'])) { </php>,理由：<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></span>
                        <elseif condition="$vo['status'] eq 2"/><span>商家拒绝退货 <php>if (!empty($vo['note'])) { </php>,理由：<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></span>
                        <elseif condition="$vo['status'] eq 3"/><span>您重新申请退货 <php>if (!empty($vo['note'])) { </php>,理由：<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></span>
                        <elseif condition="$vo['status'] eq 4"/><span>您取消申请退货 <php>if (!empty($vo['note'])) { </php>,理由：<strong style="color:red">{pigcms{$vo['note']}</strong><php>}</php></span>
                        </if>
                        {pigcms{$vo.dateline|date="Y-m-d H:i",###}                        
                    </volist>
                </th>
            </tr>
            
            <tr>
                <th colspan="4">退款操作: <button type="button" class="refund" data-type="agree" data-refund_id="{pigcms{$rowset['id']}">同意</button><button type="button" class="refund" data-type="disagree" data-refund_id="{pigcms{$rowset['id']}">不同意</button> <input type="text" name="reply_content" style="height: 24px;width:400px;" placeholder="请填写原因" ></th>
            </tr>
        </table>
        </volist>
        
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th><strong>支付状态</strong></th>
				<th style="color: green">{pigcms{$order['pay_status']}</th>
                <if condition="$order['platform'] eq 0">
				<th><strong>支付方式</strong></th>
                <th>{pigcms{$order['pay_type_str']}</th>
                <else />
				<th colspan="2"></th>
                </if>
			</tr>
			<tr>
				<th>线下需支付</th>
				<th style="color: red">￥{pigcms{$order['offline_price']|floatval}元</th>
				<th>发票信息</th>
				<th>{pigcms{$order.invoice_head}</th>
			</tr>
			<tr>
				<th><strong>订单状态</strong></th>
				<if condition="$order['is_pick_in_store'] eq 3">
					<th>
						<select name="express_id"><volist name="express_list" id="vo"><option value="{pigcms{$vo.id}" <if condition="$order['express_id'] eq $vo['id']">selected</if>>{pigcms{$vo.name}</option></volist></select>
					</th>
					<th><input type="text" name="express_number" value="{pigcms{$order['express_number']}" style=" height: 24px;"/></th>
					<th>
<!-- 						<select name="status"> -->
<!-- 						 	<option value="0" <if condition="$order['status'] eq 0">selected</if>>未确认</option> -->
<!-- 						 	<option value="1" <if condition="$order['status'] eq 1">selected</if>>已确认</option> -->
<!-- 						 	<option value="2" <if condition="$order['status'] eq 2">selected</if>>已消费</option> -->
<!-- 						 	<option value="3" <if condition="$order['status'] eq 3">selected</if> disabled>已评价</option> -->
<!-- 						 	<option value="4" <if condition="$order['status'] eq 4">selected</if>>已退款</option> -->
<!-- 						 	<option value="5" <if condition="$order['status'] eq 5">selected</if>>已取消</option> -->
<!-- 						 </select> -->
						 <button type="submit">提交</button>
					</th>
				<elseif condition="$order['status'] gt 1 AND $order['status'] lt 6" />
					<th colspan="3">
					{pigcms{$order['status_str']}
					</th>
				<else />
					<th>
						<select name="status">
						 	<option value="0" <if condition="$order['status'] eq 0">selected</if>>未确认</option>
						 	<option value="1" <if condition="$order['status'] eq 1 OR $order['status'] eq 9">selected</if>>已确认</option>
						 	<option value="2" <if condition="$order['status'] eq 2">selected</if> <if condition="$sure">disabled</if>>已消费</option>
						 	<option value="3" <if condition="$order['status'] eq 3">selected</if> disabled>已评价</option>
						 	<option value="4" <if condition="$order['status'] eq 4 OR $order['status'] eq 5">selected</if>>已取消</option>
						 </select>
					</th>
					<th style="color: red">注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，<br/>状态修改后就不能修改了</th>
					<th><button type="submit">提交</button></th>
				</if>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>客户信息</strong></th>
			</tr>
			<tr>
				<th>客户姓名：{pigcms{$order['username']}</th>
				<th>客户手机：{pigcms{$order['userphone']}</th>
			</tr>
			<if condition="$order['register_phone']">
			<tr>
				<th colspan="2" style="color:red">客户注册手机：{pigcms{$order['register_phone']}</th>
			</tr>
			</if>
            <php>if ($order['order_from'] != 6) {</php>
			<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<th colspan="2">自提地址：{pigcms{$order['address']}</th>
				</tr>
			<else />
				<tr>
					<th colspan="2">客户地址：{pigcms{$order['address']}</th>
				</tr>
			</if>
            <php>}</php>
		</table>
        <php>if ($order['order_from'] != 6 && $order['platform'] == 0) {</php>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>配送信息</strong></th>
			</tr>
			<tr>
				<th>配送方式：{pigcms{$order['deliver_str']}</th>
				<th>配送状态：{pigcms{$order['deliver_status_str']}</th>
			</tr>
			<if condition="$order['deliver_user_info']">
				<tr>
					<th>配送员姓名：{pigcms{$order['deliver_user_info']['name']}</th>
					<th>配送员电话：{pigcms{$order['deliver_user_info']['phone']}</th>
				</tr>
			</if>
		</table>
        <php>}</php>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>时间信息</strong></th>
			</tr>
			<tr>
				<th colspan="4">下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
			</tr>
			<if condition="$order['pay_time']">
				<tr>
					<th colspan="4">支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
				</tr>
			</if>
			<if condition="$order['expect_use_time']">
				<tr>
					<th colspan="4">到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
				</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>费用信息</strong></th>
			</tr>
            <if condition="$order['goods_price'] gt 0">
			<tr>
				<th colspan="4">商品总价：￥{pigcms{$order['discount_price']|floatval} 元<if condition="$config.open_extra_price eq 1 AND $order.extra_price gt 0">+{pigcms{$order.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></th>
			</tr>
            </if>
			<if condition="$order['packing_charge'] gt 0">
			<tr>
				<th colspan="4">{pigcms{$store['pack_alias']|default='打包费'}：￥{pigcms{$order['packing_charge']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['freight_charge'] gt 0">
			<tr>
				<th colspan="4">配送费用：￥{pigcms{$order['freight_charge']|floatval} 元</th>
			</tr>
			</if>
            <if condition="$order['other_money'] gt 0">
            <tr>
                <th colspan="4">加价送的金额：￥{pigcms{$order['other_money']|floatval} 元</th>
            </tr>
            </if>
			<if condition="$order['is_pick_in_store'] eq 0 AND $order['no_bill_money'] gt 0">
			<tr>
				<th colspan="4">支付平台费用：{pigcms{$order['no_bill_money']|floatval}</th>
			</tr>
			</if>
			<tr>
				<th colspan="4">订单总价：￥{pigcms{$order['discount_price'] + $order['packing_charge'] + $order['freight_charge'] + $order['other_money']|floatval} 元<if condition="$config.open_extra_price eq 1 AND $order.extra_price gt 0">+{pigcms{$order.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></th>
			</tr>
    
            <php>if (!empty($order['discount_detail'])) {</php>
            <volist name="order['discount_detail']" id="detail">
            <tr>
                <th colspan="4">优惠信息：
                <php>if($detail['discount_type']==1){</php>平台首单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) .'</b>)';}</php>
                <php>}else if($detail['discount_type']==2){</php>平台满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) . '</b>)';}</php>
                <php>}else if($detail['discount_type']==3){</php>商家首单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
                <php>}else if($detail['discount_type']==4){</php>商家满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
                <php>}else if($detail['discount_type']==5){</php>平台配送订单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b> <php>if ($detail['plat_money'] > 0 || $detail['merchant_money'] > 0) {echo '(平台补贴:<b style="color: red">' . floatval($detail['plat_money']) . '</b>商家补贴:<b style="color: red">' . floatval($detail['merchant_money']) . '</b>)';}</php>
                <php>}else if($detail['discount_type']==6){</php>>商家配送订单满<b style="color: red">{pigcms{$detail['money']|floatval}</b>减<b style="color: red">{pigcms{$detail['minus']|floatval}</b>
                <php>}</php>
                </th>
            </tr>
            </volist>
            <php>} else {</php>
            <if condition="$order['merchant_reduce'] gt 0">
            <tr>
                <th colspan="4">店铺优惠：￥{pigcms{$order['merchant_reduce']|floatval} 元</th>
            </tr>
            </if>
            <if condition="$order['balance_reduce'] gt 0">
            <tr>
                <th colspan="4">平台优惠：￥{pigcms{$order['balance_reduce']|floatval} 元</th>
            </tr>
            </if>
            <php>}</php>
			<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
			<tr>
				<th colspan="4">会员卡：{pigcms{$order['card_discount']|floatval} 折优惠(配送费不参加优惠)</th>
			</tr>
			</if>
			<tr>
				<th colspan="4">实付金额：￥{pigcms{$order['price']|floatval} 元</th>
			</tr>
			<if condition="$order['score_used_count'] gt 0">
			<tr>
				<th colspan="4">使用{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </th>
			</tr>
			<tr>
				<th colspan="4">{pigcms{$config.score_name}抵现：￥{pigcms{$order['score_deducte']|floatval} 元</th>
			</tr>
			</if>
			
			<if condition="$order['card_give_money'] gt 0">
			<tr>
				<th colspan="4">会员卡余额：￥{pigcms{$order['card_give_money']|floatval} 元</th>
			</tr>
			</if>
			
			<if condition="$order['merchant_balance'] gt 0">
			<tr>
				<th colspan="4">商家余额：￥{pigcms{$order['merchant_balance']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['balance_pay'] gt 0">
			<tr>
				<th colspan="4">平台余额：￥{pigcms{$order['balance_pay']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['payment_money'] gt 0">
			<tr>
				<th colspan="4">在线支付：￥{pigcms{$order['payment_money']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['card_id']">
			<tr>
				<th colspan="4">店铺优惠券金额：￥{pigcms{$order['card_price']} 元</th>
			</tr>
			</if>
			<if condition="$order['coupon_id']">
			<tr>
				<th colspan="4">平台优惠券金额：￥{pigcms{$order['coupon_price']} 元</th>
			</tr>
			</if>
		</table>
		<if condition="$order['cue_field']">
			<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
				<tr>
					<th colspan="2"><strong>分类填写字段</strong></th>
				</tr>
				<volist name="order['cue_field']" id="vo">
					<tr>
						<th>{pigcms{$vo.title}</th>
						<th>{pigcms{$vo.txt}</th>
					</tr>
				</volist>
			</table>
		</if>
	</form>
    <script type="text/javascript">
		$(function(){
			$('#merchant_remark_btn').click(function(){
				$(this).html('提交中...').prop('disabled',true);
				$.post("{pigcms{:U('Group/group_remark',array('order_id'=>$order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
					$('#merchant_remark_btn').html('修改').prop('disabled',false);
					alert(result.info);
				});
			});
			$('#store_id_btn').click(function(){
				$(this).html('提交中...').prop('disabled',true);
				$.post("{pigcms{:U('Group/order_store_id',array('order_id'=>$order['order_id']))}",{store_id:$('#order_store_id').val()},function(result){
					$('#store_id_btn').html('修改').prop('disabled',false);
					alert(result.info);
				});
			});
			$('#print').click(function(){
				var order_id = $(this).data('order_id'), obj = $(this);
				obj.text('打印中...');
				$.post("{pigcms{:U('Store/shop_order_print', array('order_id' => $order['order_id']))}",function(result){
					alert(result.info);
					obj.text('打印订单');
				});
			});
            $('.refund').click(function(){
                var refund_id = $(this).data('refund_id'), type = $(this).data('type'), order_id = '{pigcms{$order["order_id"]}';
                var reply_content = $(this).parent('th').find('input[name="reply_content"]').val();
                $.post("{pigcms{:U('Store/replyRefund')}", {'order_id':order_id, 'refund_id':refund_id, 'reply_content':reply_content, 'type':type}, function(response){
                    if (response.errcode == 1) {
                        layer.msg(response.msg);
                    } else {
                        location.reload();
                    }
                }, 'json');
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