<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>酒店搜索</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mui.css?2221"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/hotel_search.css?2221"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		
		<script>var public_path ="{pigcms{$static_path}"</script>
	
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
		    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		    <div>
		    	<i></i>
		   		<input type="text" id="search" name="" value="{pigcms{$_GET['search_txt']}" required="" placeholder="输入酒店名、位置等">
		    </div>
		    
		    <a class="mui-pull-right" href="#" id="search_hotel">搜索</a>
		</header>
		<div class="mui-content more_search">
		    <p class="see_my"><b></b> <span>查看我附近的酒店 </span><i class="mui-pull-right"></i></p>
		    <!--搜索历史-->
		    <div class="search_history">
				<if condition="$search_hotel_history">
		    	<div class="mui-card">
		    		<div class="mui-card-header">
		    			<span><i></i> 搜索历史</span>
		    			<p class="clean_history"><b></b> 清空</p>
		    		</div>
		    		<div class="mui-card-content">
		    			<ul>
							<volist name="search_hotel_history" id="vo" offset="0" length='8'>
								<li class="mui-pull-left" data-date="{pigcms{$vo}" data-type='name'>{pigcms{$vo}</li>
							</volist>
		    			</ul>
		    		</div>
		    	</div>
					</if>
		    </div>
		    <!--热门-->
		    <div class="hot">
		    	<div class="mui-card">
		    		<div class="mui-card-header">
		    			<span><i></i> 热门</span>
		    			<if condition="$hot_circle AND count($hot_circle) gt 8"><p><span class="hide">收起 <i></i></span> </p></if>
		    		</div>
					<if condition="$hot_circle">
		    		<div class="mui-card-content">
		    			<ul class="hot_list">
							<volist name="hot_circle" id="vo">
								<li class="mui-pull-left <if condition="$i gt 8">hide_list </if>" data-date="{pigcms{$vo.area_name}" data-type='area'>{pigcms{$vo.area_name}</li>
							</volist>
		    			</ul>
		    		</div>
					</if>
		    	</div>
		    </div>
		    <!--特色主题-->
		    <div class="theme">
		    	<div class="mui-card">
		    		<div class="mui-card-header">
		    			<span><i></i>特色主题</span>
		    			<if condition="$circle_category AND count($circle_category) gt 8"><p><span class="hide">收起 <b></b> </span></p></if>
		    		</div>
					<if condition="$circle_category">
		    		<div class="mui-card-content">
		    			<ul class="theme_list">
							<volist name="circle_category" id="vo">
								<li class="mui-pull-left <if condition="$i gt 8">hide_list </if>" data-date="{pigcms{$vo.name}" data-type='area'>{pigcms{$vo.name}</li>
							</volist>
		    		
		    			</ul>
		    		</div>
					</if>
		    	</div>
		    </div>
		</div>
		<div class="mui-content search_content hidden" id="hotel_result">
		    <div class="search_icon">
		    	<ul class="mui-clearfix  search_name"  style="display:none" data-type="name">
		    		<li class="mui-pull-left"><i></i></li>
		    		<li class="mui-pull-left  hotel_result search_txt"></li>
		    		<li class="mui-pull-right"><span class="hotel_result result_num"></span></li>
		    	</ul> 
		    	<ul class="mui-clearfix  search_area"  style="display:none" data-type="area">
		    		<li class="mui-pull-left"><b></b></li>
		    		<li class="mui-pull-left hotel_result search_txt"></li>
		    		<li class="mui-pull-right"><span class="hotel_result result_num"></span></li>
		    	</ul>
		    </div>
		    
		    <div class="adress_style">
		    
		    </div>
		</div>
	
	<script src="{pigcms{$static_path}js/mui.min.js"></script>

		<script type="text/javascript">
			var search_txt='';
			mui.init();
			//hot点击展开
			mui('.mui-content').on('tap','.hot .show',function(e){
				$(this).addClass('hidden');
				$(this).parent('p').html('<span class="hide">收起 <i></i></span>');
				$('.hot_list .hide_list').removeClass('hidden');
			});
			//hot点击收起
			mui('.mui-content').on('tap','.hot .hide',function(e){
				$(this).addClass('hidden');
				$(this).parent('p').html('<span class="show">展开 <b></b> </span> ');
				$('.hot_list .hide_list').addClass('hidden');
			});
			//theme点击展开
			mui('.mui-content').on('tap','.theme .show',function(e){
				$(this).addClass('hidden');
				$(this).parent('p').html('<span class="hide">收起 <i></i></span>');
				$('.theme_list .hide_list').removeClass('hidden');
			});
			//theme点击收起
			mui('.mui-content').on('tap','.theme .hide',function(e){
				$(this).addClass('hidden');
				$(this).parent('p').html('<span class="show">展开 <b></b> </span> ');
				$('.theme_list .hide_list').addClass('hidden');
			});
			
			//点击input
			mui('.search_icon').on('tap','.mui-clearfix',function(e){
				window.location.href="{pigcms{:U('Hotel/index')}&search_txt="+search_txt+"&type="+$(this).data('type');
			});
			
			mui('.mui-card-content').on('tap','.mui-pull-left',function(e){
				window.location.href="{pigcms{:U('Hotel/index')}&search_txt="+$(this).data('date')+"&type="+$(this).data('type');
			});
			mui('.adress_style').on('tap','.mui-clearfix',function(e){
				if(typeof($(this).data('url'))!='undefined'){
					window.location.href=$(this).data('url')
				}
			});
			mui('.mui-content').on('tap','.clean_history',function(e){
				mui.post('{pigcms{:U('del_search_history')}',{
					},function(data){
						window.location.reload();
					},'json'
				);
			});
			var loadHotelTimer= null;
			$(function(){
				
				$("#search").bind('input', function(e){
					 search_txt = $.trim($(this).val());
					if(search_txt.length > 0){
						$('.search_content').removeClass('hidden');
						$('.more_search').addClass('hidden');
						
						clearTimeout(loadHotelTimer);
						loadHotelTimer = setTimeout("searchHotel('"+search_txt+"')", 500);
						
					}else{
						$('.more_search').removeClass('hidden');
						$('.search_content').addClass('hidden');
						//window.location.href="{pigcms{:U('Hotel/hotel_search')}";
					}
				});

				$('.see_my').click(function(event){
					window.location.href="{pigcms{:U('Hotel/index')}";
				})
				
				
				$('#search_hotel').click(function(){
					$('.search_content').addClass('hidden');
					$('.more_search').addClass('hidden');
					searchHotel($.trim($("#search").val()))
				})
				
			
			})
			
		
			
			function searchHotel(search_txt){
				$.get('{pigcms{:U('ajax_search_hotel')}', {query:search_txt}, function(data){
					if(data.status == 1){
						$('.hotel_result').empty();
						data = data.info;
						
						var name_result = data.name;
						var area_result = data.area;
						var hotel_address = data.hotel_address;
						var addressHtml = '';
						var result_null = true;
						
						if( name_result.hotel_count>0){
							$('.search_name').show();
							$('.search_name .mui-pull-left').attr('data-date',search_txt);
							$('.search_name .search_txt').html(search_txt);
							$('.search_name .result_num').html('约'+name_result.hotel_count+'个结果');
							result_null = false;
						}else{
							$('.search_name').hide();
						}
						
						if(area_result && area_result.length>0){
							$('.search_area').show();
							$('.search_area .mui-pull-left').attr('data-date',search_txt);
							$('.search_area .search_txt').html(search_txt);
							$('.search_area .result_num').html('约'+area_result.length+'个结果');
							result_null = false;
						}else{
							$('.search_area').hide();
						}
						var html = ''
						if(hotel_address){
							$.each(hotel_address,function(index,val){
								html+='<ul class="mui-clearfix link-url" data-url="'+val.url+'">';
								html+='	<li class="mui-pull-left"><b></b></li>';
								html+='	<li class="mui-pull-left">';
								html+='		<ul class="address">';
								html+='			<li class="hotel_result hotel_name">'+val.group_name+'</li>';
								html+='			<li class="hotel_result hotel_address">'+val.merchant_name+'</li>';
								html+='		</ul>';
								html+='	</li>';
								if(typeof(val.juli_txt)!='undefined'){
									html+='	<li class="mui-pull-right hotel_result juli"><span>'+val.juli_txt+'</span></li>';
								}
								html+='</ul>';
							})
							result_null = false;
						}
						if(result_null){
							html+='<ul class="mui-clearfix link-url" >';
							html+='	<li class="mui-pull-left"></li>';
							html+='	<li class="mui-pull-left" style="width:100%">';
							html+='		<ul class="address">';
							html+='			<li class="hotel_result hotel_name" style="text-align:center">没有结果</li>';
							html+='		</ul>';
							html+='	</li>';
							html+='</ul>';
						}
						
						
						$('.adress_style').html(html);
						$('.search_content').removeClass('hidden');
					}else{
						//$('.search_content').addClass('hidden');
						mui.alert(data.info,'确定',function(){
							// window.location.href="{pigcms{:U('Hotel/hotel_search')}";
							$('.more_search').removeClass('hidden');
						})
					}
				});
			}
			
		</script>
	</body>
</html>