<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>地图详情</title>
		<meta name="description" content="{pigcms{$config.seo_description}">
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/shop.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	</head>
	<body>
		<div id="container">
			<div id="scroller">
				<div id="address">
					<dl class="list">
						<dd id="biz-map" style="height:100%;"></dd>
						<dd id="see_load">
							{pigcms{$now_store.area_name}{pigcms{$now_store.adress}
                            <if condition="$is_app_browser && $app_browser_type eq 'android' && $app_version EGT 25">
                                    <a class="btn right" id="luxian">查看路线</a>
                                    <input type="hidden" id="long" value="{pigcms{$now_store.long}"/>
                                    <input type="hidden" id="lat" value="{pigcms{$now_store.lat}"/>
                                    <input type="hidden" id="store_name" value="{pigcms{$now_store.name}"/>
                            <else/>
							    <a class="btn right goHere" href="{pigcms{:U('Group/get_route',array('store_id'=>$now_store['store_id']))}">查看路线</a>
						    </if>
                        </dd>
					</dl>
				</div>
				<php>$no_footer = true;</php>
				<include file="Public:footer"/>
				<script type="text/javascript">$('#biz-map').height($(window).height()-60);</script>
                <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
                    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
                    <script type="text/javascript">
                        lng = parseFloat({pigcms{$now_store.long});
                        lat = parseFloat({pigcms{$now_store.lat});
                        shopName = "{pigcms{$now_store.name}";
                        map = new google.maps.Map(document.getElementById('biz-map'), {
                            mapTypeControl:false,
                            zoom: 16,
                            center: {lng,lat}
                        });

                        var marker = new google.maps.Marker({
                            position: {lng,lat},
                            map: map,
                            draggable:false
                        });
                        attachSecretMessage(marker, decodeURIComponent(shopName));

                        function attachSecretMessage(marker, secretMessage) {
                            var infowindow = new google.maps.InfoWindow({
                                content: secretMessage
                            });
                            infowindow.open(marker.get('map'), marker);
                            marker.addListener('click', function() {
                                infowindow.open(marker.get('map'), marker);
                            });
                        }
                    </script>
                    <else />
				<script src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1"></script>
				<script type="text/javascript">
					$(function(){
						// 百度地图API功能
						var map = new BMap.Map("biz-map",{enableMapClick:false});
						map.centerAndZoom(new BMap.Point({pigcms{$now_store.long},{pigcms{$now_store.lat}), 16);

						var marker1 = new BMap.Marker(new BMap.Point({pigcms{$now_store.long},{pigcms{$now_store.lat}));  //创建标注
						map.addOverlay(marker1);                 // 将标注添加到地图中
						//创建信息窗口
						var infoWindow1 = new BMap.InfoWindow("{pigcms{$now_store.name}");
						marker1.openInfoWindow(infoWindow1);
						marker1.addEventListener("click", function(){this.openInfoWindow(infoWindow1);});


						 $('#luxian').click(function(){
							var long    =   $('#long').attr('value');
							var lat    =   $('#lat').attr('value');
							var store_name    =   $('#store_name').attr('value');
							window.lifepasslogin.startToNavigation(long,lat,store_name);
						});
						if(typeof(wxSdkLoad) != "undefined"){
							$('.goHere').hide();
							$.getJSON("{pigcms{:U('Userlonglat/baiduToGcj02')}&baidu_lat={pigcms{$now_store.lat}&baidu_lng={pigcms{$now_store.long}",function(result){
								// alert("baidu_lat={pigcms{$now_store.lat}&baidu_lng={pigcms{$now_store.long}&&&"+JSON.stringify(result));
								$('.goHere').show();
								if(result['status'] == 1){
									$('.goHere').click(function(){
										wx.ready(function (){
											wx.openLocation({
												latitude: result['info']['lat'],
												longitude: result['info']['lng'],
												name: '{pigcms{$now_store.name}', // 位置名
												address: '{pigcms{$now_store.area_name}{pigcms{$now_store.adress}', // 地址详情说明
												scale: 18, // 地图缩放级别,整形值,范围从1~28。默认为最大
												infoUrl: window.location.href // 在查看位置界面底部显示的超链接,可点击跳转
											});
										});
										return false;
									});
									$('.goHere').trigger('click');
								}
							})
						}
					});
				</script>
                </if>
			</div>
		</div>
		<script type="text/javascript">
		window.shareData = {
					"moduleName":"Group",
					"moduleID":"0",
					"imgUrl": "{pigcms{$now_store.all_pic.0}",
					"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Group/addressinfo', array('store_id' => $now_store['store_id']))}",
					"tTitle": "{pigcms{$now_store.name}|店铺地址",
					"tContent": ""
		};
		</script>
		{pigcms{$shareScript}
	</body>
</html>