<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>免单消费码</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}sub_card/css/enjoy.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}sub_card/js/sub_card.js"></script>
	
	</head>
	<body>
	<header>
		<ul class="content_left">
			<li><img src="{pigcms{$sub_card_store.pic_info}"/></li>
			<!--<li><i></i></li>-->
		</ul>
		<ul class="content_right">
			<li><b>{pigcms{$sub_card_store.name}</b></li>
			<li><span class="s1"><if condition="$sub_card_store.appoint eq 1">需预约<else />不需预约</if></span><span class="s2">本店可用<php>if(count($pass_by_share)>0) {$pass = $pass_by_share;} echo count($pass);</php>次免单 </span></li>
			<li></li>
		</ul>
	</header>
	<div class="details">
		<div class="details_header">
			套餐详情
		</div>
		<div class="car_header">
		<if condition="is_array($pic_list)">
			<section id="banner_hei" class="banner">
				<div class="swiper-container swiper-container1" style="cursor: -webkit-grab;">
					<div class="swiper-wrapper" >
						<volist name="pic_list" id="vo">
							<div class="swiper-slide swiper-slide-duplicate" >    
								<a href="#">     
									<img src="{pigcms{$vo}">    
								</a>
							</div>    
						</volist>
					</div>
					<div class="swiper-pagination swiper-pagination1"><span class="swiper-pagination-switch swiper-visible-switch swiper-active-switch"></span><span class="swiper-pagination-switch"></span></div>
				</div>
			</section>
		</if>
			<p class="more_length">{pigcms{$sub_card_store.desc_txt|html_entity_decode}</p>
			<p class="zhan"><a href="javascript:;">展开</a></p>
		</div>
	</div>
	<div class="juli link-url" data-url="">
		<div class="details_header">
			店铺地址
		</div>
		<div class="juli_footer after">
			<p class="ft"><i></i> {pigcms{$sub_card_store.adress}</p>
			<span class="rg distance"></span>
		</div>
	</div>
	<!--免单卷-->
	<div class="single">
		<div class="details_header">
			免单券
		</div>
		<volist name="pass" id="vo">
		<div class="single_content">
			
			<p class="after"><span class="ft">消 费 密 码: <b >{pigcms{$vo.pass}</b></span> 
				<if condition="$sub_card.effective_days lt 0">
					<a class="rg" style="color:red" href="javascript:;">已过期
				<elseif condition="$vo.status gt 0" />
					<a class="rg" style="color:red" href="javascript:;">已消费
				<elseif condition="$vo.share_uid gt 0" />
					<a class="rg" style="color:red" href="javascript:;"><if condition="$user_session['uid'] neq $vo['share_uid']">被领取<else />已领取</if>
				<else />
					<a class="rg" href="javascript:;">
					<if condition="$vo.share eq 1 AND empty($pass_by_share)"> 
						<b style="color:red">已分享</b>
					</if>
					未消费
				</if>
				</a>
			</p>
		
			<p class="show_code" data-pass="subcard_{pigcms{$vo.pass}"><span>消费二维码: <b> 查看二维码 <i></i></b></span></p>
			
		</div>
		</volist>
	</div>
	<!--其他推荐-->
	<if condition="$slider_list">
		<div class="recommend">
		<div class="recommend_header">
			其他推荐
		</div>
		<div class="reco_content">
		  	<ul class="after">
		  		<volist name="slider_list" id="vo">
		  			<li class="ft" data-url="{pigcms{$vo.url}">
						<dl>
							<dt style="background: url(./upload/slider/{pigcms{$vo.pic}) center no-repeat;background-size: 100% 100%;"></dt>
							<dd>{pigcms{$vo.name}</dd>
						</dl>
					</li>
				</volist>
			
			</ul>
			   
			  <!--ul class="after">
			   <if condition="$shop['is_book']">
				<li class="ft" data-url="{pigcms{:U('Foodshop/book_order', array('store_id' => $shop['store_id']))}">
					<dl>
						<dt></dt>
						<dd>在线订座</dd>
					</dl>
				</li>
				   </if>
				<if condition="$shop['is_queue']">
				<li class="ft" data-url="{pigcms{:U('Foodshop/queue', array('store_id' => $shop['store_id']))}">
					<dl>
						<dt></dt>
						<dd>排号</dd>
					</dl>
				</li>
				   </if>
				<if condition="$config['pay_in_store']">
				<li class="ft" data-url="{pigcms{:U('My/pay', array('store_id' => $shop['store_id']))}">
					<dl>
						<dt></dt>
						<dd>{pigcms{$config.cash_alias_name}</dd>
					</dl>
				</li>
				   </if>
					<if condition="$shop['is_takeout']">
				<li class="ft" data-url="{pigcms{:U('Shop/index')}#shop-{pigcms{$shop['store_id']}">
					<dl>
						<dt></dt>
						<dd>{pigcms{$config.shop_alias_name}</dd>
					</dl>
				</li>
				   </if>
				 <if condition="$card_info AND $card_info['self_get'] eq 1 ">
				<li class="ft" data-url="{pigcms{:U('My_card/merchant_card',array('mer_id'=>$shop['mer_id']))}">
					<dl>
						<dt></dt>
						<dd>会员卡</dd>
					</dl>
				</li>
				   </if>
			 </ul-->
		</div>
	</div>
	</if>
	<!--双人套餐-->
	<!--div class="double_set">
		<volist name="shop['group_list']" id="group">
		<div class="double_sets link-url" data-url="{pigcms{$group['url']}">
			<div class="double_left">
				<img src="{pigcms{$group['list_pic']}"/>
				<if condition="$group['pin_num'] eq 0"> <span class="MenuGroup"></span><else /><span class="PinGroup"></span></if>
			</div>
			<ul class="double_right">
				<li>{pigcms{$group['name']}</li>
				<li><span>{pigcms{$group['score_mean']}分</span> 已售{pigcms{$group['sale_txt']}</li>
				<li><b>￥<span>{pigcms{$group['price']}</span></b> 门市价: ￥{pigcms{$group['old_price']}</li>
			</ul>
		</div>
		</volist>
		
	</div-->
	<div class="mask">
		
	</div>
	<div class="qr_code">
		<div class="details_code">
			<img src=""/>
		</div>
		<div class="code_close">
			<p></p>
		</div>
	</div>
	
	<!-- <div id="details" style="display: none;">
		<div class="details_header">
			<i></i> 套餐详情
		</div>
		<div class="details_content">
			
			<p>{pigcms{$sub_card_store.desc}</p>
		</div>
		
		<div class="details_footer close">
			<p></p>
		</div>
	</div> -->


	<script type="text/javascript">
		var banner_height	=	$(window).width()/320;
			banner_height	=Math.ceil(banner_height*119);
			$("#banner_hei").css('height',banner_height);
			var mySwiper = new Swiper('.swiper-container',{
		     	direction:"horizontal",/*横向滑动*/  
		        loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/  
		        pagination:".swiper-pagination",/*分页器*/   
		        autoplay:3000/*每隔3秒自动播放*/  
		   	});  
		var lng=0,lat=0,now_count=0;
		$('.car_header a').click(function(e){
			if($(this).is('.active')){
				$(this).removeClass('active');
				$(this).parent().prev().removeClass('active');
				$(this).text('展开');
				
			}else{
				$(this).addClass('active');
				$(this).parent().prev().addClass('active');
			
				$(this).text('收起');
			}
		});
		//点击详情按钮
		$('.see a').click(function(e){

			$('.mask').removeClass('hidden');
			$('#details').show();
			
		});
		//关闭按钮和点击蒙层
		$('.close').click(function(e){
			$('.mask').addClass('hidden');
			$('#details').hide();
		});
		$('.tab li').click(function(e){
			$(this).addClass('active').siblings('li').removeClass('active');
		});
		$('li.ft,.link-url').click(function(){
			window.location.href=$(this).data('url')
		})
		$('.show_code').click(function(e){
			$('.qr_code img').attr('src',"{pigcms{:U('Sub_card/passqrcode')}&pass="+$(this).data('pass'))
			$('.mask').show();
			$('.qr_code').show();
			
			$('.mask').click(function(e){
				$('.mask').hide();
				$('.qr_code').hide();
			});
			$('.code_close p').click(function(e){
				$('.mask').hide();
				$('.qr_code').hide();
			});
		});
		getUserLocation({okFunction:'geoconvPlace',useHistory:false});
		function getStoreListBefore(result){
			lng1 = result.result[0].x;
			lng2 = '{pigcms{$shop.long}';
			lat1 = result.result[0].y;
			lat2 = '{pigcms{$shop.lat}';
			if(lng1&&lng2&&lat1&&lat2){
				$.post('{pigcms{:U('Sub_card/ajax_get_distance')}',{lat1,lat2,lng1,lng2},function(data){
					$('.distance').html(data.distance)
				},'json')
			}
		}
		
	</script>
	{pigcms{$hideScript}
</body>
</html>