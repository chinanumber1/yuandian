$(document).ready(function(e) {
	function b(){
	    h = $(window).height();
	    t = $(document).scrollTop();
	    if(t > h){
		    $('#gotop').show();
	    }else{
		    $('#gotop').hide();
	   }
    }
	b();
	$('#gotop').click(function(){
		$(document).scrollTop(0);	
	})
	$('#code').hover(function(){
			$(this).attr('id','code_hover');
			$('#code_img').show();
			$('#icon-02').show();
		},function(){
			$(this).attr('id','code');
			$('#code_img').hide();
			$('#icon-02').hide();
	})
	$('#coder').hover(function(){
			$(this).attr('id','coder_hover');
			$('#coder_img').show();
			$('#icon-01').show();
		},function(){
			$(this).attr('id','coder');
			$('#coder_img').hide();
			$('#icon-01').hide();
	})

	$(window).scroll(function(e){
	    b();		
    });
});

window.onerror = function(msg, url, line, col, error) {
	return true;
}