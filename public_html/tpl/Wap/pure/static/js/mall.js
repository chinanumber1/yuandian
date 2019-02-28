var mySwiper1 = null, mySwiper2 = null, mySwiper3 = null, now_page = 1, hasMorePage = true, isLoading = true, cat_html = [];
$(function(){
//	cat_fid = $('#swiper-container2 .active-nav').data('id');
//	var myswiper = new Swiper('.swiper-container1', {
//		pagination: '.swiper-container1 .swiper-pagination',
//		direction : 'horizontal',
//		paginationClickable :true,
//		autoplay :'5000',
//		autoplayDisableOnInteraction : false,
//		loop: true 
//	});
//	var myswiper = new Swiper('.swiper-container5', {
//		pagination: '.swiper-container5 .swiper-pagination',
//		direction : 'horizontal',
//		paginationClickable :true,
//		autoplayDisableOnInteraction : false,
//	});

	// 图片等比例
	$(".homepage img").each(function(){
		$(this).height($(this).width()*0.375);
	});
	
//	$(".bd_a a:nth-child(2n)").css("margin-right", 0); 
	//分类定位 
	$(window).scroll(function() {
		if ($(window).scrollTop() > $(".bd_a").offset().top) {
			if(isLoading == false && hasMorePage == true && $(document).scrollTop() >= $(document).height() - $(window).height() - 50){
				cat_fid = $('#swiper-container2 .active-nav').data('id');
				getList(true);
			}
		}
	});
	$('#search').click(function(){
		hasMorePage = true;
		now_page = 0;
		getList(0);
	});
	showListData();
});
function updateNavPosition(){
	$('#swiper-container2 .active-nav').removeClass('active-nav');
	var activeNav = $('#swiper-container2 .swiper-slide').eq(mySwiper3.activeIndex).addClass('active-nav');
	cat_fid = $('#swiper-container2 .swiper-slide').eq(mySwiper3.activeIndex).data('id');
	if (!activeNav.hasClass('swiper-slide-visible')) {
		if (activeNav.index() > mySwiper2.activeIndex) {
			var thumbsPerNav = Math.floor(mySwiper2.width/activeNav.width())-1;
			mySwiper2.slideTo(activeNav.index()-thumbsPerNav);
		} else {
			mySwiper2.slideTo(activeNav.index());
		}
	}
	hasMorePage = true;
	now_page = 0;
	getList(0);
}
function showListData(){
	//$('#pageLoadTip').css('bottom', '-500px');
	//pageLoadTip(0);
	var index = layer.open({type: 2});
	$.getJSON(ajax_url_root, function(result){
		/*顶部轮播图*/
		if(result.banner_list){
			laytpl($('#listBannerSwiperTpl').html()).render(result.banner_list, function(html){
				$('#listBanner .swiper-wrapper').html(html);
				if(result.banner_list.length > 1){
					var myswiper = new Swiper('.swiper-container1', {
						pagination: '.swiper-container1 .swiper-pagination',
						direction : 'horizontal',
						paginationClickable :true,
						autoplay :'5000',
						autoplayDisableOnInteraction : false,
						loop: true 
					});
				}
//				$(".homepage img").each(function(){
//					$(this).height($(this).width()*0.375);
//				});
				$('#listBanner').show();
				$('.hasManyCity').hide();
			});
		}else{
			$('#listHeader').addClass('fixedRoundBg');
			$('#pageList').css('padding-top','50px');
			$('#listBanner').hide();
			$('.hasManyCity').show();
		}
		
		/*九宫格*/
		if(result.slider_list){
			laytpl($('#listSliderSwiperTpl').html()).render(result.slider_list, function(html){
				$('#listSlider .swiper-wrapper').html(html);
				if(result.slider_list.length > 8){
					var mySwiper5 = $('.swiper-container5').swiper({
						pagination:'.swiper-pagination5',
						loop:true,
						grabCursor: true,
						paginationClickable: true,
						simulateTouch:false
					});
				}
				$('#listSlider').show();
			});
		}else{
			$('#listSlider').hide();
		}
		
		
		/*可选分类*/
		if(result.category_list){
			laytpl($('#listCategoryListTpl').html()).render(result.category_list, function(html){
				$('#swiper-container2 .swiper-wrapper').html(html);
				mySwiper2 = new Swiper('#swiper-container2',{
					watchSlidesProgress : true,
					watchSlidesVisibility : true,
					slidesPerView : 4.5,
					onTap: function(){
						mySwiper3.slideTo(mySwiper2.clickedIndex);
					}
				});
			});
			laytpl($('#listGoodsContentListTpl').html()).render(result.category_list, function(html){
				$('#swiper-container3 .swiper-wrapper').html(html);
				mySwiper3 = new Swiper('#swiper-container3',{
					autoHeight: true,
					onSlideChangeStart: function(){
						updateNavPosition();
					}
				});
			});
		}
		
		if (result.goods_list) {
			var data_list = result.goods_list;
			if(data_list.total > 0){
				hasMorePage = now_page < data_list.total_page ? true : false;
				laytpl($('#goodsListBoxTpl').html()).render(result.goods_list, function(html){
					$('.swiper-slide-active .bd_a').html(html);
					cat_html[cat_fid] = html;
				});
				// 图片等比例
				$(".swiper-slide-active .bd_a img").each(function(){
					$(this).height($(this).width());
				});
				$(".swiper-slide-active .bd_a a:nth-child(2n)").css("margin-right", 0); 
			} else {
				cat_html[cat_fid] = '';
				$('.swiper-slide-active .bd_a').html('');
			}
		}
		isLoading = false
		layer.close(index);
		//pageLoadTipHide();
		//$('#pageLoadTip').css('bottom', '0px');
	});
}

function getList(more)
{
	isLoading = true;
	now_page += 1;
	if (now_page == 1) {
		var index = layer.open({type: 2});
		$('.bd_a').html('');
		//pageLoadTip($('.hasManyCity').height()+$('.homepage').height()+$('.menu').height() - 18);
	}
	if (cat_html[cat_fid] == undefined || now_page != 1) {
		$.post(ajax_url, {'page':now_page, 'cat_fid':cat_fid}, function(result){
			if(result.total > 0){
				hasMorePage = now_page < result.total_page ? true : false;
				laytpl($('#goodsListBoxTpl').html()).render(result, function(html){
					if (more) {
						$('.bd_a').append(html);
					} else {
						cat_html[cat_fid] = html;
						$('.bd_a').html(html);
					}
				});
				// 图片等比例
				$(".bd_a img").each(function(){
					$(this).height($(this).width());
				});
				$(".bd_a a:nth-child(2n)").css("margin-right", 0); 
			} else {
				cat_html[cat_fid] = '';
				$('.bd_a').html('');
			}
			isLoading = false;
			layer.close(index);
			//pageLoadTipHide();
		}, 'json');
	} else {
		$('.bd_a').html(cat_html[cat_fid]);
		// 图片等比例
		$(".bd_a img").each(function(){
			$(this).height($(this).width());
		});
		$(".bd_a a:nth-child(2n)").css("margin-right", 0); 
		isLoading = false;
		layer.close(index);
		//pageLoadTipHide();
	}
}