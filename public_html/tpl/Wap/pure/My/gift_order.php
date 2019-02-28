<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{$config['gift_alias_name']}订单详情</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
     <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
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
	.share-user div{
		float:left;
		height:1.5rem;
		margin-bottom:5px;
	}
	.share-user span{
		display:block;
		text-align:center;
	}
	
	.img_list{
		width:1.2rem;
		height:1.2rem;
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
	
</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		
		<dl class="list order-top">
				<dd>
					<dl>
						<dd>
			                <a href="{pigcms{:U('Gift/gift_detail',array('gift_id'=>$now_order['gift_id']))}" class="react">
			                    <div class="more more-weak more-order-top">
			                        <h6>{pigcms{$now_order.gift_name}</h6>
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
							<div class="dealcard-img imgbox" style="background:none;"><img src="{pigcms{$now_order['list_pic']['image']}" style="width:100%;height: 1.58rem;"/></div>
							<div class="dealcard-block-right">
								<div class="dealcard-brand single-line">{pigcms{$now_order.order_name}</div>
								<div class="price">
									<span class="strong">￥{pigcms{$now_order.price}</span>&nbsp;×&nbsp;<span>{pigcms{$now_order.num}</span>
								</div>
							</div>
						</div>
						<div class="dealcard-footer">
							<span>实付款：￥{pigcms{$now_order['total_price']}</span>
						</div>
					</div>
				</dd>
			</dl>
		</a>
        <div class="wrapper-list">
			<dl class="list">
				<dd>
					<dl>
						<dt>订单详情</dt>
						<ul class="ul">
							<li>订单编号：{pigcms{$now_order.order_id}</li>
							<li>下单时间：{pigcms{$now_order.add_time|date='Y-m-d H:i',###}</li>
							<li>手机号：{pigcms{$now_order.phone}</li>
							<li>积分：{pigcms{$now_order.total_integral}</li>
							<li>平台余额：{pigcms{$now_order.payment_money}</li>
							<if condition="$now_order.express_id neq ''"><li>快递信息：{pigcms{$now_order.express_name.name}{pigcms{$now_order.express_id}</li></if>
							<li>付款方式：{pigcms{$now_order.pay_type_txt}</li>
							<li>付款时间：{pigcms{$now_order.pay_time|date='Y-m-d H:i',###}</li>
						</ul>
					</dl>
				</dd>
			</dl>
			<dl style="display:block; height:20px"></dl>
			<dl class="list coupons" id="share-url" style="display:none">
				<dd>
					<dl>
						<dd class="dd-padding coupons-code">
							<input type="text" class="input-weak" name="share-url" value="{pigcms{$config.site_url}{pigcms{:U('Group/detail',array('group_id'=>$now_group['group_id'],'fid'=>$now_order['order_id']))}">
						</dd>
					</dl>
				</dd>
			</dl>
			<if condition=" $now_order.paid eq 1 AND $now_order['status'] neq 3 AND $now_order['is_share_group']!=2">
				<div class="btn-wrapper">
					<span class="btn btn-larger btn-block" id="share_group" style="background-color:#00D618;"  onclick="<php>if($is_wexin_browser){</php>_system._guide(true)<php>}else{</php>copy()<php>}</php>"><if condition="$now_order['is_share_group'] AND $is_wexin_browser eq 0">复制以上链接分享<else />获取分享链接</if></span>
				</div>
			</if>
			
			
			
			<if condition="$now_order['status'] eq 2">
				<div class="btn-wrapper">
					<span class="order-cancel">已完成</span>
				</div>
			<elseif condition="$now_order['status'] eq 1" />
				<div class="btn-wrapper">
					<span class="order-cancel">已发货</span>
				</div>
			<elseif condition="$now_order['paid'] eq 0" />
				<div class="btn-wrapper">
					<span class="order-cancel">未支付</span>
					<span onclick="location.href='{pigcms{:U('Gift/pay_order',array('order_id'=>$now_order['order_id']))}'" class="order-pay" style="margin-bottom:15px;">付款</span>
				</div>
			<elseif condition="$now_order['paid'] eq 1" />
				<div class="btn-wrapper">
					<span class="order-cancel">已支付</span>
				</div>
			<else />
				<div class="btn-wrapper">
					<span class="order-cancel">已完成</span>
				</div>
			</if>

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
		{pigcms{$shareScript}
		</body>
</html>