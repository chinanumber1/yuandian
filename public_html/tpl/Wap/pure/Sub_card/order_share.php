<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>免单订单详情</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}sub_card/css/order_details.css?21511"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}sub_card/js/sub_card.js"></script>
	
	</head>
	<body>
	<div class="all_cars">
		<div class="car_package">
			<div class="car_header see_sub_card">
				<h4 id="sub_card_name">{pigcms{$sub_card.name}</h4>
				<p  id="sub_card_desc">{pigcms{$sub_card.desc}</p>
				<a href="javascript:;">详情</a>
			</div>
			<div class="car_content">
				<div class="frequency after">
					<span class="ft"><b>￥{pigcms{$sub_card.price}</b><span>/{pigcms{$sub_card.free_total_num}次</span></span>
					<if condition="$sub_card.effective_days gt 0"><a class="rg" href="javascript:;">{pigcms{$sub_card.effective_days}天内有效</a></if>
				</div>
				<p>{pigcms{$share_user.nickname}分享了免单，共{pigcms{$share_info.num}个，已经被领取了<b id="hadpull_num">{pigcms{$share_info.hadpull}</b>次</p>
			</div>
			<div class="car_footer after">
				
			</div>
			<div class="bg">
				
			</div>
		</div>
		
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
	
		<div class="bottom" >
			<div class="share hadpull" style="width:100%;<if condition="$my_hadpull_count eq 0">display:none;</if>">
				查看领取
			</div>
		</div>


	<!--弹层-->
	<div class="mask close hidden">
		
	</div>
<if condition="is_array($Sub_card['pic_lists'])" >
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
			<p data-desc="{pigcms{$sub_card.desc}" class="desc_txt"></p>
		</div>
		<div class="details_footer close">
			<p></p>
		</div>
	</div>

	<div class="input_box" style="display: none;">
		<input type="number" class="value select_num" name="select_num" value=""  placeholder="请输入分享次数"/>
		<a href="javascript:;">确认</a>
	</div>
	
	<script id="cardlist" type="text/html">
					
		{{# for(var i = 0, len = d.length; i < len; i++){ }}
			
			<div class="shop " >
				
				<div class="shop_content " data-url="{pigcms{:U('Sub_card/order_detail_pass')}&order_id={pigcms{$_GET['order_id']}&store_id={{ d[i].store_id }}">
					<ul class="content_left">
						<li><img src="{{ d[i].pic_info }}"/></li>
						
						{{# if(d[i].appoint==1){ }}<li><i></i></li>{{# } }}
					</ul>
					<ul class="content_right">
						<li class="after ">
						<b> {{ d[i].name }}</b><span class="rg">可以领取{{ d[i].num }}次免单</span>

						</li>
						<li style="font-size: 0.203389rem;color:#FFB905;width:100%;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">所属商家:{{ d[i].mer_name }}</li>
						
						<li class="shop_text see detail"><p>{{ d[i].desc }}</p><a href="javascript:;" data-desc="{{# if(!d[i].desc_txt){ }}{{ d[i].desc }}{{# }else{ }}{{ d[i].desc_txt }}{{# } }}" data-name="{{ d[i].name }}">详情</a></li>
						<li class="yi_use"></li>
						<li class="after "><span><i></i> {{ d[i].adress }}</span><a class="rg" href="javascript:;">距您{{ d[i].juli }}</a></li>
					</ul>
				</div>
				
					<div class="shop_footer share_sub_card change" style="text-align:right;">
							{{# if(d[i].num>0 ){ }}
						<span>已领取<b class="select_txt">{{ d[i].user_hadpull_num }}</b>次</span>
						<b class="set change_num" data-max_select = "{{ d[i].num }}" data-store_id="{{ d[i].store_id }}" >可领取{{ d[i].num }}次</b>
							{{# }else{ }} 
						<b class="set change_num active" data-max_select = "{{ d[i].num }}" data-store_id="{{ d[i].store_id }}" >已领完了</b>

							{{# } }} 
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
		window.shareData = {
			"moduleName":"Shop",
			"moduleID":"0",
			"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else />{pigcms{$config.site_logo}</if>", 
			"sendFriendLink": "{pigcms{:U('Sub_card/order_share',array('order_id'=>$_GET['order_id']))}",
			"tTitle": "免单套餐分享",
			"tContent": "{pigcms{$user_session['nickname']}分享了免单套餐"
		};
	</script>

	<script type="text/javascript">
	
		var lng=0,lat=0,now_count=0,select_num=0;
		var flag = 1;
		var store_id = [];
		var total_num = 0;
		//点击详情按钮
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
		     	direction:"horizontal",/*横向滑动*/  
		        loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/  
		        pagination:".swiper-pagination",/*分页器*/   
		        autoplay:3000/*每隔3秒自动播放*/  
		   	});  
		});
		
		//关闭按钮和点击蒙层
		$('.close').click(function(e){
			$('.mask').addClass('hidden');
			$('#details').hide();
		});
		$('.hadpull').click(function(e){
			window.location.href="{pigcms{:U('sub_card_list')}";
		});
		
		
		
		
	
		
		$('.tab li').click(function(e){
			$(this).addClass('active').siblings('li').removeClass('active');
			var type = $(this).data('type')
			var near_type = $(this).siblings('li').data('type')
			$('.'+type+'_store').show();
			$('.'+near_type+'_store').hide();
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
			console.log(val);
			if(val!=''){
				for(var i=0;i<$('.all_shops .shop').length;i++){
					var text=$('.all_shops .shop:eq('+i+') .content_right b').text();
					console.log(text.indexOf(val))
					console.log(text)
					if(text.indexOf(val)>-1){
						
						$('.all_shops .shop:eq('+i+')').removeClass('hidden');
					}else{
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
			$.post("{pigcms{:U('Sub_card/ajax_get_share_order')}",{lat:lat,lng:lng,order_id:"{pigcms{$_GET['order_id']}",share_id:"{pigcms{$_GET['share_id']}",all:1},function(result){
				data = result.store_list;
				count= result.count;
				now_count += data.length;
				total_num = result.unconsume_num;
				if(result.unconsume_num>0){
					$('.bottom').show();
				}
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
						
						$('.phone_call').bind('click',function(){
							window.location.href="tel://"+$(this).data('phone');
						})
					});
					if(result.unconsume_count==0){
						$('.consume').addClass('active');
						$('.unconsume').removeClass('active');
					}else{
						$('.unconsume').addClass('active');
						$('.consume').removeClass('active');
					}
					$('.consume_count').html('已使用 ('+result.consume_count+')');
					$('.unconsume_count').html('未使用 ('+result.unconsume_count+')');
					$('.detail a').bind('click',function(){
						$('#details .details_content h4').html($(this).data('name'));
						$('#details .details_content p').html($(this).data('desc'));
						$('.mask').removeClass('hidden');
						$('#details').show();
					});
					$('.before').click(function(e){
						$(this).hide();
						$('.after').show();
						$('.use').hide();
						$('.share_sub_card').show();
						$(this).html('取消');			
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
					   	e.stopPropagation();
					});

					

					$('.cancel').click(function(e){
						window.location.reload();		
					});
					
					$('.change_num').click(function(e){
							max_select = Number($(this).data('max_select'));
							now_store_id = $(this).data('store_id');
							var me=this;
							var edit_num = $(me).parents('.change').find('span b');
							var val= max_select-1;
							if(val<0){
								layer.open({
									content: '已经领完了'
									,btn: ['我知道了']
								});
								
							}else{
								$.post('{pigcms{:U('ajax_hadpull')}',{store_id:$(me).data('store_id'),share_id:"{pigcms{$_GET['share_id']}",order_id:"{pigcms{$_GET['order_id']}"},function(res){
									if(res.status){
										layer.open({
											content: '领取成功'
											,btn: ['我知道了']
										});
										if(val==0){
											$(me).addClass('active');
										}
										$(me).html('可领取'+val+'次')
										$(me).data('max_select',val)
										$(edit_num).text(Number($(edit_num).text())+1)
										$('#hadpull_num').html(Number($(edit_num).text()))
										$('.bottom').show();
										$(me).parents('.shop').find('.shop_content').addClass('link-url');
										$('.link-url').bind('click',function(){
											window.location.href=$(this).data('url');
										})

										layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'关注公众号后查看详情！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.wechat_qrcode}" style="width:230px;height:230px;"/>',shadeClose:false});

									}else{
										layer.open({
											content: res.info
											,btn: ['我知道了']
										});
									}
								},'json');
								
							}
							// me=null;
							
					});
			
				
				}else{
					alert('未查找到内容！');
				}
			},'json');
		}
	</script>
	{pigcms{$hideScript}
	</body>
</html>