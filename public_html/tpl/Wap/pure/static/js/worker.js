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
	var activePos = $('.tabs-header .active').position(), is_click = false;
	if (typeof activePos != 'undefined') {
		is_click = true;
		function changePos() {
			activePos = $('.tabs-header .active').position();
			$('.border').stop().css({
				left: activePos.left,
				width: $('.tabs-header .active').width()
			});
		}
		changePos();
		get_list(1);
	}
	$('.tabs-header a').on('click', function (e) {
		e.preventDefault();
		$('.tabs-header a').stop().parent().removeClass('active');
		$(this).stop().parent().addClass('active');
		changePos();
		var status = $('.tabs-header ul li.active').data('status');
		get_list(status);
	});
	

	function get_list(status) {
		$.get("/wap.php?c=Worker&a=ajax_list",{'status':status},function(data){
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

	if($(".footerMenu").length){
		$('#scroller').css({'min-height':($(window).height()-1000+1)+'px'});
	}else{
		$('#scroller').css({'min-height':($(window).height()-500+1)+'px'});
	}
	myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	if(isApp){
        $('body').append('<style>::-webkit-scrollbar{width:0px;}</style>');
        $('#container').css({'top':'-12px'});
        $('#container,#scroller').css({'position':'static'});
    }else{
        myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
    }
	if($("#upload_list").length){
        var imgUpload = new ImgUpload({
            fileInput: "#fileImage",
            container: "#upload_list",
            countNum: "#uploadNum",
			url:"/wap.php?c=Worker&a=ajaxImgUpload"
		});
		$('#submit_btn').click(function(){
			$('#j_cmnt_input').val($.trim($('#j_cmnt_input').val()));
			if($('#j_cmnt_input').val() == ''){
				motify.log('请填写内容');
				return false;
			}
			layer.open({type: 2,content: '提交中，请稍等',shadeClose:false});
			$.post(post_url,$('#repair_form').serialize(),function(result){
				layer.closeAll();
				if(result.status == 1){
					layer.open({content:'提交成功!',shadeClose:false,btn:['确定'],yes:function(){
						layer.closeAll();
						location.href = location_url;
						//window.location.reload();
					}});
				}else{
					motify.log(result.msg);
				}
			}, 'json');
		});
	}
	//保存跟进内容
	$('#submit_follow').click(function(){
		$('#followcontent').val($.trim($('#followcontent').val()));
		if($('#followcontent').val() == ''){
			motify.log('请填写跟进内容');
			return false;
		}
		layer.open({type: 2,content: '提交中，请稍等',shadeClose:false});
		$.post(post_follow_url,$('#repair_form').serialize(),function(result){
			layer.closeAll();
			if(result.status == 1){
				layer.open({content:'提交成功!',shadeClose:false,btn:['确定'],yes:function(){
					layer.closeAll();
					location.href = location_url;
					//window.location.reload();
				}});
			}else{
				motify.log(result.msg);
			}
		}, 'json');
	});
	$(document).on('click', '.do_work', function(){
		var pigcms_id = $(this).attr('data-id'), obj = $(this);
		obj.unbind('click');
		$.post(post_url,{'pigcms_id':pigcms_id, 'status':2},function(result){
			layer.closeAll();
			if(result.status == 1){
				var html = '<form id="repair_form" class="village_repair"><section><textarea id="note_msg" class="newarea" name="content" placeholder="接单留言" style="border-bottom:0;padding: 0;height: 100px;"></textarea></section></form>'
				//html += '<div class="area_btn"><input type="button" id="submit_btn" class="do_work" data-id=""  value="确定"/></div>';
				var pageii = layer.open({
				    content: html,
				    style: 'width:100%;',
				    shadeClose:false,
				    btn:['确定'],
				    yes:function(){
				    	$.post(post_url,{'pigcms_id':pigcms_id, 'status':2, 'msg':$('#note_msg').val()},function(response){
				    		if(result.status == 1){
				    			if (typeof contentDetail == 'undefined') {
									obj.unbind('click').removeClass('do_work').html('去处理');
									layer.closeAll();
								} else {
									layer.closeAll();
									location.href = location_url;
									//window.location.reload();
								}
				    		} else {
				    			motify.log(result.msg);
				    		}
				    	});
				    }
				});
			}else{
				motify.log(result.msg);
			}
		}, 'json');
		return false;
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