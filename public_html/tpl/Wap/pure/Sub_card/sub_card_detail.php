<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>免单套餐</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}sub_card/js/sub_card.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}sub_card/css/user_see.css?21511"/>
		
	</head>
	<body>
	<div class="all_cars">
		<div class="car_package">
			<div class="car_header see_sub_card ">
				<h4 id="sub_card_name">{pigcms{$sub_card.name}</h4>
				<p id="sub_card_desc">{pigcms{$sub_card.desc}</p>
				<a href="javascript:;">详情</a>
			</div>
			<div class="car_content">
				<div class="frequency after">

					<span class="ft"><b>￥{pigcms{$sub_card.price}</b><span>/{pigcms{$sub_card.free_total_num}次</span></span>
					<a class="rg" href="javascript:;"><if condition="$sub_card.use_time_type eq 1">购买后{pigcms{$sub_card.effective_days}天内有效<else /><if condition="$sub_card.forever_txt neq ''" >{pigcms{$sub_card.forever_txt}<else />购买后永久有效</if></if></a>
				</div>
				<p>共{pigcms{$sub_card.join_num}个店铺，一共可以选择{pigcms{$sub_card.free_total_num}次</p>
			</div>
			<div class="car_footer after">
				<p class="ft"><i></i><span>  <if condition="$sub_card['buy_time_type'] eq 1">限时: {pigcms{$sub_card.start_time|date="Y-m-d",###} 至 {pigcms{$sub_card.end_time|date="Y-m-d",###}</span><else />限时: 不限时</if></p>
				<span class="rg">已售: {pigcms{$sub_card.sale_count}</span>
			</div>
			<div class="bg">
				
			</div>
		</div>
	</div>
	<!--搜索框-->
	<div class="search after">
		<i></i>
		<b class="hidden"></b>
		<input type="" name="store_name" id="store_name" value="" placeholder="请输入商家或店铺名称"/>
		<a class="rg" id="search" href="javascript:;">搜索</a>
	</div>
	<!--所含店铺-->
	<div class="all_shops">
		<div class="shop_header">
			<ul>
				<li>所含店铺</li>
				<li></li>
			</ul>
		</div>
		<div class="store_list" id="store_list">
			
		</div>
	</div>
<if condition="is_array($sub_card['pic_lists'])" >
	<div id="sub_pic_list" style="display:none">
		<volist name="sub_card['pic_lists']" id="vo">
			<div class="swiper-slide swiper-slide-duplicate" >    
				<a href="#">     
					<img src="{pigcms{$vo}">    
				</a>
			</div>   
		</volist>
	</div>
	</if>
	<!--底部固定栏-->
	<div class="bottom" id="buy">
		购买
	</div>
	<!--弹层-->
	<div class="mask close hidden">
		
	</div>
	<div id="details" style="display: none;">
		<div class="details_header">
			<i></i> 查看详情
		</div>
		<div class="details_content">
			<h4 data-name="{pigcms{$sub_card.name}"></h4>
			<section id="banner_hei" class="banner">
				<div class="swiper-container swiper-container1" style="cursor: -webkit-grab;">
					<div class="swiper-wrapper" id="pic_list">
					
						
						
					</div>
					<div class="swiper-pagination swiper-pagination1"><span class="swiper-pagination-switch swiper-visible-switch swiper-active-switch"></span><span class="swiper-pagination-switch"></span></div>
				</div>
			</section>
			<p class="desc_txt" data-desc = "{pigcms{$sub_card.desc}"></p>
		</div>
		<div class="details_footer close">
			<p></p>
		</div>
	</div>
	<div class="bottom_css more" >
		点击加载更多
	</div>
	<div class="no_result" style="color:#b9b4b4;text-align:center;margin-top:-30px;display: none">未搜索到您要的结果</div>
	<script id="cardlist" type="text/html">
					
		{{# for(var i = 0, len = d.length; i < len; i++){ }}

			<div class="shop">
				<div class="shop_content">
					<ul class="content_left">
						<li><img src=" {{ d[i].pic_info }}"/></li>
						{{# if(d[i].appoint==1){ }}<li><i></i></li>{{# } }}
					</ul>
					<ul class="content_right">
						<li class="after">
							<b> {{ d[i].name }}</b>{{# if(d[i].status==3  ){ }}<span class="rg finish">已过期</span>{{# }else{ }}<span class="rg">可以使用{{ d[i].mer_free_num }}次免单</span>{{# } }} 

						</li>
						<li class="mer_name" style="font-size: 0.203389rem;color:#FFB905;width:100%;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">所属商家:{{ d[i].mer_name }}</li>
						<li class="shop_text see detail" ><p> {{ d[i].desc }}</p><a href="javascript:;" data-desc="{{# if(!d[i].desc_txt){ }}{{ d[i].desc }}{{# }else{ }}{{ d[i].desc_txt }}{{# } }}" data-name="{{ d[i].name }}">详情</a></li>
						<li class="after"><span><i></i> {{ d[i].adress }}</span><a class="rg" href="javascript:;">距您{{ d[i].juli }}</a></li>
					</ul>
				</div>
				<div class="after limit">
					{{# if(d[i]['start_time']!=''){ }}<p class="ft"><i></i>使用有效期: {{ d[i]['start_time'] }} 至 {{ d[i]['end_time'] }}</p>{{# } }}
					<span class="rg">库存:  {{ d[i].sku_ }}</span>
				</div> 
				<div class="shop_footer">
					
					<div class="navigation link-url"  data-url="{pigcms{$config.site_url}/wap.php?g=Wap&c=Group&a=addressinfo&store_id={{ d[i]['store_id']}}">
						<i></i> 导航
					</div>
					
					<div class="phone_call" data-phone="{{ d[i].phone }}">
						<i></i> 电话
					</div>
				</div>
				
				{{# if(d[i].pic_lists.length>0){ }}
				<div class="pic_list" style="display: none;">
					{{# for(var v= 0, lens = d[i].pic_lists.length; v < lens; v++){ }}
						<div class="swiper-slide swiper-slide-duplicate" >    
							<a href="#">     
									<img src="{{ d[i]['pic_lists'][v] }}">    
							</a>
						</div>   	
					{{# } }}
				</div>
				{{# } }}

			</div>
		{{# } }}
	</script>
	<script type="text/javascript">
	


		var width=$(window).width();
		
		var lng=0,lat=0,now_count=0;
	
		$('#buy').click(function(e){
			window.location.href="{pigcms{:U('Sub_card/select_store',array('sub_card_id'=>$_GET['sub_card_id']))}";
		});

		$('.see_sub_card a').click(function(e){
			var pic_list_html = $('#sub_pic_list').html();
			if(typeof(pic_list_html)!='undefined'){
				$('#pic_list').html(pic_list_html)
				$('#banner_hei').show();
			}else{
				$('#banner_hei').hide();
			}
			$('#details .desc_txt').html($('#details .desc_txt').data('desc'))
			$('#details h4').html($('#details h4').data('name'))

			$('.mask').removeClass('hidden');
			$('#details').show();
			var banner_height	=	$(window).width()/320;
			banner_height	=Math.ceil(banner_height*119);
			$("#banner_hei").css('height',banner_height);
			var mySwiper = new Swiper('.swiper-container',{
		     	loop:true,//无缝衔接滚动
		      	effect:'cube',//滑动效果
		     	autoplay:3000,
		      	autoplayDisableOnInteraction:false,//用户操作swiper之后不禁止autoplay
		      	pagination:'.swiper-pagination',
		      	paginationType:'progress',//分页器样式
		      	lazyLoading:true,//图片延迟加载
		      	lazyLoadingInPrevNext:true//前一个和后一个延迟加载 
		   	});  
		});
		
		//关闭按钮和点击蒙层
		$('.close').click(function(e){
			$('.mask').addClass('hidden');
			$('#details').hide();
		});
		//前端搜素功能
		$('.search input').keyup(function(e){
			if($(this).val()!=''){
				$('.search b').removeClass('hidden');
				$('.all_shops .shop').removeClass('hidden');
				$('.search a').addClass('active');
			}else{
				$('.search b').addClass('hidden');
				$('.all_shops .shop').removeClass('hidden');
				$('.search a').removeClass('active');
			}
		});
		//清空按钮点击
		$('.search b').click(function(e){
			$('.search input').val('');
			$('.all_shops .shop').removeClass('hidden');
			$(this).addClass('hidden');
			$('.search a').removeClass('active');
		});
		//搜索按钮点击
		$('.search a').click(function(e){
			var val=$('.search input').val();
			if(val!=''){
				for(var i=0;i<$('.all_shops .shop').length;i++){
					var text=$('.all_shops .shop:eq('+i+') .content_right b').text();
					text+=$('.all_shops .shop:eq('+i+') .content_right .mer_name').text();
					if(text.indexOf(val)>-1){
							$('.no_result').hide();
						$('.all_shops .shop:eq('+i+')').removeClass('hidden');
					}else{
							$('.no_result').show();
						$('.all_shops .shop:eq('+i+')').addClass('hidden');
					}
				}
			}else{
				$('.all_shops .shop').removeClass('hidden');
			}
		});
		//获取店铺列表
		getUserLocation({okFunction:'geoconvPlace',useHistory:false});
		function getStoreListBefore(result){
			console.log(result)
			lng = result.result[0].x;
			lat = result.result[0].y;
			show_store_list(0,'');
		}
		function show_store_list(more,key){
			if(!more){
				page = 0;
				document.getElementById('store_list').innerHTML  = '';
			}else{
				page += 20;
			}
			$.post("{pigcms{:U('Sub_card/ajax_get_card_store')}",{page:page,key:key,lat:lat,lng:lng,sub_card_id:"{pigcms{$_GET['sub_card_id']}"},function(result){
				data = result.store_list;
				count= result.count;
				now_count += data.length;
				if(data.length > 0){
					laytpl(document.getElementById('cardlist').innerHTML).render(data, function(html){
						if(more){
							document.getElementById('store_list').innerHTML += html;
						}else{
							document.getElementById('store_list').innerHTML = html;
						}
						if(count>now_count){
							$('.more').show();
						}else{
							$('.more').hide();
						}
						$('.link-url').bind('click',function(){
							window.location.href=$(this).data('url')
						})
						$('.phone_call').bind('click',function(){
							window.location.href="tel://"+$(this).data('phone')
						})
						$('.detail a').bind('click',function(){
							$('#details .details_content h4').html($(this).data('name'));
							$('#details .details_content p').html($(this).data('desc'));
							$('#details .details_content .swiper-wrapper').html($(this).parents('.shop').find('.pic_list').html());
							$('.mask').removeClass('hidden');
							$('#details').show();
						})
				
					});

					//点击详情按钮
					$('.see a').click(function(e){
						var pic_list_html = $(this).parents('.shop').find('.pic_list').html();
						if(typeof(pic_list_html)!='undefined'){
							$('#pic_list').html(pic_list_html)
							$('#banner_hei').show();
						}else{
							$('#banner_hei').hide();
						}
						
						$('.mask').removeClass('hidden');
						$('#details').show();
						var banner_height	=	$(window).width()/320;
						banner_height	=Math.ceil(banner_height*119);
						$("#banner_hei").css('height',banner_height);
						var mySwiper = new Swiper('.swiper-container',{
					     	direction:"horizontal",/*横向滑动*/  
					        loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/  
					        pagination:".swiper-pagination",/*分页器*/   
					        autoplay:3000/*每隔3秒自动播放*/  
					   	});  
					});
				}else{
					alert('未查找到内容！');
				}
			},'json');
		}
		
		
	</script>
</body>
</html>