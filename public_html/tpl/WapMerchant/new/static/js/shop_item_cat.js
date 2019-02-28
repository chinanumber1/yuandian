var myScroll;
function loaded() {
	myScroll = new iScroll('item-list-wrapper', {});
}

document.addEventListener('touchmove', function(e) {
	e.preventDefault();
}, false);
document.addEventListener('DOMContentLoaded', loaded, false);
$(".category-sort").click(function(){
	$(this).children('input').focus();
})
$(".category-title").click(function(){
	$(this).children('input').focus();
})