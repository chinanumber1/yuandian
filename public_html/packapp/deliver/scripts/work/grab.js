var lng = 0,lat = 0;
var deliver_see_freight_charge = 1;
    $(document).ready(function(){
	var indexData = common.getCache('indexData',true);
    common.http('Deliver&a=config',{},function(data){
    	common.setCache('deliver_config',data);
    	deliver_see_freight_charge = data.deliver_see_freight_charge;
	});

	if(!indexData){
		location.href = 'index.html';
		return false;
	}else{
		lng = indexData.deliver_info.map_lng;
		lat = indexData.deliver_info.map_lat;
	}
	if(common.checkWeixin()){
		document.title = '待抢单列表';
	}
	
	if(common.checkWeixin()){
		common.fillPageBg(1,'#f4f4f4');
	}
	
	$(".delivery p em").each(function(){
       $(this).width($(window).width() - $(this).siblings("i").width() -55) 
    });
    var mark = 0;
    $(document).on('click', '.rob', function(e){
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-spid");
		common.http('Deliver&a=grab',{'supply_id':supply_id}, function(data){
			mark = 0;
			layer.open({title:['抢单提示：','background-color:#289FFD;color:#fff;'],content:'抢单成功',btn: ['确定'],end:function(){},success:function(){$('.layermbtn span').width('100%');}});
			$(".supply_"+supply_id).remove();
			if($('#grab_list .robbed').size() == 0){
				$('#grab_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
			}
		},function(data){
			mark = 0;
			layer.open({title:['抢单提示：','background-color:#289FFD;color:#fff;'],content:data.errorMsg,btn: ['确定'],end:function(){},success:function(){$('.layermbtn span').width('100%');}});
//			$(".supply_"+supply_id).remove();
			if($('#grab_list .robbed').size() == 0){
				$('#grab_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
			}
		});
    });
	getList();
	// var timer = setInterval(getList, 10000);
	
	$(document).on('click', '.go_detail', function(e){
		var href = 'detail.html?supply_id='+$(this).data('spid');
		var deliver_config = common.getCache('deliver_config');
		if(deliver_config.deliver_see_detail == 0){
            layer.open({title:['提示：','background-color:#289FFD;color:#fff;'],content:'抱歉！须抢单后才可查看订单详情。',btn: ['确定']});
            return false;
		}
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
	
	if(common.checkIos()){
		console.log('IOS判断');
		window.addEventListener('touchstart',loadTipMp3, false);
	}else{
		loadTipMp3();
	}
});

var isLoaded = false;
function loadTipMp3(){
	if(isLoaded == false){
		console.log('加载音乐');
		var myVideo=document.getElementById("newOrderMp3");
		myVideo.load();
		isLoaded = true;
	}
}

var newOrderTipIndex = -2;
var gray_count = 0;
function pollorder(){
	common.http('Deliver&a=new_index',{noTip:true,poll:1,onlyGrab:true}, function(data){
		var myVideo = document.getElementById("newOrderMp3");
		var data_gray_count = parseInt(data.gray_count);
		
		console.log('======');
		console.log(gray_count);
		console.log(data_gray_count);
		
		if(gray_count != 0 && data_gray_count == 0){
			myVideo.pause();
			myVideo.currentTime = 0.0;
			if(newOrderTipIndex != -2){
				console.log('没有订单了，关闭了提示层');
				layer.close(newOrderTipIndex);
			}
			if($('#grab_list .psnone').size() == 0){
				console.log('订单没有了，切换成默认样式');
				$('#grab_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
			}
		}else if(data_gray_count > gray_count){
			if(gray_count == 0){
				getList();
				newOrderTipIndex = layer.open({
					content: '您有新的待抢订单需要处理'
					,btn: ['关闭']
					,shadeClose:false
					,yes: function(index){
						console.log('关闭了音乐-提示层ID:'+newOrderTipIndex);
						myVideo.pause();
						myVideo.currentTime = 0.0;
						layer.close(index);
					}
				});
				myVideo.play();
			}else{
				motify.log('<a onclick="javascript:getList();" style="color:white;">有新订单待抢，点击文字刷新</a>',10000,false,5);
			}
		}
		gray_count = data_gray_count;
	});
}


function getList() {
	var deliver_lng = common.getCache('deliver_lng');
	var deliver_lat = common.getCache('deliver_lat');
	if(!deliver_lng){
		deliver_lng = '';
	}
	if(!deliver_lat){
		deliver_lat = '';
	}
	list_detail(deliver_lat, deliver_lng);
	return false;
}

function locationOk(name,lng,lat){
	list_detail(lat, lng);
}

var pollorderTimer = null;
function list_detail(lat, lng){
	common.http('Deliver&a=new_deliver_list',{'lat':lat, 'lng':lng}, function(data){
		gray_count = data.length;
		if(data.length > 0){
			laytpl($('#replyListBoxTpl').html()).render(data, function(html){
				$('#grab_list').html(html);
				$(".delivery p em").each(function(){
					$(this).width($(window).width() - $(this).siblings("i").width() -55) 
				});
			});
		}else{
			$('#grab_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
		}
		if(pollorderTimer == null){
			pollorderTimer = setInterval('pollorder()',3000);	//3秒一次轮询新订单
			console.log('轮询：'+pollorderTimer);
		}
	},function(data){
		$('#grab_list').html('<div class="psnone"><img src="images/qdz_02.jpg"></div>');
		if(pollorderTimer == null){
			pollorderTimer = setInterval('pollorder()',3000);	//3秒一次轮询新订单
			console.log('轮询：'+pollorderTimer);
		}
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