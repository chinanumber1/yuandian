$(document).ready(function(){
	//插件日历
//	var currYear = new Date().getFullYear();
	var opt = {  
	    'dateYMD': {
	        preset: 'date',
	        dateFormat: 'yyyy-mm-dd',
	        theme: 'android-ics light', //皮肤样式
	        display: 'modal',           //显示方式
	        mode: 'scroller',           //日期选择模式
	        showNow: true,
	        nowText: "今天",
//	        startYear: currYear,    //开始年份
//	        endYear: currYear + 1, //结束年份
	        // minDate: new Date(),    //只能选择
	    },'select': {
	        preset: 'select'
	    }
	}
	$('#begin_time').scroller($.extend(opt['dateYMD'],opt['default']));
	$('#end_time').scroller($.extend(opt['dateYMD'],opt['default']));
	//表格高度
	$(".tab_slide").height($(window).height()-220);
	var end_time = '', begin_time = '';
	$('.query_true').click(function(){
		var end_time = $('#end_time').val(), begin_time = $('#begin_time').val();
		goodsList({'begin_time':begin_time, 'end_time':end_time});
	});
	goodsList(null);
});

function goodsList(params)
{
	common.http('Storestaff&a=statistics', params, function(data){
		if (data != '') {
			laytpl($('#tableTpl').html()).render(data, function(html){
				$('.tj_table').html(html);
			});
		} else {
			$('.tj_table').html('');
		}
	});
}