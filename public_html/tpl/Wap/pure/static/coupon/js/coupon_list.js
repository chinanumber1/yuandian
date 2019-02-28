var isApp = motify.checkApp();
$(function(){
	$("dd").click(function(event){
		window.location.href = $(this).attr('data-url');
	});
});