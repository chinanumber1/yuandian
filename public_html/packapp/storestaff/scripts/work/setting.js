$(document).ready(function(){
	
	if(common.checkWeixin()){
		$('#fixed_top').hide();
		document.title = $('#fixed_top .content').html();
	}
	
	var staffUser = common.getCache('store_staff',true);
	if(common.checkWeixin()){
		if(urlParam.openid){
			if(urlParam.openid != staffUser.openid){
				staffUser.openid = urlParam.openid;
				common.setCache('store_staff',staffUser,true);
				common.http('Storestaff&a=save_openid',{openid:staffUser.openid}, function(data){
					motify.log('已成功绑定微信');
				});
			}
		}
		if(staffUser.openid){
			$('.openid .enquire').html('已绑定');
		}else{
			$('.openid .enquire').html('未绑定');
		}
		$('.openid').show();
	}
	
	if(!staffUser.is_notice || staffUser.is_notice == 0){
		$('.notice .enquire').html('接收');
	}else{
		$('.notice .enquire').html('不接收');
	}
	$('.notice').click(function(event){
		var itemArr = ['接收','不接收'];
		common.actionsheet({
			itemArr:itemArr,
			showCancel:false,
			success:function(index){
				common.http('Storestaff&a=save_notice',{is_notice:index}, function(data){
					staffUser.is_notice = index;
					common.setCache('store_staff',staffUser,true);
					$('.notice .enquire').html(itemArr[index]);
				});
			}
		});
	});
	var flag  = false; 
	$('.cache').click(function(event){
		if(flag==true){
			return false;
		}
		flag = true;

		common.http('Storestaff&a=login',{noTip:true}, function(data){
			common.removeAllCache(false);
			common.removeAllCache(true);
			
			common.setCache('ticket',data.ticket);
			common.setCache('ticket',data.ticket,true);
			common.setCache('store_staff',data.user,true);
			common.http('Storestaff&a=index',{noTip:true}, function(data){
				flag = false;
				common.setCache('indexData',data,true);
				motify.log('清空缓存成功');
			});
		},function(){
			flag = false;
		});
	});
	$('.openid').click(function(event){
		layer.open({
			content: staffUser.openid ? '您确认前往重新授权绑定微信？' : '您确认前往绑定微信？'
			,btn: ['前往', '取消']
			,yes: function(index){
				location.href = 'http://'+requestDomain+'/wap.php?c=Packapp&a=bind&referer='+encodeURI(location.href);
				layer.close(index);
			}
		});
	});
	$('#logout').click(function(){
		layer.open({
			content: '您确定要退出吗？'
			,btn: ['确定', '取消']
			,yes: function(index){
				common.http('Storestaff&a=logout', {}, function(data){
					common.removeAllCache(false);
					common.removeAllCache(true);
					if(common.checkApp()){
						common.setCache('isLogout','true',true);
						if(common.checkAndroidApp()){
							window.pigcmspackapp.closewebview(2);
						}else{
							common.iosFunction('closewebview/2');
						}
					}else{
						location.href = 'login.html';
					}
					
					layer.close(index);
				});
			}
		});
	});
});