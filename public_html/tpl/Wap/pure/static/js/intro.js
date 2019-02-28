$('.yxc-time-con dt[data-role="date"]').click(function(){
	$('.yxc-time-con dt[data-role="date"]').removeClass('active');
	$(this).addClass('active');
	$('.date-'+$(this).data('date')).show().siblings('div').hide();
});