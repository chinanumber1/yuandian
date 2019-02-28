var myScroll;
var isApp = motify.checkApp();
$(function(){
	$('#backBtn').click(function(){
		window.history.go(-1);
	});
	if($(".footerMenu").length){
		$('#scroller').css({'min-height':($(window).height()-100+1)+'px'});
	}else{
		$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	}

	if(isApp){
        $('body').append('<style>::-webkit-scrollbar{width:0px;}</style>');
        $('#container').css({'top':'-12px'});
        $('#container,#scroller').css({'position':'static'});
    }else{
        myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    }
		$('#submit_btn').click(function(){
			$('#bind_family_phone').val($.trim($('#bind_family_phone').val()));
			$('#bind_family_name').val($.trim($('#bind_family_name').val()));

			layer.open({type: 2,content: '绑定中，请稍等',shadeClose:false});
			$.post(window.location.href,{'name':$('#bind_family_name').val(),'phone':$('#bind_family_phone').val()},function(result){
				layer.closeAll();
				if(result.err_code == 1){
					layer.open({content:'绑定成功!',shadeClose:false,btn:['确定'],yes:function(){
						window.location.href = okUrl;
					}});
				}else{
					motify.log(result.err_msg);
				}
			});
		});
});