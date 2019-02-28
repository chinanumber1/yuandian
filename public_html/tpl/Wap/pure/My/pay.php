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
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/pay.css?2151"/>
		<script>
			var mer_id = '{pigcms{$_GET['mer_id']}';
			if(mer_id){
				var can_change_store = true;
			}else{
				var can_change_store = false;
			}
			var default_money = {pigcms{$_GET['money']|default=0};;
			var vip_discount_type  = {pigcms{$store.vip_discount_type|default=0};
			var level_discount_type  = {pigcms{$level_off['type']|intval};
			var discount_v  = {pigcms{$level_off['vv']/10|floatval};
		</script>
    </head>
    <body>
		<div id="widget_container">
			<form action="" method="POST" autocomplete="off">
				<section class="payment">
					<div class="pay_top">
						<i>付款给</i>
						<span id="store_id" data-store_id="{pigcms{$store.store_id}"><if condition="$store">{pigcms{$store.name}<else />请手动选择门店</if></span>
						<if condition="$_GET['mer_id']"><em>更换</em></if>
					</div>
					<input type="hidden" name="store_id" value="<if condition="$store">{pigcms{$store.store_id}</if>"/>
					<input type="hidden" name="total_money" class="total_money"/>
					<input type="hidden" name="no_discount_money" class="no_discount_money"/>
					<input type="hidden" name="store_id" value="<if condition="$store">{pigcms{$store.store_id}</if>"/>
					<div class="pay_con">
						<div class="li clr total_money">
							<div class="fl">消费总额：</div>
							<div class="fr">
								<div id="totalNumber" style="width:198px;text-align:right;height:20px;"></div>
							</div>
						</div>
						<div class="cik discount_div <if condition="empty($store) OR  $store.discount_type eq 0">hide</if>">输入不参与优惠金额</div>
						<div class="li li_s clr no_discount_money">
							<div class="fl">不可优惠金额：</div>
							<div class="fr">
								<div id="noDiscountNumber" style="width:168px;text-align:right;height:20px;"></div>
							</div>
						</div>
					</div>
					
					<div class="lists">
						<ul>
								<li class="clr discount_div <if condition="empty($store) OR  $store.discount_type eq 0">hide</if>" >

								<div class="fl man clr <if condition="$store.discount_type eq 1 OR $store.discount_type eq 0">hide</if>">
									<i class="fl">满</i>
									<span class="fl">每满<em class="master">{pigcms{$store.condition_price}</em>元减<em class="reduce">{pigcms{$store.minus_price}</em>元</span>
								</div>

								<div class="fl zhe clr <if condition="$store['discount_type'] eq 2 OR $store['discount_type'] eq 0">hide</if>">
									<i class="fl">折</i>
									<span class="fl"><em class="frac">{pigcms{$store.discount_percent}</em>折</span>
								</div>
								<span>&nbsp;&nbsp;&nbsp;{pigcms{$config.cash_alias_name}优惠</span>
								<div class="fr price hide">-<em class="che">0</em></div>
							</li>
							
							<php>if(!empty($level_off)){</php>
							<li class="clr discount_div " >
							
								<div class="fl man clr ">
									<i class="fl"><if condition="$level_off.type eq 2 ">减<elseif condition="$level_off['type'] eq 1 " />折</if></i>
									<span class="fl"><em class="reduce_level" data-type="{pigcms{$level_off.type}"><if condition="$level_off.type eq 2 ">{pigcms{$level_off['vv']}<elseif condition="$level_off['type'] eq 1 " />{pigcms{$level_off['vv']/10|floatval}</if></em><if condition="$level_off.type eq 2 ">元<elseif condition="$level_off['type'] eq 1 " />折</if></span>
								</div>
					
						
								<span>&nbsp;&nbsp;&nbsp;vip 优惠</span>
								<div class="fr price hide">-<em class="che_1">0</em></div>
							</li>
							<php>}else if(empty($_SESSION['user']['phone'])){</php>
								
								<li class="clr discount_div " id="bind_user" data-href="{pigcms{:U('My/bind_user')}&referer={pigcms{:urlencode(U('My/pay',array('store_id'=>$_GET['store_id'])))}" >
							
								<div class="fl man clr ">
									<i class="fl">惠</i>
									<span class="fl"><em class="reduce_level" data-type="{pigcms{$level_off.type}"></em></span>
								</div>
								<span style="color:red" >您还没有绑定手机号，请绑定后查看优惠</span>
								<div class="fr price hide"></div>
							</li>
							<php>}</php>
						
							
					
							<li class="clr fan <if condition="$store.vip_discount_type eq 0 OR ($store.vip_discount_type eq 2 AND $level_off['type'] eq 1 AND $level_off['vv']/10 gt $store['discount_percent'] )">hide</if>">
								<div class="fl clr">
									<i class="fl">规</i>
									<span >
									<if condition="$store.vip_discount_type eq 2">折上折。折上折的意思是如果这个用户是有平台VIP等级，平台VIP等级有折扣优惠。那么这个用户的优惠计算方式是先用店铺的优惠进行打折后，再用VIP折扣进去打折；<elseif condition="$store.vip_discount_type eq 1" />折扣最优。折扣最优是指：购买产品的总价用店铺优惠打折后的价格与总价跟VIP优惠打折后的价格进行比较，取最小值的优惠方式。</if></span>
								</div>
							</li>
							<if condition="$config.user_store_score_get gt 0">
							<li class="clr fan <if condition="$config.score_get eq 0">hide</if>">
								<div class="fl clr">
									<i class="fl">返</i>
									<span class="fl">支付此单可返支付金额一定比例的{pigcms{$config.score_name}数</span>
								</div>
							</li>
							</if>
						</ul>
					</div>
					<button class="submit" style="width: 94%;">确认支付 <i class="hide">￥<em class="surplus">0</em></i></button>
					<div class="choice">
						<div class="h2">请选择所在的门店</div>
						<div class="iscroll">
							<ul>
								<volist name="store_list" id="vo">
									<li>
										<h2 data-store_id="{pigcms{$vo.store_id}" data-discount_type="{pigcms{$vo.discount_type}" data-discount_percent="{pigcms{$vo.discount_percent}" data-condition_price="{pigcms{$vo.condition_price}" data-minus_price="{pigcms{$vo.minus_price}">{pigcms{$vo.name}</h2>
										<p>{pigcms{$vo.area_ip_desc} {pigcms{$vo.adress}</p>
										<if condition="$vo['range']">
											<div class="distance">距离您的位置 {pigcms{$vo.range}</div>
										</if>
									</li>
								</volist>
							</ul>
						</div>
						<div class="close">关闭</div>
					</div>
					<div class="mask"></div>
				</section>
			</form>
        </div>
		<div class="actual_pay_box" style="position:fixed;left:0;width:100%;text-align:right;height:46px;line-height:46px;background:white;display:none;"><span style="margin-right:10px;">实付金额：￥<span class="actual_pay_span" style="font-weight:bold;margin-left:2px;font-size:18px;">0</span></span></div>
        {pigcms{$hideScript}
      	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js?11" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/common.js?2112" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_public}number/number.js?1122" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/pay.js?14" charset="utf-8"></script>	
    </body>
</html>