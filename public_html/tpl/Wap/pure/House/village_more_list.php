<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>更多</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/new_village.css" />
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<style type="text/css">
			header{background:#06C1AE;}
		</style>
	</head>
	<body style="zoom: 1; min-height: 520px; overflow-y: auto;">
		<div class="cat-top"></div> 
		<div class="cat-content">
			<ul>
				<li onclick="location.href='{pigcms{:U('village_manager_list',array('village_id'=>$_GET['village_id']))}'">
					<p><img src="{pigcms{$static_path}img/house_index_2.png" />小区管家</p>
				</li>
				
				<li onclick="location.href='{pigcms{:U('House/village_my_pay',array('village_id'=>$_GET['village_id']))}'">
					<p><img src="{pigcms{$static_path}images/house_index_5.png" />生活缴费</p>
				</li>
				<li onclick="location.href='{pigcms{:U('Ride/ride_list',array('village_id'=>$_GET['village_id']))}'">
					<p><img src="{pigcms{$static_path}images/house_index_4.png" />社区用车</p>
				</li>
				<li onclick="location.href='{pigcms{:U('Library/express_service_list',array('village_id'=>$_GET['village_id']))}'">
					<p><img src="{pigcms{$static_path}images/house_index_1.png" />快递代收</p>
				</li>
				<li onclick="location.href='{pigcms{:U('House/village_activitylist',array('village_id'=>$_GET['village_id']))}'">
					<p><img src="{pigcms{$static_path}img/house_index_3.png" />社区活动</p>
				</li>
				<li onclick="location.href='{pigcms{:U('House/village_grouplist',array('village_id'=>$_GET['village_id']))}'">
					<p><img src="{pigcms{$static_path}img/house_index_7.png" /><if condition="$houseConfig['shop_alias_name']">{pigcms{$houseConfig.group_alias_name}<else />周边团购</if></p>
				</li>
				<li onclick="location.href='{pigcms{:U('Shop/index#cat-all')}'">
					<p><img src="{pigcms{$static_path}img/house_index_6.png" /><if condition="$houseConfig['shop_alias_name']">{pigcms{$houseConfig.shop_alias_name}<else />周边快店</if></p>
				</li>
				<li onclick="location.href='{pigcms{:U('House/village_appointlist',array('village_id'=>$_GET['village_id']))}'">
					<p><img src="{pigcms{$static_path}img/house_index_9.png" /><if condition="$houseConfig['appoint_alias_name']">{pigcms{$houseConfig.appoint_alias_name}<else />周边预约</if></p>
				</li>
				
				<if condition='$more_service_cat_list'>
					<volist name="more_service_cat_list" id='cat'>
						<li onclick="location.href='{pigcms{$cat.cat_url}'">
							<p><img src="{pigcms{$cat.cat_img}" />{pigcms{$cat.cat_name}</p>
						</li>
					</volist>
				</if>
			</ul>
			<div class="clear" style="clear:both"></div>
		</div>
        <include file="House:footer"/>
		{pigcms{$shareScript}
		</footer>
	</body>

</html>