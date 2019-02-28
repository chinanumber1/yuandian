var power=common.getCache('power');
if(power){
	power.card==1?$('.shop_plus').show():$('.shop_plus').hide();//会员
	power.merchant_money==1?$('.merchant_money').show():$('.merchant_money').hide();//商家余额
	power.store_list==1?$('.shop_mange').show():$('.shop_mange').hide();//店铺
	power.shop==1?$('.shop_list').show():$('.shop_list').hide();//快店
	power.meal==1?$('.eat_list').show():$('.eat_list').hide();//餐饮
	power.group==1?$('.grounp_buy').show():$('.grounp_buy').hide();//团购
	power.appoint==1?$('.booking').show():$('.booking').hide();//预约
	power.hardware==1?$('.printer').show():$('.printer').hide();//打印机
	power.fun_group==1?$('.fans_send').show():$('.fans_send').hide();//粉丝群发
	if(power.store_list!=1&&power.shop!=1&&power.meal!=1&&power.group!=1&&power.appoint!=1){
		$('#Gallery').hide();
	}else{
		$('#Gallery').show();
	}
}



//点击进入用户评价
mui('.mui-bar-tab').on('tap','.shop_take',function(e){
	if(this.className.indexOf('mui-active') >= 0) {
		return false;
	} else {
		mui.openWindow({
			url: 'user_rating.html',
			id: 'user_rating'
		});
	}		
});
//
mui('.mui-bar-tab').on('tap', '.shop_home', function(e) {
	if(this.className.indexOf('mui-active') >= 0) {
		return;
	} else {
		mui.openWindow({
			url: 'index.html',
			id: 'index'
		});
	}
});

//点击进入会员页面
mui('.mui-bar-tab').on('tap','.shop_plus',function(e){
	if(this.className.indexOf('mui-active') >= 0) {
		return;
	} else {
		mui.openWindow({
			url: 'plus.html',
			id: 'plus'
		});
	}
});

//点击我的进入页面
mui('.mui-bar-tab').on('tap', '.shop_my', function(e) {
	if(this.className.indexOf('mui-active') >= 0) {
		return;
	} else {
		mui.openWindow({
			url: 'personal_center.html',
			id: 'personal_center'
		});
	}

});

var exitLayer = -1;
function appbackmonitor(){
	if(exitLayer != -1){
		window.pigcmspackapp.closewebview(2);
	}else{
		layer.closeAll();
		exitLayer = layer.open({
			content: '您确定要退出程序吗？再次按返回键将退出。'
			,btn: ['确定', '取消']
			,yes: function(index){
				window.pigcmspackapp.closewebview(2);
				layer.close(index);
			}
			,end: function(index){
				exitLayer = -1;
			}
		});
	}
}