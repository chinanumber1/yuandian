<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<title>约单首页</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link rel="stylesheet" type="text/css" href="http://hf.pigcms.com/tpl/Wap/pure/static/shop/css/shopBase.css?t=1499760439">
		<script type="text/javascript" src="https://apps.bdimg.com/libs/jquery/1.9.0/jquery.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/static/js/jquery.lazyload.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/tpl/Wap/pure/static/js/idangerous.swiper.min.js" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/index.css"/>
	</head>
	<body style="">
		<div id="pageList" class=" nowPage" style="width:100%;display: block;">
			<a href="{pigcms{:U('search')}" class="Cable"> <div><i></i>搜索服务</div> </a>
			<section id="banner_hei" class="banner">
				<div class="swiper-container swiper-container1" style="cursor: -webkit-grab;">
					<div class="swiper-wrapper" >
						<volist name="yuedan_index_top_lunbo" id="vo">
							<div class="swiper-slide swiper-slide-duplicate" >    
								<a href="{pigcms{$vo.url}">     
									<img src="{pigcms{$vo.pic}">    
								</a>
							</div>    
						</volist>
					</div>
					<div class="swiper-pagination swiper-pagination1"> </div>
				</div>
			</section>
		</div>

		<!--分类列表-->
		<div class="place_lists" style="margin-top: 10px;">
			<ul>
				<volist name="catList" key="k" id="vo"  offset="0" length='9'>
					<li class="left_style <if condition='$k eq 1'>active</if>" >
						<dd class="addddd"  data-url="{pigcms{$vo.click_icon}" data-hrefurl="{pigcms{:U('service_list',array('cid'=>$vo['cid'],'type'=>1))}" data-img="{pigcms{$vo.icon}" data-key="{pigcms{$vo.cid}">
							<img id="aaa_{pigcms{$vo.cid}" src="{pigcms{$vo.icon}" alt="">
						</dd>
						<dd>{pigcms{$vo.cat_name}</dd>
					</li>
				</volist>
				<a href="{pigcms{:U('cat_list')}">
					<li class="left_style all_lists"> <dd><i></i></dd> <dd>全部</dd> </li>
				</a>
			</ul>
		</div>

		<!--附近的服务-->
		<a href="{pigcms{:U('around')}" >
			<div class="nearby" style="margin-top: 10px;">
				<img src="{pigcms{$config.yuedan_background_picture}"/>
				<ul>
					<li>附近的服务</li>
					<!-- <li><span>精选附近4814个服务</span></li> -->
					<li>> > ></li>
				</ul>
			</div>
		</a>

		<!-- 同城热约 -->
		<h3>同城热约</h3>
		<div class="city_about">
			<ul class="clear">
				<volist name="service_list" id="vo">
					<a href="{pigcms{:U('Yuedan/service_detail',array('rid'=>$vo['rid']))}">
						<li class="ft">
							<!-- <img src="{pigcms{$vo.listimg}" alt="{pigcms{$vo.title}"> -->
							<p class="img_click" data-src="{pigcms{$vo.listimg}" style="display:inline-block;    margin: 5px 5px; width: 90%;background: transparent url({pigcms{$vo.listimg}) no-repeat 0% 0px;background-size:cover; height:90px;text-align: center;"></p>
							<dl>
								<dt>{pigcms{$vo.title}</dt>
								<dd><span>{pigcms{$vo.price}元</span>/{pigcms{$vo.unit}</dd>
							</dl>
						</li>
					</a>
				</volist>
			</ul>
		</div>

		<!--详情内容-->
		<h3>智能推荐</h3>
		<div class="content_details"><div id="recommend_list"></div><div id="no_data" style="display: none; text-align: center; color: red; font-size: 18px; padding: 5px;">暂无数据</div></div>

		<div style="padding-bottom: 50px;"></div>
		<div class="bottom">
			<div class="index active"> <dd class="icon1"></dd> <dd>首页</dd> </div>
			<div class="release"> <dd class="icon2"></dd> <dd>发布</dd> </div>
			<div class="person"> <dd class="icon3"></dd> <dd>我的</dd> </div>
		</div>

		<script type="text/javascript">
		 	var banner_height	=	$(window).width()/320;
			banner_height	=Math.ceil(banner_height*119);
			$("#banner_hei").css('height',banner_height);
			
			window.onload = function() {
		   		var mySwiper = new Swiper('.swiper-container',{
			     	direction:"horizontal",/*横向滑动*/  
			        loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/  
			        pagination:".swiper-pagination",/*分页器*/   
			        autoplay:3000/*每隔3秒自动播放*/  
			   	});
			}

		  	$('.city_about ul').width(110*$('.city_about ul li').length);

			$('.index').click(function(e){
				location.href=location.href;
			});

			$('.release').click(function(e){
				location.href="{pigcms{:U('release')}";
			});

			$('.person').click(function(e){
				location.href="{pigcms{:U('my_index')}";
			});

			//tab切换头部
			$('.place_lists').off('click','ul li').on('click','ul li',function(e){
				$(this).addClass('active').siblings('li').removeClass('active');
				location.href = $(this).find('.addddd').data('hrefurl');
			});
		</script>

		<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
		<script type="text/javascript">
			var page = 0;
			var lat = '';
			var lng = '';
			var geolocation = new BMap.Geolocation();
			geolocation.getCurrentPosition(function(r){
				if(this.getStatus() == BMAP_STATUS_SUCCESS){
					lat = r.point.lat;
					lng = r.point.lng;
					recommend_list_ajax(lng,lat,page);
				} else {
					alert('failed'+this.getStatus());
				}
			},{enableHighAccuracy: true})

			function recommend_list_ajax(lng,lat,page){
				var recommend_list_ajax_url = "{pigcms{:U('recommend_list_ajax')}";
				$.post(recommend_list_ajax_url,{lng:lng,lat:lat,page:page},function(data){
					if(data.error == 1){
						$("#recommend_list").append(data.html);
						var width1=$(window).width();
						var li_p=(width1-55)/3;
						$('.list_content ul li p').width(li_p);
						$('.list_content ul li p').height(li_p);
						$("#no_data").css('display','none');
					}else{
						$("#no_data").css('display','block');
						// alert(data.msg);
					}
				},'json');

			}
		</script>
	</body>
</html>