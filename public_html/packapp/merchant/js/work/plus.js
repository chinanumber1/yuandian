mui.init();
mui('.mui-scroll-wrapper').scroll({
    deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006 
});
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var be_date='';
var stor_list=[];//
var type_list=[];//
var pindex=1;
var change_class=[];
var keyword="";
var searchtype="";
var moren=0;//下拉框默认选中
var userList=1;//查看详情用户分组下拉默认选中
var tops=0;
function selectChange(lists){
	stor_list=[];
	$.each(lists,function(i,val){
		var goods_id_list ={'value':'','text':''};
		goods_id_list.text=val.name;
		goods_id_list.value=val.store_id;
		stor_list.push(goods_id_list);
	});
}
// 渲染所有会员卡
function plusllshou(pindex,keyword,searchtype){
	common.http('Merchantapp&a=card_new_list',{'client':client,'pindex':pindex,'keyword':keyword,'searchtype':searchtype},function(data){
		//console.log(data);
		if(data.data.length==0){
			$('.pullup').html('没有更多数据啦');
			$('.loading').hide();
			$('.pullup').show();

		}else{
			data.data.length<=9&&$('.pullup').html('没有更多数据啦');
			laytpl(document.getElementById('pluscardLists').innerHTML).render(data, function(html){
				$('.allPluscard').append(html);
				$('.loading').hide();
				$('.pullup').show();
			});
			if(data.data.length>9&&pindex<=Number(data.page)){
				var flag = false;
				// 上拉加载或下拉刷新
				$(window).scroll(function(e) {
				   e.stopPropagation();
					e.preventDefault(); 
				    if(flag){
				      //数据加载中
				      return false;
				    }
				    //上拉加载
				    if ($(document).scrollTop() == $(document).height() - $(window).height()) {
			    		pindex++;
			    		$('.pullup').hide();
				    	$('.loading').show();
				       plusllshou(pindex,keyword,searchtype);
				       flag=true;
				    }
				});
			
			}	
		}	
	});
}

// 渲染所有会员数据
var userPluses = common.getCache('userPluses',true);
if(userPluses){
	setPlusesData(userPluses);
}
function setPlusesData(data){
	$('.all_order li:eq(0) ul li:eq(0)').text(data.today_count);
	$('.all_order .this_month').text(data.this_month_count);
	$('.all_order .all_counts').text(data.all_count);
	//更多会员卡新增页面渲染
	$('#middlePopover7 ul li:eq(0) dl dt').text(data.this_week_count);
	$('#middlePopover7 ul li:eq(1) dl dt').text(data.this_month_count);
	$('#middlePopover7 ul li:eq(2) dl dt').text(data.this_season_count);
	$('#middlePopover7 ul li:eq(3) dl dt').text(data.this_year_count);
	$('#middlePopover7 ul li:eq(4) dl dt').text(data.last_week_count);
	$('#middlePopover7 ul li:eq(5) dl dt').text(data.last_month_count);
	$('#middlePopover7 ul li:eq(6) dl dt').text(data.last_seasion_count);
	$('#middlePopover7 ul li:eq(7) dl dt').text(data.last_year_count);
}

function allpluses(){
	common.http('Merchantapp&a=card_num_date',{'client':client,'noTip':true},function(data){
		console.log(data);
		common.setCache('userPluses',data,true);
		setPlusesData(data);
	});
}
$('.allPluscard').html('');
plusllshou(pindex,keyword,searchtype);
allpluses();


/*搜索按钮点击*/
mui('.search_plus').on('tap','.search_btn',function(e){
	keyword=$('.select_input input').val();
	if(keyword!=""){
		pindex=1;
		$('.allPluscard').html('');
		if($('#seach_key').text()=="会员名称"){
			searchtype='nickname';
			plusllshou(pindex,keyword,searchtype);
		}else if($('#seach_key').text()=="手机号"){

			searchtype='phone';
			plusllshou(pindex,keyword,searchtype);
		}else{
			searchtype='card_id';
			plusllshou(pindex,keyword,searchtype);
		}
	}else{
		mui.toast('请输入搜索值');
	}
});



// 会员卡正常、禁止点击
mui('.mui-content').on('tap','.status',function(e){
	var id=$(this).parents('.mui-card').data('id');
	var status=$(this).data('status');
	if(status==1){
		status=0;
	}else{
		status=1;
	}
	console.log(status);
	common.http('Merchantapp&a=card_new_status',{'client':client,'status':status,'card_id':id},function(data){
		mui.toast('修改成功');
		// $('.allPluscard').html('');
		// plusllshou();
	});
	if($(this).is('.disalbled ')){
		$(this).removeClass('disalbled ').addClass('normal');
		$(this).text('正常');
		status=$(this).data('status',1);
	}else if($(this).is('.normal')){
		$(this).removeClass('normal').addClass('disalbled');
		$(this).text('禁止');
		status=$(this).data('status',0);
	}
	
});


mui('.plus_man').on('tap','.search_click',function(e){
	$('.plus_man').addClass('hidden');
	$('.search_plus').removeClass('hidden');
	$('.select_input input').val('');
	$('.search_plus input').focus();

});

mui('.search_plus').on('tap','.mui-pull-left',function(e){
	$('.plus_man').removeClass('hidden');
	$('.search_plus').addClass('hidden');
	$('.allPluscard').html('');
	pindex=1;keyword="";searchtype="";
	plusllshou(pindex,keyword,searchtype);

});

//查询记录按钮点击
mui('.mui-bar-nav').on('tap','.recharge_record',function(e){
	openWindow({
		url:'recharge_record.html',
		id:'recharge_record'
	});
});

//会员添加按钮点击
var mask = mui.createMask();
$(".add_plus").on("click",function(){
	tops=$(document).scrollTop();
    $("body").css({
        'overfloww': 'hidden',
        'position': 'fixed',
        'top': -tops
         })
    $("#dialogs").show();
    $('#middlePopover1 input').val('');
})

// 确认按钮点击
mui('body').on('tap','.new_card_confirm',function(e){
	var physical_id=$('#middlePopover1 ul li:eq(0) input').val();
	var card_money=$('#middlePopover1 ul li:eq(1) input').val();
	var card_score=$('#middlePopover1 ul li:eq(2) input').val();
	var status=1;
	if($('#state').text()=="请选择状态"){
		mui.toast('请选择会员卡状态')
	}else{
		status=$('#state').text()=="禁止"?0:1;
	}
	console.log(card_money);
	common.http('Merchantapp&a=card_new_add_user',{'client':client,'status':status,'physical_id':physical_id,'card_score':card_score,'card_money':card_money},function(data){
		mui.toast(data.msg);
		$(".allPluscard").html("");
		plusllshou();
		allpluses();
		
	});
    $("body").css({
        'overfloww':'auto',
        'position':'static',
		'overflow-y':'auto'

    })
    document.documentElement.scrollTop=tops;
    document.body.scrollTop=tops;
	//$("html,body").animate({scrollTop:tops},10);
    $("#dialogs").hide();
});

//编辑会员卡点击
mui('.plus_class').on('tap','.edit_plus',function(e){
	openWindow({
		url:'edit_plus.html',
		id:'edit_plus'
	});
});
//新增会员更多点击
mui('.mui-content').on('tap','.show_pluses',function(){
	mask.show();
	mui('#middlePopover7').popover('show');

});
mui('body').on('tap','.mui-popover7 p i',function(){
	mask.close();
	mui('#middlePopover7').popover('hide');
});
// 消费记录
var page_jilu=1;
function recardsJilu(id,page_jilu){
	common.http('Merchantapp&a=card_new_consume_record',{'client':client,'id':id,'pindex':page_jilu},function(data){
		console.log(data);
		if(data.data.length==0){
			$('.pullup1').html('没有更多数据啦');
			$('.loading1').hide();
			$('.pullup1').show();
			// $('.all_cardsjilu').html('');
		}else{
			data.data.length<=9&&$('.pullup1').html('没有更多数据啦');
			laytpl(document.getElementById('recardsJilu').innerHTML).render(data, function(html){
				$('.all_cardsjilu').append(html);
				$('.loading1').hide();
				$('.pullup1').show();
			});
			if(data.data.length>9){
				 var flag = false;

				$('.mui-popover8').scroll(function(e) {
				    //上拉加载
				     e.stopPropagation();
				    if(flag){
				      //数据加载中
				      return false;
				    }
				    if ($('.mui-scroll-wrapper').scrollTop() >= $('.mui-scroll-wrapper').height() - $('.mui-popover8').height()) {
				    	$('.pullup1').hide();
				    	$('.loading1').show();
				    	flag = true;
				        page_jilu++;
				       	recardsJilu(id,page_jilu)
				    }
				});
			}
		}
	});
}

mui('.mui-content').on('tap','.records_jilu',function(e){
	var id=$(this).data('id');
	$('.all_cardsjilu').html('');
	page_jilu=1;
	recardsJilu(id,page_jilu);
	mask.show();
	mui('#middlePopover8').popover('show');
	
});
//关闭按钮点击
mui('.mui-popover8').on('tap','p i.mui-pull-right',function(e){
	mask.close();
	mui('#middlePopover8').popover('hide');
});


//分组管理点击
mui('.plus_class').on('tap','.plus_grouping',function(e){
	openWindow({
		url:'plus_grouping.html',
		id:'plus_grouping'
	});
});

//优惠劵点击
mui('.plus_class').on('tap','.discount',function(e){
	openWindow({
		url:'coupon.html',
		id:'coupon'
	});
});



function sleep(numberMillis) { 
	var now = new Date(); 
	var exitTime = now.getTime() + numberMillis; 
	  while (true) { 
	    now = new Date(); 
	    if (now.getTime() > exitTime) 
	    return; 
	  } 
}



//关闭按钮点击
mui('.mui-popover1').on('tap','p i.mui-pull-right',function(e){
    $("body").css({
        'overfloww':'auto',
        'position':'static',
        'overflow-y':'auto'

    })
    document.documentElement.scrollTop=tops;
    document.body.scrollTop=tops;
    //$("html,body").animate({scrollTop:tops},10);
	$("#dialogs").hide()
});
//蒙层点击关闭
mui('body').on('tap','.mui-backdrop',function(e){
	mask.close();
	mui('#middlePopover1').popover('hide');
	mui('#middlePopover2').popover('hide');
	mui('#middlePopover3').popover('hide');
	mui('#middlePopover7').popover('hide');
	mui('#middlePopover8"').popover('hide');
});
mui('body').on('tap','.weui-mask',function(e){
    $("body").css({
        'overfloww':'auto',
        'position':'static',
        'overflow-y':'auto'

    })
    document.documentElement.scrollTop=tops;
    document.body.scrollTop=tops;
    $("#dialogs").hide()
});



//点击查看二维码
mui('.mui-content').on('tap','.scan_code',function(e){
	var id=$(this).parents('.mui-card').data('id');
	$('#middlePopover2 img').attr('src','images/27-_07.png');
	common.http('Merchantapp&a=see_qrcode',{'client':client,'id':id},function(data){
		console.log(data);
		$('#middlePopover2 img').attr('src',data.qrcode);
		// sleep(4000);
		mask.show();
		mui('#middlePopover2').popover('show');
	});
	
	
});
//关闭按钮点击
mui('.mui-popover2').on('tap','.see_ma i.mui-pull-right',function(e){
	mask.close();
	mui('#middlePopover2').popover('hide');
});


//查看会员卡详细信息点击
mui('.mui-content').on('tap','.see_user_group',function(e){
	mask.show();
	mui('#middlePopover3').popover('show');
	var card_recharge=common.getCache('card_recharge');
	if(card_recharge==1){
		$('.card_recharge').show();
	}else{
		$('.card_recharge').hide();
	}
	mui('#pullrefresh').pullRefresh().scrollTo(0, 0, 1000);//滚动到顶部
	$('#balance_text').text('请选择');
	$('#integral_text').text('请选择');
	$('#middlePopover3 input').val('');
	$('#class_change li span').text('请选择分组');
	$('#card_status_text').text('请选择状态');
	var id=$(this).parents('.mui-card').data('id');
	$('.determine').data('id',id);

	common.http('Merchantapp&a=card_new_user_detail',{'client':client,'id':id},function(data){
		//console.log(data);
		$('.card_num').val(data.card.physical_id);
		$('#middlePopover3 tbody tr td:eq(0)').text(data.card.nickname);
		$('#middlePopover3 tbody tr td:eq(1)').text(data.card.id);
		$('#middlePopover3 tbody tr td:eq(2) a').text(data.card.phone);
		$('#middlePopover3 tbody tr td:eq(2) a').attr('href','tel:'+data.card.phone);
		//$('.card_num').val(data.card.id);
		$('.add_time').text(data.card.add_time);
		var setMonty=parseFloat(data.card.card_money)+parseFloat(data.card.card_money_give);
		$('.current_balance').text('当前余额￥'+setMonty);
		$('.current_scorce').text('当前积分'+data.card.card_score);
		data.card.status==0?$('#card_status_text').text('禁止'):$('#card_status_text').text('正常');
		$('#user_class').attr('data-user',data.card.gid);
		change_class=[];
		userList=data.card.gid;
		if(data.card.card_group!=null){
			$.each(data.card.card_group,function(i,val){
				var group_list={'value':'','text':''};
				group_list.value=val.id;
				group_list.text=val.name;
				change_class.push(group_list);
				if(data.card.gid==val.id){
					$('#class_change li span').text(val.name);
				}
			});
		}
		

	});
	
});
// 取消点击
mui('.bottom').on('tap','.cancel',function(e){
	mask.close();
	mui('#middlePopover3').popover('hide');
});
// 确认点击
mui('.bottom').on('tap','.determine',function(e){
	var physical_id=$('.card_num').val();
	var id=$('#middlePopover3 tbody tr td:eq(1)').text();
	var set_money_type=0,set_score_type=0,status=0;
	set_money_type=$('#balance_text').text()=="增加"?1:0;
	set_score_type=$('#integral_text').text()=="增加"?1:0;
	if($('#card_status_text').text()=="请选择状态"){
		mui.toast('请选择状态');
	}else{
		status=$('#card_status_text').text()=='禁止'?0:1;
		var set_money=0,set_score=0,gid  =0;
		gid=$('#user_class').data('user');
		//console.log(gid);

			set_money=$('#balance_text').text()!="请选择"?$('#balance_val').val():0;
			set_score=$('#integral_text').text()!="请选择"?$('#integral_val').val():0;
		
        common.http('Merchantapp&a=card_new_user_edit',{'client':client,'physical_id':physical_id,'set_money_type':set_money_type,'set_score_type':set_score_type,'set_money':set_money,'set_score':set_score,'gid':gid,'status':status,'id':id},function(data){
            console.log(data);
            mui.toast(data.msg);
            mask.close();
            mui('#middlePopover3').popover('hide');
        });
	}
	
	
	
});
mui('.see_user').on('tap','p i.mui-pull-right',function(e){
	mask.close();
	mui('#middlePopover3').popover('hide');
});

var test = document.getElementById('middlePopover3');
var inputs = test.getElementsByTagName('input');

	//筛选
 (function($, doc) {
	$.init();
	mui('body').on('tap','.select_state',function(e){
		
			 // document.querySelector('body').addEventListener('touchend', function(e) {  
    //         if(e.target.className != 'input') {  
    //             document.querySelector('.input').blur();  
    //         }  
    //     });  
    document.activeElement.blur();
		document.getElementById('bg').style.display="block";
			/**
			 * 获取对象属性的值
			 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
			 * @param {Object} obj 对象
			 * @param {String} param 属性名
			 */
			var _getParam = function(obj, param) {
				return obj[param] || '';
			};
			//普通示例
			var userPicker = new $.PopPicker();
			userPicker.setData([{
				value: '1',
				text: '正常'
			},{
				value: '0',
				text: '禁止'
			}]);

			userPicker.show(function(items) {
				document.getElementById('state').innerHTML = items[0].text;
				document.getElementById('bg').style.display="none";
				//返回 false 可以阻止选择框的关闭
				//return false;
				
			});
			mui('body').on('tap','#bg',function(e){
				userPicker.hide();
				document.getElementById('bg').style.display="none";
			});
			mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
				document.getElementById('bg').style.display="none";
			});
		
		
		});

	mui('body').on('tap','.select_state1',function(e){
		
		document.getElementById('bg').style.display="block";
			/**
			 * 获取对象属性的值
			 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
			 * @param {Object} obj 对象
			 * @param {String} param 属性名
			 */
			var _getParam = function(obj, param) {
				return obj[param] || '';
			};
			//普通示例
			var userPicker = new $.PopPicker();
			userPicker.setData([{
				value: '1',
				text: '正常'
			},{
				value: '0',
				text: '禁止'
			}]);

			userPicker.show(function(items) {
				document.getElementById('state').innerHTML = items[0].text;
				document.getElementById('bg').style.display="none";
				//返回 false 可以阻止选择框的关闭
				//return false;
				
			});
			mui('body').on('tap','#bg',function(e){
				userPicker.hide();
				document.getElementById('bg').style.display="none";
			});
			mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
				document.getElementById('bg').style.display="none";
			});
		
		
		});
})(mui, document);
// 会员卡详情修改状态
(function($, doc) {
	$.init();
	mui('body').on('tap','#card_status',function(e){
			document.activeElement.blur();
		// document.getElementsByTagName('input').blur;
		document.getElementById('bg').style.display="block";
			/**
			 * 获取对象属性的值
			 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
			 * @param {Object} obj 对象
			 * @param {String} param 属性名
			 */
			var _getParam = function(obj, param) {
				return obj[param] || '';
			};
			//普通示例
			var userPicker = new $.PopPicker();
			userPicker.setData([{
				value: '1',
				text: '正常'
			},{
				value: '0',
				text: '禁止'
			}]);

			userPicker.show(function(items) { 		
				document.getElementById('card_status_text').innerHTML = items[0].text;
				document.getElementById('bg').style.display="none";
				document.getElementById('card_status_text').style.color="#333";
				//返回 false 可以阻止选择框的关闭
				//return false;
				
			});
			mui('body').on('tap','#bg',function(e){
				userPicker.hide();
				document.getElementById('bg').style.display="none";
			});
			mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
				document.getElementById('bg').style.display="none";
			});
		
		
		});
})(mui, document);
// 最简单数组去重法 
function unique5(array){ 
	var r = []; 
	for(var i = 0, l = array.length; i < l; i++) { 
		 for(var j = i + 1; j < l; j++) {
		  if (array[i] === array[j]) j = ++i; 
		 	r.push(array[i]); 
		 } 
		 return r; 
	}
}
//用户分组筛选 
 (function($, doc) {
	$.init();
			mui('body').on('tap','#class_change',function(e){

				document.activeElement.blur();
				document.getElementById('bg').style.display="block";
					/**
					 * 获取对象属性的值
					 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
					 * @param {Object} obj 对象
					 * @param {String} param 属性名
					 */
					var _getParam = function(obj, param) {
						return obj[param] || '';
					};
					//普通示例
					var userPicker = new $.PopPicker();
					console.log(change_class);
					userPicker.setData(change_class);
					userPicker.pickers[0].setSelectedValue(userList);
					userPicker.show(function(items) {
						document.getElementById('user_class').innerText = items[0].text;
						document.getElementById('user_class').style.color='#333';
						document.getElementById('user_class').setAttribute('data-user',items[0].value);	
						document.getElementById('bg').style.display="none";
						//返回 false 可以阻止选择框的关闭
						//return false;
					});
					mui('body').on('tap','#bg',function(e){
						userPicker.hide();
						document.getElementById('bg').style.display="none";
					});
					mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
						document.getElementById('bg').style.display="none";
					});
				
				
				});
			})(mui, document);
//筛选
 (function($, doc) {
				$.init();
			mui('body').on('tap','#integral',function(e){
				document.activeElement.blur(); 
				document.getElementById('bg').style.display="block";
					/**
					 * 获取对象属性的值
					 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
					 * @param {Object} obj 对象
					 * @param {String} param 属性名
					 */
					var _getParam = function(obj, param) {
						return obj[param] || '';
					};
					//普通示例
					var userPicker = new $.PopPicker();
					userPicker.setData([
					{
						value: '1',
						text: '增加'
					}, {
						value: '0',
						text: '减少'
					}]);

					var showUserPickerButton1 = doc.getElementById('integral');
					userPicker.show(function(items) {
						document.getElementById('integral_text').innerHTML = items[0].text;
						document.getElementById('integral_text').style.color='#333';
						document.getElementById('bg').style.display="none";
						//返回 false 可以阻止选择框的关闭
						//return false;
					});
					mui('body').on('tap','#bg',function(e){
						userPicker.hide();
						document.getElementById('bg').style.display="none";
					});
					mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
						document.getElementById('bg').style.display="none";
					});
				
				
				});
			})(mui, document);

//筛选
 (function($, doc) {
	$.init();
	mui('body').on('tap','#balance',function(e){
		document.activeElement.blur();
		document.getElementById('bg').style.display="block";
			/**
			 * 获取对象属性的值
			 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
			 * @param {Object} obj 对象
			 * @param {String} param 属性名
			 */
			var _getParam = function(obj, param) {
				return obj[param] || '';
			};
			//普通示例
			var userPicker = new $.PopPicker();
			userPicker.setData([
			{
				value: '1',
				text: '增加'
			}, {
				value: '0',
				text: '减少'
			}]);
			var showUserPickerButton1 = doc.getElementById('balance');
			userPicker.show(function(items) {
				document.getElementById('balance_text').innerHTML = items[0].text;
				document.getElementById('balance_text').style.color='#333';
				document.getElementById('bg').style.display="none";
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
			mui('body').on('tap','#bg',function(e){
				userPicker.hide();
				document.getElementById('bg').style.display="none";
			});
			mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
				document.getElementById('bg').style.display="none";
			});
	});
})(mui, document);


//搜索筛选
 (function($, doc) {
	$.init();
	mui('body').on('tap','#search_class',function(e){
		document.activeElement.blur();
		// document.getElementById('bg').style.display="block";
			/**
			 * 获取对象属性的值
			 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
			 * @param {Object} obj 对象
			 * @param {String} param 属性名
			 */
			var _getParam = function(obj, param) {
				return obj[param] || '';
			};
			//普通示例
			var userPicker = new $.PopPicker();
			userPicker.setData([
			{
				value: '0',
				text: '会员卡号'
			}, {
				value: '1',
				text: '手机号'
			},{
				value: '2',
				text: '会员名称'
			}]);
			userPicker.pickers[0].setSelectedValue(moren);
			var showUserPickerButton1 = doc.getElementById('balance');
			userPicker.show(function(items) {
				document.getElementById('seach_key').innerHTML = items[0].text;
				document.getElementById('seach_key').style.color='#333';
				moren=items[0].value;
				// document.getElementById('bg').style.display="none";
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
			// mui('body').on('tap','#bg',function(e){
			// 	userPicker.hide();
			// 	document.getElementById('bg').style.display="none";
			// });
			// mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
			// 	document.getElementById('bg').style.display="none";
			// });
	});
})(mui, document);