/*function showHide(e,objname){     
    var obj = $('#'+objname),
		inner = $('#list_nav_2013'),
		uls = inner.find('.block'),
		btns = inner.find('.xias');
    if(!!obj.hasClass('block')){
		obj.removeClass('block');
        e.className="rights";
    }else{
        obj.addClass('block');
        e.className="xias";
    }
	uls.removeClass('block');
	btns.removeClass('xias').addClass('rights');
	return false;
}*/
$('#list_nav_2013').find('.ul').each(function(){
	$(this).find('li:first').remove();
	if($(this).find('li').length < 4){
		$(this).css('height','auto')
	}
});

$('#list_nav_2013').find('.item').hover(function(){
	$(this).addClass('open');
}).mouseleave(function(){
	$(this).removeClass('open');
});