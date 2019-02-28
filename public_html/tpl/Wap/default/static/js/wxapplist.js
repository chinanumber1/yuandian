function list_location(obj){
	close_dropdown();
	
	if(obj.attr('data-category-id')){
		now_cat_url = obj.attr('data-category-id');
	}
	var go_url = location_url;
	if(now_cat_url != '-1'){
		go_url += "&cat_url="+now_cat_url;
	}
	if(mer_id != 0){
		go_url += "&mer_id="+mer_id;
	}
	$('.deal-container .loading').removeClass('hide');
	
	window.location.href = go_url;
}