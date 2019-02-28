$(document).ready(function(){
	//查看更多
    $(".three p").each(function(){
        if($(this).height()>22){
            $(".three").addClass("casmore")
        }
    });
    $(".three").click(function(){
        if($(this).hasClass("hasmore")){
            $(this).removeClass("hasmore")
        }else{
            $(this).addClass("hasmore")
        }
    });
    
    $(window).scroll(function(){
        if($(this).scrollTop() > 200){
           $(".coping").show(); 
        }else{
           $(".coping").hide();  
        }
    });
    $(".coping .top").click(function(){
        $("body,html").animate({
            scrollTop: 0
        },500);
    });
	common.http('Storestaff&a=appointDetail', {'order_id':urlParam.order_id}, function(data){

		laytpl($('#userDetail').html()).render(data, function(html){
			$('.caption').html(html);
		});
		laytpl($('#orderDetail').html()).render(data, function(html){
			$('.g_details .rese_infor ul').html(html);
		});
	});
	
    //验证
    $(document).on('click', '.verification', function(e){
        e.stopPropagation();
        $('#order_id').val($(this).data('id'));
        $(".seek, .mask").show();
    });
    $(document).on('click', '.ensure', function(e){
        common.http('Storestaff&a=appointVerify',{'order_id':$('#order_id').val(), noTip:true}, function(data){
            location.reload();
        });
    });
    $(".mask,.seek .del, .close").click(function(){
        $(".seek,.mask").hide();
    });
});