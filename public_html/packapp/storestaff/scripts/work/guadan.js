$(document).ready(function(){
	
	$(".guadan ul").height($(window).height()-95);
	common.onlyScroll($(".guadan ul"));
	
	guadan_render();
});
function guadan_render(){
	var guadan_list = common.getCache('guadan_list');
	
	if(guadan_list){
		laytpl($('#listGuadanTpl').html()).render(guadan_list, function(html){
			$(".guadan ul").html(html);
			$.each($('.guadan ul li'),function(i,item){
				var index = $(this).find("dd").size();
				if(index > 3){
					$(this).find(".more").show();
					$(this).find("dd").each(function(){
						if($(this).index()>2){
						   $(this).addClass("hide");  
						}
					});
				}
			});
			$('#guadan_count').html(guadan_list.length);
		});
	}
	$(".guadan li .more").click(function(){
		$(this).hide().siblings("dl").find("dd").removeClass("hide");
	});

	$(".guadan .check").click(function(){
		if($(this).hasClass("on")){
			$(this).removeClass("on"); 
		}else{
			$(this).addClass("on");
			$(this).parents("li").siblings("li").find(".check").removeClass("on"); 
		}
	});
	
	$(".guadan .total_n .indeed").click(function(){
		var guadan_list = common.getCache('guadan_list');
		if($('.guadan .check.on').size() < 1){
			motify.log('您没有选中订单');
			return false;
		}
		var index = $('.guadan .check.on').closest('li').data('index');
		common.setCache('buy_list',guadan_list[index],true);
		delete guadan_list[index];
		var new_guadan_list = [];
		for(var i in guadan_list){
			new_guadan_list.push(guadan_list[i]);
		}
		common.setCache('guadan_list',new_guadan_list);
		redirect('back');
	});
	
	$(".guadan .total_n .del").click(function(){
		var guadan_list = common.getCache('guadan_list');
		if($('.guadan .check.on').size() < 1){
			motify.log('您没有选中订单');
			return false;
		}
		var index = $('.guadan .check.on').closest('li').data('index');
		delete guadan_list[index];
		var new_guadan_list = [];
		for(var i in guadan_list){
			new_guadan_list.push(guadan_list[i]);
		}
		common.setCache('guadan_list',new_guadan_list);
		guadan_render();
		// $('.guadan .check.on').closest('li').remove();
		motify.log('删除成功');
	});
}
function guadan_add(order){
	var guadan_list = common.getCache('guadan_list');
	if(!guadan_list){
		guadan_list = [];
	}
	guadan_list.unshift(order);
	common.setCache('guadan_list',guadan_list);
	guadan_count();
}
function guadan_count(){
	var guadan_list = common.getCache('guadan_list');
	var guadan_count = guadan_list ? guadan_list.length : 0;
	$('.gd em').html(guadan_count);
}