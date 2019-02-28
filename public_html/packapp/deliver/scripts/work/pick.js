var lng = 0,lat = 0,now_tab = 'pick';
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	
	if(!indexData){
		location.href = 'index.html';
		return false;
	}
	if(common.checkWeixin()){
		document.title = '处理中订单';
		common.fillPageBg(1,'#f4f4f4');
	}
	
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	
	if(common.checkAndroidApp()){
		$('.Navigation').css('top','44px');
	}
	$(".Dgrab").css({"margin-top":"40px"});
	
	$(".nav_end .Dgrab").width($(window).width());
	
	var mark = 0;
    $(document).on('click', '.Pick,.Dis,.service', function(e){
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-spid");
		
		if(now_tab == 'pick'){
			var postUrl = 'Deliver&a=pick';
		}else if(now_tab == 'send'){
			var postUrl = 'Deliver&a=send';
		}else if(now_tab == 'my'){
			var postUrl = 'Deliver&a=complete';
		}
		
		common.http(postUrl,{'supply_id':supply_id}, function(data){
			mark = 0;
			layer.open({title:['抢单提示：','background-color:#289FFD;color:#fff;'],content:'更新配送状态成功！',btn: ['确定'],end:function(){},success:function(){$('.layermbtn span').width('100%');}});
			$(".supply_"+supply_id).remove();
			
			if($('#pick_list .robbed').size() == 0){
				$('#pick_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
			}
			
			common.http('Deliver&a=pick_count',{noTip:true}, function(data){
				common.setData(data);
			});
		},function(){
			mark = 0;
			layer.open({title:['抢单提示：','background-color:#289FFD;color:#fff;'],content:data.errorMsg,btn: ['确定'],end:function(){},success:function(){$('.layermbtn span').width('100%');}});
			$(".supply_"+supply_id).remove();
			if($('#pick_list .robbed').size() == 0){
				$('#pick_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
			}
		});
    });
	
	$('.Navigation li').click(function(){
		now_tab = $(this).data('type');
		$(this).addClass('on').siblings().removeClass('on');
		get_list();
	});
	$('.Navigation li:eq(0)').trigger('click');
	
	$(document).on('click', '.go_detail', function(e){
		var href = 'detail.html?supply_id='+$(this).data('spid');
		if(common.checkApp()){
			common.setCache('supply_id',$(this).data('spid'),true);
			common.setCache('supply_status',$(this).data('status'),true);
			
			href = window.location.protocol+'//'+requestDomain+'/packapp/'+visitWork+'/'+href;
			if(common.checkAndroidApp()){
				window.pigcmspackapp.createwebview(href);
			}else if(common.checkIosApp()){
				var iosHref = window.btoa(href);
				iosHref = iosHref.replace('/','&');
				common.iosFunction('createwebview/'+iosHref);
			}
		} else{
			location.href = href;
		}
    });
	
	common.http('Deliver&a=pick_count',{noTip:true}, function(data){
		common.setData(data);
	});
});

function get_list(){
	if(now_tab == 'pick'){
		var postUrl = 'Deliver&a=new_pick_list';
	}else if(now_tab == 'send'){
		var postUrl = 'Deliver&a=new_send_list';
	}else if(now_tab == 'my'){
		var postUrl = 'Deliver&a=new_my_list';
	}
	$('#pick_list').empty();
	common.http(postUrl,{'lat':lat, 'lng':lng}, function(data){
		if(data.length > 0){
			data[0].now_tab = now_tab;
			laytpl($('#replyListBoxTpl').html()).render(data, function(html){
				if($('#scroller').width() == 0 || $('#pick_list').width() == 0){
					$('#scroller').width($(window).width());
					$('#pick_list').width($(window).width());
				}
				$('#pick_list').html(html);
				$(".delivery p em").each(function(){
					$(this).width($(window).width() - $(this).siblings("i").width() -55) 
				});
			});
		}else{
			$('#pick_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
		}
	},function(data){
		$('#pick_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
	});
}

function pageShowFunc(){
	var supply_id = common.getCache('supply_id',true);
	var supply_status = common.getCache('supply_status',true);
	if(supply_id && supply_status){
		common.http('Deliver&a=new_detail',{supply_id:supply_id}, function(data){
			if(data.supply.status != supply_status || data.supply.is_hide == '1'){
				$('.supply_'+supply_id).remove();
				if($('#pick_list .robbed').size() == 0){
					location.reload();
				}else{
					common.http('Deliver&a=pick_count',{noTip:true}, function(data){
						common.setData(data);
					});
				}
			}
		});
	}
}