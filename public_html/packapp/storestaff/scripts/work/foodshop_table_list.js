$(document).ready(function(){
	
	common.onlyScroll($('.tab_list'));
	
	$(".Mask").height($(window).height());
	
	common.http('Storestaff&a=foodshop_table_list',{}, function(data){
		laytpl($('#tableTypeTpl').html()).render(data, function(html){
			$('#tableTypeBox').html(html);
		});
		laytpl($('#tableListTpl').html()).render(data, function(html){
			$('#tableBox').html(html);
		});
		
		//顶部切换
		$(".tab li").click(function(){
			$(this).addClass("on").siblings().removeClass("on");
			$(".lock").eq($(this).index()).show().siblings(".lock").hide();
			$('body,html').animate({scrollTop: 0},300);
			
			$('#searchTxt').val('');
			$('#tableSearchBox').hide();
			$('#tableBox').show();
		}).eq(0).trigger('click');
		
		//切换导航
		var swiper = new Swiper('.swiper-container', {
			direction : 'horizontal',
			freeMode:true,
			slidesPerView: 'auto'
		});
	});
	
	$(".table_details .del,.Mask").click(function(){
		$(".table_details,.Mask").hide();
	})
		
	//查看预订
	$(document).on('click','.ck',function(){
		common.http('Storestaff&a=book_list',{table_id:$(this).data('table_id')},function(result){
			if(result.length > 0){
				laytpl($('#bookListTpl').html()).render(result, function(html){
					$('.tab_list ul').html(html);
				});
			}else{
				$('.tab_list ul').html('<div class="jroll-infinite-tip">暂无预订</div>');
			}
			$(".table_details,.Mask").show();
			$('.tab_list').scrollTop(0);
		});
	})
		
	//锁定解锁
	$(document).on('click','.dj',function(){
		var that = $(this);
		var lockThat = $('.lock_table_'+that.data('table_id'));
		common.http('Storestaff&a=tmp_table_lock',{id:that.data('table_id'),lock:that.hasClass('uolock') ? 0 : 1},function(saveResult){
			if(that.hasClass('uolock')){
				lockThat.addClass('unlock').removeClass('uolock').html('点击锁定');
				lockThat.closest('.lock_n').find('.current').addClass('unlock').removeClass('uolock').html('当前状态：解锁');
			}else{
				lockThat.addClass('uolock').removeClass('unlock').html('点击解锁');
				lockThat.closest('.lock_n').find('.current').addClass('uolock').removeClass('unlock').html('当前状态：锁定');
			}
		});
	});
		
	$('#searchTxt').keyup(function(){
		var txt = $('#searchTxt').val();
		var tableArr = [];
		if(txt == ''){
			$('#tableSearchBox').hide();
			$('#tableBox, #tableTypeBox').show();
		}else{
			var html = '';
			$.each($('#tableBox .lock li'),function(i,item){
				if($(item).find('h2').html().indexOf(txt) >= 0){
					html += $(item).prop("outerHTML");
				}
			});
			$('#tableSearchBox .clr').html(html);
			$('#tableSearchBox').show();
			$('#tableBox, #tableTypeBox').hide();
		}
	});
});