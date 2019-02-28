$(function(){
	$('.table_body').height($(window).height() - 50 - 160 -63);
	var swiper = new Swiper('.swiper-container', {
        slidesPerView: 'auto',
        spaceBetween: 10,
		prevButton:'.swiper-button-prev',
		nextButton:'.swiper-button-next',
    });  
	// $('.tabele_cat li').eq(0).addClass('cur');
	$('.tabele_cat li').click(function(){
		$(this).addClass('cur').siblings('li').removeClass('cur');
		var index = layer.load(0, {shade: 0.1});
		location.href = $(this).data('href');
	});
	
	$('.table_tab div').click(function(){
		$(this).addClass('cur').siblings('div').removeClass('cur');
		$('.table_body li').hide();
		$('.table_search input').val('');
		if($(this).hasClass('tab_lock')){
			$('.table_body li.locked').show();
		}else if($(this).hasClass('tab_all')){
			$('.table_body li').show();
		}else{
			$('.table_body li.unlocked').show();
		}
	});
	
	$('.table_search input').keyup(function(){
		if($('.table_search input').val() == ''){
			if($('.table_tab .cur').size() > 0){
				if($('.table_tab .cur').hasClass('tab_lock')){
					$('.table_body li.locked').show();
				}else if($('.table_tab .cur').hasClass('tab_all')){
					$('.table_body li').show();
				}else{
					$('.table_body li.unlocked').show();
				}
			}else{
				$('.table_body li').show();
			}
		}else{
			$('.table_body li').hide();
			if($('.table_tab .cur').size() > 0){
				if($('.table_tab .cur').hasClass('tab_lock')){
					$.each($('.table_body li.locked'),function(i,item){
						if($(item).find('.cat_name').html().indexOf($('.table_search input').val()) >= 0){
							$(item).show();
						}
					});
				}else if($('.table_tab .cur').hasClass('tab_all')){
					$.each($('.table_body li'),function(i,item){
						if($(item).find('.cat_name').html().indexOf($('.table_search input').val()) >= 0){
							$(item).show();
						}
					});
				}else{
					$.each($('.table_body li.unlocked'),function(i,item){
						if($(item).find('.cat_name').html().indexOf($('.table_search input').val()) >= 0){
							$(item).show();
						}
					});
				}
			}else{
				$.each($('.table_body li'),function(i,item){
					if($(item).find('.cat_name').html().indexOf($('.table_search input').val()) >= 0){
						$(item).show();
					}
				});
			}
		}
	});
	$('.cat_status_btn').click(function(){
		var index = layer.load(0, {shade: 0.1});
		var that = $(this);
		var li = $(this).closest('li');
		$.post(table_lock_url,{id:li.data('id'),lock:li.hasClass('locked') ? 0 : 1},function(result){
			layer.close(index);
			if(result.status == 1){
				if(li.hasClass('locked')){
					li.addClass('unlocked').removeClass('locked');
					li.find('.cat_status').html('当前状态：解锁');
					that.html('点击锁定');
				}else{
					li.addClass('locked').removeClass('unlocked');
					li.find('.cat_status').html('当前状态：锁定');
					that.html('点击解锁');
				}
			}else{
				layer.msg(result.info);
			}
		});
	});
});