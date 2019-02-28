<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>店员中心</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
	<style>
    .btn-wrapper {
        margin: .28rem .2rem;
    }
    .hotel-price {
        color: #ff8c00;
        font-size: 12px;
        display: block;
    }
    .dealcard .line-right {
        display: none;
    }
    .agreement li {
        display: inline-block;
        width: 50%;
        box-sizing: border-box;
        color: #666;
    }

    .agreement li:nth-child(2n) {
        padding-left: .14rem;
    }

    .agreement li:nth-child(1n) {
        padding-right: .14rem;
    }

    .agreement ul.agree li {
        height: .32rem;
        line-height: .32rem;
    }

    .agreement ul.btn-line li {
        vertical-align: middle;
        margin-top: .06rem;
        margin-bottom: 0;
    }

    .agreement .text-icon {
        margin-right: .14rem;
        vertical-align: top;
        height: 100%;
    }

    .agreement .agree .text-icon {
        font-size: .4rem;
        margin-right: .2rem;
    }


    #deal-details .detail-title {
        background-color: #F8F9FA;
        padding: .2rem;
        font-size: .3rem;
        color: #000;
        border-bottom: 1px solid #ccc;
    }

    #deal-details .detail-title p {
        text-align: center;
    }

    #deal-details .detail-group {
        font-size: .3rem;
        display: -webkit-box;
        display: -ms-flexbox;
    }

    .detail-group .left {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        display: block;
        padding: .28rem 0;
        padding-right: .2rem;
    }

    .detail-group .right {
        display: -webkit-box;
        display: -ms-flexbox;
        -webkit-box-align: center;
        -ms-box-align: center;
        width: 1.2rem;
        padding: .28rem .2rem;
        border-left: 1px solid #ccc;
    }

    .detail-group .middle {
        display: -webkit-box;
        display: -ms-flexbox;
        -webkit-box-align: center;
        -ms-box-align: center;
        width: 1.7rem;
        padding: .28rem .2rem;
        border-left: 1px solid #ccc;
    }

    ul.ul {
        list-style-type: initial;
        padding-left: .4rem;
        margin: .2rem 0;
    }

    ul.ul li {
        font-size: .3rem;
        margin: .1rem 0;
        line-height: 1.5;
    }
    .coupons small{
        float: right;
        font-size: .28rem;
    }
    strong {
        color: #FDB338;
    }
    .coupons-code {
        color: #666;
        text-indent: .2rem;
    }
    .voice-info {
        font-size: .3rem;
        color: #eb8706;
    }
</style>
</head>
<body id="index" data-com="pagecommon">
        <div id="tips" class="tips"></div>
        <div class="wrapper-list" style="padding-bottom: 10px;">
			<h4 style="margin-top:.3rem;">{pigcms{$now_order.appoint_name} </h4><a class="btn" style="float:right;margin-right: 12px;margin-right: 15px;top:-.7rem;;position: relative;" href="{pigcms{:U('Storestaff/appoint_list')}">返 回</a>	
			<dl class="list coupons">
				<dd style="overflow:visible;">
					<dl>
						<dt style="overflow:visible;">订单详情</dt>
						<dd class="dd-padding coupons-code">
							订单编号： <span>{pigcms{$now_order.order_id}</span>
						</dd>
						<if condition="$now_order.orderid neq 0">
						<dd class="dd-padding coupons-code">
							订单流水号： <span>{pigcms{$now_order.orderid}</span>
						</dd>
						</if>
						<dd class="dd-padding coupons-code">
							预约商品： <span><a href="{pigcms{:U('Appoint/detail',array('appoint_id'=>$now_order['appoint_id']))}" target="_blank">{pigcms{$now_order.appoint_name}</a></span>
						</dd>
						
						<if condition='!empty($now_order["appoint_date"]) AND !empty($now_order["appoint_time"])'>
							<dd class="dd-padding coupons-code">
								预约时间： <span>{pigcms{$now_order.appoint_date}&nbsp;{pigcms{$now_order.appoint_time}</span>
							</dd>
						</if>
						<dd class="dd-padding coupons-code">
							订单状态： <span>
							<if condition="$now_order['paid'] == 0" >
								<font color="red">未支付</font>
								<if condition="$now_order['service_status'] == 0" >
									<font color="red">未服务</font>
									<!--span onclick="appoint_verify_btn({pigcms{$vo['order_id']},$(this));return false;" style="color:#428bca">验证服务</span-->
								<elseif condition="$now_order['service_status'] == 1" />
									<font color="green">已服务</font>
								</if>
							<elseif condition="$now_order['paid'] == 1" />
								<font color="green">已支付</font>
								<if condition="$now_order['service_status'] == 0" >
									<font color="red">未服务</font>
									<!--span onclick="appoint_verify_btn({pigcms{$now_order['order_id']},$(this));return false;" style="color:#428bca">验证服务</span-->
								<elseif condition="$now_order['service_status'] == 1" />
									<font color="green">已服务</font>
								</if>
							<elseif condition="$now_order['paid'] == 2" />
								<font color="red">已退款</font>
							<else/>
								<font color="red">订单异常</font>
							</if></span>
						</dd>
						<if condition="$now_order['paid'] eq 1" >
						<dd class="dd-padding coupons-code">
						余款支付方式：<span>{pigcms{$now_order.paytypestr}</span>
					    </dd>
						
						<if condition='!empty($now_order["product_name"])'><dd class="dd-padding coupons-code">服务名称：{pigcms{$now_order["product_name"]}</dd></if>
						<if condition='$now_order["product_payment_price"] != "0.00"'><dd class="dd-padding coupons-code">选择服务定金：¥ {pigcms{$now_order["product_payment_price"]}</dd></if>
						<if condition='$now_order["product_price"] != "0.00"'><dd class="dd-padding coupons-code">选择服务全价：¥ {pigcms{$now_order["product_price"]}</dd></if>
						<if condition='$now_order["product_use_time"]'><dd class="dd-padding coupons-code">选择服务时间：{pigcms{$now_order["product_use_time"]}分钟</dd></if>
						<if condition="($now_order['product_card_discount'] lt 10) AND ($now_order['product_card_discount'] gt 0)"><dd class="dd-padding coupons-code">余额享受会员卡折扣：{pigcms{$now_order['product_card_discount']}折</dd></if>
						<if condition="$now_order['product_merchant_balance'] neq 0"><dd class="dd-padding coupons-code">商家会员卡余额抵扣：{pigcms{$now_order['product_merchant_balance']+$now_order['product_card_give_money']}元</dd></if>
						<if condition="$now_order['product_merchant_balance'] neq 0"><dd class="dd-padding coupons-code">平台余额抵扣：{pigcms{$now_order['product_merchant_balance']}元</dd></if>
						<if condition="$now_order['product_card_price'] gt 0"><dd class="dd-padding coupons-code">商家优惠券：{pigcms{$now_order['product_card_price']}元</dd></if>
						<if condition="$now_order['product_coupon_price'] gt 0"><dd class="dd-padding coupons-code">平台优惠券：{pigcms{$now_order['product_coupon_price']} 元</dd></if>
						<if condition="$now_order['product_score_deducte'] gt 0"><dd class="dd-padding coupons-code">{pigcms{$config.score_name}抵扣：{pigcms{$now_order['product_score_deducte']}元</dd></if>
						<if condition='$now_order["product_id"] gt 0'>
							<?php if($now_order['product_payment_price'] != '0.00'){?><dd class="dd-padding coupons-code">定金支付金额：<?php if($now_order["balance_pay"] > 0){?>{pigcms{$now_order['balance_pay']}<?php }else{?>{pigcms{$now_order['product_payment_price']}<?php } ?>元</dd><?php } ?>
							
							<dd class="dd-padding coupons-code">定金支付方式：<?php if(!empty($now_order['pay_type'])){ ?>{pigcms{$now_order['pay_type']}<?php }else{ ?>余额支付<?php } ?></dd>
							<?php if($now_order['pay_time'] > 0){ ?><dd class="dd-padding coupons-code">定金支付时间：{pigcms{$now_order['pay_time']|date='Y-m-d H:i:s',###}</dd>
							<?php }elseif(($now_order['payment_money'] > 0) && ($now_order['is_initiative'] == 1)){?>
							<dd class="dd-padding coupons-code">定金支付金额：{pigcms{$now_order['payment_money']}元</dd>
							
							<dd class="dd-padding coupons-code">定金支付方式：<?php if(!empty($now_order['pay_type'])){?>{pigcms{$now_order['pay_type']}<?php }else{ ?>余额支付<?php } ?></dd>
							<?php if($now_order['pay_time'] > 0){?><dd class="dd-padding coupons-code">定金支付时间：{pigcms{$now_order['pay_time']|date='Y-m-d H:i:s',###}</dd><?php } ?>
							<?php } ?>
							
							<?php if(($now_order['user_pay_money'] > 0)  AND ($now_order['is_initiative'] == 1)){?>
								<dd class="dd-padding coupons-code">余款支付金额：{pigcms{$now_order['user_pay_money']}元</dd>
							<?php }elseif($now_order['is_initiative'] == 1){?>
								<dd class="dd-padding coupons-code">余款支付金额：
									<?php if($now_order['product_id'] > 0){?>
									{pigcms{$now_order['product_price'] - $now_order['product_payment_price']}
									<?php }else{ ?>
									{pigcms{$now_order['appoint_price'] - $now_order['payment_money']}
									<?php } ?>
									元
								</dd>
								
								<dd class="dd-padding coupons-code">余款实际支付金额：
									<?php if($now_order['product_id'] > 0){?>
										{pigcms{$now_order['product_balance_pay']}
									<?php }else{ ?>
										{pigcms{$now_order['balance_pay']}
									<?php } ?>元
								</dd>
								
								<dd class="dd-padding coupons-code">余款支付方式：
									<?php if(!empty($now_order['product_pay_type'])){ ?>
										{pigcms{$now_order['product_pay_type']}
									<?php }else{ ?>
										余额支付
									<?php }?>
								</dd>
								
								<?php if($now_order['user_pay_time'] > 0){ ?>
									<dd class="dd-padding coupons-code">余额支付时间：
										{pigcms{$now_order['user_pay_time']|date='Y-m-d H:i:s',###}
									</dd>
								<?php } ?>
							<?php } ?>
							
						</if>
						<dd class="dd-padding coupons-code">
							买家留言： <span><if condition="$now_order['content']">{pigcms{$now_order.content}<else/>无</if></span>
						</dd>
					</dl>
				</dd>
			</dl>
			<dl class="list coupons">
				<dd style="overflow:visible;">
					<dl>
						<dt style="overflow:visible;">自定义填写项</dt>
						<volist name="cue_list" id="val">
							<dd class="dd-padding coupons-code">
								{pigcms{$val.name}：<if condition="$val['type'] eq 2">地址：{pigcms{$val.address}{pigcms{$val.value}<else/>{pigcms{$val.value}</if>
							</dd>
						</volist>
					</dl>
				</dd>
			</dl>
			<if condition="$now_order['paid'] eq '1'">
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>用户信息</dt>
							<dd class="dd-padding coupons-code">
								用户ID： <span>{pigcms{$now_order.uid}</span>
							</dd>
							<dd class="dd-padding coupons-code">
								用户名： <span>{pigcms{$now_order.nickname}</span>
							</dd>
							<dd class="dd-padding coupons-code">
								手机号： <span><a href="tel:{pigcms{$now_order.phone}" style="color:blue;">{pigcms{$now_order.phone}</a></span>
							</dd>
						</dl>
					</dd>
				</dl>
			</if>
		</div>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<!---<include file="Storestaff:footer"/>--->
		<script type="text/javascript">
			$(function(){
				<if condition="$now_order['paid'] eq 1 && $now_order['status'] eq 0">var fahuo=1;<else/>var fahuo=0;</if>
				$('#express_id_btn').click(function(){
					if(fahuo == 1){
						if(confirm("您确定要提交快递信息吗？提交后订单状态会修改为已发货。")){
							express_post();
						}
					}else{
						express_post();
					}
				});
				$('#merchant_remark_btn').click(function(){
					$(this).prop('disabled',true);
					$.post("{pigcms{:U('Storestaff/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						if(result.status == 0){						
							$('#merchant_remark_btn').prop('disabled',false);
							alert(result.info);
						}else{
							window.location.href = window.location.href;
						}
					});
				});
				function express_post(){
					$('#express_id_btn').prop('disabled',true);
					$.post("{pigcms{:U('Storestaff/group_express',array('order_id'=>$now_order['order_id']))}",{express_type:$('#express_type').val(),express_id:$('#express_id').val()},function(result){
						if(result.status == 1){
							fahuo=0;
							window.location.href = window.location.href;
						}else{
							$('#express_id_btn').prop('disabled',false);
							alert(result.info);
						}
					});
				}
			});
		</script>
</body>
</html>