<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>酒店首页</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mui.css?2221"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/hotel_index.css?2221"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			var public_path ="{pigcms{$static_path}";
			//var many_city = "{pigcms{$config.many_city}";
			var now_select_city_name = "{pigcms{$config.now_select_city.area_name}";
			var noAnimate = true;
		</script>
        <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
            <script>var is_google_map = "{pigcms{$config.goole_map_ak}";</script>
        </if>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	
	</head>
	<body>
	
	<if condition="!$is_app_browser">
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	    <h1 class="mui-title">酒店首页</h1>
	</header>
	</if>
	<div class="mui-content">
	   
		<if condition="$hotel_adver">
			<section id="banner_hei" class="banner">
				<div class="swiper-container swiper-container1">
					<div class="swiper-wrapper">
						<volist name="hotel_adver" id="vo">
							<div class="swiper-slide">
								<a class="link-url" data-url="{pigcms{$vo.url}">
									<img src="{pigcms{$vo.pic}"/>
								</a>
							</div>
						</volist>
					</div>
					<div class="swiper-pagination swiper-pagination1"></div>
				</div>
			</section>
		</if>
		<!--筛选条件-->
		<div class="screen">
			<div class="mui-card">
				<div class="mui-card-content adress" data-url="{pigcms{$config.site_url}">
					<i></i>
					<span <if condition="$config['many_city'] eq 1">class="now_city"</if>>{pigcms{$config.now_select_city.area_name}</span>
					<p class="mui-pull-right"></p>
					<if condition="$config['many_city'] eq 1"><b class="mui-pull-right"></b></if>

				</div>
				<div class="mui-card-content date" id="choose_date">
					<sub></sub>
					<span class="startdate"></span>
					<i class="startweek"></i>
					<b class="date_days"></b>
					<span class="enddate"></span>
					<i class="endweek"></i>
				</div>
				<div class="mui-card-content search">
					<i></i>
					
					<input type="text" name="search_txt" value="{pigcms{$_GET['search_txt']}" placeholder="<if condition="$_GET['search_txt']">{pigcms{$_GET['search_txt']}<else />  位置/酒店名称</if>" readonly />
					<b class="mui-pull-right"></b>
				</div>
				<div class="mui-card-content btn_search">
					<a href="#" class="search_hotel">查找酒店</a>
				</div>
			</div>
		</div>
		
		
		<!--附近酒店推荐-->
		<div class="hearby_hotel">
			<div class="mui-card">
				<div class="mui-card-header">
					<ul>
						<li>附近酒店推荐</li>
						<li><b>MORE</b></li>
					</ul>
				</div>
				
			</div>
		</div>
		
		<div id="J_Calendar" class="calendar" style="display:none;">
			 <header class="mui-bar mui-bar-nav">
				<a id="close_yui" class=" mui-icon mui-icon-left-nav mui-pull-left"></a>
				<h1 class="mui-title">日期选择</h1>
			</header>
			<ul class="calendar-title bar">
				<li>周日</li>
				<li>周一</li>
				<li>周二</li>
				<li>周三</li>
				<li>周四</li>
				<li>周五</li>
				<li>周六</li>
			</ul>
		</div>

	</div>
<script src="{pigcms{$static_path}js/mui.min.js"></script>
<script src="https://cdn.bootcss.com/yui/3.18.1/yui/yui.js"></script>
<script src="{pigcms{$static_path}hotel/hotel_index.js" type="text/javascript" charset="utf-8"></script>
<script id="HotelListTpl" type="text/html">

	{{# for(var i = 0, len = d.length; i < len; i++){ }}

		<div class="mui-card-content link-url" data-url="{{ d[i].url }}">
			<div class="mui-row">
				<div class="mui-col-sm-4">
					<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d[i].list_pic) }}" alt="{{ d[i].s_name }}"/>
				</div>
				<div class="mui-col-sm-8">
					<ul>
						<li class="hotel_hidden">{{ d[i].group_name }}</li>
						<li class="distance">{{ d[i].juli }}-{{ now_area_name }}</li>
						<li class="score"><b>{{ d[i].score_mean }}分</b> {{ d[i].reply_count }}条评论</li>
						<li class="hotel_icon">{{# if(d[i].is_refund){ }}<i></i>{{# } }} {{# if(d[i].discount){ }} <b class="huistyle"></b>{{# } }}  <span class="mui-pull-right"><b>￥{{ d[i].price }}</b>起</span></li>
					</ul>
				</div>
			</div>
		</div>
	{{# } }}
</script>
<script type="text/javascript" charset="utf-8">
		var now_area_name = $.cookie('userLocationName');
      	mui.init();
      	//点击搜索框
		$('.search').click(function(){
			window.location.href='{pigcms{:U('Hotel/hotel_search')}&search_txt='+$('input[name="search_txt"]').val()+'&type={pigcms{$_GET['type']}';
		});
  
		mui('.mui-content').on('tap','.search_hotel',function(e){

      		mui.openWindow(
      			{
      				url:'{pigcms{:U('Hotel/hotel_list')}&search_txt='+$('input[name="search_txt"]').val()+'&type={pigcms{$_GET['type']}',
      				id:'hotel_list'
      			}

      		);
      	});
      	mui('.hearby_hotel').on('tap','.mui-card-content',function(e){
      		window.location.href=$(this).data('url')
      	});
		
		// mui('.mui-content').on('tap','#close_yui',function(e){
			// console.log(111)
			// $('#J_Calendar').hide();
		// })
		$('body').off('click','#close_yui').on('click','#close_yui',function(){
			$('#J_Calendar').hide();
		})
</script>
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Hotel",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Hotel/index')}",
		"tTitle": "酒店预订",
		"tContent": "{pigcms{$config.seo_description}"
	};
</script>
{pigcms{$shareScript}
</body>
</html>