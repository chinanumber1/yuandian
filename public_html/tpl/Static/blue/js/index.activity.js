$(function(){
	$(window).resize(function(){
		var gd_box_width = ($(window).width()-1200)/2-80;
		$('.gd_box').css('margin-left',gd_box_width+'px');
	});
	var gd_box_width = ($(window).width()-1200)/2-80;
	$('.gd_box').css('margin-left',gd_box_width+'px');
	$('.gd_box').addClass('gd_position');
	var component_slider_timer = null;
	$('.menu_main').css('background-color',$('.mt-slider-current-trigger').attr('data-color'));
	$('.activityDesc h1').html($('.mt-slider-current-trigger').attr('data-name'));
	$('.activityInfo').html($('.mt-slider-current-trigger').attr('data-subname'));
	$('.activityDesc a:first').addClass('select');
	// $('.activityDesc a:first').addClass('select');
	function component_slider_play(){
		component_slider_timer = window.setInterval(function(){
			var slider_index = $('.activityDiv ul li.mt-slider-current-trigger').index();
			if(slider_index == $('.activityDiv ul li').size() - 1){
				slider_index = 0;
			}else{
				slider_index++;
			}
			var obj= $('.activityDiv ul li').eq(slider_index);
			$('.activityDesc h1').html(obj.attr('data-name'));
			$('.activityInfo').html(obj.attr('data-subname'));
			obj.css({'opacity':'0','display':'block'}).animate({opacity:1},600).siblings().hide();
			obj.addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
			$('.activityDesc a').removeClass('select').addClass('un_select');
			$('.activityDesc #point'+(slider_index+1)).removeClass('un_select').addClass('select');
			$('.menu_main').css('background-color',obj.attr('data-color'));
		},3400);
	}
	component_slider_play();
	$('.activityDiv').hover(function(){
		window.clearInterval(component_slider_timer);
		$('.activityDiv .mt-slider-previous,.activityDiv .mt-slider-next').css({'opacity':'0.6'}).show();
	},function(){
		window.clearInterval(component_slider_timer);
		component_slider_play();
		$('.activityDiv .mt-slider-previous,.activityDiv .mt-slider-next').css({'opacity':'0'}).hide();
	});
	$('.activityDiv .mt-slider-previous,.activityDiv .mt-slider-next').hover(function(){
		$(this).css({'opacity':'1'});
	},function(){
		$(this).css({'opacity':'0.6'});
	});
	$('.activityDiv .mt-slider-previous').click(function(){
		var slider_index = $('.activityDiv ul li.mt-slider-current-trigger').index()-1;
		if(slider_index < 0){
			slider_index = $('.activityDiv ul li').size()-1;
		}
		var obj= $('.activityDiv ul li').eq(slider_index);
		$('.activityDesc h1').html(obj.attr('data-name'));
		$('.activityInfo').html(obj.attr('data-subname'));
		$('.menu_main').css('background-color',obj.attr('data-color'));
		$('.activityDesc a').removeClass('select').addClass('un_select');
		$('.activityDesc #point'+(slider_index+1)).removeClass('un_select').addClass('select');
		obj.css({'opacity':'0','display':'block'}).animate({opacity:1},600).siblings().hide();
		obj.addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
	});
	$('.activityDiv .mt-slider-next').click(function(){
		var slider_index = $('.activityDiv ul li.mt-slider-current-trigger').index()+1;
		if(slider_index == $('.activityDiv ul li').size()){
			slider_index = 0;
		}
		var obj= $('.activityDiv ul li').eq(slider_index);
		$('.activityDesc h1').html(obj.attr('data-name'));
		$('.activityInfo').html(obj.attr('data-subname'));
		$('.menu_main').css('background-color',obj.attr('data-color'));
		$('.activityDesc a').removeClass('select').addClass('un_select');
		$('.activityDesc #point'+(slider_index+1)).removeClass('un_select').addClass('select');
		obj.css({'opacity':'0','display':'block'}).animate({opacity:1},600).siblings().hide();
		obj.addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
	});
	$('.activityDesc a').click(function(){
		var slider_index = $('.activityDiv ul li.mt-slider-current-trigger').index()+1;
		if(slider_index == $('.activityDiv ul li').size()){
			slider_index = 0;
		}
		$('.activityDesc a').removeClass('select').addClass('un_select');
		$(this).removeClass('un_select').addClass('select');
		var obj= $('.activityDiv ul li').eq(slider_index);
		
		$('.activityDesc h1').html(obj.attr('data-name'));
		$('.activityInfo').html(obj.attr('data-subname'));
		$('.menu_main').css('background-color',obj.attr('data-color'));
		obj.css({'opacity':'0','display':'block'}).animate({opacity:1},600).siblings().hide();
		obj.addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
	});
});