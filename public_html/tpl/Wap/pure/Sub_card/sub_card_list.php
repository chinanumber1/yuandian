<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>我的免单订单列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}sub_card/css/package_center.css?21511"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}sub_card/js/sub_card.js"></script>
	
	</head>
	<body>
	<if condition="is_array($sub_card_list)">
	<div class="center">
	
		<volist name="sub_card_list" id="vo">
		<div class="car_center link-url" data-url="<if condition="$vo.share_uid gt 0">{pigcms{:U('Sub_card/share_order_detail',array('order_id'=>$vo['order_id'],'share_uid'=>$vo['share_uid']))}<else />{pigcms{:U('Sub_card/order_detail',array('order_id'=>$vo['order_id']))}</if>">	
			<div class="buy_date after">
			
				<h4>{pigcms{$vo.name} <if condition="$vo.share_uid gt 0 AND $vo['share_uid'] eq $user_session['uid']"><a class="rg" href="javascript:;">来自分享</a></if></h4>
				<p><if condition="$vo.share_uid gt 0">获得时间<else />购买时间</if>: {pigcms{$vo.pay_time|date="Y/m/d H:i:s",###}</p>
			</div>
			
			<p class="frequency after">
				<span class="ft"><b>￥{pigcms{$vo.price|floatval}</b><span>/<if condition="$vo.share_uid gt 0">{pigcms{$vo.store_num}<else />{pigcms{$vo.free_total_num}</if>次</span></span>
				<a class="rg" href="javascript:;">共含{pigcms{$vo.store_num}个店铺</a>
				
			</p>
			<div class="car_footer after">
				<span  class="ft">共<if condition="$vo.share_uid gt 0">{pigcms{$vo.store_num}<else />{pigcms{$vo.free_total_num}</if>次, 已使用{pigcms{$vo.use_count}次</span>
				<p  class="rg"><i></i><span> <if condition="$vo.use_time_type eq 0">永久有效<elseif condition="$vo.effective_days gt 0" />{pigcms{$vo.effective_days}天后到期<elseif condition="$vo.effective_days eq 0" />今天是最后期限<else />已过期</if></span></p>
			</div>
		</div>
		</volist>
		
	</div>
	<else />
	<div class="tishi" style="text-align: center;position: fixed; width: 100%;top: 50%;margin-top: -10px;color: #b9b4b4;">您还没有订单哦,赶紧去下单吧。</div>
	</if>
	<script>
		$('.link-url').bind('click',function(){
			window.location.href=$(this).data('url')
		})
	</script>
		{pigcms{$hideScript}
</body>

</html>