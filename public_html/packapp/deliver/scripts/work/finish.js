var nowPage = 1,hasMore = true;
$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	
	if(!indexData){
		location.href = 'index.html';
		return false;
	}
	if(common.checkWeixin()){
		document.title = '已完成订单';
	}
	
	if(common.checkWeixin()){
		common.fillPageBg(1,'#f4f4f4');
	}
	
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	$(".nav_end .Dgrab").width($(window).width());
	
    $(document).on('click', '.del', function(e){
		var supply_id = $(this).attr("data-spid");
		layer.open({
			content: '删除后就不再显示了，但是不影响您的接单统计!'
			,btn: ['确定', '取消']
			,yes: function(index){
				common.http('Deliver&a=new_del',{'supply_ids':supply_id}, function(data){
					layer.open({title:['删除提示：','background-color:#289FFD;color:#fff;'],content:'删除成功',btn: ['确定'],end:function(){},success:function(){$('.layermbtn span').width('100%');}});
					$(".supply_"+supply_id).remove();
					
					if($('#pick_list .robbed').size() == 0){
						$('#pick_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
					}
				});
				layer.close(index);
			}
		});
    });
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
	

	if(common.checkApp()){
		$('#pick_list').css({height:$(window).height()-44});
	}else{
		$('#pick_list').css({height:$(window).height()});
	}
	
	$('#pick_list').append('<div class="jroll-infinite-tip">正在加载中...</div>');
	common.scroll($('#pick_list'),function(scrollIndex){
		get_list(scrollIndex);
	});
	get_list();
	
});

function get_list(scrollIndex){
	if(hasMore == false){
		return false;
	}
	common.http('Deliver&a=new_finish',{page:nowPage}, function(data){
		if(data.list.length > 0){
			laytpl($('#replyListBoxTpl').html()).render(data.list, function(html){
				if(nowPage == 1){
					$('#pick_list').html(html);
				}else{
					$('#pick_list').append(html);
				}
				$(".delivery p em").each(function(){
					$(this).width($(window).width() - $(this).siblings("i").width() -55) 
				});
				common.scrollEnd(scrollIndex);
			});
			if(nowPage >= data.total_page){
				hasMore = false;
			}
			nowPage++;
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
			}
		});
	}
}