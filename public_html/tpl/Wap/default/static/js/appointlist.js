$(function(){
	$('.view_album').click(function(){
		$('#buy_box').removeAttr('style');
		show_buy_box = false;
		var album_more = $(this).attr('data-pics');
		var album_array = album_more.split(',');
		if(is_weixin()){
			wx.previewImage({
				current:album_array[0],
				urls:album_array
			});
		}else{
			var album_html = '<div class="albumContainer h_gesture_ tap_gesture_" style="display:block;">';
				album_html += '<div class="swiper-container">';
				album_html += '		<div class="swiper-wrapper">';
			$.each(album_array,function(i,item){
				album_html += '			<div class="swiper-slide">';
				album_html += '				<img src="'+item+'"/>';
				album_html += '			</div>';
			});
				album_html += '		</div>';
				album_html += '  	<div class="swiper-pagination"></div><div class="swiper-close" onclick="close_swiper()">X</div>';
				album_html += '</div>';
			
			album_html += '</div>';
			$('body').append(album_html);
		
			mySwiper = $('.swiper-container').swiper({
				pagination:'.swiper-pagination',
				loop:true,
				grabCursor: true,
				paginationClickable: true
			});
		}
	});
})
function list_location(obj){
	close_dropdown();
	
	if(obj.attr('data-category-id')){
		now_cat_url = obj.attr('data-category-id');
	}else if(obj.attr('data-area-id')){
		now_area_url = obj.attr('data-area-id');
	}else if(obj.attr('data-sort-id')){
		now_sort_id = obj.attr('data-sort-id');
	}
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
	
	$('.deal-container .loading').removeClass('hide');
	
	window.location.href = go_url;
}
function close_swiper(){
	$('.albumContainer').remove();
	show_buy_box = true;
}
