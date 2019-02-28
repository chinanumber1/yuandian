$.fn.animationEnd=function(a){
	var t=this,d=["webkitAnimationEnd","OAnimationEnd","MSAnimationEnd","animationend"];
	if(a){
		for(var i=0;i<d.length;i++){
			t.on(d[i],a);
		}
	}
	return this;
}
var scrollHe = {
	pageOther : $('#pageOther'),
	pageMain : $('#pageMain'),
	showLayer:function(){
		scrollHe.pageOther.removeClass('page-from-center-to-right').addClass('page-from-right-to-center page-current');
		scrollHe.pageMain.removeClass('page-from-left-to-center page-current').addClass('page-from-center-to-left');
	},
	hideLayer:function(){
		scrollHe.pageOther.removeClass('page-from-right-to-center page-current').addClass('page-from-center-to-right');
		scrollHe.pageMain.removeClass('page-from-left-to-center').addClass('page-from-left-to-center page-current');
	}
};
scrollHe.pageMain.animationEnd(function(e){
	var g_class='page-from-center-to-left page-from-center-to-right page-from-right-to-center page-from-left-to-center';
	scrollHe.pageOther.removeClass(g_class);
	scrollHe.pageMain.removeClass(g_class);
});