
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>订单详情</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
     <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <!-- <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/> -->
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
	<style>
    .btn-wrapper {
        margin: .28rem .2rem;
		position:fixed; bottom:0; right:0; width:100%;
		border-top:1px solid #e5e5e5;
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
	/*.share-user div{
		float:left;
		height:1.5rem;
		margin-bottom:5px;
	}*/
	/*.share-user span{
		display:block;
		text-align:center;
	}*/
	
	.img_list{
		width:0.9rem;
		height:0.9rem;
		border-radius:50%; overflow:hidden;
	}
	.dealcard{ background:#f4f4f4; height:2rem}
	#index .order-top{ margin-top:0}
	.dealcard .dealcard-brand{ height:.8rem; line-height:.8rem;overflow: hidden;}
	.dealcard .price{ height:1rem; line-height:1rem; color:#000}
	.dealcard-footer{ font-weight:bold; margin-top:15px; padding-left:.1rem}
	.dealcard-img{ left:.1rem; top:.1rem}
	.more .more-after-order-top{ position:static;}
	.btn-wrapper{ background:#fff; height:1rem; margin:.3rem 0 0 0;}
	.order-cancel,.order-pay{   display:block; float:right; margin-right:.2rem; border-radius:4px; padding:.2rem; margin-top:.1rem;}
	.order-cancel{border:1px solid #e5e5e5;color:#666}
	.order-pay{  color:#06c1bb; border:1px solid rgb(214, 247, 246); padding:.2rem .5rem}
	
	.hidden_after:after{
		display:none;
	}

	.m-simpleFooter-text{
		font-size: 0;
	}
	.w-button {
		width: 50%;
		background: #db3652!important;
		text-align: center;
		white-space: nowrap;
		font-size: 14px;
		display: inline-block;
		color: #fff;
		background: #3399FE;
		border-width: 0;
		border-style: solid;
		border-color: #1B7DE0;
		
	
		height: 1rem;
		line-height: 1rem;
		cursor: pointer;
		text-decoration: none!important;
		outline: none;
		font-size:14px;
	}
	.m-simpleFooter-text .w-button:last-child{
		background:#fff!important;
		font-size: 14px;
		color: #333;
	}
	h6{
		font-size:0.28rem;
	}	
</style>
<style>	
	.listTuan{
		display: -webkit-flex;
	    display: flex;
	    -webkit-box-pack: justify;
	    -webkit-justify-content: space-between;
	    justify-content: space-between;
	    -webkit-box-align: center;
	    -webkit-align-items: center;
	    align-items: center;
	    width: 100%;
	    padding:6px 0;
	    border-bottom: 1px solid #f4f4f4;
	}
	.listTuan img{
		width: 0.9rem;
		height: 0.9rem;
		border-radius:50%;
	}
	.listTuan ul{
		width: 85%;
	}
	.listTuan ul li:first-child{
		color: #333;
	}
	.listTuan ul li:first-child span{
		background: #FF4C44;
		/*width: 40px;*/
		text-align:center;
		border-radius: 3px;
		padding: 2px 5px;
		color: #fff;
	}
	.listTuan ul li:last-child{
		color: #999;
	}
	#user-list{
		height: 150px;
		overflow-y:scroll;
	}
</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		
		<dl class="list order-top">
				<dd>
					<dl>
						<dd>
			                <a href="{pigcms{:U('Wap/Index/index',array('token'=>$now_group['mer_id']))}" class="react">
			                    <div class="more more-weak more-order-top">
			                        <h6>{pigcms{$now_group.merchant_name}</h6>
			                    </div>
			                </a>
		                </dd>
					</dl>
				</dd>
			</dl>
		
		<a href="{pigcms{$now_order.url}">
			<dl class="list">
				<dd class="dd-padding">
					<div>
						<div class="dealcard">
							<div class="dealcard-img imgbox" style="background:none;"><img src="{pigcms{$now_order.list_pic}" style="width:100%;height: 1.58rem;"/></div>
							<div class="dealcard-block-right">
								<div class="dealcard-brand single-line">{pigcms{$now_order.s_name}</div>
								<div class="price">
									<span class="strong">￥{pigcms{$now_order.price}</span>&nbsp;×&nbsp;<span>{pigcms{$now_order.num}</span>
								</div>
							</div>
						</div>
						<div class="dealcard-footer">
							<span>实付款：￥{pigcms{$now_order['total_money']}</span>
						</div>
					</div>
				</dd>
			</dl>
		</a>
        <div class="wrapper-list">
			<dl class="list" style="border-bottom:none;"></dl>
			<dl class="list">
					<if condition="$now_group.group_type neq 2">
				<dd>
					<dl>
						<dd>
			               
							
							 <div class="more more-weak hidden_after" style="    padding: 0.27rem 0rem .28rem 0rem;">
								<h6>{pigcms{$config.group_alias_name}有效期</h6>
								<span class="more-after">{pigcms{$now_group.deadline_time|date='Y-m-d H:i:s',###}</span>
							</div>
		                </dd>
					</dl>
					</dd>
					</if>
				<dd>
					<dl>
						<dd>
			                <a class="react" href="{pigcms{:U('Group/branch',array('group_id'=>$now_order['group_id']))}">
			                    <div class="more more-weak">
			                        <h6>商家信息</h6>
			                        <span class="more-after">查看</span>
			                    </div>
			                </a>
		                </dd>
					</dl>
				</dd>
			</dl>
			<php>if($my_group_join['status']==2) {</php>
			<dl class="list coupons">
						<dd>
							<dl>
								<dd class="dd-padding coupons-code">该团购已超时</dd>
								
							</dl>
						</dd>
					</dl>
			<php>}elseif(!$my_group_join['status'] && $now_order['status']<3 && $now_order['single_buy']==0 && $now_order['paid']) {</php>
					<dl class="list coupons">
						<dd>
							<dl>
								<dd class="dd-padding coupons-code">参团份数还未达到 {pigcms{$my_group_join['complete_num']} 份的份数要求</dd>
								<dd class="dd-padding coupons-code" id="my_group_join">
									还差 {pigcms{$my_group_join['complete_num']-$my_group_join['num']} 人就可以成团&nbsp;<php>if($now_order['status']!=3){</php><strong id="countdown">&nbsp;&nbsp;</strong>秒后刷新数据<php>}</php>
								</dd>
									<dd class="dd-padding coupons-code" id="hour_show"></dd>
							</dl>
						</dd>
					</dl>
			<php>}else{</php>
				<if condition="($now_order['tuan_type'] neq 2 AND $now_order['paid'] )OR ($now_order['single_buy'] AND $now_order['tuan_type'] neq 2 AND $now_order['paid']  ) ">
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>{pigcms{$config.group_alias_name}券</dt>
								<php>if($now_order['pass_array']){</php>
								<volist name="pass_array" id="vv">
									<dd class="dd-padding coupons-code">
										消费密码: <php>if($vv['status'] == 2){</php><font color="red">无法查看</font><php>}else{</php>{pigcms{$vv.group_pass}<php>}</php> <small><php>if($vv['status']==0){</php>未消费<php>}elseif($vv['status']==1){</php>已消费<php>}elseif($vv['status']==3){</php><font color="red">还需支付：{pigcms{$vv['need_pay']} 元</font><php>}elseif($vv['status']==2){</php><font color="red">已退款</font><php>}</php></small>
									</dd>
								</volist>
								<php>}else{</php>
									<dd class="dd-padding coupons-code">
										消费密码: {pigcms{$now_order.group_pass_txt} <small>{pigcms{$now_order.status_txt}</small>
									</dd>
								<php>}</php>
								<php> if($now_order['status']!=4&&$now_order['status']!=6&&$now_order['status']!=3){</php>
								<dd class="dd-padding coupons-code" >
									消费二维码: <a id="see_storestaff_qrcode">查看二维码</a>
								</dd>
								<php>}</php>
						</dl>
						
					</dd>
				</dl>

			<elseif condition="$now_order['paid']" />
				<php> if(!$now_order['is_pick_in_store']){</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>快递信息</dt>
							<dd class="dd-padding coupons-code">
								收货人：{pigcms{$now_order.contact_name}
							</dd>
							<dd class="dd-padding coupons-code">
								地址：{pigcms{$now_order.adress}
							</dd>
							<dd class="dd-padding coupons-code">
								邮编：{pigcms{$now_order.zipcode}
							</dd>
							<dd class="dd-padding coupons-code">
								电话：{pigcms{$now_order.phone}
							</dd>
							<if condition="$now_order['express_type']">
								<dd class="dd-padding coupons-code">
									快递公司：{pigcms{$now_order.express_info.name}
								</dd>
								<dd class="dd-padding coupons-code">
									快递单号：{pigcms{$now_order.express_id}<small><a href="http://m.kuaidi100.com/index_all.html?type={pigcms{$now_order.express_info.code}&postid={pigcms{$now_order.express_id}&callbackurl=<?php echo 'http://'.urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>" target="_blank" style="color:#1B9C46;">查看物流信息</a></small>
								</dd>
							</if>
						</dl>
					</dd>
				</dl>
				<php>}else{</php>
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>自取地址</dt>
							<dd class="dd-padding coupons-code">
								地址：{pigcms{$now_order.adress}
							</dd>
							<dd class="dd-padding coupons-code">
								电话：{pigcms{$now_order.phone}
							</dd>
							
						</dl>
					</dd>
				</dl>
				<php>}</php>
				
			</if>
			
			<php>}</php>

			<if condition="$now_order['status'] lt 3 AND $now_order['single_buy'] eq 0 && $now_order['paid']">
			<dl class="list coupons">
				<dd>
					<dl>
						<dd class="dd-padding coupons-code share-user" <if condition="count($buyer) gt 5">style="height:3.5rem"</if>>
						<h5 style="padding-bottom:5px; border-bottom:1px solid #f4f4f4;font-size:0.28rem;margin-top: 0; ">参团记录:</h5>
							<div id="user-list">
								<!-- <volist name="buyer" id="vo">
									<div id="uid-{pigcms{$vo.uid}" data-id="{pigcms{$vo.uid}">
									
										<if condition="$vo.is_head gt 0">
										<b style="background:url({pigcms{$static_path}img/group_head.png);    display: inline-block;height: 17px;width: 43px;background-size: contain;position: absolute;left: 0px;"></b>
										
										</if>
										<if condition="$key lt 10">
										<img class="img_list" src="{pigcms{$vo.avatar}" />
										<span >{pigcms{$vo.nickname}</span>
										<else />
										<span >......</span>
										</if>
									</div>
								</volist> -->
								<volist name="buyer" id="vo">
									<div class="listTuan" id="uid-{pigcms{$vo.uid}" data-id="{pigcms{$vo.uid}">
										<img src="<if condition="$vo['avatar'] neq ''">{pigcms{$vo.avatar}<else />{pigcms{$static_public}img/images/nohead.png</if>" alt="">
										<ul>
											<li>{pigcms{$vo.nickname} <if condition="$vo.is_head gt 0"><span>团长</span></if> <if condition="$vo.uid eq $user_session['uid']"><span>我</span></if></li>
											<li>{pigcms{$vo.pay_time|date='Y-m-d H:i',###} <if condition="$vo.is_head gt 0">开团<else />参团</if></li>
										</ul>
									</div>
								</volist>
									
							</div>
						</dd>
					</dl>
				</dd>
			</dl>
			</if>

			<dl class="list">
				<dd>
					<dl>
						<dt>订单详情<!-- <if condition="$now_order.is_head gt 0 AND $effective_time gt 0 AND $my_group_join['status'] lt 2"><font color="red" size="1">(团长订单不能取消)</font></if> --></dt>
						<ul class="ul">
							<li>订单编号：{pigcms{$now_order.real_orderid}</li>
							<li>下单时间：{pigcms{$now_order.add_time|date='Y-m-d H:i',###}</li>
							<li>手机号：{pigcms{$now_order.phone}</li>
							<if condition="$now_order.express_fee gt 0"> <li>配送费：{pigcms{$now_order.express_fee|floatval}元</li></if>
								
							<if condition="$now_order['card_discount'] gt 0 AND $now_order['card_discount'] neq 10 "><li>享受会员卡折扣：{pigcms{$now_order['card_discount']}折</li></if>
							<if condition="$now_order.merchant_balance neq 0"><li>商家会员卡余额抵扣：{pigcms{$now_order['merchant_balance']+$now_order['card_give_money']}元</li></if>
							<if condition="$now_order.balance_pay neq 0"><li>平台余额抵扣：{pigcms{$now_order.balance_pay}元</li></if>
							<if condition="$now_order.card_price gt 0"><li>商家优惠券：{pigcms{$now_order.card_price}元</li></if>
							<if condition="$now_order.coupon_price gt 0"><li>平台优惠券：{pigcms{$now_order.coupon_price} 元</li></if>
							<if condition="$now_order['score_deducte'] gt 0"><li>{pigcms{$config['score_name']}抵扣：{pigcms{$now_order.score_deducte}元</li></if>
							<if condition="$now_order.payment_money neq 0"><li>在线支付：{pigcms{$now_order.payment_money}元</li></if>
							<if condition="$now_order['third_id'] eq '0' AND $now_order['pay_type'] eq 'offline'">
								<li>线下需向商家付金额：<font color="red">￥{pigcms{$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price']}元</font></li>
							<elseif condition="($now_order['pay_type']=='offline' AND $now_order['paid'] AND !empty($now_order['third_id'])) OR ($now_order['pay_type']!='offline' AND $now_order['paid'])"/>
								<li>付款方式：{pigcms{$now_order.pay_type_txt}</li>
								<li>付款时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i',###}</li>
							   <if condition="!empty($now_order['use_time'])">
								<li>消费时间：{pigcms{$now_order.use_time|date='Y-m-d H:i',###}</li>
							  </if>
							</if>
							<if condition="$now_order['status'] eq 3 OR $now_order['status'] eq 6">
							<li>
							已退款：<font color="red">{pigcms{$now_order['refund_total']}</font>元<if condition="$now_order['refund_fee'] gt 0">(手续费：{pigcms{$now_order['refund_fee']})</if>
							</li>
							</if>
						</ul>
					</dl>
				</dd>
			</dl>
			<dl style="display:block; height:20px"></dl>
		
			<dl class="list coupons" id="share-url" <php>if($now_order['single_buy']==1||$is_wexin_browser==1||$is_app_browser==1||$my_group_join['status']!=0||($now_order['status']==4||$now_order['status']==3||$now_order['status']==6)||$effective_time < 0){</php>style="display:none"<php>}</php>>
				<dd>
					<dl>
						<dd class="dd-padding coupons-code">
							<input type="text" class="input-weak" name="share-url" value="{pigcms{$config.site_url}{pigcms{:U('Group/detail',array('group_id'=>$now_group['group_id'],'gid'=>$my_group_join['id']))}">
						</dd>
					</dl>
				</dd>
			</dl>
			
			<if condition="$now_order['status'] eq 4 OR  $now_order['status'] eq 6">
				<div class="btn-wrapper">
					<span class="order-cancel" style="background-color:#BBB9B5;">订单已失效</span>
				</div>
			<elseif condition="$now_order['status'] eq 3 OR $now_order['status'] eq 4" />
				<div class="btn-wrapper">
					<span class="order-cancel">订单已取消</span>
				</div>
			<elseif condition="$now_order['status'] eq 2" />
				<div class="btn-wrapper">
					<span class="order-cancel">订单已完成</span>
				</div>
			<elseif condition="empty($now_order['paid'])" />
				<div class="btn-wrapper">			
					<span onclick="window.location.href='{pigcms{:U('Pay/check',array('type'=>'group','order_id'=>$now_order['order_id']))}'" class="order-pay" style="margin-bottom:15px;">付款</span>
					<a id="cancel_order" class="order-cancel">删除订单</a>
				</div>
			
			<elseif condition="$now_order['status'] eq 0 "/>
				<div class="btn-wrapper">
					<php> if($effective_time > 0 && $my_group_join['status'] == 0 && $now_order['single_buy'] == 0){</php>	
						<!--span class="order-cancel" id="share_group" style="float:left;"  onclick="<php>if($is_wexin_browser){</php>_system._guide(true)<php>}else{</php>copy()<php>}</php>">分享团购</span-->
					
							<div class="m-simpleFooter-text">
								<a id="share_group" class="w-button w-button-main" onclick="<php>if($is_wexin_browser||$is_app_browser){</php>_system._guide(true)<php>}else{</php>copy()<php>}</php>">邀请好友拼团</a>
								<a  class="w-button w-button-main"  href="javascript:void;">团长订单不能取消</a>
							</div>
						
					<php>}else{</php>
						<php> if($now_group['group_refund_fee'] == 100 ){</php>	
							<if condition="$now_order['status'] eq 0 AND $now_order.is_pick_in_store eq 0 AND $now_group.tuan_type eq 2 AND $now_order.express_id neq ''">
					
							<span  class="order-cancel confirm">确认取货</span>
							</if>
							<span class="order-cancel" style="background-color:#BBB9B5;">该团购不能退款</span>
						<php>}else{</php>
							
							<if condition="$now_order['status'] eq 0 AND $now_order.is_pick_in_store eq 0 AND $now_group.tuan_type eq 2 AND $now_order.express_id neq ''">
					
							<span  class="order-cancel confirm">确认取货</span>
							<elseif condition="$now_order.is_head gt 0 AND $effective_time gt 0 AND $my_group_join['status'] lt 2"/>
								
							<else />
							<php> if($config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0 && $now_merchant['sub_mch_refund'] == 0 && $now_order['is_own'] == 2 && $now_order['pay_type'] == 'weixin'){</php>
								<a  class="order-cancel" href="tel:{pigcms{$now_merchant.phone}">该订单不能退款，请联系商家【{pigcms{$now_merchant.name}】</a>
							<php>}else if($now_group['no_refund']==1){</php>
								<a class="order-cancel" href="tel:{pigcms{$now_merchant.phone}">该订单不能退款，请联系商家 【{pigcms{$now_merchant.name}】</a>
							<php>}else{</php>
							<span onclick="window.location.href='{pigcms{:U('My/group_order_refund',array('order_id'=>$now_order['order_id']))}'" class="order-cancel">取消订单</span>
							
							<php>}</php>
							</if>
						<php>}</php>
					<php>}</php>
				
				</div>
			<elseif condition="$now_order['status'] eq 1"/>
				<div class="btn-wrapper">
					<span onclick="window.location.href='{pigcms{:U('My/group_feedback',array('order_id'=>$now_order['order_id']))}'" class="order-cancel">评价</span>
				</div>
			</if>
					</div>

    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/jquery.qrcode.min.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>		
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<style type="text/css">
		button{width:100%;text-align:center;border-radius:3px;}
		.button2{font-size:16px;padding:8px 0;border:1px solid #adadab;color:#000000;background-color: #e8e8e8;background-image:linear-gradient(to top, #dbdbdb, #f4f4f4);background-image:-webkit-gradient(linear, 0 100%, 0 0, from(#dbdbdb),to(#f4f4f4));box-shadow: 0 1px 1px rgba(0,0,0,0.45), inset 0 1px 1px #efefef; text-shadow: 0.5px 0.5px 1px #ffffff;}
		.button2:active{background-color: #dedede;background-image: linear-gradient(to top, #cacaca, #e0e0e0);background-image:-webkit-gradient(linear, 0 100%, 0 0, from(#cacaca),to(#e0e0e0));}
		#mess_share{margin:15px 0;}
		#share_1{float:left;width:49%;}
		#share_2{float:right;width:49%;}
		#mess_share img{width:22px;height:22px;}
		#cover{display:none;position:absolute;left:0;top:0;z-index:18888;background-color:#000000;opacity:0.7;}
		#guide{display:none;position:absolute;right:18px;top:5px;z-index:19999;}
		#guide img{width:260px;height:180px;}
		</style>
		<script type="text/javascript">
			var flag=false;
			
			<if condition="$now_group.pin_num gt 0 AND $now_order['status']!='3'">
				var last_num = {pigcms{$my_group_join['complete_num']-$my_group_join['num']} ;
				var complete_num = {pigcms{$my_group_join['complete_num']};
				
				function ajax_get_num(){
					$.post('{pigcms{:U('My/ajax_now_pin_num')}', {order_id:{pigcms{$now_order.order_id},uid:{pigcms{$now_order.uid}}, function(data, textStatus, xhr) {
						data = JSON.parse(data);
						if(data.error_code){
							alert(data.msg);
						}else{
							if(data.num.num!=last_num){
								last_num = data.num;
								flag = true;
							}else {
								flag = false;
							}
							var num = complete_num-data.num.num;
							if(num<=0){
								document.location.reload();
							}
							if(flag)
								ajax_get_user();
							$('#my_group_join').html('还差 '+num+' 份就可以成团&nbsp;<strong id="countdown">&nbsp;&nbsp;</strong>秒后刷新数据');
						}
					});
				}
				
				function ajax_get_user(){
					var uids='';
					$.each($('#user-list .listTuan'), function(index, val) {
						uids += $(this).attr('data-id')+',';
					});
					$.post('{pigcms{:U('My/ajax_group_user')}', {type:'pin',order_id:{pigcms{$now_order.order_id},uid:{pigcms{$now_order.uid},uids:uids}, function(data, textStatus, xhr) {
						data = JSON.parse(data);
						if(data.error_code){
							alert(data.msg);
						}else{
							var user_arr = data.res.user_arr;
							if(user_arr){
								$.each(user_arr, function(index, val) {
									//console.log(val.uid+'->'+$('#uid-'+val.uid).length);
									if($('#uid-'+val.uid).length==0){
							
										$('#user-list').append('<div class="listTuan" id="uid-'+val.uid+'" data-id="'+val.uid+'"><img src="'+val.avatar+'" alt=""><ul><li>'+val.nickname+'</li><li>'+val.pay_time+'参团</li></ul></div>');
										var lens1=$('#user-list .listTuan').length;
										console.log(lens1);
										if(lens1<=1){
											$('#user-list').height(60*lens1);
										}else{
											$('#user-list').height(150);
										}
										
										
									}
									
								});
							}
							
							if(data.res.not_in){
								$.each(data.res.not_in, function(index, val) {
									$('#uid-'+val).remove();
								});	
							}
							flag=false;
						}
					});
				}
				var start = 5;
				var step = -1;
				function count(){
					document.getElementById("countdown").innerHTML = start;
					start += step;
					if(start ==0){
						start =5;
						ajax_get_num();
						
					}
					setTimeout("count()",1000);
				}
				window.onload = count;
			</if>
						
			var _system={
				$:function(id){return document.getElementById(id);},
		   _client:function(){
			  return {w:document.documentElement.scrollWidth,h:document.documentElement.scrollHeight,bw:document.documentElement.clientWidth,bh:document.documentElement.clientHeight};
		   },
		   _scroll:function(){
			  return {x:document.documentElement.scrollLeft?document.documentElement.scrollLeft:document.body.scrollLeft,y:document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop};
		   },
		   _cover:function(show){
			  if(show){
				 this.$("cover").style.display="block";
				 this.$("cover").style.width=(this._client().bw>this._client().w?this._client().bw:this._client().w)+"px";
				 this.$("cover").style.height=(this._client().bh>this._client().h?this._client().bh:this._client().h)+"px";
			  }else{
				 this.$("cover").style.display="none";
			  }
		   },
			_guide:function(click){
				  this._cover(true);
				  this.$("guide").style.display="block";
				  this.$("guide").style.top=(_system._scroll().y+5)+"px";
				  window.onresize=function(){_system._cover(true);_system.$("guide").style.top=(_system._scroll().y+5)+"px";};
				if(click){_system.$("cover").onclick=function(){
					 _system._cover();
					 _system.$("guide").style.display="none";
				 _system.$("cover").onclick=null;
				 window.onresize=null;
				  };
			  }
			  //is_share_group();
		   },
		   _zero:function(n){
			  return n<0?0:n;
		   }
		}
		</script>
		<script>
			$(function(){
				$('input[name="share-url"]').select();
				$('#cancel_order').click(function(){
					if(confirm('您确定取消订单吗？取消后不能恢复！')){
						window.location.href = "{pigcms{:U('My/group_order_del',array('order_id'=>$now_order['order_id']))}";
					}
				});
				$('#see_storestaff_qrcode').click(function(){
					var qrcode_width = $(window).width()*0.6 > 256 ? 256 : $(window).width()*0.6;
					layer.open({
						title:['消费二维码','background-color:#8DCE16;color:#fff;'],
						content:'生成的二维码仅限提供给商家店铺员工扫描验证消费使用！<br/><br/><div id="qrcode"></div>',
						success:function(){
							$('#qrcode').qrcode({
								width:qrcode_width,
								height:qrcode_width,
								text:"{pigcms{$config.site_url}/wap.php?c=Storestaff&a=group_qrcode&order_id={pigcms{$now_order.order_id}&id={pigcms{$now_order.group_pass}"
							});
						}
					});
					$('.layermbox0 .layermchild').css({width:qrcode_width+30+'px','max-width':qrcode_width+30+'px'});
				});
				
				$('.confirm').click(function(){
					layer.open({
						content: '确定收货吗？'
						,btn: ['确定', '取消']
						,skin: 'footer'
						,yes: function(index){
						   window.location.href='{pigcms{:U('My/group_recive_confirm',array('order_id'=>$now_order['order_id']))}';
						}
					});
				});
			});
			
			function copy(){
				$('#share-url').css('display','block');
				$('input[name="share-url"]').select();
				// $('#share_group').css('width','120px')
				$('#share_group').html('<font color="#fff">复制链接分享好友</font>');
			}
			
			
		</script>
		</script>
		<div id="cover"></div>
		<div id="guide"><img src="{pigcms{$static_path}images/guide1.png"></div>
	
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<script type="text/javascript">
			
			window.shareData = {  
				"moduleName":"Group",
				"moduleID":"0",
				"imgUrl": "{pigcms{$config.site_url}/upload/group/{pigcms{$now_group.pic.0}", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Group/detail', array('group_id'=>$now_order['group_id'],'gid'=>$my_group_join['id']))}",
				"tTitle": "邀您一起参加拼团活动！",
				"tContent": "{pigcms{$user_session['nickname']}邀请你参加【{pigcms{$now_group['name']}】拼团，点击进入",
				"firendTitle":  "快来拼团，{pigcms{$user_session['nickname']}正在邀请你参与【{pigcms{$now_group['name']}】拼团商品"
			};
			
			<if condition="$my_group_join['status'] eq 0 AND $effective_time">
			var h={pigcms{$effective_time['h']};
			var m={pigcms{$effective_time.m};
			var s={pigcms{$effective_time.s};
			time=setInterval("run()",1000);
			
			function run(){
				--s;
				if(s<0){
					--m;
					s=59;
				}
				if(m<0){
					--h;
					m=59
				}
				if(h<0){
					s=0;
					m=0;
					 window.location.reload();
				}
				$('#hour_show').html('该团有效期还有：<strong id="hour_show"><s id="h"></s>'+h+'</strong>:<strong id="minute_show"><s></s>'+m+'</strong>:<strong id="second_show"><s></s>'+s+'</strong>');
			}
		</if>
		</script>
		<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.0.js"></script>
	<script type="text/javascript">
		wx.config({
		  debug: false,
		  appId: 	'{pigcms{$sign_data['appId']}',
		  timestamp: {pigcms{$sign_data['timestamp']},
		  nonceStr: '{pigcms{$sign_data['nonceStr']}',
		  signature: '{pigcms{$sign_data['signature']}',
		  jsApiList: [
		    'checkJsApi',
		    'onMenuShareTimeline',
		    'onMenuShareAppMessage',
		    'onMenuShareQQ',
		    'onMenuShareWeibo',
		    'scanQRCode',
			'chooseImage',
			'previewImage',
			'uploadImage',
			'downloadImage',
			'getLocation',
			'openLocation',
			'getNetworkType',
			'startRecord',
			'stopRecord',
			'onVoiceRecordEnd',
			'playVoice',
			'translateVoice',
		 	'requireSoterBiometricAuthentication',
			'getSupportSoter',
			'addCard',
			'chooseCard',
			'openCard'
		  ]
		});
		var wxSdkLoad = true;
	</script>
	<script type="text/javascript">
	wx.ready(function () {
		wx.showOptionMenu();
	  // 1 判断当前版本是否支持指定 JS 接口，支持批量判断
	  /*document.querySelector('#checkJsApi').onclick = function () {
	    wx.checkJsApi({
	      jsApiList: [
	        'getNetworkType',
	        'previewImage'
	      ],
	      success: function (res) {
	        //alert(JSON.stringify(res));
	      }
	    });
	  };*/

	  // 2. 分享接口
	  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
	    wx.onMenuShareAppMessage({
			title: window.shareData.tTitle,
			desc: window.shareData.tContent,
			link: window.shareData.sendFriendLink + '&openid=' + '{pigcms{$_SESSION['openid']}',
			imgUrl: window.shareData.imgUrl,
		    type: '', // 分享类型,music、video或link，不填默认为link
		    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		    success: function () { 
				//shareHandle('frined');
		        //alert('分享朋友成功');
		    },
		    cancel: function () { 
		        //alert('分享朋友失败');
		    }
		});


	  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
		wx.onMenuShareTimeline({
			title: window.shareData.firendTitle,
			link: window.shareData.sendFriendLink + '&openid=' + '{pigcms{$_SESSION['openid']}',
			imgUrl: window.shareData.imgUrl,
		    success: function () { 
				//shareHandle('frineds');
		        //alert('分享朋友圈成功');
		    },
		    cancel: function () { 
		        //alert('分享朋友圈失败');
				alter( window.shareData.firendTitle)
		    }
		});	

	  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
		wx.onMenuShareWeibo({
			title: window.shareData.tTitle,
			desc: window.shareData.tContent,
			link: window.shareData.sendFriendLink + '&openid=' + '{pigcms{$_SESSION['openid']}',
			imgUrl: window.shareData.imgUrl,
		    success: function () { 
				//shareHandle('weibo');
		       	//alert('分享微博成功');
		    },
		    cancel: function () { 
		        //alert('分享微博失败');
		    }
		});
		
	});
		

</script>
		<if condition="$is_app_browser">
           
            <script type="text/javascript">
                window.lifepasslogin.shareLifePass("{pigcms{$config.group_alias_name}】{pigcms{$now_group.s_name}","{pigcms{$user_session['nickname']}邀请你参加团购，享受优惠","{pigcms{$config.site_url}/upload/group/{pigcms{$now_group.pic.0}","{pigcms{$config.site_url}{pigcms{:U('Group/detail', array('group_id' => $now_group['group_id'],'fid'=>$now_order['order_id']))}");
            </script>
        </if>
		</body>
</html>