$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	$('.public .content').html(indexData.have_meal_name);
	
	initData();
	setInterval('initData()', 1000);
});
function initData()
{
    common.http('Storestaff&a=foodshop', {'noTip':true}, function(data){
        $('#lock_count').html(data.lock_count);
        $('#open_count').html(data.open_count);
        $('#eating_count').html(data.eating_count);
        $('#book_count').html(data.book_count);
        $('#confirm_count').html(data.confirm_count);
        if (data.queue_is_open == 0) {
            $('#queue_count').html('').next('div.ex').html('排号功能已关闭');
        } else {
            $('#queue_count').html(data.queue_count);
        }
    });
}