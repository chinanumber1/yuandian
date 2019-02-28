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
	.duobao-code b {
	  display: inline-block;
	  margin-right: 6px;
	  margin-bottom: 2px;
	  font-weight: normal;
	}
	.coupons-code div{
		 margin-bottom: 0.3rem;
	}
</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		<a href="{pigcms{$now_activity.url}">
			<dl class="list">
				<dd class="dd-padding">
					<div class="more more-weak">
						<div class="dealcard">
							<div class="dealcard-img imgbox" style="background:none;"><img src="{pigcms{$now_activity.list_pic}" style="width:100%;height: 1.58rem;"/></div>
							<div class="dealcard-block-right">
								<div class="dealcard-brand single-line">{pigcms{$now_merchant.name}</div>
								<div class="title text-block">[{pigcms{$now_activity.type_txt}] {pigcms{$now_activity.name}</div>
								<div class="price">
									数量：<span class="strong" style="color:#2bb2a3;">{pigcms{$now_activity.part_count}/{pigcms{$now_activity.all_count}</span>
								</div>
							</div>
						</div>
					</div>
				</dd>
			</dl>
		</a>
        <div class="wrapper-list">
			<dl class="list" style="border-bottom:none;"></dl>
			<dl class="list">
				<dd>
					<dl>
						<dd>
			                <a class="react" href="{pigcms{:U('Index/index',array('token'=>$now_merchant['mer_id']))}">
			                    <div class="more more-weak">
			                        <h6>商家信息</h6>
			                        <span class="more-after">查看</span>
			                    </div>
			                </a>
		                </dd>
					</dl>
				</dd>
			</dl>
			<if condition="$now_activity['type'] eq 1">
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>夺宝号码</dt>
							<dd class="dd-padding duobao-code">
								<volist name="number_list" id="vo">
									<b>{pigcms{$vo['number']+10000000}</b>
								</volist>
							</dd>
						</dl>
					</dd>
				</dl>
			<elseif condition="$now_activity['type'] eq 2"/>
				<dl class="list coupons">
					<dd>
						<dl>
							<dt>夺宝号码</dt>
							<dd class="dd-padding coupons-code">
								<volist name="number_list" id="vo">
									<div>消费密码: {pigcms{$vo.number} <small><if condition="$vo['check_time']">已验证<else/>未验证</if></small></div>
								</volist>
							</dd>
						</dl>
					</dd>
				</dl>
			<else/>
			</if>
			<!--dl class="list">
				<dd>
					<dl>
						<dt>订单详情</dt>
						<ul class="ul">
							<li>订单编号：{pigcms{$now_order.order_id}</li>
							<li>下单时间：{pigcms{$now_order.add_time|date='Y-m-d H:i',###}</li>
							<li>手机号：{pigcms{$now_order.phone}</li>
							<li>数量：{pigcms{$now_order.num}</li>
							<li>总价：{pigcms{$now_order.total_money} 元</li>
							<if condition="($now_order['pay_type']=='offline' AND $now_order['paid'] AND !empty($now_order['third_id'])) OR ($now_order['pay_type']!='offline' AND $now_order['paid'])">
								<li>付款方式：{pigcms{$now_order.pay_type_txt}</li>
								<li>付款时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i',###}</li>
							   <if condition="!empty($now_order['use_time'])">
								<li>消费时间：{pigcms{$now_order.use_time|date='Y-m-d H:i',###}</li>
							  </if>
							</if>
						</ul>
					</dl>
				</dd>
			</dl-->
		</div>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/jquery.qrcode.min.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>		
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>