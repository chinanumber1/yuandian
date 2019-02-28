var myScroll;
var isApp = motify.checkApp();
$(function(){
    $('.storeProList .more').click(function(){
        $(this).remove();
        $('.storeProList li').show();
		if(!isApp){
			myScroll.refresh();
		}
    });
});