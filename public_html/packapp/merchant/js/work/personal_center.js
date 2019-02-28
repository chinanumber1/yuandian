mui.init();
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var config = common.getCache('config',true);
if(!config){
	common.http('Merchantapp&a=config', {}, function(data){
		common.setCache('config',data,true);
		config=common.getCache('config');
	});
}


if(config.open_merchant_change_phone == '1'){
	console.log(1111)
	$('.modify_phone').show();

}

function set_index_data(data){
	if(data.logo){
		$('.center img').attr('src',data.logo);
	}
	$('.today').text(data.count_number.todayordercount);
	$('.month').text(data.count_number.monthordercount);
	$('.alls').text(data.count_number.allordercount);
	$('.plus').text(data.count_number.fans_count);
	$('.names').text(data.name);
}

//首页主体数据
/*先初始化判断有没有缓存，有的话先展示缓存内容*/
var index_data = common.getCache('index_data',true);
if(index_data){
	set_index_data(index_data);
}
common.http('Merchantapp&a=index',{'client':client,noTip:1},function(data){
	common.setCache('index_data',data,true);
	if(data.invit_code!=''){
		$('#invit_code').html(data.invit_code);
		$('.invit_code').show();
	}
	set_index_data(data);
});  

//修改密码
mui('.mui-content').on('tap','.modify_password',function(e){
	openWindow({
		url:'modify_password.html',
		id:'modify_password'
	});
});

mui('.mui-content').on('tap','.modify_phone',function(e){
	openWindow({
		url:'modify_phone.html',
		id:'modify_phone'
	});
});


mui('.mui-content').on('tap','.sign_out',function(e){
	var btnArray = ['否', '是'];
	mui.confirm('您确认要退出登录吗？', '退出登录', btnArray, function(e){
		if(e.index == 1){
			common.removeCache('ticket');
			common.removeCache('ticket',true);
			location.href = 'login.html';
		}
	})
});

//打印机管理点击
mui('.mui-content').on('tap','.printer',function(e){
	openWindow({
		url:'printer.html',
		id:'printer'
	});
});

//粉丝群发点击
mui('.mui-content').on('tap','.fun_group',function(e){
	openWindow({
		url:'fun_group.html',
		id:'fun_group'
	});
});


//银行卡管理
mui('.mui-content').on('tap','.bank_card',function(e){
	openWindow({
		url:'bank_manger.html',
		id:'bank_manger'
	});
});


if(common.checkWeixin()){
	$('.mui-bar-nav').remove();
}