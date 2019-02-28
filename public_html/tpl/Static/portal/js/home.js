$(function(){
	$(".listForm input").val("");
	    $(".listForm button").on("mouseover",function(){
             	 $(".listForm").css("width",'400px');
             	  $(".listForm input").show();
             	  $(".listForm button").css('width','17%');
             })
             $(".listForm button").on("mouseout",function(){
                 $(".listForm").css("width",'40px');
             	  $(".listForm button").css('width','40px');
             	  $(".listForm input").hide();
             	
             })
             $(".listForm input").on("mouseover",function(){
                    $(".listForm").css("width",'400px');
             	  $(".listForm input").show();
             	  $(".listForm button").css('width','17%');
             })
             $(".listForm input").on("mouseout",function(){
                   $(".listForm").css("width",'40px');
             	  $(".listForm button").css('width','40px');
             	  $(".listForm input").hide();
             })
             
	$('.flexslider').flexslider({
		directionNav: false,
		pauseOnAction: false
	});
	$('.col1').flexslider({
		directionNav: false,
		pauseOnAction: true,
		pauseOnHover: true, 
		animation: 'slide',
		manualControlEvent: "hover",
		animation: 'slide'
	});
		
	$('.menu-wrap .menu .item').hover(function(){
		$(this).addClass('hover');
		if($(this).hasClass('first-item')){
			$(this).find('.list-item').addClass('active').css({'top':360-$(this).index()*60-360+'px'}).animate({opacity:1},400);
		}
	},function(){
		$(this).removeClass('hover');
		if($(this).hasClass('first-item')){
			$(this).find('.list-item').removeClass('active').animate({opacity:0},400);
		}
	});
	
	$('.video').on('click','li',function() {
		var divEl = $('<div class="video_1" style="width:498px;height:510px;overflow:hidden;"></div>');
		var str = '<object id="" style="visibility:visible;" width="498" height="510" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">'
				  +'<param value="'+  $(this).attr("url") +'" name="movie">'
				  +'<param value="high" name="quality">'
				  +'<param value="never" name="allowScriptAccess">'
				  +'<param value="true" name="allowFullScreen">'
				  +'<param value="playMovie=true&amp;auto=1&amp;adss=0" name="flashvars">'
				  +'<param value="transparent" name="wmode">'
				  +'<embed id="" allowscriptaccess="never" style="visibility:visible;" pluginspage="http://get.adobe.com/cn/flashplayer/" flashvars="playMovie=true&amp;auto=1&amp;autostart=true" width="498" height="510" allowfullscreen="true" quality="high" src="'+ $(this).attr("url") +'" type="application/x-shockwave-flash" wmode="transparent">'
				  +'</object>';
		divEl.html(str)
		document.body.appendChild(divEl[0]);
		//iframe层-多媒体
		layer.open({
			type: 1,
			title: false,
			area: ['498px', '515px'],
			shade: 0.8,
			closeBtn: false,
			shadeClose: true,
			content: $('.video_1'),
			end: function(){
				document.body.removeChild(divEl[0]);
				divEl[0] = null;
			}
		});
		layer.msg('点击背景关闭');
	});
});