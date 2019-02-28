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
</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
		<a <if condition='$now_order["appoint_id"]'>href="{pigcms{$now_order.url}"</if>>
			<dl class="list">
				<dd class="dd-padding">
					<div class="more more-weak">
						<div class="dealcard" <if condition='!$now_order["appoint_id"]'>style=" height:1.6rem"</if> >
							<div class="dealcard-img imgbox" style="background:none;"><img src="{pigcms{$now_order.list_pic}" style="width:100%;height: 1.58rem;"/></div>
							<div class="dealcard-block-right">
								<div class="dealcard-brand single-line">{pigcms{$now_order.appoint_name}</div>
								<div class="title text-block">
									定金：￥{pigcms{$now_order.payment_money}元<br/>
									服务类型：
										<if condition='!$now_order["appoint_id"]'>
											<span style="color:green">自营</span>
										<else />
											<if condition="$now_order['appoint_type'] eq 0"><span style="color:green">到店</span>
												<elseif condition="$now_order['appoint_type'] eq 1" /><span style="color:green">上门</span>
											</if>
										</if>
									
										
								</div>
								<if condition='$now_order["appoint_id"]'>
									<div class="price">
										全价：<span class="strong" style="color:#2bb2a3;">￥{pigcms{$now_order.appoint_price}</span><span class="strong-color">元</span>
									</div>
								</if>
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
			                <a class="react" <if condition='$now_order["appoint_id"]'>href="{pigcms{:U('Appoint/branch',array('appoint_id'=>$now_order['appoint_id']))}"</if>>
			                    <div class="more more-weak">
			                        <h6>商家信息</h6>
			                        <span class="more-after">查看</span>
			                    </div>
			                </a>
		                </dd>
					</dl>
				</dd>
			</dl>
			
			<if condition='$now_order["type"] neq 1'>
			<dl class="list coupons">
				<dd>
					<dl>
						<dt>{pigcms{$config.appoint_alias_name}时间</dt>
						<dd class="dd-padding coupons-code">
							预约时间: {pigcms{$now_order.appoint_date}&nbsp;&nbsp;{pigcms{$now_order.appoint_time}
						</dd>
					</dl>
				</dd>
			</dl>
			</if>
			<dl class="list">
				<dd>
					<dl>
						<dt>{pigcms{$config.appoint_alias_name}详情</dt>
						<ul class="ul">
							<li>订单编号：{pigcms{$now_order.order_id}</li>
							<li>下单时间：{pigcms{$now_order.order_time|date='Y-m-d H:i',###}</li>
							<li>手机号：{pigcms{$now_order.phone}</li>
							
							<if condition='$now_order["product_detail"]'>
								<li>选择服务：{pigcms{$now_order["product_detail"]['name']}</li>
								<li>选择服务价格：¥ {pigcms{$now_order["product_detail"]['price']}</li>
							</if>
							
							<li>服务状态：
								<if condition="$now_order['service_status'] eq 0"><span style="color:red">未服务</span>
								<elseif condition="$now_order['service_status'] eq 1" /><span style="color:green">已服务</span>
                                <elseif condition="$now_order['service_status'] eq 2" /><span style="color:green">已评价</span>
								</if>
								
								<if condition='$now_order["is_del"] neq 0'>
									<span style="color:red">已取消</span>
								</if>
							</li>
						</ul>
					</dl>
				</dd>
			</dl>
			<if condition="$now_order['paid'] eq 2 && $now_order['payment_status'] eq 1">
				<div class="btn-wrapper">
					<span class="btn btn-larger btn-block" style="background-color:#BBB9B5;">已退款</span>
				</div>
			<elseif condition="$now_order['paid'] eq 1 && $now_order['service_status'] eq 1"/>
				<div class="btn-wrapper">
					<span onclick="window.location.href='{pigcms{:U('My/appoint_feedback',array('order_id'=>$now_order['order_id']))}'" class="btn btn-larger btn-block btn-strong">评价</span>
				</div>
			<elseif condition="($now_order['paid'] eq 0) && ($now_order['payment_status'] eq 1) && ($now_order['is_del'] eq 0)" />
				<div class="btn-wrapper">			
					<span onclick="window.location.href='{pigcms{:U('Pay/check',array('type'=>'appoint','order_id'=>$now_order['order_id']))}'" class="btn btn-larger btn-block btn-strong" style="margin-bottom:15px;">付款</span>
					<!--span onclick="cancel_order({pigcms{$now_order['order_id']})" class="btn btn-larger btn-block" style="margin-bottom:15px;">取消订单</span-->
				</div>
			<elseif condition="$now_order['payment_status'] eq 0"/>
				<if condition='($now_order["is_del"] eq 0) && ($now_order["paid"] eq 0)'>
					<!--div class="btn-wrapper">
						<span onclick="cancel_order({pigcms{$now_order['order_id']})" class="btn btn-larger btn-block btn-strong" style="margin-bottom:15px;">取消订单</span>
					</div-->
				<else />
					<div class="btn-wrapper">
						<span onclick="window.location.href='{pigcms{:U('My/appoint_order_list')}'" class="btn btn-larger btn-block btn-strong" style="margin-bottom:15px;">确定</span>
					</div>
				</if>
				
			</if>
			
			
		</div>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/jquery.qrcode.min.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>		
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<include file="Public:footer"/>
		<script>
			$(function(){
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
			});
			
			
			function cancel_order(order_id){
				if(confirm('取消后，将无法恢复，是否确认取消？')){
					var url = "{pigcms{:U('ajax_wap_user_del')}";
					$.post(url,{'order_id':order_id},function(data){
						alert(data.msg);
						if(data.status){
							location.href="{pigcms{:U('appoint_order_list')}";
						}
					},'json')
				}
			}
		</script>
{pigcms{$hideScript}
</body>
</html>