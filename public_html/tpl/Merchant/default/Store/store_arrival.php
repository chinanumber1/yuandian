<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
		<script>
			var scan_pay_url = "{pigcms{:U('scan_payid_check')}"
		</script>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">店内收银</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink cur" data-url="{pigcms{:U('store_arrival')}">
						<div class="icon order"></div>
						<div class="text">店内收银</div>
					</li>
					<if condition="$config['is_cashier']">
						<li class="urlLink" data-url="{pigcms{:U('cashier')}" style="display:none">
							<div class="icon cashier"></div>
							<div class="text">收银台（独立）</div>
						</li>
					</if>
				</ul>
			</div>
			<div class="rightMain">
				<div class="fixed_header">
					<button class="btn btn-success handle_btn" data-title="创建订单" data-box_width="800px" data-box_height="580px" href="{pigcms{:U('store_arrival_add')}">创建订单</button>
					<if condition="$config['open_score_fenrun'] eq 1"><button class="btn btn-success handle_btn scan_pay" data-title="扫码支付" data-box_width="800px" data-box_height="580px" href="{pigcms{:U('store_scan_payid')}">扫码支付</button></if>
				</div>
				<div class="alert alert-block alert-success">
					<p>
						店内收银功能分为两种：
						<br/>
						<br/>
						第1种：店员输入用户微信付款码付款，理解为扫用户码（暂时仅支持微信）。
						<br/>
						第2种：店员输入好价钱后，让用户扫码付款，支持平台全部在线支付渠道。
					</p>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>订单号</th>
								<th>用户信息</th>
								<th class="hide_col">订单描述</th>
								<th>订单金额</th>
								<th class="hide_col">优惠金额</th>
								<if condition="open_extra_price eq 1"><th class="hide_col">获得{pigcms{$config.score_name}数</th></if>
								<th class="hide_col">使用{pigcms{$config.score_name}数</th>
								<th class="hide_col">{pigcms{$config.score_name}抵扣</th>
								<th class="hide_col">商家优惠券抵扣</th>
								<th class="hide_col">平台优惠券抵扣</th>
								<th>实付金额</th>
								<th>支付时间</th>
								<th>支付类型</th>
							</tr>
						</thead>
						<tbody>
							<volist name="order_list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
								
									<td><div class="tagDiv">{pigcms{$vo.order_id}</div></td>
									<td>
										<if condition="$vo['uid']">
											昵称：{pigcms{$vo['nickname']}<br/>手机：{pigcms{$vo['phone']}
										<else/>
											&nbsp;<br/>&nbsp;
										</if>
									</td>
									<td class="hide_col">{pigcms{$vo['desc']}<if condition="$vo.payid neq '' "><if condition="$vo.desc neq ''">,</if>支付码(平台)：{pigcms{$vo.payid}</if></td>
									<td>{pigcms{$vo['total_price']|floatval}</td>
									<td class="hide_col">{pigcms{$vo['discount_price']|floatval}</td>
									<if condition="open_extra_price eq 1"><td class="hide_col">{pigcms{$vo.score_give|floatval}</td></if>
									<td class="hide_col">{pigcms{$vo.score_used_count}</td>
									<td class="hide_col">{pigcms{$vo.score_deducte|floatval}</td>
									<td class="hide_col">{pigcms{$vo.card_price|floatval}</td>
									<td class="hide_col">{pigcms{$vo.coupon_price|floatval}</td>
									<td>{pigcms{$vo['pay_money']|floatval}</td>
									<td>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</td>
									<td>{pigcms{$vo.pay_type_show}</td>
								</tr>
							</volist>
						</tbody>
					</table>
					{pigcms{$pagebar}
				</div>
			</div>
			
			<div class="fix chat" >
				<div class="chat_top clr">
					<div class="fl wx_chat">扫码支付</div>
					<div class="fr return">返回 Esc</div>
				</div>
				<div class="chat_end" style="    background: gainsboro;">
					<div class="chat_n">
						<h2>扫描用户平台付款码支付</h2>
						<div class="input clr">
							<input type="text" class="port fl" id ="scan_payid" value="" id="weixin_txt">
							<div class="firm fr" id ="scan_pay" data-title="创建订单" data-box_width="800px" data-box_height="580px" data-href="{pigcms{$config.site_url}{pigcms{:U('store_arrival_add')}">确认支付</div>
						</div>
						<p>建议使用扫码枪直接扫描得到值，或者先刷新用户的付款码，再写入。如果扫码提示错误，可以关闭本页面重新创建订单。</p>
					</div>
				</div>
			</div>
			<div class="shadow_two"></div>
		</div>
		<script>
			$(function(){
				if($(window).width() <= 1024){
					$('.hide_col').remove();
				}
			});
			
			if(checkAndroidApp()){
				window.pigcmspackapp.get_custom_display('get_custom_display');
			}
			var customDisplayCan = false;
			var customDisplayType = '';
			var customCanImage = '';
			function get_custom_display(displayType,canImage){
				if(displayType){
					customDisplayCan = true;
					customDisplayType = displayType;
					customCanImage = canImage;
					window.pigcmspackapp.custom_display_work('','');
				}
			}
		</script>
	</body>
</html>