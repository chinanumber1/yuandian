<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>商家地图</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/merchant_map.js?210" charset="utf-8"></script>
		<style>
			#introBox{padding:0 12px;background-color:white;height:79px;border-top:1px solid #f1f1f1;}
			#introBox .title{font-size:18px;height:40px;line-height:40px;}
			#introBox .title .right{font-size:12px;float:right;}
			.anchorBL{height:20px!important;}
			.anchorBL a img{height:12px!important;width:34px!important}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="scroller">
				<div id="around-map"></div>
				<div id="introBox">
					<div class="title">{pigcms{$now_group.store_list.0.name}<if condition="$now_group['store_list'][0]['range']"><div class="right">{pigcms{$now_group['store_list'][0]['range']}</div></if></div>
					<div class="desc">{pigcms{$now_group.store_list.0.area_name}{pigcms{$now_group.store_list.0.adress}</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$('#around-map').height($(window).height()-80);
			var _ary = [<volist name="now_group['store_list']" id="vo">[{pigcms{$vo['long']},{pigcms{$vo['lat']},"{pigcms{$vo['name']}","{pigcms{$vo['store_id']}","{pigcms{$vo['phone']}","{pigcms{$vo.area_name}{pigcms{$vo.adress}","<if condition="$vo['range']">{pigcms{$vo['range']}</if>"],</volist>];
		</script>
        <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
            <script type="text/javascript">
               var map = new google.maps.Map(document.getElementById('around-map'), {
                    mapTypeControl:false,
                    zoom: 11,
                    center: {lng:parseFloat(_ary[0][0]),lat:parseFloat(_ary[0][1])}
                });
               var store_point =[];
               var result = getSearchListPoints();
               $.each(result,function(i,item){
                   if(i == 0){
                       var marker = new google.maps.Marker({
                           position: item.point,
                           map: map,
                           icon: "{pigcms{$static_path}images/blue_marker.png"
                       });
                   }else{
                       var marker = new google.maps.Marker({
                           position: item.point,
                           map: map,
                           icon: "{pigcms{$static_path}images/red_marker.png"
                       });
                   }
                   store_point[i] = marker;
                   var tmpHtml = '<div class="title">'+item.title+(item.range != -1 ? '<div class="right">'+item.range+'</div>' : '')+'</div><div class="desc">'+item.adress+'</div>';
                   marker.addListener("click", function(){
                       $.each(result,function(k,ktem){
                           if(i == k){
                               store_point[k].setIcon("{pigcms{$static_path}images/blue_marker.png");
                           }else{
                               store_point[k].setIcon("{pigcms{$static_path}images/red_marker.png");
                           }
                       });

                       $('#introBox').html(tmpHtml);
                   });
               });

               function getSearchListPoints(){
                   var $$=[],b;
                   for(b in _ary){
                       8!=_ary[b][0] && $$.push({
                           i:b,
                           point:{lng:_ary[b][0],lat:_ary[b][1]},
                           title:_ary[b][2],
                           id:_ary[b][3],
                           phone:_ary[b][4],
                           adress:_ary[b][5],
                           range:(_ary[b][6] ? _ary[b][6] : -1)
                       });
                   }
                   return $$;
               }
            </script>
            <else />
		<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
		<script type="text/javascript">	
			// 百度地图API功能
			var map = new BMap.Map("around-map",{enableMapClick:false});            // 创建Map实例
			map.centerAndZoom(new BMap.Point(_ary[0][0],_ary[0][1]),11);                 // 初始化地图,设置中心点坐标和地图级别。
			var store_point =[];
			var result = getSearchListPoints();
			$.each(result,function(i,item){
				if(i == 0){
					var marker = new BMap.Marker(item.point,{icon:new BMap.Icon("{pigcms{$static_path}images/blue_marker.png", new BMap.Size(24,25))});
				}else{
					var marker = new BMap.Marker(item.point,{icon:new BMap.Icon("{pigcms{$static_path}images/red_marker.png", new BMap.Size(24,25))});
				}
				store_point[i] = marker;
				map.addOverlay(marker);
				var tmpHtml = '<div class="title">'+item.title+(item.range != -1 ? '<div class="right">'+item.range+'</div>' : '')+'</div><div class="desc">'+item.adress+'</div>';
				marker.addEventListener("click", function(){
					$.each(result,function(k,ktem){
						if(i == k){
							store_point[k].setIcon(new BMap.Icon("{pigcms{$static_path}images/blue_marker.png", new BMap.Size(24,25)));
						}else{
							store_point[k].setIcon(new BMap.Icon("{pigcms{$static_path}images/red_marker.png", new BMap.Size(24,25)));
						}
					});
					
					$('#introBox').html(tmpHtml);
				});
			});
			function getSearchListPoints(){
				var $$=[],b;
				for(b in _ary){
					8!=_ary[b][0] && $$.push({
						i:b,
						point:new BMap.Point(_ary[b][0],_ary[b][1]),
						title:_ary[b][2],
						id:_ary[b][3],
						phone:_ary[b][4],
						adress:_ary[b][5],
						range:(_ary[b][6] ? _ary[b][6] : -1)
					});
				}
				return $$;
			}
		</script>
        </if>
	</body>
</html>