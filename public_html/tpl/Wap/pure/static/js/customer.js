var myScroll;
var isApp = motify.checkApp();
window.IScroll.utils.click = function (e) {
	var target = e.target, ev;
	if ( !(/(SELECT|TEXTAREA)/i).test(target.tagName) ) {
		ev = document.createEvent('MouseEvents');
		ev.initMouseEvent('click', true, true, e.view, 1,
			target.screenX, target.screenY, target.clientX, target.clientY,
			e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
			0, null);

		ev._constructed = true;
		target.dispatchEvent(ev);
	}
};
$(function(){
	var activePos = $('.village_my').position(), is_click = false;

	if (typeof activePos != 'undefined') {
		is_click = true;
		$('#untreated').on('click',function(){
			get_list(0);
		});
		$('#processed').on('click',function(){
			get_list(1);
		});
		get_list(0);
		function get_list(status) {
			$.get("/wap.php?c=Customer&a=ajax_list",{'status':status},function(data){
				if (data.status) {
					laytpl($('#indexRecommendBoxTpl').html()).render(data.order_list, function(html){
						$('.order_list').html(html);
						myScroll.refresh();
					});
				} else {
					$('.order_list').html('');
				}
			},'json');
		}
	}

	if($(".footerMenu").length){
		$('#scroller').css({'min-height':($(window).height()-100+1)+'px'});
	}else{
		$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
	}
	//myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	if(isApp){
        $('body').append('<style>::-webkit-scrollbar{width:0px;}</style>');
        $('#container').css({'top':'-12px'});
        $('#container,#scroller').css({'position':'static'});
    }else{
        //myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    }
	
	$('#submit_btn').click(function(){
		layer.open({type: 2,content: '提交中，请稍等',shadeClose:false});
		$.post(post_url,$('#repair_form').serialize(),function(result){
			layer.closeAll();
			if(result.status == 1){
				layer.open({content:'提交成功!',shadeClose:false,btn:['确定'],yes:function(){
					window.location.href='/wap.php?c=Customer&a=index';
				}});
			}else{
				motify.log(result.msg);
			}
		}, 'json');
	});
	
	$('img').click(function(){
		var album_array = [];
		$(this).parents('.upload_list').children('.upload_item').children('img').each(function(l){
			album_array[l] = $(this).attr('src');
		});
		wx.previewImage({
			current:album_array[0],
			urls:album_array
		});
		return false;
	});
});