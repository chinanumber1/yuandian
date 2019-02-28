<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>附近酒店</title>
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
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?321" charset="utf-8"></script>
		<script type="text/javascript"><if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>var static_path = "{pigcms{$static_path}";
		var city_name="{pigcms{$city_name}"
		</script>
        <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
            <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}";var static_path = "{pigcms{$static_path}";</script>
            <else />
		<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1"></script>
        </if>
		<script type="text/javascript" src="{pigcms{$static_path}hotel/hotel_around.js?a=222232" charset="utf-8"></script>
		<style>
			#listBtn{background:url({pigcms{$static_path}img/listBtn.png) no-repeat;background-size:100%;width:35px;height:35px;right:8px;bottom:15px;position:absolute;z-index:10;}
			#listBg{position:fixed;top:0;left:0;bottom:0;padding:0;z-index:998;width:100%;background-color:rgba(0,0,0,0.5);display:none;}
			#listList{position:fixed;top:10%;left:10%;bottom:10%;right:10%;z-index:999;background-color:white;border-radius:5px;overflow:hidden;display:none;}
			#listList dl{background-color:#F3F3F3;}
			#listList dd{border-bottom:1px solid #D6D6D6;padding:6px 12px;}
			#listList dd:last-child{border-bottom:none;}
			.reply{
				color:#06c1ae;
			}
			.hotel_name{
				font-size:16px;
				color:#000;
				margin-bottom:10px;
			}
			.windowBox:after{
				position: absolute;
				top: 36px;
				right: 3px;
				content: '';
				display: inline-block;
				width: 10px;
				height: 10px;
				border-top: 1px solid #656565;
				border-right: 1px solid #656565;
				transform: rotate(45deg);
				-webkit-transform: rotate(45deg);
			}
		</style>
	</head>
	<body>
		<div id="container">
			<div id="scroller">
				<div id="around-map"></div>
			</div>
		</div>
		<div id="listBtn"></div>
		<div id="listBg"></div>
		<div id="listList">
			<div>
				<dl></dl>
			</div>
		</div>
        <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
            <else/>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
			
		
			
			function SquareOverlay(center, length, text,sort){  
			 this._center = center;  
			 this._length = length;  
			 this._text = text;  
			 this._sort = sort;  
			}  
			// 继承API的BMap.Overlay  
			SquareOverlay.prototype = new BMap.Overlay(); 

			//2、初始化自定义覆盖物
			// 实现初始化方法  
			SquareOverlay.prototype.initialize = function(map){  
			// 保存map对象实例  
			 this._map = map;      
			
			 
			 
			  var div = this._div = document.createElement("div");
			  div.style.position = "absolute";
			  div.style.zIndex = BMap.Overlay.getZIndex(this._center.lat);
		      div.style.backgroundColor = "#6caeca";
			  div.style.border = "1px solid #5993af";
			  div.style.color = "white";
			  div.style.height = "18px";
			  div.style.padding = "2px";
			  div.style.lineHeight = "18px";
			  div.style.whiteSpace = "nowrap";
			  div.style.MozUserSelect = "none";
			  div.style.fontSize = "12px";
			  

			  var span = this._span = document.createElement("span");
			  $(span).addClass('hotel_price')
			  $(span).addClass('hotel_price_'+this._sort)
			  div.appendChild(span);
			  span.appendChild(document.createTextNode(this._text));      
			  var that = this;

			  var arrow = this._arrow = document.createElement("div");
			  arrow.style.background = "url(http://map.baidu.com/fwmap/upload/r/map/fwmap/static/house/images/label.png) no-repeat  0px -20px";
			  arrow.style.position = "absolute";
			  arrow.style.width = "11px";
			  arrow.style.height = "10px";
			  arrow.style.top = "22px";
			  arrow.style.left = "10px";
			  arrow.style.overflow = "hidden";
			  div.appendChild(arrow);
			 // 将div添加到覆盖物容器中  
			 map.getPanes().markerPane.appendChild(div);    
			
			 // 保存div实例  
			 this._div = div;    
			 // 需要将div元素作为方法的返回值，当调用该覆盖物的show、  
			 // hide方法，或者对覆盖物进行移除时，API都将操作此元素。  
			 return div;  
			}

			//3、绘制覆盖物
			// 实现绘制方法  
			SquareOverlay.prototype.draw = function(){  
			// 根据地理坐标转换为像素坐标，并设置给容器 
			 var position = this._map.pointToOverlayPixel(this._center);
			 this._div.style.left = position.x - this._length / 2 + "px";  
			 this._div.style.top = position.y - this._length / 2 + "px";  
			}

			//4、显示和隐藏覆盖物
			// 实现显示方法  
			SquareOverlay.prototype.show = function(){  
			 if (this._div){  
			   this._div.style.display = "";  
			 }  
			}    
			// 实现隐藏方法  
			SquareOverlay.prototype.hide = function(){  
			 if (this._div){  
			   this._div.style.display = "none";  
			 }  
			}

			//5、添加其他覆盖物方法
			//比如，改变颜色 
			SquareOverlay.prototype.yellow = function(){  
			 if (this._div){  
				this._div.style.background = "yellow"; 
			 }     
			}

			//6、自定义覆盖物添加事件方法
			SquareOverlay.prototype.addEventListener = function(event,fun){
				this._div['on'+event] = fun;
			}

			  
			  
		</script>
        </if>
		{pigcms{$shareScript}
	</body>
</html>