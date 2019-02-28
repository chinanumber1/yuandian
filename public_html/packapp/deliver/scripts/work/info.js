$(document).ready(function(){
	var indexData = common.getCache('indexData',true);
	
	if(!indexData){
		location.href = 'index.html';
	}
	
	if(common.checkWeixin()){
		$('.MyEx').css('min-height',$(window).height());
		common.fillPageBg(2,['#289ffd','#f4f4f4']);
	}
	
	$('.MyEx_top span.bjt').css('background-image','url(' + ( indexData.deliver_info.is_system ? indexData.deliver_info.system_image : indexData.deliver_info.store_image ) + ')');
	indexData.deliver_info.tip = indexData.deliver_info.is_system ? '系统配送员' : '商家配送员 ';
	common.setData(indexData);
		
	$('.peiEvelaue').click(function(){
	    openWebviewUrl('reply.html');
	});
	common.http('Deliver&a=new_info',{}, function(data){
	    $('#scoreWidth').width(data.score_width);
		common.setData(data);
	});
	
	$('.Setup').click(function(){
		/*启动监听APP退出事件*/
		if(common.checkApp()){
			setInterval(function(){
				var isLogout = common.getCache('isLogout',true);
				if(isLogout){
					common.removeCache('isLogout',true);
					location.href = 'login.html';
				}
			},300);
		}
		var href = 'setting.html';
		if(common.checkApp()){
			href = window.location.protocol+'//'+requestDomain+'/packapp/'+visitWork+'/'+href;
			
			if(common.checkAndroidApp()){
				window.pigcmspackapp.createwebview(href);
			}else if(common.checkIosApp()){
				common.iosFunction('createwebview/'+window.btoa(href));
			}
		} else{
			location.href = href;
		}
		return false;
	});
});