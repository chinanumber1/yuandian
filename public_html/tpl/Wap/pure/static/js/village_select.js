var myScroll;
var isApp = motify.checkApp();
$(function(){
	if(isApp){
		$('header').hide();
	}
	$('#backBtn').click(function(){
		redirect(backUrl,'openLeftWindow');
	});
	$('#scroller').css({'min-height':($(window).height()-57+1)+'px'});
	// myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	if(isApp){
        $('#container').css({'top':'0px'});
    }else{
        myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    }
});