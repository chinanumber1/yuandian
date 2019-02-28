mui.init();
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var pindex=1;//初始化页面数
var porson_val="nickname";//个人派发下拉框值
var card_group_id=[];
var uid='';
mui('.mui-scroll-wrapper').scroll({
          deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006 
});
mui('.sroll_hua').scroll({
  	deceleration: 0.0005,//flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
 		scrollY:false,
    scrollX:true
});
//tab长度
var tab_width=$('.class_list ul li').width();
$('.class_list ul').width(tab_width*4);


// mui('.mui-bar-nav').on('tap','a.mui-icon-left-nav',function(e){
// 	openWindow({
// 		url:'coupon.html',
// 		id:'coupon'
// 	});
// });

// 
function trim(str){ //删除左右两端的空格
　　return str.replace(/(^\s*)|(\s*$)/g, "");
}
//初次加载页面渲染内容
common.http('Merchantapp&a=send_coupon',{'ticket':ticket,'client':client},function(data){
	console.log(data);

	if(data.card_group.length>0){
		var str='';
		$.each(data.card_group,function(i,val){
			str+='<div class="diamonds mui-clearfix checkbox_click" ><img src="images/42-1_08.png" class="mui-pull-left"> <span>'+val.name+'</span> <i class="mui-pull-right " data-id='+val.id+'></i></div>';
		});
		$('.all_plus').html(str);
	}else{
		mui.toast('还没有可用的分组	,快去创建吧');
	}
	if(data.coupon_list.length>0){
		var sum='';
		$.each(data.coupon_list,function(i,val){
			sum+='<div class="promotion mui-clearfix checkbox_click"><img src='+val.img+' class="mui-pull-left"> <span>'+val.name+'</span> <i class="mui-pull-right" data-id='+val.coupon_id+'></i></div>';
		});
		$('.allCoupons').html(sum);
		$('.ponsonCoupons').html(sum);
		$('.all_change_coupons').html(sum);
		$('.weixin_coupons').html(sum);
		
		
	}else{
		mui.toast('还没有可用的优惠券,快去创建吧');
	}

	common.http('Merchantapp&a=card_detail',{'ticket':ticket,'client':client},function(data){
		console.log(data);	
		if(data.data.weixin_send_couponlist!=null){
			var coupon_ids=[];
				
			coupon_ids=data.data.weixin_send_couponlist.split(',');
			console.log(coupon_ids);
			$.each($('.weixin_coupons .checkbox_click'),function(i,val){
				var b=$(this).find('i').attr('data-id');
				var me=this;
		  		var a = coupon_ids.length;
				while (a--) {
				    if (coupon_ids[a] == b) {
				     	$(me).find('i').addClass('active');
				    }
				}
			});
			  
			  
			
			
			$('.set_money input').val(data.data.weixin_send_money);
		}


	});
	

});






//头部tab切换
mui('.mui-content').on('tap','.class_list li',function(e){
	$(this).addClass('active').siblings('li').removeClass('active');
	var distance=$(this).offset().left;
	console.log(distance)
	$('.class_list').animate({"scrollLeft":distance},300);
	var index=$(this).index();
	$('.show_hide>div:eq('+index+')').removeClass('hidden').siblings('div').addClass('hidden');
	if(index!=2){
		$('.distribution_coupons').removeClass('hidden');
		$('.preservation').addClass('hidden');
	}else{
		$('.distribution_coupons').addClass('hidden');
		$('.preservation').removeClass('hidden');
	}
});

//分组派发多选框点击
mui('.mui-content').on('tap','.checkbox_click',function(e){
	$(this).find('i').is('.active')?$(this).find('i').removeClass('active'):$(this).find('i').addClass('active');
});
// 根据会员分组获取优惠券
 mui('.mui-content').on('tap','.all_plus>.checkbox_click',function(e){
 	card_group_id=[];
 	$.each($('.all_plus .checkbox_click'),function(i,val){
		if($(this).find('i').is('.active')){
			card_group_id.push($(this).find('i').data('id'));
		}
	});
 	common.http('Merchantapp&a=ajax_get_send_coupon',{'ticket':ticket,'client':client,'card_group_id':card_group_id},function(data){
 		console.log(data);
 		if(data.coupon_list!=''&&data.coupon_list!=null){

			addCoupons('.allCoupons',data.coupon_list);
		}else{
			mui.toast('该分组下还没有优惠券');
		}
 	});
 });
// 封装方法
function addCoupons(class1,coupon_list){
	var sum='';
	if(coupon_list!=null){
		$.each(coupon_list,function(i,val){
			sum+='<div class="promotion mui-clearfix checkbox_click"><img src='+val.img+' class="mui-pull-left"> <span>'+val.name+'</span> <i class="mui-pull-right" data-id='+val.coupon_id+'></i></div>';
		});
		$(class1).html(sum);
	}else{
		mui.toast('还没有可以派发的优惠券');
	}
	
}


// 个人派发 搜索按钮点击事件
mui('.mui-content').on('tap','.search_coupon',function(e){
	var keyword=trim($('.search_record input').val());
	if(keyword!=''){
		common.http('Merchantapp&a=get_user',{'ticket':ticket,'client':client,'keyword':porson_val,'search_val':keyword},function(data){
			console.log(data);
			if(data.user_list!=null){
				$('.searchNull').hide();
				var str='';
				$.each(data.user_list,function(i,val){
					str+='<div class="mui-card"><div class="mui-card-header checkbox_click" data-uid='+val.uid+'><span>用户会员卡ID: '+val.id+'</span><i class=""></i></div><div class="mui-card-content"><ul><li><i>用户昵称 </i><span>'+val.nickname+'</span></li><li><i>用户手机号 </i><span>'+val.phone+'</span></li></ul></div></div>';
				});
				$(".uesr_plus").html(str);

			}else{
				$('.searchNull').show();
				mui.toast('暂未查找到相关用户');
			}
		});
	}else{
		mui.toast('请输入内容再进行搜索');
	}
	

});
//个人派发
mui('body').on('tap','.uesr_plus .checkbox_click',function(e){
	$(this).find('i').addClass('active').parents('.mui-card').siblings('.mui-card').find('i').removeClass('active');
	uid=$(this).data('uid');
	common.http('Merchantapp&a=ajax_get_send_coupon',{'ticket':ticket,'client':client,'uid':uid},function(data){
		console.log(data);
		if(data.coupon_list!=''&&data.coupon_list!=null){
			addCoupons('.ponsonCoupons',data.coupon_list);
		}
	});
});

// 派发优惠劵点击
// mui('body').on('tap','.distribution_coupons',function(e){
$('body').off('click','.distribution_coupons').on('click','.distribution_coupons',function(e){

	if($('.class_basic ').is('.active')){//分组派发的派发优惠券点击
		var coupon_id =[];
		$.each($('.allCoupons .checkbox_click'),function(i,val){
			if($(this).find('i').is('.active')){
				coupon_id.push($(this).find('i').attr('data-id'));
			}
		});
		console.log(coupon_id);
		if(card_group_id.length>0){
			if(coupon_id.length>0){
				common.http('Merchantapp&a=card_new_send ',{'ticket':ticket,'client':client,'coupon_id':coupon_id,'card_group_id':card_group_id},function(data){
					console.log(data);
					if(data.status==1){
						mask.show();
						mui('#middlePopover').popover('show');
						$('.successText').text('添加派送记录成功');
					}else{
						mui.toast(data.msg);
					}
				});
			}else{
				mui.toast('请选择你要派发的优惠劵');
			}
		}else{
			mui.toast('请选择你要派发的分组');
		}
		
		
	} else if($('.porsons').is('.active')){//个人派发的派发优惠券点击
		if(uid==''){
			mui.toast('请先选择用户会员卡在进行派发');
		}else{
			var coupon_id =[];
			$.each($('.ponsonCoupons .checkbox_click'),function(i,val){
				if($(this).find('i').is('.active')){
					coupon_id.push($(this).find('i').attr('data-id'));
				}
			});
			if(coupon_id.length>0){
				common.http('Merchantapp&a=card_new_send ',{'ticket':ticket,'client':client,'coupon_id':coupon_id,'uid':uid},function(data){
					console.log(data);
					if(data.status==1){
						mask.show();
						mui('#middlePopover').popover('show');
						$('.successText').text('添加派送记录成功');
					}else{
						mui.toast(data.msg);
					}
				});
			}else{
				mui.toast('请选择你要派发的优惠劵');
			}
			
		}
		
	}else if($('.weixin_buy').is('.active')){//微信购买派发的优惠券点击
		var money=$('.set_money input').val();
		console.log(money);
		var coupon_id =[];
		$.each($('.weixin_coupons  .checkbox_click'),function(i,val){
				if($(this).find('i').is('.active')){
					coupon_id.push($(this).find('i').attr('data-id'));
				}
		});
		console.log(money);
		if(money!=''){
			if(Number(money)>=0.01){
				if(coupon_id.length>0){
					common.http('Merchantapp&a=card_new_weixin_send_save ',{'ticket':ticket,'client':client,'coupon_id':coupon_id,'money':money},function(data){
						console.log(data);
						if(data.msg=="保存成功"){
							mask.show();
							mui('#middlePopover').popover('show');
							$('.successText').text('设置微信购买派发成功');
						}else{
							mui.toast(data.msg);
						}
					});
				}else{
					mui.toast('请选择你要派发的优惠劵');
				}
			}else{
				mui.toast('请填写大于等于0.01的数字');
			}
			
		}else{
			mui.toast('请填写你要购买的金额');
		}
		
	}
});

// 导航条全部派发点击
mui('.mui-content').on('tap','.allPai',function(e){
	common.http('Merchantapp&a=ajax_get_send_coupon ',{'ticket':ticket,'client':client,'all':1},function(data){
		console.log(data);
		if(data.coupon_list!=null||data.coupon_list!=[]){
			addCoupons('.all_change_coupons',data.coupon_list);
		}
	});
	// 保存按钮点击
	mui('.preservation').on('tap','a',function(e){
		var coupon_id =[];
		$.each($('.all_change_coupons .checkbox_click'),function(i,val){
			if($(this).find('i').is('.active')){
				coupon_id.push($(this).find('i').data('id'));
			}
		});
		common.http('Merchantapp&a=card_new_send ',{'ticket':ticket,'client':client,'coupon_id':coupon_id,'all':1},function(data){
			console.log(data);
			if(data.status==1){
				mask.show();
				mui('#middlePopover').popover('show');
			}
		});
	});
});

//筛选用户手机
(function($, doc) {
	$.init();
	
	mui('.mui-content').on('tap','#user_phone',function(e) {
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData([
		{
			value: 'nickname',
			text: '用户名称'
		}, {
			value: 'phone',
			text: '用户手机'
		}]);
		var that=this;
		userPicker.show(function(items) {
			that.children[0].innerHTML = items[0].text;
			that.children[0].style.color="#333333";
			porson_val=items[0].value;
		}, false);
	
	});
	
})(mui, document);
var mask = mui.createMask();
//派发优惠劵点击
// mui('.distribution_coupons').on('tap','a',function(e){
// 	mask.show();
// 	mui('#middlePopover').popover('show');
// });
//点击蒙版
mui('body').on('tap','.mui-backdrop',function(){
	mui('#middlePopover').popover('hide');
	mask.close();
});

//派发记录点击
mui('.mui-bar-nav').on('tap','a.mui-pull-right',function(e){
	openWindow({
		url:'distribution_records.html',
		id:'distribution_records'
	});
});

//查看派发记录点击
mui('#middlePopover').on('tap','a',function(e){
	openWindow({
		url:'distribution_records.html',
		id:'distribution_records'
	});
});
//关闭按钮点击
mui('body').on('tap','.mui-popover>p',function(e){
	mui('#middlePopover').popover('hide');
	mask.close();
});








