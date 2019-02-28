<html lang="zh-CN"><head>
		<meta charset="utf-8">
		<title>首页</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/list_details.css"/>
		<script type="text/javascript" src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/static/js/jquery.lazyload.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/tpl/Wap/pure/static/js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>

		<!-- <script type="text/javascript" src="{pigcms{$static_path}yuedan/js/index.js" charset="utf-8"></script> -->
</head>
<body style="zoom: 1;">
	<div id="container" style="top:50px;-webkit-transform:translate3d(0,0,0)">
		<div id="scroller" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
			
			<section id="banner_hei" class="banner">
					<div class="swiper-container swiper-container1">
						<div class="swiper-wrapper">

							<volist name="service_info.img" id="vo">
								<div class="swiper-slide">
									<a href="javasctipt:void(0);">
										<!-- <span class="img_click" data-src="{pigcms{$vo}" style="width: 100%;background: transparent url({pigcms{$vo['url']}) no-repeat -100% -50px;background-size: 100%; height:220px;"></span> -->
										<img  class="img_click" src="{pigcms{$vo['url']}" alt="" style="position:absolute;clip: rect(0px,375px,211px,0px);">

									</a>
								</div>
							</volist>
						</div>
						<div class="swiper-pagination swiper-pagination1">
							<!-- <span class="swiper-pagination-switch swiper-visible-switch swiper-active-switch"></span>
							<span class="swiper-pagination-switch swiper-visible-switch "></span>
							<span class="swiper-pagination-switch swiper-visible-switch "></span> -->
						</div>
					</div>
			</section>
		</div>
	</div>
	<!--手绘-->
	<div class="shouhui">
		<p class="after"><span>{pigcms{$service_info.title} </span><if condition="$authentication['authentication_status'] eq 2"><button class="rg" type="button">已认证</button><else/><button style="background-color: red;">未认证</button></if></p>
	</div>
	<!--评分-->
	<div class="score">
		<ul>
			<!-- <li>{pigcms{$totalGrade}</li> -->
			<li><p class="stars"><span class="add"><i class="active" style="width: {pigcms{$totalGrade*10*2}%"></i><span class="fen">{pigcms{$totalGrade}</span></span>
			</p></li>
			<li>{pigcms{$service_info.price} 元/{pigcms{$service_info.unit}</li>
			<!-- <li><span>服务范围全国</span></li> -->
		</ul>
	</div>

	<div style="background: #fff; margin-top: 10px; width: 94%; padding: 10px 3%;">
		<p style="text-align: center; margin-bottom: 10px;font-size: 17px; color: #333;">服务内容</p>
		<div style="color: #666; font-size: 15px;">{pigcms{$service_info.describe}</div>
	</div>

	<!--评价-->
	<div class="evaluate">
		<p>评价</p>

		<volist name="commentList" id="vo">
			<div class="content">
				<div class="xing">
					<p>{pigcms{$vo.nickname}</p>
					<p class="stars1"><span class="acc"><i style="width: {pigcms{$vo[total_grade]*10}%"></i></span></p>
				</div>
				<p>{pigcms{$vo.add_time|date="Y-m-d",###}</p>
				<div class="lun">
					{pigcms{$vo.content}
				</div>
			</div>
		</volist>
		
	</div>


	<a href="{pigcms{:U('Yuedan/all_evaluate',array('rid'=>$_GET['rid']))}">
		<div class="see_all" >
			<p>查看全部评价 ({pigcms{$commentCount})</p>
			<span></span>
		</div>
	</a>
	<if condition="$service_info['uid'] neq $user_session['uid'] && $service_info['status'] eq 2">
		<div class="bottom">
			<a href="javascript:;" class="collection <if condition="is_array($collectionInfo)">active</if> "><i></i></a>
			<a href="javascript:;" class="order">立即下单</a>
		</div>
	</if>
	
 	<div class="mask1 hidden img_close">
			<div class="ajj">
				<div class="section">
					<img class="img_close" src="" alt="" style="width: 100%;">
				</div>
			</div>
			
		</div>
	<a href="JavaScript:history.back(-1)" class="return_top"></a>
	<script type="text/javascript">
		// window.onload = function() {
		   	var mySwiper = new Swiper('.swiper-container',{
		     	direction:"horizontal",/*横向滑动*/  
		        loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/  
		        pagination:".swiper-pagination",/*分页器*/   
		        autoplay:3000/*每隔3秒自动播放*/  
		   	});  
		 // };
		var width1= $(window).width();
		$('.img_click').width($(window).width());
		$('.img_click').css('clip','rect(0px, '+width1+'px, 211px, 0px)');
		var banner_height	=	$(window).width()/320;
		banner_height	=Math.ceil(banner_height*180);
		$("#banner_hei").css('height',banner_height);
		$(".shouhui").css('padding-top',banner_height);
		
		// 点击查看放大图片
		$('body').off('click','.img_click').on('click','.img_click',function(e){
		  e.stopPropagation();
		  var sic=$(this).attr('src');
		  $('.mask1').removeClass('hidden');
		  $('.mask1 img').prop('src',sic);
		  $('.img_close').click(function(e){
		  	  $('.mask1').addClass('hidden');
		  });
		});
		$('.order').click(function(e){
			var service_uid = "{pigcms{$service_info['uid']}";
			var uid = "{pigcms{$user_session['uid']}";
			var rid = "{pigcms{$_GET['rid']}";
			if(!uid){
	            layer.open({
	                content: '请先登录后再下单'
	                ,btn: ['登录']
	                ,yes: function(index){
	                    location.href = "{pigcms{:U('Login/index')}";
	                }
	            });
	            return false;
	        }
			if(service_uid == uid){
				layer.open({
					content: '不可以选择自己的服务'
					,skin: 'msg'
					,time: 2 
				});
				return false;
			}
			location.href="{pigcms{:U('next_order')}&rid="+rid;
		});


		//收藏点击
		$('.collection').click(function(e){
			var service_uid = "{pigcms{$service_info['uid']}";
			var uid = "{pigcms{$user_session['uid']}";
			if(!uid){
	            layer.open({
	                content: '请先登录再进行收藏'
	                ,btn: ['登录']
	                ,yes: function(index){
	                    location.href = "{pigcms{:U('Login/index')}";
	                }
	            });
	            return false;
	        }
			if(service_uid == uid){
				layer.open({
					content: '不可以选择自己的服务'
					,skin: 'msg'
					,time: 2 
				});
				return false;
			}
			var rid = "{pigcms{$_GET['rid']}";
			var collectionUrl = "{pigcms{:U('Yuedan/collection')}";

			$.post(collectionUrl,{'rid':rid},function(data){
				if(data.error == 1){
					$('.collection').addClass('active');
					layer.open({
						content: data.msg
						,skin: 'msg'
						,time: 2 
					});
				}else if(data.error == 3){
					$('.collection').removeClass('active');
					layer.open({
						content: data.msg
						,skin: 'msg'
						,time: 2 
					});
				}else{
					layer.open({
						content: data.msg
						,skin: 'msg'
						,time: 2 
					});
				}
			},'json')

		});
		

	</script>
</body>
</html>