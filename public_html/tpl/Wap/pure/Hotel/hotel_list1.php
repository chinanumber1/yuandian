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
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/hotel_list.css?2221"/>
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
		</script>
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
		</style>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav" style="z-index:102">
		    <a class=" mui-pull-left back" ><i></i> 返回</a>
		    <h1 class="mui-title">{pigcms{$config.now_select_city.area_name}<b></b></h1>
		    <span class="mui-pull-right">附近酒店</span>
		</header>
		<div class="mui-content"  style="z-index:102;">
			
		    <div class="map_change"  >
		    	<ul class="dep_end">
		    		<li class="ru"></li>
		    		<li class="li"></li>
		    	</ul>
		    
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
		
		<!--div class="hearby_hotel" >
			<div class="mui-scroll-wap" id="pullrefresh">
				<div class="mui-scroll">
				
					<div class="mui-card " >
						<!--div class="mui-card-content">
							<div class="mui-row">
								<div class="mui-col-sm-4">
									<img src="imanges/1-酒店团购_14.png"/>
								</div>
								<div class="mui-col-sm-8">
									<ul>
										<li class="hotel_hidden">桔子酒店-精选 (合肥万达...</li>
										<li class="distance">距您>100m-之心城</li>
										<li class="score"><b>4.2分</b> 15684条评论</li>
										<li class="hotel_icon"><i></i> <sub></sub> <span class="mui-pull-right"><b>￥137</b>起</span></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		
			</div>
		</div-->
		
			<!--附近酒店推荐-->
			<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
				<div class="mui-scroll">
						<div class="hearby_hotel">
							<div class="mui-card Hotel_list" id="addddd">
								<!--div class="mui-card-content">
									<div class="mui-row">
										<div class="mui-col-sm-4">
											<img src="imanges/1-酒店团购_14.png"/>
										</div>
										<div class="mui-col-sm-8">
											<ul>
												<li class="hotel_hidden">桔子酒店-精选 (合肥万达...</li>
												<li class="distance">距您>100m-之心城</li>
												<li class="score"><b>4.2分</b> 15684条评论</li>
												<li class="hotel_icon"><i></i> <sub></sub> <span class="mui-pull-right"><b>￥137</b>起</span></li>
											</ul>
										</div>
									</div>
								</div-->
								
							</div>
						</div>
					
				</div>
			</div>	
		
		<div id="J_Calendar" class="calendar" style="display:none;">
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
			<dl></dl>
			<div class="shade hide"></div>
		
		</section>
		
		<script src="{pigcms{$static_path}js/mui.min.js"></script>
		<script src="https://cdn.bootcss.com/yui/3.18.1/yui/yui.js"></script>
		<script id="groupListBoxTpl" type="text/html">
			{{# for(var i = 0, len = d.group_list.length; i < len; i++){ }}
				
				
				<div class="mui-card-content link-url"  data-url="{{ d.group_list[i].url }}">
					<div class="mui-row">
						<div class="mui-col-sm-4">
							<img "{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.group_list[i].list_pic) }}" alt="{{ d.group_list[i].s_name }}"/>
						</div>
						<div class="mui-col-sm-8">
							<ul>
								<li class="hotel_hidden">{{ d.group_list[i].s_name }}</li>
								<li class="distance">{{ d.group_list[i].juli_txt }}</li>
								<li class="score"><b>{{ d.group_list[i].score_count }}分</b> {{ d.group_list[i].reply_count }}条评论</li>
								<li class="hotel_icon"><i></i> <sub></sub> <span class="mui-pull-right"><b>￥{{ d.group_list[i].price }}</b>起</span></li>
							</ul>
						</div>
					</div>
				</div>
			{{# } }}
		</script>
		

		<script type="text/javascript">
		var myScroll,myScroll2=null,myScroll3=null,now_page = 0,hasMorePage = true,isLoading = true;
		options = {
			scrollY: true, //是否竖向滚动
			scrollX: false, //是否横向滚动
			startX: 0, //初始化时滚动至x
			startY: 0, //初始化时滚动至y
			indicators: true, //是否显示滚动条
			deceleration:0.0006, //阻尼系数,系数越小滑动越灵敏
			bounce: true //是否启用回弹
		}
			mui.init({
				pullRefresh: {
					container: '#pullrefresh',
					down: {
						style:'circle',
						color:'#2BD009', 
						callback: pulldownRefresh
					},
					up: {
						contentrefresh: '正在加载...',
						callback: pullupRefresh
					}
				}
			});
			/**
			 * 下拉刷新具体业务实现
			 */
			function pulldownRefresh() {
				console.log(1)
				setTimeout(function() {
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
				console.log(2)
					mui('#pullrefresh').pullRefresh().endPullupToRefresh((++count > 2)); //参数为true代表没有更多数据了。
					getList(true);
				}, 1500);
			}
			mui('.mui-scroll-wap').scroll({
				deceleration: 0.0003 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
			});
			// mui('.mui-bar-nav').on('tap','a.mui-pull-left',function(e){
				// mui.openWindow(
					// {
						// url:'index.html',
						// id:'index'
					// }
				// );
			// });
			
			
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
			
			//分类菜单
			// var isLoading = true;
			$('.dropdown-toggle').click(function(){
				if($(this).hasClass('active')){
					close_dropdown();
					return false;
				}
				if(isLoading == true){
					return false;
				}
		

				
				$(".shade").css("height", $(window).height() - 41);
				$('.listBox .shade').removeClass('hide');
				
				$(this).addClass('active');
				var nav = $(this).attr('data-nav');

				$('.dropdown-wrapper').addClass(nav+' active');
				$('.'+nav+'-wrapper').addClass('active');
				if($('#dropdown_scroller').height() < ($(window).height() - 97)*0.5){
					$('#dropdown_scroller,.dropdown-module').height(($(window).height() - 97)*0.5);
					// $('#dropdown_scroller,.dropdown-module').height($('#dropdown_scroller div').height());
				}else if($('#dropdown_scroller').height() < ($(window).height() - 97)*0.8){
					$('#dropdown_scroller,.dropdown-module').height($('#dropdown_scroller').height());
				}else{
					$('#dropdown_scroller,.dropdown-module').height(($(window).height() - 97)*0.8);
					myScroll3 = new IScroll('#dropdown_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
				}
				if($('.listBox').height() < $('#dropdown_scroller').height()){
					$('.listBox .shade').height($('#scroller').height()+'px');
				}else{
					$('.listBox .shade').removeAttr('style');
				}
				
				if($('.'+nav+'-wrapper').find('.active').attr('data-has-sub')){
					$('#dropdown_sub_scroller').html('<div>'+$('.'+nav+'-wrapper').find('.active').find('.sub_cat').html()+'<div>').css('left','160px');
					$('#dropdown_scroller').width('160px');
					if($('#dropdown_sub_scroller>div').height() > $('#dropdown_sub_scroller').height()){
						myScroll2 = new IScroll('#dropdown_sub_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
					}
				}
				myScroll2 = new IScroll('#dropdown_sub_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
			});
			
			$('.listBox .shade').click(function(){
				close_dropdown();
			});
			
			$('.biz-wrapper ul>li, .category-wrapper ul>li').click(function(){
				$('#dropdown_sub_scroller').css({'overflow':'hide','overflow-y':''});
				$('.biz-wrapper ul>li, .category-wrapper ul>li').removeClass('active');	
				if($(this).attr('data-has-sub')){
					$(this).addClass('active');
					$('#dropdown_sub_scroller').html('<div>'+$(this).find('.sub_cat').html()+'<div>').css('left','160px');
					$('#dropdown_scroller').width('160px');
					if($('#dropdown_sub_scroller>div').height() > $('#dropdown_sub_scroller').height()){
						myScroll2 = new IScroll('#dropdown_sub_scroller', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick()});
					}
					// myScroll2.on('scrollStart', function (e) { console.log(e); });  
					// myScroll2.on("scroll",function(e){console.log(e);});
				}
			});
		})(document, window);
			

		$(function(){
			getList(false);
			
			//$('#scroller').css({'min-height':($(window).height()-98+1)+'px'});
			// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
			// myScroll = new IScroll('#container', 
				// { 	
					// probeType: 3,
					// isableMouse:true,
					// disablePointer:true,
					// mouseWheel: false,
					// scrollX: false,
					// scrollY:true,
					// click:iScrollClick(),
					// scrollbars:false,
					// useTransform:false,
					// useTransition:false
				// }
			// );
			// var upIcon = $("#pullUp"),
				// downIcon = $("#pullDown");
				// isPulled =false;
			// myScroll.on("scroll",function(e){
				// var maxY = this.maxScrollY - this.y;
				// console.log(this.y);
				// if(this.y >= 50){
					// if(!downIcon.hasClass("reverse_icon")) downIcon.addClass("reverse_icon").find('.pullDownLabel').html('松开可以刷新');
					// return "";
					
				// }else if(this.y < 50 && this.y > 0){
					// if(downIcon.hasClass("reverse_icon")) downIcon.removeClass("reverse_icon").find('.pullDownLabel').html('下拉可以刷新');
					// return "";
				// }
				// if(maxY >= 50){
					// if(!upIcon.hasClass("reverse_icon")) upIcon.addClass("reverse_icon").find('.pullUpLabel').html('松开加载更多');
					// return "";
				// }else if(maxY < 50 && maxY >=0){
					// if(upIcon.hasClass("reverse_icon")) upIcon.removeClass("reverse_icon").find('.pullUpLabel').html('上拉加载更多');
					// return "";
				// }
			// });
			// myScroll.on("slideDown",function(){
				// if(this.y > 50){
					// now_page = 0;
					// hasMorePage = true;
					// upIcon.removeClass('noMore loading').show();
					// pageLoadTip(92);
					// if(now_sort_id == 'juli'){
						// getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
					// }else{
						// pageGetList(false);
					// }
				// }
			// });
			/*myScroll.on("slideUp",function(){
				if(hasMorePage){
					$('.listBox dl').append('<dd class="loadMoreList">正在加载</dd>');
					// upIcon.addClass('loading');
					// setTimeout(function(){
						myScroll.refresh();
						myScroll.scrollTo(0,this.maxScrollY);
						getList(true);
					// },200);
				}
			
			});*/
		var scroll_height = $(window).height();
		$('.mui-scroll').css('height',scroll_height)
		
			$('#search-form').submit(function(){
				var keyword = $.trim($('#keyword').val());
				$('#keyword').val(keyword);
				if(keyword.length == 0){
					layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'请输入搜索词！',btn: ['确定']});
					return false;
				}
				var searchHistory = $.cookie('searchHistory');
				if(searchHistory){
					searchArr = searchHistory.split('~^%@$$@%^~');
					var newSearchArr = [];
					for(var i in searchArr){
						if(searchArr[i] != keyword) newSearchArr.push(searchArr[i]);
					}
					newSearchArr.unshift(keyword);
					newSearchHistory = newSearchArr.join('~^%@$$@%^~');
				}else{
					newSearchHistory = keyword;
				}
				$.cookie('searchHistory',newSearchHistory,{expires:730,path:'/'});
				window.addEventListener("pagehide", function(){
					$('#keyword').val('');
				},false);
			});
			$(document).on('click','.listBox li.more',function(){
				$(this).hide().siblings('li').show();
				$(this).prev().css({'border-bottom':'none'});
				// setTimeout(function(){
					myScroll.refresh();
				// },200);
			});

			// $('.listBox').css('min-height',$(window).height()-95);
			pageLoadTip(92);
			// if(user_long == '0'){
				// getUserLocation({okFunction:'pageGetList',okFunctionParam:[true],errorFunction:'pageGetList',errorFunctionParam:[false]});
			// }else{
				// pageGetList(user_long,user_lat);
			// }
			
			$('#hotel_in_map').click(function(){
				window.location.href="{pigcms{:U('Hotel/hotel_around')}";
			})	
			
			$('#search_txt').click(function(){
				window.location.href="{pigcms{:U('Hotel/index')}&search_txt="+$(this).val();
			})
			
			$('.back').click(function(){
				window.history.go(-1);  
			})
			
			$('.dep_end').click(function(){
				$('#J_Calendar').show();
			})
			
			
			
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
									alert('不能选同一天'); 
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
		function pageGetList(type){
			if(type == true){
				//now_sort_id = 'juli';
				//$('.dropdown-toggle.sort span').html('离我最近');
				//$('.sort-wrapper>ul li:first').data('sort-id','juli').find('span').html('离我最近');
			}
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
			$('.listBox dl').empty().hide();
			$('.listBox .no-deals').addClass('hide');

			$("#pullUp").removeClass('noMore loading').show();
			$('.listBox dl .noMore').remove();
			pageLoadTip(92);
			getList(false);
		}
		
		function close_dropdown(){
			$('#dropdown_scroller,#dropdown_sub_scroller').css('width','');
			$('.dropdown-toggle').removeClass('active');
			$('.dropdown-wrapper').prop('class','dropdown-wrapper');
			$('#dropdown_scroller,.dropdown-module').css('height','');
			$('.listBox .shade').addClass('hide');
			$('#dropdown_sub_scroller').css('left','100%');
			$('#dropdown_scroller>div>ul>li').removeClass('active');
			if(myScroll3){
				myScroll3.destroy();
				myScroll3 = null;
				$('#dropdown_scroller>div').removeAttr('style');
			}
			if(myScroll2){
				myScroll2.destroy();
				myScroll2 = null;
				$('#dropdown_sub_scroller>div').removeAttr('style');
			}
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
		
			now_page += 1;
			go_url += "&page="+now_page;
			$.post(go_url,function(result){
				if(result.group_list){
					
				
					laytpl($('#groupListBoxTpl').html()).render(result, function(html){
						if(more){
						
							$('.loadMoreList').remove();
							$('.Hotel_list').append(html);
						}else{
							$('.Hotel_list').html(html).addClass('dealcard').show();
						}
					});
				
				}
				
				pageLoadTipHide();
			
				isLoading = false;
			});
		}

		var obj2String = function(_obj) {
			var t = typeof (_obj);
			if (t != 'object' || _obj === null) {
			  // simple data type
			  if (t == 'string') {
				_obj = '"' + _obj + '"';
			  }
			  return String(_obj);
			} else {
			  if ( _obj instanceof Date) {
				return _obj.toLocaleString();
			  }
			  // recurse array or object
			  var n, v, json = [], arr = (_obj && _obj.constructor == Array);
			  for (n in _obj) {
				v = _obj[n];
				t = typeof (v);
				if (t == 'string') {
				  v = '"' + v + '"';
				} else if (t == "object" && v !== null) {
				  v = this.obj2String(v);
				}
				json.push(( arr ? '' : '"' + n + '":') + String(v));
			  }
			  return ( arr ? '[' : '{') + String(json) + ( arr ? ']' : '}');
			}
		  };
		  
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
  
		</script>
	</body>
</html>