<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache">
	<meta name="format-detection" content="telephone=no"/>
	<meta charset="utf-8">
	<title>{pigcms{$config.site_name} - 店员管理中心</title>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
	<script src="{pigcms{$static_path}js/iscroll.js"></script>
	<script src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js"></script>
	<script src="{pigcms{$static_public}js/layer/layer.js"></script>
	<script>var ajax_goods_list = "{pigcms{:U('Store/ajax_shop_goods')}", store_id = "{pigcms{:$store_id}", arrival_pay = "{pigcms{:U('arrival_pay')}", changePriceUrl = "{pigcms{:U('shop_change_price')}", ajax_card_url = "{pigcms{:U('Store/ajax_card')}", shop_order_save = "{pigcms{:U('Store/shop_order_save')}", shop_arrival_check = "{pigcms{:U('shop_arrival_check')}";</script>
	<script type="text/javascript" src="{pigcms{$static_path}js/market.js"></script>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/market.css"/>
	<!--[if lte IE 9]>
	<script src="scripts/html5shiv.min.js"></script>
	<![endif]-->
</head>

<body>
	<div class="headerBox">
		<div class="txt">线下零售</div>
		<div class="back" data-url="{pigcms{:U('shop_list')}" title="返回订单列表"></div>
		<div class="reload" title="同步库存"></div>
	</div>
	<div class="left">
		<div class="left_n">
			<div class="left_input">
				<input placeholder="条码/商品名称" class="input" id="number_or_name"/>
				<div class="code"></div>
				<div class="del"></div>
			</div>
			<div class="left_tab">
				<div class="tab_top"></div>
				<div class="tab_list">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr class="clr">
							<td class="dy fl">店员: {pigcms{$name}</td>
							<td class="rq fr">日期: {pigcms{$date}</td>
						</tr>
						<tr class="clr">
							<td class="hyh fl left_card_id">会员号: 无</td>
							<td class="ye fr">余额: <span class="jg left_card_money">￥0</span></td>
						</tr>
					</table>
				</div>
				<div class="tabx_list">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr class="top">
							<th width="42">项</th>
							<th width="235">名称</th>
							<th width="55">数量</th>
							<th width="115">金额（元）</th>
						</tr>
					</table>
					<div class="roll_table">
						<div class="tabcon">
							<table cellpadding="0" cellspacing="0" border="0" class="slide">
							</table>
						</div>
					</div>
				</div>
				<div class="number clr">
					<div class="fr">
					合计: <span class="ef2">0</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="right clr">
		<div class="right_1uick fl">
			<ul>
				<li class="js" data-opt="/">结算 /</li>
				<li class="jia num" data-opt="+">+</li>
				<li class="jian num" data-opt="-">-</li>
				<li class="sl p12" data-opt="*">数量 *</li>
				<li class="del p12" data-opt="u">删除 u</li>
				<li class="gd p12" data-opt="h">挂单 h <em>0</em></li>
				<li class="hyk p12" data-opt="y">会员卡 y</li>
				<li class="qk p12" data-opt="d">清空 d</li>
			</ul>
		</div>
		<div class="right_list">
			<div class="right_top">
				<div class="swiper-button-prev"></div> 
				<div class="swiper-container">
					<div class="swiper-wrapper">
					</div> 
				</div>
				<div class="swiper-button-next"></div> 
			</div>
			<div class="right_end">
				<div class="tab_ul">
				</div>
			</div>
		</div>
		<div class="prompt clr"><em></em>提示：<span class="prompt_span"></span><!--span class="prompt_text"> </span--></div>
	</div>

	<div class="fix settlement">
		<div class="set_top">
			<div class="set">结算订单</div>
			<div class="return">返回 Esc</div>
		</div>
		<div class="set_end clr">
			<div class="set_fl fl">
				<div class="number bf">
					<div class="tit clr">
						<div class="fl">订单编号</div>
						<div class="fr">订单总价</div>
					</div>
					<div class="pri clr">
						<div class="fl" id="order_id">0</div>
						<div class="fr on" id="order_price" data-price="0">
							<span>￥0</span>
							<div class="disk"></div>
						</div>
					</div>
				</div>
				<div class="discount bf d4 h40 clr">
					<div class="fl">商家会员卡折扣</div>
					<div class="fr" id="card_discount" data-discount="10">无折扣</div>
				</div>
				<div class="choice bf d4 h40 clr">
					<div class="fl">商家优惠券</div>
					<div class="fr cho_yhj" data-price="0" id="coupon_price" data-coupon="0">选择优惠券</div>
				</div>
				<div class="discount bf d4 h40 clr">
					<div class="fl">应付金额</div>
					<div class="fr">
						<span id="pay_price" data-old_price="0" data-price="0">￥0</span>
						<input type="hidden" id="change_price_reason" />
						<input type="hidden" id="isChange" value="0"/>
						
						<if condition="$is_change eq 1">
						<span class="mod_hui" style="display:none;">
							<em>修改前：￥100</em>
							<em>备注：哈哈</em>
						</span>
						<span class="mod_hand">修改金额</span>
						</if>
					</div>
				</div>
				<div class="credit bf d4">
					<ul class="clr">
						<li class="wb3">
							<div class="li_n">
								<h2 class="name">会员卡号</h2>
								<div class="text" id="user_card_number">无</div>
							</div>
						</li>
						<li class="wb2">
							<div class="li_n">
								<h2 class="name">当前余额</h2>
								<div class="text ef2" id="user_card_total" data-price="0">￥0</div>
							</div>
						</li> 
						<li class="w3">
							<div class="li_n">
								<h2 class="name">本次可用</h2>
								<div class="text ef2"><span id="user_card_money" data-price="0">￥0</span> <span class="modify">修改金额</span> </div>
							</div>
						</li> 
						<!--li class="w2">
							<div class="li_n">
								<div class="text"><span class="use">使用会员卡</span></div>
							</div>
						</li-->
					</ul>
				</div>
				<div class="still bf d4 clr">
					<div class="fr" id="go_pay_money" data-price="0"><span class="still_zf">还需支付：</span>￥0</div>
				</div>
				<div class="pay">
					<ul class="clr">
						<li class="line">
							<div class="line_n">
							<span>线下支付</span>
							</div>
						</li>
						<li class="wx">
							<div class="line_n">
							<span>微信支付</span>
							</div>
						</li>
						<if condition="$config.arrival_alipay_open eq 1">
						<li class="alipay">
							<div class="line_n">
							<span>支付宝</span>
							</div>
						</li>
						</if>
						<li class="confirm">
							<span>确认支付Ent</span>
						</li>
					</ul>
				</div>
			</div>
			<div class="set_fr fr">
				<ul>
					<li class="cancel">
						<div class="line_n">整单取消 z</div>
					</li>
					<li class="on-line">
						<div class="line_n">用户扫码支付</div>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="fix guadan">
		<div class="gua_top">挂单</div>
		<div class="gua_end clr">
			<div class="fl member">
				<h2 class="member_id">挂单号</h2>
				<div class="leaguer">
					<ul>
						<li></li>
						<li></li>
					</ul>
				</div>
			</div>
			<div class="member_list">
				<table class="top">
					<tr>
						<th width="48">行号</th>
						<th width="138">货号</th>
						<th width="187">商品</th>
						<th width="50">数量</th>
						<th width="50">售价</th>
						<th width="75">小计</th>
					</tr>
				</table>
				<div class="tab_end">
					<div class="tab_table">
					</div>
				</div>
			</div>
		</div>
		<div class="operation clr">
			<div class="fl op_gy op_del">删除 F4</div>
			<div class="fr op_gy op_confirm">确认 Ent</div>
			<div class="fr op_gy op_cancel">取消 Esc</div>
		</div>
	</div>


	<div class="fix card">
		<div class="card_top">会员卡</div>
		<div class="card_end clr">
			<div class="inbox_top clr">
				<div class="inbox fl">
					<input type="text" id="card_value" placeholder="会员卡/手机号/昵称" />
					<em></em>
				</div>
				<div class="query fr clr">
					<div class="fr query_qr">确认使用</div>
					<div class="fr query_cx">查询</div>
				</div>
			</div>
			<div class="information">
				<h2 class="h2_top"><span>会员信息</span></h2>
				<ul class="clr">
					<li class="clr">
						<span>会员卡号：</span>
						<div class="p95" id="card_number"></div>
					</li>
					<li class="clr">
						<span>姓&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp名：</span>
						<div class="p95" id="card_name"></div>
					</li>
					<li class="clr">
						<span>实体卡号：</span>
						<div class="p95" id="card_number2"></div>
					</li>
					<li class="clr">
						<span>性&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp别：</span>
						<div class="p95" id="card_sex"></div>
					</li>
					<li class="clr">
						<span>手机号码：</span>
						<div class="p95" id="card_phone"></div>
					</li>
					<li class="clr">
						<span>折&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp扣：</span>
						<div class="p95" id="card_discount_p"></div>
					</li>
					<li class="bot">
						<div class="p15" id="card_score">可用{pigcms{$config['score_name']}：0</div>
					</li>
					<li class="bot">
						<div class="p15">储值余额：<em id="card_money">￥0</em></div>
					</li>
				</ul>
				<!-- 没有数据时 -->
				<div class="img" style="display: none;"><img src="{pigcms{$static_path}images/myxg_03.jpg"></div>
				<!-- 没有数据时 -->
			</div>
		</div>
	</div>

	<div class="fix payment">
		<div class="payment_top clr">
			<div class="fl pay_xs">线上支付</div>
			<div class="fr return">返回 Esc</div>
		</div>
		<div class="payment_end">
			<div class="pay_wc">
				<div class="pay_nc">
					<h2 class="tit">用户<if condition="$config['cash_pay_qrcode']">微信</if>扫码支付</h2>
					<img src="" style="width:198px;height:198px;" id="pay_qrcode_url"/>
				</div>
			</div>
		</div>
	</div>

	<div class="fix chat">
		<div class="chat_top clr">
			<div class="fl wx_chat">微信支付</div>
			<div class="fr return">返回 Esc</div>
		</div>
		<div class="chat_end">
			<div class="chat_n">
				<h2>扫描用户微信付款码支付</h2>
				<div class="input clr">
					<input type="text" class="port fl" value="" id="weixin_txt">
					<div class="firm fr" data-paymethod="weixin">确认支付</div>
				</div>
				<p>建议使用扫码枪直接扫描得到值，或者先刷新用户微信中的码，再写入。如果扫码提示微信错误，可以关闭本页面重新创建订单。</p>
			</div>
		</div>
	</div>

	<div class="fix alip">
		<div class="chat_top clr">
			<div class="fl wx_chat">支付宝</div>
			<div class="fr return">返回 Esc</div>
		</div>
		<div class="chat_end">
			<div class="chat_n">
				<h2>扫描用户支付宝付款码支付</h2>
				<div class="input clr">
					<input type="text" class="port fl" value="" id="alipay_txt">
					<div class="firm fr" data-paymethod="alipay">确认支付</div>
				</div>
				<p>建议使用扫码枪直接扫描得到值，或者先刷新用户支付宝中的码，再写入。如果扫码提示支付宝错误，可以关闭本页面重新创建订单。</p>
			</div>
		</div>
	</div>

	<div class="fix coupon">
		<div class="coupon_top">
			<span>选择优惠券</span>
		</div>
		<div class="coupon_end">
			<div class="swiper-button-prev"></div> 
			<div class="swiper-container">
				<div class="swiper-wrapper">
				</div> 
			</div>
			<div class="swiper-button-next"></div> 
		</div>
	</div>

	<div class="fix container slcontainer counter only_counter" data-type="0">
		<div class="con_pos">
			<h2>请输入数量</h2>
			<input type="text" id="text" name="text" class="text" />
			<div class="buttons">
				<input type="button" value="7" class="button show">
				<input type="button" value="8" class="button show">
				<input type="button" value="9" class="button show">
				<input type="button" value="退格" class="button funback">
				<input type="button" value="4" class="button show">
				<input type="button" value="5" class="button show">
				<input type="button" value="6" class="button show">
				<input type="button" value="取消" class="button cancel funclear">
				<input type="button" value="1" class="button show">
				<input type="button" value="2" class="button show">
				<input type="button" value="3" class="button show">
				<input type="button" value=" " class="button"  style="border: none; background: none;" >
				<input type="button" value="00" class="button show">
				<input type="button" value="0" class="button show">
				<input type="button" value="." class="button point" id="intdd1"> 
				<input type="button" value="确认" class="button confirm getResult" >
			</div>
			<input type="button"  class="jt funclear">
		</div> 
	</div>

	<div class="fix linepay line_600 offline_pay">
		<div class="chat_top clr">
			<div class="fl wx_chat">线下支付</div>
			<div class="fr return">返回 Esc</div>
		</div>
		<div class="linepay_end">
			<div class="over">
				<div class="pay_top clr">
					<div class="pay_fl">
						<ul>
							<li>
								<div class="pay_n clr">
								<div class="fl li_fl">应收</div>
								<div class="fr li_fr" id="offline_total_money" data-price="0">0</div>
								</div>
							</li>
							<li>
								<div class="pay_n pay_input clr" >
								<div class="fl li_fl">已收</div>
								<input type="text" id="offline_finish_money" placeholder="输入收取的金额" name="text" class="text" >
								</div>
							</li>
							<li>
								<div class="pay_n1 clr">
									<div class="fl li_fl">待收</div>
									<div class="fr li_fr" id="offline_wait_money" data-price="0">0</div>
								</div>
								<div class="pay_n2 clr">
								<div class="fl li_fl">找零</div>
								<div class="fr li_fr" id="offline_return_money" data-price="0">0</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="pay_end offline_pay">
					<ul class="clr">
                    <if condition="empty($offline_pay_list)">
						<li class="zfb" data-id="0">现金支付</li>
                    </if>
						<volist name="offline_pay_list" id="row">
						<li class="wx" data-id="{pigcms{$row['id']}">{pigcms{$row['name']}</li>
						</volist>
					</ul>
				</div>
			</div>
		</div>
	</div>


	<div class="fix linepay line_600 change_price">
		<div class="chat_top clr">
			<div class="fl wx_chat">修改金额</div>
			<div class="fr return">返回 Esc</div>
		</div>
		<div class="linepay_end">
			<div class="over">
				<div class="pay_top clr">
					<div class="pay_fl">
						<ul>
							<li>
								<div class="pay_n  pay_input clr">
									<div class="fl li_fl">修改值</div>
									<input type="number" placeholder="这里输入您想要的修改的金额" name="change_price" class="text" >
								</div>
							</li>
							<li>
								<div class="pay_n pay_input clr" style="line-height: 50px;">
									<div class="fl li_fl">备注</div>
									<input type="text" placeholder="输入备注，限定8字以内" name="change_price_reason" class="text" >
								</div>
							</li>
							<li>
								<div class="pay_35 clr">
								<div class="fl li_fl">当前值</div>
								<div class="fr li_fr" data-price="0" id="show_last_price">0</div>
								</div>
							</li>
						</ul>
					</div>
					
				</div>
				<div class="pay_end pay_ends">
					<ul class="clr">
						<li class="hf">
							<span>恢复初始价格</span>
						</li>
						<li class="qr">
							<span>确认修改</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="shadow"></div>
	<div class="shadow_two"></div>
	<script>
		setInterval(function(){
			$.post("/store.php?g=Merchant&c=Store&a=ping");
		},60000);
	</script>
</body>
</html>
