var myScroll;
function loaded() {
	myScroll = new iScroll('staff-list-wrapper', {
		checkDOMChanges: true
	});
	
}
document.addEventListener('touchmove', function(e) {
	e.preventDefault();
}, false);
document.addEventListener('DOMContentLoaded', loaded, false);
function checkDel(obj){
	confirm_open('删除店员','确定删除吗？',"",obj);
}
function checkExit(obj){
	confirm_open('退出店铺','退出后将无法管理该店铺','确认退出吗？',obj);
}