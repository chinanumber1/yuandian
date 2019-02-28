//动画显示边条内容区域
$(function() {
$(".quick-menu dl").hover(function() {
	$(this).addClass("hover");
},
function() {
	$(this).removeClass("hover");
});

});
//下拉选框
$(function(){
	$(".nav_bbs p").click(function(){
		var ul=$(".keyword_new");
		if(ul.css("display")=="none"){
			ul.slideDown();
		}else{
			ul.slideUp();
		}
	});
	$(".set").click(function(){
		var _name = $(this).attr("name");
		if( $("[name="+_name+"]").length > 1 ){
			$("[name="+_name+"]").removeClass("select");
			$(this).addClass("select");
		} else {
			if( $(this).hasClass("select") ){
				$(this).removeClass("select");
			} else {
				$(this).addClass("select");
			}
		}
	});
	$(".nav_bbs li").click(function(){
		var li=$(this).text();
		$(".nav_bbs p").html(li);
		$(".keyword_new").hide();
		/*$(".set").css({background:'none'});*/
		$("p").removeClass("select");
		$('#keyword_c').val($(this).attr('data-val'));  
	});
});