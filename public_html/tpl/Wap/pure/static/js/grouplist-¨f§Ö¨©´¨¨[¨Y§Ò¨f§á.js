var myScroll;
$(function(){
	$('#scroller').css({'min-height':($(window).height()+1)+'px'});
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	myScroll.on("scroll",function(){
		console.log(this.y);
		if(this.y < -50){
			if(!$('.navBox').hasClass('absolute')){
				$('.navBox').addClass('absolute');
			}
			$('.navBox').css({'top':Math.abs(this.y)+'px'});
		}else{
			$('.navBox').removeClass('absolute');
		}
	});
	$(window).resize(function(){
		window.location.reload();
	});
	
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
		$('.historyBox .clear').removeClass('none');
		window.addEventListener("pagehide", function(){
			$('#keyword').val('');
		},false);
	});
	$(document).on('click','.storeListBox li.more',function(){
		$(this).hide().siblings('li').show();
		$(this).prev().css({'border-bottom':'none'});
		myScroll.refresh();
	});
	
	$('.storeListBox').css('min-height',$(window).height()-95);
	pageLoadTip(50);
	getList();
});

var now_page = 0;
function list_location(obj){
	close_dropdown();
	now_page = 0;
	if(obj.attr('data-category-id')){
		$('.dropdown-toggle.category .nav-head-name').html(obj.find('span').data('name'));
		now_cat_url = obj.attr('data-category-id');
	}else if(obj.attr('data-area-id')){
		$('.dropdown-toggle.biz .nav-head-name').html(obj.find('span').data('name'));
		now_area_url = obj.attr('data-area-id');
	}else if(obj.attr('data-sort-id')){
		$('.dropdown-toggle.sort .nav-head-name').html(obj.find('span').data('name'));
		now_sort_id = obj.attr('data-sort-id');
	}
	$('.storeListBox dl').empty().hide();
	$('.storeListBox .no-deals').addClass('hide');
	
	pageLoadTip(50);
	getList();
}

function getList(){
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
	now_page += 1;
	
	$.post(go_url,function(result){
		data = $.parseJSON(result);
		if(data.store_count > 0 || data.group_count > 0){
			if(data.style == 'store'){
				$('.listBox').addClass('storeListBox');
				laytpl($('#storeListBoxTpl').html()).render(data, function(html){
					$('.listBox dl').html(html).removeClass('dealcard').show();
				});
			}else{
				$('.listBox').removeClass('storeListBox');
				laytpl($('#groupListBoxTpl').html()).render(data, function(html){
					$('.listBox dl').html(html).addClass('dealcard').show();
				});
			}
		}else{
			$('.storeListBox .no-deals').removeClass('hide');
		}
		pageLoadTipHide();
		myScroll.refresh();
	});
}