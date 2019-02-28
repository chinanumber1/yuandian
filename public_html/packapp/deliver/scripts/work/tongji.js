$(document).ready(function(){
    var date = new Date();
    var month=(date.getMonth()+1)>9?(date.getMonth()+1):'0'+(date.getMonth()+1);
    var data=date.getDate()>9?date.getDate():'0'+date.getDate();
    $(".data").val(date.getFullYear()+"-"+month+"-"+data);
    var begin_time = $('#appDate').val();
    var end_time =$('#appDate1').val();
	var indexData = common.getCache('indexData',true);
	if(!indexData){
		location.href = 'index.html';
	}
	
	if(common.checkWeixin()){
		common.fillPageBg(2,['#fff','#f4f4f4']);
	}
	
	if(!indexData.deliver_info.is_system){
		$('.merchant_hide').hide();
	}
	
	var calendar = new lCalendar();
	calendar.init({
		'trigger': '#appDate',
		'type': 'date',
		'callFunc':function(date){
			changeDate();
		}
	});
	
	$('#begin h2').click(function(){
		$('#appDate').trigger('click');
	});
	
	var calendar2 = new lCalendar();
	calendar2.init({
		'trigger': '#appDate1',
		'type': 'date',
		'callFunc':function(date){
			changeDate();
		}
	});
	
	$('#end h2').click(function(){
		$('#appDate1').trigger('click');
	});
	
	tongji();
	
    $(".tabs").on('click', 'div', function(){
        $(this).addClass("flexActive").siblings().removeClass("flexActive");;
        var order_from = parseInt($(this).data('order_from'));
        if (order_from == 0) {
            $(".tjt").show();
            $(".xiaofei").hide()
        } else {
            $(".tjt").hide();
            $(".xiaofei").show()
        }
        tongji()
    })
    
});


function changeDate(){
    var begin_time = $('#appDate').val();
    var end_time = $('#appDate1').val();
    if(begin_time && end_time){
        tongji();
    }
}

function tongji(){
    var order_from = parseInt($('.tabs .flexActive').data('order_from')), begin_time = $('#appDate').val(), end_time = $('#appDate1').val();
    common.http('Deliver&a=new_tongji',{begin_time:begin_time,end_time:end_time,order_from:order_from}, function(data){
        common.setData(data);
        $('#appDate').val(data.appDate);
        $('#appDate1').val(data.appDate1);
    });
}
