<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>配送首页</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<body>
	<section class="clerk">
		<div class="clerk_top">
			<div class="fl clerk_img">
				<if condition="$deliver_session['store_id']">
                <span style="background: url({pigcms{$store['image']}) center no-repeat; background-size: contain;"></span>
                <else />
                <span style="background: url(<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>) center no-repeat; background-size: contain;"></span>
                </if>
			</div>
			<div class="clerk_r">
				<h2>{pigcms{$deliver_session['name']}<i> , 您好</i></h2>
				<p><if condition="$deliver_session['store_id']">商家配送员  {pigcms{$store['name']}店铺<else />系统配送员</if></p>
			</div>
		</div>
		<div class="clerk_end">
			<ul class="clr">
				<li class="Grab fl">
					<a href="{pigcms{:U('Deliver/grab')}">
						<i></i>
						<h2 id="gray_count">{pigcms{$gray_count}</h2>
						<p>待抢单</p> 
					</a>
				</li>
				<li class="Handle fl">
					<a href="{pigcms{:U('Deliver/pick')}">
						<i></i>
						<h2 id="deliver_count">{pigcms{$deliver_count}</h2>
						<p>处理中</p> 
					</a>
				</li>
				<li class="complete fl">
					<a href="{pigcms{:U('Deliver/finish')}">
						<i></i>
						<h2 id="finish_count">{pigcms{$finish_count}</h2>
						<p>已完成</p> 
					</a>
				</li>
			</ul>
		</div>
	</section>
	<section class="Map" id="biz-map">
	</section>
	<section class="bottom">
		<div class="bottom_n">
			<ul>
				<li class="Statistics fl">
                    <a href="{pigcms{:U('Deliver/tongji')}">统计</a>
				</li>
				<li class="home homeon fl">
					<a href="javascript:void(0);"><i></i>首页</a>
				</li>
				<li class="My fl">
					<a href="{pigcms{:U('Deliver/info')}">我的</a>
				</li>
			</ul>
		</div>
	</section>
	<script type="text/javascript">$('#biz-map').height($(window).height()-267);</script>
	<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/report_location.js" charset="utf-8"></script>
	<script type="text/javascript">
	$(function(){
				var map = new BMap.Map("biz-map");
				var point = new BMap.Point({pigcms{$deliver_session['lng']}, {pigcms{$deliver_session['lat']});
				map.centerAndZoom(point, 16);

				var geolocation = new BMap.Geolocation();
				geolocation.getCurrentPosition(function(r){
					if(this.getStatus() == BMAP_STATUS_SUCCESS){
						map.panTo(r.point);
						var mk = new BMap.Marker(r.point);
						map.addOverlay(mk);
// 						mk.setAnimation(BMAP_ANIMATION_BOUNCE); 
// 						alert('您的位置：'+r.point.lng+','+r.point.lat);
					}
					else {
						alert('failed'+this.getStatus());
					}        
				},{enableHighAccuracy: true})

				setInterval(function(){
					$.get("{pigcms{:U('Deliver/index_count')}", function(response){
						if (response.err_code == false) {
							$('#gray_count').html(response.gray_count);
							$('#deliver_count').html(response.deliver_count);
							$('#finish_count').html(response.finish_count);
						}
					}, 'json');
				}, 10000);
	});			
// 		$(function(){
// 			var map = new BMap.Map("biz-map");
// 			map.centerAndZoom(new BMap.Point({pigcms{$deliver_session['lng']}, {pigcms{$deliver_session['lat']}), 16);
			
// 			$.getScript("http://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2",function(){
// 				var geolocation = new BMap.Geolocation();
// 				geolocation.getCurrentPosition(function(r){
// 					if(this.getStatus() == BMAP_STATUS_SUCCESS){
// 						var mk = new BMap.Marker(r.point);
// 						map.addOverlay(mk);
// 						map.panTo(r.point);
// 					} else {
// 						alert('failed'+this.getStatus());
// 					}        
// 				},{enableHighAccuracy: true})
// 			});
// 		});
	</script>
</body>
</html>