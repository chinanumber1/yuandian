<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{$config.cash_alias_name}</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/pay_ticket.css?2151"/>
		<script>
			var default_money = {pigcms{$default_money|default=0};
			//最低购票数量
			var limit_num = {pigcms{$store['store_trade_ticket']['limit_num']};
			//支付手续费
			var have_charge = false;
			var charge_1 = 1,charge_tikcet_1 = 0;
			var charge_2 = 3,charge_tikcet_2 = 300;
			var charge_3 = 0.01,charge_tikcet_3 = 2000;
			//乘意险
			var have_insure = <if condition="$store['store_trade_ticket']['have_insure'] && $config['store_ticket_have_insure']">true<else/>false</if>;
			var choose_cinsure = <if condition="$store['store_trade_ticket']['have_insure'] && $config['store_ticket_have_insure']">true<else/>false</if>;
			var insure_1 = {pigcms{$store['store_trade_ticket']['insure_1']|floatval=###},insure_tikcet_1 = {pigcms{$store['store_trade_ticket']['insure_tikcet_1']|floatval=###};
			var insure_2 = {pigcms{$store['store_trade_ticket']['insure_2']|floatval=###},insure_tikcet_2 = {pigcms{$store['store_trade_ticket']['insure_tikcet_2']|floatval=###};
			var insure_3 = {pigcms{$store['store_trade_ticket']['insure_3']|floatval=###},insure_tikcet_3 = {pigcms{$store['store_trade_ticket']['insure_tikcet_3']|floatval=###};
		</script>
    </head>
    <body>
		<div id="widget_container">
			<div class="container">
				<div class="merchants clr">
					<div class="add_img1"><img src="{pigcms{$static_path}images/store_img_{pigcms{$store['store_trade_ticket']['use_scene']}.png"/></div>
					<p>{pigcms{$store.name}</p>
				</div>
				<div class="pay-input clr">
					<div class="clr">
						<p class="gray1" id="moneyTip" style="font-size:18px;">请输入单张票金额</p> 
						<p class="gray" id="moneyBox">
							<span class="gray3">¥</span>
							<span class="gray2" id="money"></span> 
						</p>
					</div>
					<span style="float:right;font-size:18px;color:#333;line-height:48px;">张</span>
					<div style="float:right; width:164px;margin:10px 6px 10px 0;">
						<span style="font-size:18px;color:#333;line-height:27px;">购买张数：</span>
						<div class="spinner"><button class="decrease" disabled="disabled">-</button><input type="text" id="pay_more" class="spinnerExample value" readonly="readonly" maxlength="2" value="{pigcms{$store['store_trade_ticket']['limit_num']}"/><button class="increase">+</button></div> 
					</div>
				</div>
				<div class="pay-input2 clr" style="display:<if condition="$store['store_trade_ticket']['have_insure'] && $config['store_ticket_have_insure']">block<else/>none</if>">
					 <div class="pay-input_l clr">
						<div class="clr">
							<p class="pa">{pigcms{$store['store_trade_ticket']['insure_name']}</p>
							<p class="pa" style="color: #ebb140;padding-top: 1px;">
								<img id="dome_img" class="img1" onclick="opendiv('0')" src="{pigcms{$static_path}images/tan.png"/>
								<span id="k_fy">￥0</span>			               
							</p>
						</div>
						<p class="pc">{pigcms{$store['store_trade_ticket']['insure_name']}<if condition="$store['store_trade_ticket']['insure_mustby']">必须<else/>自愿</if>购买，{pigcms{$store['store_trade_ticket']['insure_1']|floatval=###}元保{pigcms{$store['store_trade_ticket']['insure_money_1']|floatval=###}<if condition="$store['store_trade_ticket']['insure_2']">，{pigcms{$store['store_trade_ticket']['insure_2']|floatval=###}元保{pigcms{$store['store_trade_ticket']['insure_money_2']|floatval=###}</if><if condition="$store['store_trade_ticket']['insure_3']">，{pigcms{$store['store_trade_ticket']['insure_3']|floatval=###}元保{pigcms{$store['store_trade_ticket']['insure_money_3']|floatval=###}</if>。</p>
					</div>
					<if condition="!$store['store_trade_ticket']['insure_mustby']">
						<form class="layui-form pay-input_r" onclick="changefy();">
							<input type="checkbox" name="likes" lay-skin="primary" id="like0" <if condition="$store['store_trade_ticket']['have_insure'] && $config['store_ticket_have_insure']">checked="checked"</if>/>
							<div class="checkboxDom">
							</div>
						</form>
					<else/>
						<form class="layui-form pay-input_r" onclick="changefy();" style="display:none;">
							<input type="checkbox" name="likes" lay-skin="primary" id="like0" checked="checked"/>
							<div class="checkboxDom">
							</div>
						</form>
					</if>
				</div>
				<div class="pay-input4 clr">
					<p class="p1" style="opacity:0;">支付手续费 <span id="g_fy"></span></p>
					<p class="p2">实付金额 <span id="smoney">0</span><span>元</span></p>
				</div>
				<div style="height:20px"></div>
				<button class="button pay_submit" style="width: 94%; display: inline-block;" disabled="disabled">确认支付</button>
			</div>
        </div>
		<form action="" method="POST" autocomplete="off" style="display:none;">
			<input name="store_id" value="{pigcms{$store.store_id}"/>
			<input name="isTicket" value="1"/>
			<input name="ticketPrice" id="ticketPrice"/>
			<input name="pay_num" id="pay_num"/>
			<input name="choose_cinsure" id="choose_cinsure"/>
			<button type="submit" class="submit">确认支付</button>
		</form>
		<div class="popup" style="display:none;">
			<div class="code">
				<pre id="pre_id">{pigcms{$store['store_trade_ticket']['insure_info']}</pre>
			</div>
		 </div>
        {pigcms{$hideScript}
      	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js?11" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/common.js?2112" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_public}number/number.js?1122" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/pay_ticket.js?11" charset="utf-8"></script>	
    </body>
</html>