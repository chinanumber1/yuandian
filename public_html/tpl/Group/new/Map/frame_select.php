<!doctype html>
<html>
	<head>
		<script src="http://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
		<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
		<link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />
		<style>body,html{margin:0;padding:0;width:100%;height:100%;overflow:hidden;font-family:'微软雅黑';font-size:14px;}.BMapLib_SearchInfoWindow{font-size:14px;font-family:'微软雅黑'}#container{height:500px;margin:15px 15px 0;width:770px;}.declaration{color:#999;font-size:12px;text-align:right;margin-right:20px;}.BMap_cpyCtrl{display:none;}.BMapLib_bubble_content{height:85px!important;}.BMapLib_trans{top:123px!important;}</style>
	</head>
	<body>
		<div id="cmmap" style="overflow:hidden;zoom:1;position:relative;">
			<div id="container"></div>
		</div>
		<div id="notice" style="position:absolute;top:5px;left:45%;background-color:green;padding:10px 20px;color:white;display:none;">您的坐标设置成功</div>
		<script type="text/javascript">
			var marker=null;
			var map = new BMap.Map('container',{"enableMapClick":false});
			var setPoint = function(mk,b){
				var pt = mk.getPosition();
				(new BMap.Geocoder()).getLocation(pt,function(rs){
					addComp = rs.addressComponents;
					if (b===true){
						window.parent.setPoint('{pigcms{$_GET.randNum}',pt.lng,pt.lat,addComp.province + " " + (addComp.city == addComp.province ? '' :addComp.city + " ") + addComp.district + " " + addComp.street + " " + addComp.streetNumber);
					}
				});
			}
			//添加缩放条
			map.addControl(new BMap.NavigationControl());
			//启用滚轮放大缩小
			map.enableScrollWheelZoom();
			
			//定位
			<if condition="$_GET['map_point']">
				var point = new BMap.Point({pigcms{$_GET['map_point']});
				map.centerAndZoom(point,18);
				marker = new BMap.Marker(point);
			<else/>
				marker = new BMap.Marker(new BMap.Point(116.331398,39.897445));
				function myFun(result){
					oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
					map.centerAndZoom(oPoint,12);
					marker.setPosition(oPoint);
				}
				var myCity = new BMap.LocalCity();
				myCity.get(myFun);
			</if>
			marker.enableDragging();			
			map.addOverlay(marker);
			marker.addEventListener("dragend", function(){
				setPoint(marker,true);
				notice();
			});
			marker.addEventListener("click", function(e){	
				setPoint(marker,true);
				notice();
			});
			var timer = null;
			function notice(){
				clearTimeout(timer);
				document.getElementById('notice').style.display = 'block';
				timer = setTimeout(function(){
					document.getElementById('notice').style.display = 'none';
				},3000);
			}
		</script>
	</body>
</html>