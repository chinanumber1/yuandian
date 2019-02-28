<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>酒店列表</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mui.css?2221"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/hotel_list.css?2221"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		
		<script>
			var public_path ="{pigcms{$static_path}";var location_url = "{pigcms{:U('Hotel/ajaxList')}";
			var now_cat_url="<if condition="!empty($now_cat_url)">{pigcms{$now_cat_url}<else/>-1</if>";
			var now_area_url="<if condition="!empty($now_area_url) && $all_area_list">{pigcms{$now_area_url}<else/>-1</if>";
			var now_sort_id="<if condition="!empty($now_sort_array) AND $config.open_default_sort eq 0">{pigcms{$now_sort_array.sort_id}<else/>defaults</if>";
			<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
			var search_type = '{pigcms{$_GET['type']}';
			var search_txt = '{pigcms{$_GET['search_txt']}';
		</script>
		
			<script type="text/javascript" src="{pigcms{$static_path}js/dropdown.js?210" charset="utf-8"></script>
		<style>
			.shade {
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				width: 100%;
				height: 100%;
				background: rgba(0,0,0,.7);
				z-index: 101;
			}
			.mui-scroll-wrapper {
			    position: absolute;
			    z-index: 2;
			    top: 120px;
			    bottom: 0;
			    left: 0;
			    overflow: hidden;
			    width: 100%;
			}
			.mui-bar-nav~.mui-content .mui-pull-top-pocket {
			    top: 0;
			}
			
			#pageLoadTip{
				background-color: white;
				position: absolute;
				z-index: 9999;
				top: 0px;
				bottom: 0px;
				left: 0;
				width: 100%;
				overflow: hidden;
			}
			#pageLoadTip div{
				height:50px;
				line-height:50px;
				text-align:center;
				padding-top:50px;
				background: url({pigcms{$static_path}/img/loading.gif) no-repeat;
				background-position: center top;
				background-size:50px;
			}

		</style>
	</head>
	<body>

		<header class="mui-bar mui-bar-nav" style="z-index:102;">
		    <a class=" mui-pull-left back"><i></i> </a>
		    <h1 class="mui-title" id="now_area_name">{pigcms{$config.now_select_city.area_name}<b></b></h1>
		    <span class="mui-pull-right" id="hotel_around_c">附近酒店</span>
		</header>
		<div class="mui-content"  style="z-index:102;">
		
			 <div class="map_change" style="z-index:102;">
		    	<ul class="dep_end">
		    		<li class="ru"></li>
		    		<li class="li"></li>
		    	</ul>
		    	<!--<span>入 06-19 </span>-->
		    	<i></i>
		    	<b></b>
		    	<input type="text" name="search_txt" id="search_txt" required="" value="{pigcms{$_GET['search_txt']}" placeholder="酒店名称/商圈/地标等"  x-webkit-grammar="builtin:search" lang="zh-CN" readonly>
		    	<sub id="hotel_in_map"></sub>
		    </div>
		    <!--筛选条件-->
		    <section class="navBox pageSliderHide " style="z-index:102;width:100%;">
			
			<ul>
				<li class="dropdown-toggle caret category" data-nav="category">
					<span class="nav-head-name"><if condition="$now_category">{pigcms{$now_category.cat_name}<else/>酒店</if></span>
				</li>
				<li class="dropdown-toggle caret biz subway" data-nav="biz">
					<span class="nav-head-name"><if condition="$now_area">{pigcms{$now_area.area_name}<else/>全城</if></span>
				</li>
				<li class="dropdown-toggle caret sort" data-nav="sort">
					<span class="nav-head-name">{pigcms{$now_sort_array.sort_value}</span>
				</li>
			</ul>
			<div class="dropdown-wrapper">
				<div class="dropdown-module">
					<div class="scroller-wrapper">
						<div id="dropdown_scroller" class="dropdown-scroller" style="overflow:hidden;">
							<div>
								<ul>
									<li class="category-wrapper">
										<ul class="dropdown-list">
											
											<volist name="all_category_list" id="vo">
												<li data-category-id="{pigcms{$vo.cat_url}" <if condition="$vo['cat_count'] gt 1">data-has-sub="true"<else/>onclick="list_location($(this));return false;"</if> class="<if condition="$vo['cat_count'] gt 1">right-arrow-point-right</if> <if condition="$top_category['cat_url'] eq $vo['cat_url']">active</if>">
													<span data-name="{pigcms{$vo.cat_name}">{pigcms{$vo.cat_name}</span>
													<if condition="$vo['cat_count'] gt 1"><span class="quantity"><b></b></span></if>
													<div class="sub_cat hide" style="display:none;">
														<if condition="$vo['cat_count'] gt 1">
															<ul class="dropdown-list sub-list">
																<li data-category-id="{pigcms{$vo.cat_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$vo.cat_name}">全部</span></div></li>
																<volist name="vo['category_list']" id="voo" key="j">
																	<li data-category-id="{pigcms{$voo.cat_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$voo.cat_name}">{pigcms{$voo.cat_name}</span></div></li>
																</volist>
															</ul>
														</if>
													</div>
												</li>
											</volist>
										</ul>
									</li>
									<if condition="$all_area_list">
										<li class="biz-wrapper">
											<ul class="dropdown-list">
												<li data-area-id="-1" <if condition="empty($now_area_url)">class="active"</if> onclick="list_location($(this));return false;"><span data-name="全城">全城</span></li>
												<volist name="all_area_list" id="vo">
													<li data-area-id="{pigcms{$vo.area_url}" <if condition="$vo['area_count'] gt 0">data-has-sub="true"<else/>onclick="list_location($(this));return false;"</if> class="<if condition="$vo['area_count'] gt 0">right-arrow-point-right</if> <if condition="$top_area['area_url'] eq $vo['area_url']">active</if>">
														<span>{pigcms{$vo.area_name}</span>
														<if condition="$vo['area_count'] gt 0"><span class="quantity"><b></b></span></if>
														<div class="sub_cat hide" style="display:none;">
															<if condition="$vo['area_count'] gt 0">
																<ul class="dropdown-list sub-list">
																	<li data-area-id="{pigcms{$vo.area_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$vo.area_name}">全部</span></div></li>
																	<volist name="vo['area_list']" id="voo" key="j">
																		<li data-area-id="{pigcms{$voo.area_url}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{pigcms{$voo.area_name}">{pigcms{$voo.area_name}</span></div></li>
																	</volist>
																</ul>
															</if>
														</div>
													</li>
												</volist>
											</ul>
										</li>
									</if>
									<li class="sort-wrapper">
										<ul class="dropdown-list">
											<volist name="sort_array" id="vo">
												<li data-sort-id="{pigcms{$vo.sort_id}" <if condition="$vo['sort_id'] eq $now_sort_array['sort_id']">class="active"</if> onclick="list_location($(this));return false;"><span data-name="{pigcms{$vo.sort_value}">{pigcms{$vo.sort_value}</span></li>
											</volist>
										</ul>
									</li>
								</ul>
							</div>
						</div>
						<div id="dropdown_sub_scroller" class="dropdown-sub-scroller"><div></div></div>
					</div>
				</div>
			</div>
		</section>
		
	
		
			
			<!--附近酒店推荐-->
			<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
				<div class="mui-scroll">
							<div class="hearby_hotel">
								<div class="mui-card Hotel_list" id="addddd">
									
									
								</div>
								<p style="text-align: center;margin-top: 35px;display:none;" id="no_result"> 暂无搜索结果</p>
							</div>
					
				</div>
			</div>	
		
		<div id="J_Calendar" class="calendar" style="display:none;">
			<header class="mui-bar mui-bar-nav">
				<a id="close_yui" class=" mui-icon mui-icon-left-nav mui-pull-left" style="    margin-top: 9px;  padding-top: 0px;"></a>
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
		<section class="storeListBox listBox">
			<div class="shade hide"></div>
		
		</section>
		
		<script src="{pigcms{$static_path}js/mui.min.js"></script>
		<script src="https://cdn.bootcss.com/yui/3.18.1/yui/yui.js"></script>
		<script id="groupListBoxTpl" type="text/html">
			{{# for(var i = 0, len = d.group_list.length; i < len; i++){ }}
				
				
				<div class="mui-card-content link-url"  data-url="{{ d.group_list[i].url }}">
					<div class="mui-row"  >
						<div class="mui-col-sm-4">
							<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.group_list[i].list_pic) }}" alt="{{ d.group_list[i].s_name }}"/>
						</div>
						<div class="mui-col-sm-8">
							<ul>
								<li class="hotel_hidden">{{ d.group_list[i].group_name }}</li>
								<li class="distance">{{ d.group_list[i].juli_txt }}</li>
								<li class="score"><b>{{ d.group_list[i].score_mean }}分</b> {{ d.group_list[i].reply_count }}条评论</li>
								<li class="hotel_icon">{{# if(d.group_list[i].is_refund){ }}<i></i>{{# } }} {{# if(d.group_list[i].discount){ }}<sub></sub>{{# } }} <span class="mui-pull-right"><b>￥{{ d.group_list[i].price }}</b>起</span></li>
							</ul>
						</div>
					</div>
				</div>
			{{# } }}
		</script>
		

		<script type="text/javascript">
			var now_area_name ;
			var myScroll,myScroll2=null,myScroll3=null,now_page = 0,hasMorePage = true,isLoading = true;
		
			mui.init()

			mui.init({
				pullRefresh: {
					container: '#pullrefresh',
					down: {
						callback: pulldownRefresh
					},
					up: {
						contentrefresh: '正在加载...',
						callback: pullupRefresh
					}
				}
			});
			function pulldownRefresh() {
				setTimeout(function() {
					var table = document.body.querySelector('.mui-card');
					var cells = document.body.querySelectorAll('.mui-card-content');
					now_page = 0,
					getList(false);
					mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
				}, 1500);
			}
			var count = 0;
			/**
			 * 上拉加载具体业务实现
			 */
			function pullupRefresh() {
				setTimeout(function() {
					mui('#pullrefresh').pullRefresh().endPullupToRefresh((++count > 2)); //参数为true代表没有更多数据了。
					if(hasMorePage){
						getList(true);
					}
				}, 1500);
			}
			
			mui('#pullrefresh').on('tap','.mui-card-content',function(e){
				mui.openWindow(
					{
						url:$(this).data('url'),
						id:'index'
					}
				);
			});
		
			
			
			(function (doc, win) {
			  var docEl = doc.documentElement,
				resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
				recalc = function () {
				  var clientWidth = docEl.clientWidth;
				  if (!clientWidth) return;
				  docEl.style.fontSize = 20 * (clientWidth / 320) + 'px';
				};
			 
			  if (!doc.addEventListener) return;
			  win.addEventListener(resizeEvt, recalc, false);
			  doc.addEventListener('DOMContentLoaded', recalc, false);
			
			
		})(document, window);
			

		$(function(){
			//getList(false);
	
			pageLoadTip(92);
			if(user_long == '0'){
				//getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
				getUserLocation({okFunction:'geoconvPlace',useHistory:false});
				
				console.log(111)
			}else{
				pageGetList(user_long,user_lat);
			}
			
			$('#hotel_in_map').click(function(){
				window.location.href="{pigcms{:U('Hotel/hotel_around')}";
			})	
			
			$('#search_txt').click(function(){
				window.location.href="{pigcms{:U('Hotel/hotel_search')}&search_txt="+$(this).val();
			})
			$('#hotel_around_c').click(function(){
				window.location.href="{pigcms{:U('Hotel/hotel_list')}&type=around";
			})
			
			$('.back').click(function(){
				window.history.go(-1);  
			})
			
			$('.dep_end').click(function(){
				$('#J_Calendar').show();
			})
		
			$('#now_area_name').click(function(){
				window.location.href='./wap.php?g=Wap&c=Changecity&a=index&hotel=1';
			});
			
			
			now_area_name = $.cookie('userLocationName');
			<if condition="$_GET['type'] eq 'around'">
				$('#now_area_name').html(now_area_name+'<b></b>')
			</if>
			
			//============日历开始==============
			
			YUI({
				modules: {
					'price-calendar': {
						fullpath: public_path+'/trip-calendar/price-calendar.js',
						type    : 'js',
						requires: ['price-calendar-css']
					},
					'price-calendar-css': {
						fullpath: public_path+'/trip-calendar/price-calendar.css',
						type    : 'css'
					}
				}
			}).use('price-calendar', function(Y) {
				
				/**
				 * 非弹出式日历实例
				 * 直接将日历插入到页面指定容器内
				 */
				 
				 if($.cookie('dep_date')!=''){
					var depDate_ =$.cookie('dep_date');
					var endDate_ = $.cookie('end_date');
				 }else{
					var now = new Date();
					var tomorrow = new Date();  
					//设置第二天
					tomorrow.setDate(tomorrow.getDate()+1)
					var depDate_ = now.getFullYear() + '-' + Appendzero(now.getMonth() + 1) + '-' + Appendzero(now.getDate());
					var endDate_ = tomorrow.getFullYear() + '-' + Appendzero(tomorrow.getMonth() + 1) + '-' + Appendzero(tomorrow.getDate()); 
				 }
				
				oCal = new Y.PriceCalendar({
					container   : '#J_Calendar' //非弹出式日历时指定的容器（必选）
					// ,selectedDate: new Date       //指定日历选择的日期
					,count		: 3
					,afterDays	: 180
					,depDate	: depDate_
					,endDate	: endDate_
				});
				changeTime();
				$('.price-calendar-bounding-box table td').click(function(){
					if($(this).hasClass('disabled')){
						return false;
					}else{
						if(($('.dep-date').size() > 0 && $('.end-date').size() > 0) || ($('.dep-date').size() == 0 && $('.end-date').size() == 0)){
							$('.dep-date').find('.mark').empty();
							$('.dep-date').removeClass('dep-date');
							$('.end-date').find('.mark').empty();
							$('.end-date').removeClass('end-date');
							oCal.set('endDate','');
							
							$('.selected-range').removeClass('selected-range');
							
							oCal.set('depDate',$(this).data('date'));
							$(this).addClass('dep-date').find('.mark').html('入住');
						}else if(oCal.get('depDate')){
							var nowTmpdate = $(this).data('date').replace(/-/g,'');
							var prevTmpdate = oCal.get('depDate').replace(/-/g,'');
						
							if(nowTmpdate < prevTmpdate){
								$('.dep-date').find('.mark').empty();
								$('.dep-date').removeClass('dep-date');
								oCal.set('depDate',$(this).data('date'));
								$(this).addClass('dep-date').find('.mark').html('入住');
							}else{
								var tmp_dep_data = $(this).attr('class');
								if(tmp_dep_data=='dep-date'){
									mui.alert('不能选同一天'); 
								}else{
									oCal.set('endDate',$(this).data('date'));
									
									var depTmpdate = parseInt(oCal.get('depDate').replace(/-/g,''));
									var endTmpdate = parseInt(oCal.get('endDate').replace(/-/g,''));
									$(this).addClass('end-date').find('.mark').html('离店');
									for(var i = depTmpdate+1;i<endTmpdate;i++){
										var tmpI = i.toString();
										var tmpDate = tmpI.substr(0,4)+'-'+tmpI.substr(4,2)+'-'+tmpI.substr(6,2);
										$('td[data-date="'+tmpDate+'"]').addClass('selected-range');
									}
							
									setTimeout(function(){
										changeTime()
									},300);
								}
							}
						}
					}
				});
			});
		});
		function geoconvPlace(userLongLat,lng,lat){
			geoconv('getStoreListBefore',lng,lat);
			geocoder('showlocation',lng,lat)
		}
		
		function showlocation(obj){
			if(obj.result.pois.length > 0){
				$.cookie('userLocationName',obj.result.pois[0].name,{expires:700,path:'/'});
				
			}else{
				$.cookie('userLocationName',obj.result.addressComponent.street,{expires:700,path:'/'});
			}
			now_city_name = obj.result.addressComponent.city;
			
			if(now_select_city_name==now_city_name.replace('市','')){
				$('.now_city').html($.cookie('userLocationName'))
			}
		}

		function getStoreListBefore(result){
			lng = result.result[0].x;
			lat = result.result[0].y;
			getList(false);
		}
		function pageGetList(result){
			getList(false);
		}
			
		function list_location(obj){
			close_dropdown();
			now_page = 0;
			if(obj.attr('data-category-id')){
				obj.addClass('red');
				$('.dropdown-toggle.category .nav-head-name').html(obj.find('span').data('name'));
				now_cat_url = obj.attr('data-category-id');
			}else if(obj.attr('data-area-id')){
				$('.dropdown-toggle.biz .nav-head-name').html(obj.find('span').data('name'));
				now_area_url = obj.attr('data-area-id');
			}else if(obj.attr('data-sort-id')){
				obj.addClass('active').siblings('li').removeClass('active');
				$('.dropdown-toggle.sort .nav-head-name').html(obj.find('span').data('name'));
				now_sort_id = obj.attr('data-sort-id');
			}
			$('.Hotel_list').empty().hide();
			pageLoadTip(11);
			getList(false);
		}

		function getList(more){
			isLoading = true;
			var go_url = location_url;
			if(now_cat_url != '-1'){
				go_url += "&cat_url="+now_cat_url;
			}
			if(now_area_url != '-1'){
				go_url += "&area_url="+now_area_url;
			}
			if(now_sort_id != 'defaults'){
				go_url += "&sort_id="+now_sort_id;
			}
			if($('#search_txt').val() != ''){
				go_url += "&search_txt="+$('#search_txt').val();
			}
			if(search_type){
				go_url += "&type="+search_type;
			}
			now_page += 1;
			go_url += "&page="+now_page;
			$.post(go_url,function(result){
				if(result.group_count>0){
					$('#no_result').hide();
					hasMorePage = now_page < result.totalPage ? true : false;
					laytpl($('#groupListBoxTpl').html()).render(result, function(html){
						if(more){
							$('.loadMoreList').remove();
							$('.Hotel_list').append(html);
						}else{
							$('.Hotel_list').html(html).addClass('dealcard').show();
						}
					});
				}else{
					$('#no_result').show();
				}
				pageLoadTipHide();
				isLoading = false;
			},'json');
		}

		function Appendzero(obj)  
		{  
			if(obj<10) return "0" +""+ obj;  
			else return obj;  
		} 
		
		function changeTime(){
			var dep_time = $('.dep-date').data('date');
			var end_time = $('.end-date').data('date');
			var aDate_start  = dep_time.split("-")  
			$('.ru').html('入 '+aDate_start[1]+'-'+aDate_start[2])
			var aDate_end  =  end_time.split("-")  
			$('.li').html('离 '+aDate_end[1]+'-'+aDate_end[2])
			$.cookie('dep_time',aDate_start[0]+aDate_start[1]+aDate_start[2])
			$.cookie('end_time',aDate_end[0]+aDate_end[1]+aDate_end[2])
			$.cookie('dep_date',dep_time)
			$.cookie('end_date',end_time)
		
			$('#J_Calendar').hide();
		}
		
		$('.shade').css('height','100%')
		$('body').off('click','#close_yui').on('click','#close_yui',function(){
	
			$('#J_Calendar').hide();
		})
		</script>
	</body>
</html>