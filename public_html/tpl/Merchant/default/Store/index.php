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
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffIndex.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffIndex.css"/>
		<script type="text/javascript">var countArr = [], titleArr = [];</script>
	</head>
	<body>
		<div class="infoBox">
			<div class="logo">
				<img src="{pigcms{$config.site_merchant_logo}"/>
			</div>
			<div class="text">
				{pigcms{$staff_session.name}<if condition="$staff_session['type_name']">({pigcms{$staff_session['type_name']})</if>
				<br/>
				<span>{pigcms{$store.name}</span>
			</div>
		</div>
		<div class="pageBg"></div>
		<div class="pageLink">
			<ul>
				<if condition="$store['have_group']">
					<li class="group" data-url="{pigcms{$config.site_url}{pigcms{:U('group_list')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.group_alias_name}</div>
						<div class="loader hide" id="group_loader"><em></em></div>
					</li>
					<script type="text/javascript">countArr.push('group'), titleArr.push('{pigcms{$config.group_alias_name}');</script>
				</if>
				<if condition="$store['have_meal']">
					<li class="meal" data-url="{pigcms{$config.site_url}{pigcms{:U('foodshop')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.meal_alias_name}</div>
						<div class="loader hide" id="foodshop_loader"><em></em></div>
					</li>
					<script type="text/javascript">countArr.push('foodshop'), titleArr.push('{pigcms{$config.meal_alias_name}');</script>
				</if>
				<if condition="$store['have_shop']">
					<li class="shop" data-url="{pigcms{$config.site_url}{pigcms{:U('shop_list')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.shop_alias_name}</div>
						<div class="loader hide" id="shop_loader"><em></em></div>
					</li>
					<script type="text/javascript">countArr.push('shop'), titleArr.push('{pigcms{$config.shop_alias_name}');</script>
				</if>
				<if condition="$config['appoint_page_row']">
					<li class="appoint" data-url="{pigcms{$config.site_url}{pigcms{:U('appoint_list')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.appoint_alias_name}</div>
						<div class="loader hide" id="appoint_loader"><em></em></div>
					</li>
					<script type="text/javascript">countArr.push('appoint'), titleArr.push('{pigcms{$config.appoint_alias_name}');</script>
				</if>
				<if condition="$config['is_cashier'] OR $config['pay_in_store']">
					<li class="store" data-url="{pigcms{$config.site_url}{pigcms{:U('store_order')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.cash_alias_name}</div>
						<div class="loader hide" id="cash_loader"><em></em></div>
					</li>
					<li class="arrival" data-url="{pigcms{$config.site_url}{pigcms{:U('store_arrival')}">
						<div class="icon"></div>
						<div class="text">店内收银</div>
						<div class="loader hide" id="store_loader"><em></em></div>
					</li>
					<script type="text/javascript">countArr.push('cash'), titleArr.push('{pigcms{$config.cash_alias_name}');</script>
				</if>
				<li class="coupon" data-url="{pigcms{$config.site_url}{pigcms{:U('coupon_list')}">
					<div class="icon"></div>
					<div class="text">优惠券</div>
				</li>
				<if condition="$store['have_meal'] OR $config['is_cashier'] OR $config['pay_in_store']">
					<li class="report" data-url="{pigcms{$config.site_url}{pigcms{:U('report')}">
						<div class="icon"></div>
						<div class="text">报表统计</div>
					</li>
				</if>
				<li class="physical_card" data-url="{pigcms{$config.site_url}{pigcms{:U('physical_card')}">
					<div class="icon"></div>
					<div class="text">实体卡管理</div>
				</li>
				<if condition="$staff_session['type'] eq 2">
					<li class="store_manage" data-url="{pigcms{:U('Config/store', '', true, false, true)}">
						<div class="icon"></div>
						<div class="text">店铺管理</div>
					</li>
				</if>
				<if condition="$store['store_wx_sub_mchid'] neq '' OR $store['store_alipay_sub_mchid'] neq ''">
					<li class="store_manage" data-url="{pigcms{:U('money_list', '', true, false, true)}">
						<div class="icon"></div>
						<div class="text">店铺余额</div>
					</li>
				</if>
				
				<if condition="$config['open_sub_card'] eq 1">
					<li class="sub_card" data-url="{pigcms{:U('sub_card', '', true, false, true)}">
						<div class="icon"></div>
						<div class="text">免单套餐</div>
					</li>
				</if>
				
				<li class="printer" style="display:none;">
					<div class="icon"></div>
					<div class="text">打印机参数</div>
				</li>
				<li class="logout" data-confirm="您确定要退出吗？" data-url="{pigcms{$config.site_url}{pigcms{:U('logout')}">
					<div class="icon"></div>
					<div class="text">退出</div>
				</li>
			</ul>
		</div>
	</body>
	<script type="text/javascript">
		$('body').append('<audio style="display:none;" id="playMp3Tip" controls="true" loop="loop" src="{pigcms{$static_public}file/new_order.mp3"></audio>');
		var nowIndex = 0;
		var timeArr = {};
		var playMp3Tip = null;
		var newOrderTip = null;
		setInterval(function(){
			var nowType = countArr[nowIndex];
			$('.loader').removeClass('on');
			$('#'+nowType+'_loader').addClass('on').show();
			$.post('/store.php?g=Merchant&c=Store&a=' + nowType + '_count', {time:timeArr[nowType]}, function(response){
				var data = response.data;
				timeArr[nowType] = data.time;
				$('#'+nowType+'_loader em').html(data.count);
				$('.loader').removeClass('on');
				
				if(data.count > 0){
					if(newOrderTip != null){
						$('#playMp3Tip').trigger('pause');
						layer.close(newOrderTip);
					}
					
					if(playMp3Tip == null){
						$('#playMp3Tip').trigger('play');
					}
					
					/*音乐播放5分钟*/
					playMp3Tip = setTimeout(function(){
						$('#playMp3Tip').trigger('pause');
					}, 300000);
					newOrderTip = layer.open({
						title:'新订单提示'
						,content:'您有新的' + titleArr[nowIndex] + '订单需要处理。'
						,btn: ['确定']
						,end: function(index){
							$('#playMp3Tip').trigger('pause');
							clearTimeout(playMp3Tip);
							playMp3Tip = null;
							newOrderTip = null;
						}
					});
				}
				if(nowIndex+1 == countArr.length){
					nowIndex = 0;
				}else{
					nowIndex++;
				}
			}, 'json');
		},5000);
		
	</script>
</html>