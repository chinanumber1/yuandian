<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>免单套餐-选择店铺</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}sub_card/css/selected.css?21511"/>
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
			
			<div class="car_header">
				<p>{pigcms{$sub_card.price}元{pigcms{$sub_card.free_total_num}次免单，共{pigcms{$sub_card.join_num}个店铺，一共可以选择{pigcms{$sub_card.free_total_num}次</p>
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
	<!--底部固定栏-->
	<div class="bottom">
		已选中<b>0</b>次，去支付	
	</div>
	<!--弹层-->
	<div class="mask close hidden">
		
	</div>
	<div id="details" style="display: none;">
		<div class="details_header">
			<i></i> 查看详情
		</div>
		<div class="details_content">
			<h4>{pigcms{$sub_card.name}</h4>
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
	<div class="input_box" style="display: none;">
		<input type="number" class="value select_num" name="select_num" value=""  placeholder="请输入免单次数"/>
		<a href="javascript:;">确认</a>
	</div>
	<script id="cardlist" type="text/html">
					
		{{# for(var i = 0, len = d.length; i < len; i++){ }}

			<div class="shop">
				<div class="shop_content">
					<ul class="content_left">
						<li><img src=" {{ d[i].pic_info }}"/></li>
						{{# if(d[i].appoint==1){ }}<li><i></i></li>{{# } }}
					</ul>
					<ul class="content_right">
						<li class="after"><b> {{ d[i].name }}</b>{{# if(d[i].status==3  ){ }}<span class="rg finish">已过期</span>{{# }else{ }}<span class="rg">可以使用{{ d[i].mer_free_num }}次免单</span>{{# } }} </li>
						<li class="mer_name" style="font-size: 0.203389rem;color:#FFB905;width:100%;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">所属商家:{{ d[i].mer_name }}</li>
						<li class="shop_text see detail"><p> {{ d[i].desc }}</p><a href="javascript:;" data-desc="{{# if(!d[i].desc_txt){ }}{{ d[i].desc }}{{# }else{ }}{{ d[i].desc_txt }}{{# } }}" data-name="{{ d[i].name }}">详情</a></li>
						<li class="after"><span><i></i> {{ d[i].adress }}</span><a class="rg" href="javascript:;">距您{{ d[i].juli }}</a></li>
					</ul>
				</div>
				 <div class="after limit">
					{{# if(d[i]['start_time']!=''){ }}<p class="ft"><i></i>使用有效期: {{ d[i]['start_time'] }} 至 {{ d[i]['end_time'] }}</p>{{# } }}
					<span class="rg">库存:  {{ d[i].sku_ }}</span>
				</div>
				{{# if(d[i].status==1  ){ }}
				<if condition="$sub_card.mer_free_num eq 1">
					<div class="shop_footer">
						<b class="set select" href="javascript:;" data-store_id="{{ d[i].store_id }}" data-status="{{ d[i].status }}">设为选中</b>
					</div>
				<elseif condition="$sub_card.mer_free_num gt 1" />
					<div class="shop_footer change" style="text-align:right">
						<span>可选择<b class="select_txt" >{{ d[i].sku }}</b>次免单</span>
						<b class="set change_num"  data-sku="{{ d[i].sku }}" data-mer_free_num="{{ d[i].mer_free_num }}" data-store_id="{{ d[i].store_id }}" data-mer_id = "{{ d[i].mer_id }}" data-select_num="0" data-status="{{ d[i].status }}" >设置</b>
					</div>
				</if>
				{{# } }}
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
		var store_id = [],mer_id_select=[],mer_select_arr=[];
		var total_num = Number("{pigcms{$sub_card.free_total_num}");
		var user_mer_max_select = Number("{pigcms{$sub_card.user_mer_max_select}");
	
		$('.see_sub_card a').click(function(e){
			var pic_list_html = $('#sub_pic_list').html();
			
			if(typeof(pic_list_html)!='undefined'){
				$('#pic_list').html(pic_list_html)
				$('#banner_hei').show();
				
			}else{
				$('#banner_hei').hide();
			}
			$('#details .desc_txt').html($('#details .desc_txt').data('desc'))

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
		//选中按钮点击
		
		//前端搜素功能
		$('.search input').keyup(function(e){
			if($(this).val()!=''){
				$('.search b').removeClass('hidden');
				$('.all_shops .shop').removeClass('hidden');
				$('.search a').addClass('active');
			}else{
				$('.search b').addClass('hidden');
				$('.all_shops .shop').removeClass('hidden');
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
						$('.all_shops .shop:eq('+i+')').addClass('hidden');
						$('.no_result').show();
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
						$('.select').click(function(e){
							if($(this).data('status')==3){
								layer.open({
									content: '该店铺已过期不能选择'
									,btn: ['我知道了']
								  });
							}else if($(this).is('.active')){
								$(this).removeClass('active');
								$(this).text('设为选中');
								store_id.splice($.inArray($(this).data('store_id'), store_id), 1);
								select_num--;
								
							}else{
								
								if(select_num+1>total_num){
									layer.open({
										content: '选择的次数超过限制'
										,btn: ['我知道了']
									  });
									
								}else{
									$(this).addClass('active');
									$(this).text('已选中');
									store_id.push($(this).data('store_id'));
									select_num++;
								}
							}

						
							$('.bottom b').html(select_num)
						});
						$('.detail a').bind('click',function(){
							$('#details .details_content h4').html($(this).data('name'));
							$('#details .details_content p').html($(this).data('desc'));
							$('.mask').removeClass('hidden');
							$('#details').show();
						})

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
						//选中效果
						$('.change_num').click(function(e){
							if($(this).attr('disabled')=='disabled'){
								return false;
							}
						
							
							$('.mask').removeClass('hidden');
							$('.input_box').show();
							$('.value').val('');
							max_select = Number($(this).data('sku'));
							now_store_id = $(this).data('store_id');
							var me=this;
							var edit_num = $(me).parents('.change').find('span b');
							var select_before_num = $(me).data('select_num');
							if(select_before_num>0){
								$('.value').val(select_before_num)
							}
			
							//输入框弹层确认按钮点击
							$('.input_box').off('click','a').on('click','a',function(e){
								if($(this).data('status')==3){
									layer.open({
										content: '该店铺已过期不能选择'
										,btn: ['我知道了']
								  	});
								}else{
									var val= Number($('.value').val());
						
									if(typeof(mer_id_select[$(me).data('mer_id')])=='undefined'){
										mer_id_select[$(me).data('mer_id')]= 0;
									}
									

									tmp = [];
									for(var c in store_id){
										if(store_id[c]!=now_store_id){
											tmp.push(store_id[c]);
										}else{
											select_num--;
											if(mer_id_select[$(me).data('mer_id')]>0){
												mer_id_select[$(me).data('mer_id')]--;
												if(mer_id_select[$(me).data('mer_id')]==0){
													mer_select_arr.splice($.inArray($(this).data('mer_id'), mer_select_arr), 1);
												}
											}
										}
									}
									if($.inArray($(me).data('mer_id'),mer_select_arr)==-1){
										mer_select_arr.push($(me).data('mer_id'))
									}
									store_id = tmp;
									if(mer_select_arr.length>user_mer_max_select && user_mer_max_select>0 ){
										layer.open({
											content: '选择的商家超过限制！'
											,btn: ['我知道了']
									  	});
										mer_select_arr.splice($.inArray($(this).data('mer_id'), mer_select_arr), 1);
										$(me).removeClass('active');
									}else if(val>select_before_num && ( val>max_select || val+store_id.length>{pigcms{$sub_card.free_total_num})){
										layer.open({
											content: '选择的次数超过限制'
											,btn: ['我知道了']
										  });
										$(me).removeClass('active');
									}else if(val+mer_id_select[$(me).data('mer_id')]>$(me).data('mer_free_num')){
										layer.open({
											content: '选择的次数超过限制！'
											,btn: ['我知道了']
										  });
										$(me).removeClass('active');
									}else{
										for(i=0;i<val;i++){
											store_id.push(now_store_id);
											select_num++;
										}
										// var edit_num = $(me).parent('.change').find('span b');
										var sku = $(me).data('sku')
										$(edit_num).text(sku-val)
									 	$(me).data('select_num',val);
									 	
									 	mer_id_select[$(me).data('mer_id')] += val;
									 	
									 		console.log(mer_id_select)
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
										var select = $(this).parent('.shop_footer').find('.select_txt');
										var mer_select_num = mer_id_select[$(this).data('mer_id')];
										if(!mer_select_num){
											mer_select_num = 0;
										}
										var can_select = $(this).data('mer_free_num')-mer_select_num;
										console.log(mer_select_arr)
									
										if(mer_select_arr.length>=user_mer_max_select && user_mer_max_select>0 && $.inArray($(this).data('mer_id'), mer_select_arr)==-1 ){
											can_select = 0;
										}
										var sku = $(this).data('sku')-$(this).data('select_num');
										if(can_select==0){
											$(select).text(can_select)
										}else{
											if(can_select<total_num-select_num && sku<=can_select){
										
												$(select).text(sku);
											}else if(can_select<total_num-select_num && sku>can_select){
										
												$(select).text(can_select);
											}else if(can_select>=total_num-select_num && sku>total_num-select_num){
											
												$(select).text(total_num-select_num);
											}else if(can_select>=total_num-select_num && sku<=total_num-select_num){
									
												$(select).text(sku);
											}
										}
										if($(select).text()==0 && $(this).data('select_num')==0){
											$(this).addClass('hui');
											$(this).attr('disabled',true);
										}else{
											$(this).removeClass('hui')
											$(this).removeAttr('disabled');
										}
									})
								}
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


						// $('.input_box a').unbind('click');
						});
					});
				}else{
					alert('未查找到内容！');
				}
			},'json');
		}
		
		$('.bottom').click(function(){
			$.post("{pigcms{:U('Sub_card/sub_card_buy')}",{store_id:store_id,sub_card_id:"{pigcms{$_GET['sub_card_id']}"},function(result){
				if(result.status){
					//layer.open(result.msg);
					window.location.href=result.url;
				}else{
					layer.open({
						content: result.info
						,btn: ['我知道了']
						,yes: function(index){
							if(result.url){
								window.location.href=result.url;
							}
							layer.close(index);
						}
				  	});
				}
			},'json');
			
		})
	</script>
	
</body>
</html>