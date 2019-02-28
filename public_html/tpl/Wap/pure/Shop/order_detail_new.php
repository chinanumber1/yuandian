<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{$config.shop_alias_name}订单详情</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="{pigcms{$static_path}shop/css/order_detail.css?11111" rel="stylesheet"/>
        <!-- <link href="../static/shop/css/order_detail.css?11111" rel="stylesheet"/> -->
        <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
       	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?2112222" charset="utf-8"></script>
	
    </head>
    <body>
		<if condition="$is_wexin_browser AND $config['open_share_lottery'] eq 1 AND $config['lottery_must_share'] eq 1 AND !empty($lottery) AND $order_details.paid  eq 1 AND $order_details.share_status eq 0">
			<div class="t_remind <if condition="$weixin_browser_type eq 'android'">az</if>" style="z-index:880">
				<p style="padding: 16px 0 5px 0">{pigcms{$lottery['detail_msg']}</p>
			</div>
		</if>
		<if condition="$is_wexin_browser AND $order_details.share_status eq 0 AND $config['open_share_lottery'] eq 0 AND $config.share_coupon eq 1 AND $order_details.paid  eq 1 AND $order_details.status neq 4 AND !empty($share_coupon)"  >
		<div class="t_remind <if condition="$weixin_browser_type eq 'android'">az</if>" style="z-index:880" >
			<p>{pigcms{$config.share_coupon_notice}</p>
		</div>

		</if>
        <section class="public">
            <a class="return link-url" id="goBackUrl" href="{pigcms{:U('My/shop_order_list')}" data-url="{pigcms{:U('My/shop_order_list')}"></a>
            <div class="content">订单详情</div>
            <a class="ipho phone" data-phone="{pigcms{$store['phone']}" style="cursor: pointer;"></a>
        </section>
        <if condition="$order_details['deliver_log_list']">
        <section class="defrayal">
            <div class="defrayal_n">
                <a href="{pigcms{:U('Shop/order_detail', array('order_id' => $order_details['order_id'],'app_no_header'=>1))}">
                    <if condition="$order_details['deliver_log_list']['status'] eq 0"> <h2>订单生成成功</h2> <p>订单编号：{pigcms{$order_details.real_orderid}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 1"/> <h2>订单支付成功</h2> <p>订单编号：{pigcms{$order_details.real_orderid}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 2"/> <h2>店员接单</h2> <p>正在为您准备商品</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 3"/> <h2>配送员接单</h2> <p>配送员正在赶往店铺取货</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 4"/> <h2>配送员取货</h2> <p>已取货，准备配送，请耐心等待</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 5"/> <h2>配送员配送中</h2> <p>配送员正快速向您靠拢，请耐心等待！</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 6"/> <h2>订单已完成</h2> <p><php>if ($order_details['is_pick_in_store'] < 2) {</php>配送员已完成配送，欢迎下次光临！<php>}else{</php>订单编号：{pigcms{$order_details.real_orderid}<php>}</php></p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 7"/>
                        <php>if ($order_details['is_pick_in_store'] == 3) { </php><h2>店员已发货</h2> <p>已发货给快递公司<strong style="color:red">【{pigcms{$order_details['express_name']}】</strong>，快递单号:<strong style="color:green">{pigcms{$order_details['express_number']}</strong></p>
                        <php> } else {</php><h2>店员验证消费</h2> <p>订单改成已消费</p>
                        <php>}</php>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 8"/> <h2>完成评论</h2> <p>您已完成评论，谢谢您提出宝贵意见！</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 9"/> <h2>已完成退款</h2> <p><php> if (empty($order_details['deliver_log_list']['note'])) { </php>您已完成退款<php> } else { </php>{pigcms{$order_details['deliver_log_list']['note']}<php>}</php></p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 10"/> <h2>已取消订单</h2> <p><php> if (empty($order_details['deliver_log_list']['note'])) { </php>您已经取消订单<php> } else { </php>{pigcms{$order_details['deliver_log_list']['note']}<php>}</php></p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 11"/> <h2>商家分配自提点</h2> <p>店员给您分配了自提点</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 12"/> <h2>商家发货到自提点</h2> <p>店员已经给您发货到配送点</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 13"/> <h2>自提点已接货</h2> <p>自提点已经接到您的货物了</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 14"/> <h2>自提点已发货</h2> <p>自提点已经给您发货了</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 15"/> <h2>您在自提点取货</h2> <p>您在自提点已经把您的货提走了！</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 30"/> <h2>店员为您修改了价格</h2> <p>店员已将订单的总价修改成{pigcms{$order_details['deliver_log_list']['note']}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 31"/> <h2>配送员放弃配送</h2> <p>{pigcms{$order_details['deliver_log_list']['note']}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 34"/> <h2>订单已被取消</h2> <p>已为您退款！</p>
                    </if>
                    <div class="time">{pigcms{$order_details['deliver_log_list']['dateline']|date="Y-m-d H:i",###}</div>
                    <em>更多状态</em>
                </a>
            </div>
        </section>
        </if>
        <div class="h105"></div>
        <section class="g_details p40">
            
            <div class="infor">
                <ul>
                    <li class="first storext">
						<a href="javascript:;" id="go_shop" data-store_id="{pigcms{$store['store_id']}" data-is_mult_class="{pigcms{$store.is_mult_class}">
                            <div class="img">
                                <img src="{pigcms{$store['image']}">
                            </div>
                            <div class="tit">{pigcms{$store['name']}</div>
                        </a>
                    </li>
                </ul>
                
                    <volist name="info" id="fgoods">
                    <dl class="kd_dd">
                    <if condition="!empty($fgoods['name'])">
					<dd class="clr"><span style="color: red">{pigcms{$fgoods['name']}</span></dd>
					</if>
                    <volist name="fgoods['list']" id="goods">
                    <dd class="clr">
                        <div class="table" >
                            <div class="left">
                                <h2>
                                <if condition="in_array($goods['discount_type'], array(1, 3, 4))">
                                    <em class="dd1">折</em>
                                <elseif condition="in_array($goods['discount_type'], array(2, 5))" />
                                    <em class="d40">折</em>
                                </if>
                                {pigcms{$goods['name']}</h2>
                                <if condition="!empty($goods['spec'])">
                                <p>{pigcms{$goods['spec']}</p>
                                </if>
                            </div>
                            <div class="right">
                                <div class="fl ride">x{pigcms{$goods['num']}</div>
                                <div class="fl del">￥{pigcms{$goods['total']}</div>
                                <div class="fl price">￥{pigcms{$goods['discount_total']}</div> 
                            </div>
                        </div>  
                    </dd>
                    </volist>
                </dl>
                    </volist>
                <div class="mealfee">
                    <dl>
                        <if condition="$order_details['packing_charge'] gt 0">
                        <dd class="clr">
                            <div class="fl">{pigcms{$store['pack_alias']|default="打包费"}</div>
                            <div class="fr">￥{pigcms{$order_details['packing_charge']}</div>
                        </dd>
                        </if>
                        <if condition="$order_details['freight_charge'] gt 0">
                        <dd class="clr">
                            <div class="fl">{pigcms{$store['freight_alias']|default="配送费"}</div>
                            <div class="fr">￥{pigcms{$order_details['freight_charge']}</div>
                        </dd>
                        </if>
                        <if condition="$order_details['other_money'] gt 0">
                        <dd class="clr">
                            <div class="fl">加价送的金额</div>
                            <div class="fr">￥{pigcms{$order_details['other_money']|floatval}</div>
                        </dd>
                        </if>
                    </dl>
                </div>
                <if condition="!empty($discount_detail)">
                <div class="reduce">
                    <dl>
                        <volist name="discount_detail" id="discount">
                            <if condition="$discount['discount_type'] eq 1">
                            <dd class="clr">
                                <div  class="fl clr">
                                    <em class="fl e0c">首</em>
                                    <div class="p20">平台首单满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                                </div>
                                <div class="fr ff3">-￥{pigcms{$discount['minus']|floatval}</div>
                            </dd>
                            <elseif condition="$discount['discount_type'] eq 2" />
                            <dd class="clr">
                                <div class="fl clr">
                                    <em class="fl d52">减</em>
                                    <div class="p20">平台优惠满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                                </div>
                                <div class="fr ff3">-￥{pigcms{$discount['minus']|floatval}</div>
                            </dd>
                            <elseif condition="$discount['discount_type'] eq 3" />
                            <dd class="clr">
                                <div class="fl clr">
                                    <em class="fl ffa">首</em>
                                    <div class="p20">店铺首单满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                                </div>
                                <div class="fr ff3">-￥{pigcms{$discount['minus']|floatval}</div>
                            </dd>
                            <elseif condition="$discount['discount_type'] eq 4" />
                            <dd class="clr">
                                <div class="fl clr">
                                    <em class="fl ff6">减</em>
                                    <div class="p20">店铺优惠满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                                </div>
                                <div class="fr ff3">-￥{pigcms{$discount['minus']|floatval}</div>
                            </dd>
                            <elseif condition="$discount['discount_type'] eq 5" />
                            <dd class="clr">
                                <div class="fl clr">
                                    <em class="fl ff0">惠</em>
                                    <div class="p20">商品满{pigcms{$discount['money']|floatval}元配送费减{pigcms{$discount['minus']|floatval}元</div>
                                </div>
                                <div class="fr ff3">-￥{pigcms{$discount['minus']|floatval}</div>
                            </dd>
                            </if>
                        </volist>
                    </dl>
                </div>
                </if>
                <div class="answer clr">
                    <div class="fl">订单￥{pigcms{$order_details['discount_price']|floatval}<if condition="$order_details['minus_price'] gt 0"> 优惠-￥{pigcms{$order_details['minus_price']|floatval}</if></div>
                    <div class="fr">
                       <div>应收总额: ￥{pigcms{$order_details['go_pay_price']|floatval}</div>
                       <php> if (($order_details['status'] == 2 || $isShowRefund == 1) && $config['is_open_shop_refund'] == 1) {</php>
                          <div class="shenshou" style='padding:5px 0px 0px 0px '>
                         <a type="button" href="{pigcms{:U('Shop/refund', array('order_id' => $order_details['order_id']))}">申请售后</a>
                         </div>
                     <php> } </php>
                    </div>
                </div>
            
            </div>

            <php>if ($order_details['order_from'] != 6) {</php>
            <div class="infor">
                <ul>
                    <if condition="$order_details['is_pick_in_store'] eq 2">
                    <li class="clr first">
                        <div class="fl match">自提信息</div>
                    </li>
                    <li class="clr">
                        <div class="fl">配送方式</div>
                        <div class="fr">{pigcms{$order_details['deliver_str']}</div>
                    </li>
                    <li class="clr">
                        <div class="fl">自提地址</div>
                        <div class="p90">
                            <p>{pigcms{$order_details['address']}</p>
                        </div>
                    </li>
                    <else />
                    <li class="clr first">
                        <div class="fl match">配送信息</div>
                    </li>
                    <li class="clr">
                        <div class="fl">配送方式</div>
                        <div class="fr">{pigcms{$order_details['deliver_str']}</div>
                    </li>
                    <li class="clr">
                        <div class="fl">期望时间</div>
                        <div class="fr">{pigcms{$order_details['expect_use_time']}</div>
                    </li>
                    <li class="clr">
                        <div class="fl">收货信息</div>
                        <div class="p90">
                            <p>{pigcms{$order_details['address']}</p>
                            <p>{pigcms{$order_details['username']} {pigcms{$order_details['userphone']}</p>
                        </div>
                    </li>
                    <if condition="$order_details['is_pick_in_store'] eq 3 AND $order_details['express_name'] AND $order_details['express_number']">
                    <li class="clr">
                        <div class="fl">快递详情</div>
                        <dl class="kd_dl kd_dls">
                            <dd>
                                <h2 class="endt">店员已发货：{pigcms{$order_details['express_name']} <a>{pigcms{$order_details['express_number']}</a></h2>
                                <p><a href="http://m.kuaidi100.com/index_all.html?type={pigcms{$order_details['express_code']}&postid={pigcms{$order_details['express_number']}&callbackurl=<?php echo 'http://'.urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>" target="_blank" >查看物流 ></a></p>
                            </dd>
                        </dl> 
                    </li>
                    </if>
                    <if condition="$order_details['deliver_info']">
                    <li class="clr">
                        <div class="fl">配送详情</div>
                        <dl class="kd_dl kd_dls">
                            <dd>
                                <h2 class="endt">配送员：{pigcms{$order_details['deliver_info']['name']} <a href="tel:{pigcms{$order_details['deliver_info']['phone']}">{pigcms{$order_details['deliver_info']['phone']}</a></h2>
                            </dd>
                        </dl> 
                    </li>
                    </if>
                    </if>
                </ul>
            </div>
            <php>}</php>
            
            <div class="infor">
                <ul>
                    <li class="clr first">
                        <div class="fl book">订单信息</div>
                    </li>
                    <li class="clr">
                        <div class="fl">订单编号</div>
                        <div class="fr">{pigcms{$order_details['real_orderid']}</div>
                    </li>
                    <li class="clr">
                        <div class="fl">下单时间</div>
                        <div class="fr">{pigcms{$order_details['create_time']}</div>
                    </li>
                    <li class="clr">
                        <div class="fl">备注信息</div>
                        <div class="fr">{pigcms{$order_details['note']}</div>
                    </li>
                    <if condition="$order_details['cue_field']">
                    <volist name="order_details['cue_field']" id="row">
                    <li class="clr">
                        <div class="fl">{pigcms{$row['title']}</div>
                        <div class="fr">{pigcms{$row['txt']}</div>
                    </li>
                    </volist>
                    </if>
                </ul>
            </div>
             <if condition="$order_details['paid'] eq 1">
             <div class="infor">
                 <ul>
        	         <li class="clr first">
        	             <div class="fl branch">支付信息</div>
        	         </li>
                     <li class="clr">
                         <div class="fl">支付时间</div>
                         <div class="fr">{pigcms{$order_details['pay_time']}</div>
                     </li>
                     <li class="clr">
                         <div class="fl">支付方式</div>
                         <div class="fr">{pigcms{$order_details['pay_type_str']}</div>
                     </li>
        	         <li class="clr">
        	             <div class="fl">应收总额</div>
        	             <div class="p90">
        	                 <if condition="$order_details['change_price'] gt 0">
        	                 <p class="e2c">￥{pigcms{$order_details['price']}</p>
        	                 <p class="kdsize">（修改前：￥{pigcms{$order_details['change_price']|floatval}，备注：{pigcms{$order_details['change_price_reason']|default="无"}）</p>
        	                 <else />
        	                 <p class="e2c">￥{pigcms{$order_details['go_pay_price']}</p>
        	                 </if>
        	             </div>
        	         </li>
        	         <if condition="$order_details['card_discount'] neq 0 AND $order_details['card_discount'] neq 10">
        	         <li class="clr">
        	             <div class="fl">商家会员卡折扣</div>
        	             <div class="p90">
                            <p class="e2c">-￥{pigcms{$order_details['minus_card_discount']}（{pigcms{$order_details['card_discount']}折）</p>
                            <p class="kdsize">（备注：{pigcms{$store['freight_alias']|default="配送费"}不参加折扣）</p>
                         </div>
        	         </li>
        	         </if>
        	         <if condition="$order_details['coupon_price'] gt 0">
        	         <li class="clr">
        	             <div class="fl">平台优惠券</div>
        	             <div class="fr e2c">-￥{pigcms{$order_details['coupon_price']|floatval}</div>
        	         </li>
        	         </if>
        	         <if condition="$order_details['card_price'] gt 0">
        	         <li class="clr">
        	             <div class="fl">商家优惠券</div>
        	             <div class="fr e2c">-￥{pigcms{$order_details['card_price']|floatval}</div>
        	         </li>
        	         </if>
        	         <if condition="$order_details['score_deducte'] gt 0">
                     <li class="clr">
                         <div class="fl">{pigcms{$config.score_name}抵扣</div>
                         <div class="fr e2c">-￥{pigcms{$order_details['score_deducte']|floatval}（使用{pigcms{$order_details['score_used_count']|floatval}{pigcms{$config.score_name}）</div>
                     </li>
                     </if>
                     <if condition="$order_details['card_give_money'] gt 0">
                     <li class="clr">
                         <div class="fl">会员卡赠送余额支付</div>
                         <div class="fr e2c">-￥{pigcms{$order_details['card_give_money']|floatval}</div>
                     </li>
                     </if>
                     <if condition="$order_details['merchant_balance'] gt 0">
                     <li class="clr">
                         <div class="fl">商家余额支付</div>
                         <div class="fr e2c">-￥{pigcms{$order_details['merchant_balance']|floatval}</div>
                     </li>
                     </if>
                     <if condition="$order_details['balance_pay'] gt 0">
                     <li class="clr">
                         <div class="fl">平台余额支付</div>
                         <div class="fr e2c">-￥{pigcms{$order_details['balance_pay']|floatval}</div>
                     </li>
                     </if>
                     <if condition="$order_details['payment_money'] gt 0">
                     <li class="clr">
                         <div class="fl">{pigcms{$order_details['pay_type_str']}</div>
                         <div class="fr e2c">-￥{pigcms{$order_details['payment_money']|floatval}</div>
                     </li>
                     </if>
                 </ul>
             </div>
            </if>
            <div class="consume consumes">
                <ul class="clr">
                    <if condition="$order_details['status'] lt 3 OR ($order_details['paid'] eq 1 AND $order_details['status'] eq 5) OR ($order_details['paid'] eq 0 AND ($order_details['status'] eq 7 OR $order_details['status'] eq 9))">
                        <if condition="$order_details['paid'] eq 0">
							<li class="fl firmly order-pay" data-order_id="{pigcms{$order_details['order_id']}">支付订单</li>
                        </if>
                        <php> if($config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0 && $now_merchant['sub_mch_refund'] == 0 && $order['is_own'] == 2 && $order['pay_type'] == 'weixin'){</php>
                        	<li class="fr zlyd">该订单不能退款，请联系商家 【{pigcms{$now_merchant.name}】</li>
                        <php>}else{</php>
                        
                        <if condition="$order_details['paid'] eq 0">
                        <li class="fr replace" data-url="{pigcms{:U('Shop/orderdel', array('order_id' => $order_details['order_id']))}">取消订单</li>
                        <elseif condition="$order_details['paid'] eq 1 AND $order_details['status'] eq 1 AND $order_details['is_pick_in_store'] eq 3" />
                        <li class="fl firmly finishorder" data-url="{pigcms{:U('Shop/finishOrder', array('order_id' => $order_details['order_id']))}">确认收货</li>
                        <li class="fr replace" data-url="{pigcms{:U('My/shop_order_refund', array('order_id' => $order_details['order_id']))}">取消订单</li>
                        <elseif condition="$order_details['paid'] eq 1 AND $order_details['status'] lt 2" />
                        <li class="fr replace" data-url="{pigcms{:U('My/shop_order_refund', array('order_id' => $order_details['order_id']))}">取消订单</li>
                        <elseif condition="$order_details['paid'] eq 1 AND $order_details['status'] eq 5" />
                        <li class="fr replace" data-url="{pigcms{:U('My/shop_order_refund', array('order_id' => $order_details['order_id']))}">退款</li>
                        </if>
                        <php>}</php>
                        <if condition="$order_details['status'] eq 2">
                            <li class="fl replace" data-url="{pigcms{:U('My/shop_feedback',array('order_id' => $order_details['order_id']))}">去评价</li>
                            <li class="fr zlyd" data-url="{pigcms{:U('Shop/confirm_order', array('order_id' => $order_details['order_id'], 'store_id' => $store['store_id']))}">再来一单</li>
                        </if>
                    <else/>
                    <li class="fr zlyd" data-url="{pigcms{:U('Shop/confirm_order', array('order_id' => $order_details['order_id'], 'store_id' => $store['store_id']))}">再来一单</li>
                    </if>
                </ul>
            </div>
        </section>
		<if condition="$config.open_share_lottery eq 1 AND $is_wexin_browser AND $order_details.paid  eq 1 AND $lottery_info.status neq 1">
			<div class="float-open" id="float-open"  <if condition=" ( $lottery.status eq 0) OR  ($order_details.share_status eq 0 AND $config.lottery_must_share eq 1)">style="display:none;"</if> ><a class="open-btn" href="javascript:void(0);"><img src="{pigcms{$static_path}shop/images/lottery.png"></a></div>
		<elseif condition="$config.open_share_lottery eq 0  AND $config.share_coupon eq 1 AND $is_wexin_browser AND $config.get_coupon_must_share eq 0 AND $order_details.paid  eq 1 AND !empty($share_coupon)" />
			<div class="float-open" id="send_friend" ><a class="open-btn" href="javascript:void(0);"><img src="{pigcms{$static_path}shop/images/share_coupon.png"></a></div>
		</if>
		
		
		 <if condition="$config.open_share_lottery eq 0 AND $order_details.paid  eq 1 AND $is_wexin_browser  AND (($order_details['share_status'] eq 0 AND $config.get_coupon_must_share eq 1) OR $config.get_coupon_must_share eq 0) AND $config.share_coupon eq 1 AND $config.share_coupon_num gt 0 AND $order_details.status neq 4 AND !empty($share_coupon) AND !empty($share_coupon)">
            <div class="coupon_share mongolia_layer coupon_my"  <if condition="$config.get_coupon_must_share eq 0 OR $order_details.show_lottery_first gt 0">style="display:none"</if>></div>
            <div class="coupon_share Coupon coupon_my afters"  <if condition="$config.get_coupon_must_share eq 0">style="display:none"</if>>
                <span class="delate_money rg"></span>
				<h3>恭喜您获得{pigcms{$config.share_coupon_num}张优惠劵</h3>
                <p>赶快把优惠劵分享给大伙伴们抢吧,分享后您自己可获得{pigcms{$config.share_coupon_get_num}张优惠劵</p>
                <button class="btn" id="share_btn">立即分享</button>
            </div>
         </if>
	
		  <if condition="$config.open_share_lottery eq 0 AND $order_details.paid  eq 1 AND $is_wexin_browser AND ($order_details['share_status'] eq 1 OR $config['get_coupon_must_share'] eq 0)  AND $config.share_coupon eq 1 AND $config.share_coupon_num gt 0 AND $order_details.status neq 4 AND !empty($share_coupon)">
            <div class="coupon_share mongolia_layer" ></div>
            <div class="coupon_share Coupon afters" >
                <span class="delate_money rg"></span>
				<h3>恭喜您获得{pigcms{$config.share_coupon_get_num}张平台优惠劵</h3>
                <p style="text-align: center;">已存入您的账号</p>
                <button class="btn" id="use_right_now">立即查看</button>
            </div>
			
         </if>
            <!-- <div id="come_quick">
                
            </div> -->
    </body>

	<script>
			window.shareData = {
				"moduleName":"Shop",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else />{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "<if condition="$config['share_coupon'] eq 1 AND ($config['open_share_lottery'] eq 0 OR empty($lottery))">{pigcms{$config.site_url}{pigcms{:U('Share_lottery/share_coupon',array('order_id'=>$order_details['order_id'],'type'=>'shop'))}<else />{pigcms{$config.site_url}{pigcms{:U('Shop/index')}&shop-id={pigcms{$store.store_id}</if>",
				"tTitle": "<if condition="$config['share_coupon'] eq 1 AND ($config['open_share_lottery'] eq 0 OR empty($lottery))">{pigcms{$config.share_coupon_title}<else />{pigcms{$config.shop_alias_name|default="快店"} - {pigcms{$config.site_name}</if>",
				"tContent": "<if condition="$config['share_coupon'] eq 1 AND ($config['open_share_lottery'] eq 0 OR empty($lottery))">{pigcms{$config.share_coupon_num}张优惠劵，快来抢啊！<else />{pigcms{$config.seo_description}</if>"
			};
			<if condition="$is_wexin_browser OR 1==1 ">
			function call_back(){
				<if condition="$config.open_share_lottery eq 0 AND $config.share_coupon eq 1 AND $order_details.paid  eq 1">
					$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'shop',order_id: {pigcms{$order_details['order_id']}}, function(data, textStatus, xhr) {
						if(!data.status){
							layer.open({content:data.info,btn: ['确定']});
						}
						window.location.reload();
					},'json');
				
				</if>
				
				<if condition="$config.open_share_lottery eq 1 AND $order_details.paid  eq 1" >
					$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'shop',order_id: {pigcms{$order_details['order_id']}}, function(data, textStatus, xhr) {
						if(data.status){
							$('#float-open').show(); 
						}
					},'json');
				</if>
			}
			</if>
			
			<if condition="$config.open_share_lottery eq 0 AND $is_wexin_browser AND $order_details.paid  eq 1  AND $config.share_coupon eq 1 AND $order['share_status'] eq 0 AND $config.get_coupon_must_share eq 0">
				$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'shop',order_id: {pigcms{$order_details['order_id']}}, function(data, textStatus, xhr) {
					if(!data.status){
						//layer.open({content:data.info,btn: ['确定']});
					}
					//window.location.reload();
				},'json');
				
			</if>
			
			<if condition="$config.open_share_lottery eq 1 AND $is_wexin_browser AND $order_details.paid  eq 1  AND $order['share_status'] eq 0 AND $config.lottery_must_share eq 0">
					$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'store',order_id: {pigcms{$order['order_id']}}, function(data, textStatus, xhr) {
						if(data.status){
							$('#float-open').show(); 
						}
						//window.location.reload();
					},'json');
				
				</if>
			$('.delate_money').click(function(){
                $('.mongolia_layer').hide();
                $('.Coupon').hide();
			})
			$('#share_btn').click(function(){
                $('.mongolia_layer').hide();
                $('.Coupon').hide();
				$('.t_remind').css('min_height','43px');
				$('.t_remind').css('height','43px');
				$('.t_remind').show();
				$(".public").each(function(){
					$(this).css("top",$(".t_remind").height())
				});
				$(".defrayal").each(function(){
					$(this).css("top",$(".t_remind").height()+44);
				});
				$(".h105").each(function(){
					$(this).css("height",105 + $(".t_remind").height());
				})
			})
			
			$('#use_right_now').click(function(){
				window.location.href='{pigcms{:U('Share_lottery/my_get_coupon',array('order_id'=>$order_details['order_id'],'type'=>'shop'))}';
			})

          
	</script>
	<if condition="$config['open_share_lottery'] eq 1 AND !empty($lottery) AND $order_details.paid  eq 1">
	{pigcms{$shareScript}
	<elseif condition="$config['share_coupon'] eq 1 AND $config['open_share_lottery'] eq 0  AND $order_details.paid  eq 1" />
	{pigcms{$shareScript}
	<else />
	{pigcms{$hideScript}
	</if>
	<div class="pageSliderHide"></div>
</html>
<style>
	.lottery_before{
		color:#999;
		border-color:#999;
	}
	.lottery{
		color:red;
		border-color:red;
	}
	.float-open{
		
		right: 0px;
		height: 60;

		padding: 4px 4px 4px 6px;
		width: 60px;
		z-index: 99;
		top: 50%;
		position: fixed;
		
	}
</style>
<script>
$(document).ready(function(){
	$('.consumes ul li').click(function(){
        if ($(this).hasClass('finishorder')) {
            var postUrl = $(this).data('url');
            layer.open({
                content: '您确定已经收到货物了？'
                ,btn: ['确定收货', '取消']
                ,yes: function(index){
                    $.post(postUrl, function(res) {
                        layer.close(index);
                        if (res.errcode == 1) {
                            layer.open({
                                content: res.msg
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                              });
                        } else {
                            location.reload();
                            layer.close(index);
                        }
                    }, 'json');
                }
              });
        } else {
            var url = $(this).data('url');
			if(url){
				location.href = url;
			}
        }
    });
	$('#send_friend').click(function(){
		$('.coupon_my').show();
		
	})
	
	if(motify.checkWeixinApp()){
	
		$('.t_remind').remove()  
		$('#goBackUrl').hide()  
		$(".public").each(function(){
			$(this).css("top",0)
		});
		$(".defrayal").each(function(){
			$(this).css("top",44);
		});
		$(".h105").each(function(){
			$(this).css("height",105 );
		})
	}
	
	$('#float-open').click(function(){
		$.post('{pigcms{$config.site_url}{pigcms{:U('My/ajax_share_friend')}', {order_type:'shop',order_id: {pigcms{$order_details['order_id']}}, function(data, textStatus, xhr) {
			if(data.status){
					window.location.href=data.url;
			}else{
					layer.open({content:data.info,btn: ['确定'],
					 yes: function(index){
						  location.reload();
						  layer.close(index); 
					}});
			}
		},'json');
	});
	
    $(document).on('click','.phone',function(event){
        if($(this).attr('data-phone')){
            var tmpPhone = $(this).attr('data-phone').split(' ');
            var msg_dom = '<div class="msg-bg"></div>';
            msg_dom+= '<div id="msg" class="msg-doc msg-option">';
            msg_dom+= '<div class="msg-bd">'+($(this).data('phonetitle') ? $(this).data('phonetitle') : '拨打电话')+'</div>';
            for(var i in tmpPhone){
                msg_dom+= '<div class="msg-option-btns"><a class="btn msg-btn" href="tel:'+tmpPhone[i]+'">'+(tmpPhone.length == 1 && $(this).data('phonetip') ? $(this).data('phonetip') : tmpPhone[i])+'</a></div>';
            }
            msg_dom+= '     <button class="btn msg-btn-cancel" type="button">取消</button>';
            msg_dom+= '</div>'; 
            $('body').append(msg_dom);
        }
        event.stopPropagation();
    });
    $(document).on('click','.msg-btn-cancel,.msg-bg',function(){
        $('.msg-doc,.msg-bg').remove();
    });
	if(typeof(wxSdkLoad) != "undefined"){
		wx.ready(function(){
			if(window.__wxjs_environment === 'miniprogram'){
				go_shop_bind(2);
			}else{
				go_shop_bind(1);
			}
		});
	}else{
		go_shop_bind(1);
	}
});

function go_shop_bind(type){
	if(type == 1){
		$('#go_shop').click(function(){
			if($(this).data('is_mult_class') == '1'){
				location.href = "{pigcms{:U('Shop/classic_shop')}&shop_id={pigcms{$store['store_id']}";
			}else{
				location.href = "{pigcms{:U('Shop/index')}#shop-{pigcms{$store['store_id']}";
			}
		});
	}else{
		$('#go_shop').click(function(){
			wx.miniProgram.navigateTo({url: "/pages/shop_new/shopDetail/shopDetail?store_id={pigcms{$store['store_id']}"});
		});
	}
}

$(".public").each(function(){
    $(this).css("top",$(".t_remind").height())
});
$(".defrayal").each(function(){
    $(this).css("top",$(".t_remind").height()+44);
});
$(".h105").each(function(){
    $(this).css("height",105 + $(".t_remind").height());
})

if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
	var reg = /versioncode=(\d+),/;
	var arr = reg.exec(navigator.userAgent.toLowerCase());
	if(arr == null){
		
	}else{
		var version = parseInt(arr[1]);
		if(version >= 50){
			if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
				$('#goBackUrl').click(function(){
					if(version >= 100){
						$('body').append('<iframe src="pigcmso2o://closeWebView" style="display:none;"></iframe>');
					}else{
						$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
					}
					return false;
				});
			}else{
				if(version >= 100){
					window.lifepasslogin.hideWebViewHeader(false);
				}
				$('#goBackUrl').click(function(){
					if(version >= 100){
						window.lifepasslogin.closeWebView();
					}else{
						window.lifepasslogin.webViewGoBack();
					}
					return false;
				});
			}
		}
	}
}

function appbackmonitor(){
	if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
		$('body').append('<iframe src="pigcmso2o://closeWebView" style="display:none;"></iframe>');
	}else{
		window.lifepasslogin.closeWebView();
	}
}
$('.order-pay').click(function(){
	var order_id = $(this).data('order_id');
	if(typeof wx !=='undefined' && window.__wxjs_environment === 'miniprogram'){
		wx.miniProgram.navigateTo({url:'/pages/pay/index?order_id='+ order_id +'&type=shop'});
	}else{
		location.href = "{pigcms{:U('Pay/check',array('type'=>'shop'))}&order_id="+order_id;
	}
});
</script>