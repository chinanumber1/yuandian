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
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
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
					<span class="ft"><b>￥{pigcms{$sub_card.price}</b><span>/{pigcms{$sub_card.share_num}次</span></span>
					<if condition="$sub_card.effective_days gt 0"><a class="rg" href="javascript:;">购买后{pigcms{$sub_card.effective_days}天内有效</a></if>
				</div>
				<p>来自{pigcms{$share_user.nickname}分享了免单，共{pigcms{$share_info.num}次免单，还有{pigcms{$share_info['hadpull']-$consume_num}次免单</p>
			</div>
			<div class="car_footer after">
				<p class="ft"><i></i><span> <if condition="$sub_card['use_time_type'] eq 1"><if condition="$sub_card.effective_days gt 0 ">{pigcms{$sub_card.effective_days}天后到期<elseif condition="$sub_card.effective_days eq 0" />今天是最后期限<else /> 已过期</if><else />永久有效</if></span></p>
				
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

	<div class="input_box" style="display: none;">
		<input type="number" class="value select_num" name="select_num" value=""  placeholder="请输入分享次数"/>
		<a href="javascript:;">确认</a>
	</div>
	<div id="cover"></div>
	<div id="guide"><img src="{pigcms{$static_path}/images/guide1.png"></div>
	<script id="cardlist" type="text/html">
					
		{{# for(var i = 0, len = d.length; i < len; i++){ }}
			<div class="shop" >
				
				<div class="shop_content link-url" data-url="{pigcms{:U('Sub_card/order_detail_pass')}&order_id={pigcms{$_GET['order_id']}&store_id={{ d[i].store_id }}">
					<ul class="content_left "/>
						<li><img src="{{ d[i].pic_info }}"></li>
						
						{{# if(d[i].appoint==1){ }}<li><i></i></li>{{# } }}
					</ul>
					<ul class="content_right ">
						<li class="after " >

						<b> {{ d[i].name }}</b>{{# if(d[i].effective_days>0  ){ }}<span class="rg">{{ d[i].effective_days }}天后到期</span>{{# }else if(d[i].effective_days==0  ){ }}<span class="rg finish"> 今天是最后期限</span>{{# }else if(d[i].effective_days<0 ){ }}<span class="rg finish">已过期</span>{{# }else if(d[i].num==0){ }}<span class="rg finish">已用完</span> {{# } }} 
						{{# if(d[i].share_num>0){ }}<span class="rg" style="color:#f92503;border: 1px solid #f92503;">已分享{{ d[i].share_num }}次</span>{{# } }} 	
						

						</li>
						<li style="font-size: 0.203389rem;color:#FFB905;width:100%;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">所属商家:{{ d[i].mer_name }}</li>
						<li class="shop_text see detail"><p>{{ d[i].desc }}</p><a href="javascript:;" data-desc="{{# if(!d[i].desc_txt){ }}{{ d[i].desc }}{{# }else{ }}{{ d[i].desc_txt }}{{# } }}" data-name="{{ d[i].name }}">详情</a></li>
						<li class="yi_use"><p style="width: 77%">{{# if(d[i].effective_days<0 ){ }}其他已过期不可用{{# }else{ }}可以使用{{ d[i].hadpull_num }}次免单{{# } }},已使用{{ d[i].consume_num }}次免单  </p></li>
						<li class="after "><span><i></i> {{ d[i].adress }}</span><a class="rg" href="javascript:;">距您{{ d[i].juli }}</a></li>
					</ul>
				</div>
				<!-- <div class="after limit">
					<p class="ft"><i></i>使用有效期: 2017-8-17 至 2017-8-27</p>
					<span class="rg">库存:  0</span>
				</div> -->
				<div class="shop_footer use" >
					<div class="xiang link-url" data-url="{pigcms{:U('Sub_card/order_detail_pass')}&order_id={pigcms{$_GET['order_id']}&store_id={{ d[i].store_id }}">
						<i></i> 享用
					</div>
					<div class="navigation link-url" data-url="{pigcms{$config.site_url}/wap.php?g=Wap&c=Group&a=addressinfo&store_id={{ d[i]['store_id']}}">
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
			$.post("{pigcms{:U('Sub_card/ajax_get_share_order')}",{lat:lat,lng:lng,order_id:"{pigcms{$_GET['order_id']}",share_id:"{pigcms{$share_info.id}"},function(result){
				data = result.store_list;
				count= result.count;
				now_count += data.length;
				total_num = result.unshare_num;
				if(result.unconsume_num>0 && result.unshare_num>0){
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
						$('.link-url').bind('click',function(){
							window.location.href=$(this).data('url');
						})
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
					   		  e.stopPropagation()
					});

					
					$('.cancel').click(function(e){
						window.location.reload();		
					});

					
					$('.change_num').click(function(e){
							$('.mask').removeClass('hidden');
							$('.input_box').show();
							$('.value').val('');
							max_select = Number($(this).data('max_select'));
							can_share_num = Number($(this).data('unshare_num'));
							now_store_id = $(this).data('store_id');
							var me=this;
							var edit_num = $(me).parents('.change').find('span b');
							var select_before_num = $(edit_num).data('select_num');
							if(select_before_num>0){
								$('.value').val(select_before_num)
							}
			
							//输入框弹层确认按钮点击
							$('.input_box').off('click','a').on('click','a',function(e){
								
								var val= Number($('.value').val());

								if(val>select_before_num && (  val>max_select || val+store_id.length>{pigcms{$sub_card.free_total_num})){
									layer.open({
										content: '选择的次数超过限制'
										,btn: ['我知道了']
									  });
									$(me).removeClass('active');
								}else{
									tmp = [];
									for(var c in store_id){
										if(store_id[c]!=now_store_id){
											tmp.push(store_id[c]);
										}else{
											select_num--;
										}
									}
									store_id = tmp;
									for(i=0;i<val;i++){
										store_id.push(now_store_id);
										select_num++;
									}
									var edit_num = $(me).parent('.change').find('span b');
									var sku = $(edit_num).data('sku')
									$(edit_num).text(sku-val)
									 $(me).data('select_num',val);
									if(val==0){
										$(me).text('设置');
										$(me).removeClass('active');
									}else{
										var input_text='已选择'+val+'次';
										$(me).text(input_text);
										$(me).addClass('active');
									}
									
								}
								me=null;
								$('.mask').addClass('hidden');
								$('.input_box').hide();
								$('.bottom b').html(select_num)
								$('.change_num').each(function(i,e){
									var share_num_txt = $(this).parent('.change').find('span .select_txt');
									var tmp=$(this).data('max_select')-$(this).data('select_num')
									console.log(tmp)
									if(tmp>=total_num-select_num){
										$(share_num_txt).text(total_num-select_num)
									}else{
										$(share_num_txt).text(tmp)
									}
								})
							});
						//蒙层点击
						$('.mask').click(function(e){
							$('.mask').addClass('hidden');
							$('.input_box').hide();
						});
						
						$(".select_num").keyup(function(){
							if($(this).val().length==1)    {                        
								$(this).val($(this).val().replace(/[^0-9.]/g,'')); 
							}else{
								$(this).val($(this).val().replace(/\D/g,''));
							}                
							//$(this).val($(this).val().replace(/^[0]*/g,''));
						}).bind("paste",function(){ 
							if($(this).val().length==1)    {                        
								$(this).val($(this).val().replace(/[^0-9.]/g,'')); 
							}else{
								$(this).val($(this).val().replace(/\D/g,''));
							}    
							//$(this).val($(this).val().replace(/^[0]*/g,''));
						})
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