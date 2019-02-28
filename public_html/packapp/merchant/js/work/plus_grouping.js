mui.init();
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
// 初次加载
var pindex=1;//初始页面
var change_class=[];//
var setStatus=0;//会员卡状态
var addid=0;
mui('.mui-scroll-wrapper').scroll({
    deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006 
});
$('.all_pluszu').html('');
function all_coupons(){
	common.http('Merchantapp&a=card_new_group',{'ticket':ticket,'client':client},function(data){
		// console.log(data);
		var data=data;
		if(data.group_list.length!=0){
			laytpl(document.getElementById('cardlist').innerHTML).render(data, function(html){
				$('.all_pluszu').append(html);
				// $('.loading').hide();
				// $('.pullup').show();
				// if(data.group_list.length<10){$('.pullup').html('没有更多数据啦');}
			});
		}else{
			// $('.pullup').html('没有更多数据啦');
			// $('.loading').hide();
			// $('.pullup').show();
		}
	});
}	
all_coupons();	
// $(window).scroll(function() {
//     //$(document).scrollTop() 获取垂直滚动的距离
//     //$(document).scrollLeft() 这是获取水平滚动条的距离
//     //下拉刷新
//     // if ($(document).scrollTop() <= 0) {
//     //     alert("滚动条已经到达顶部为0");
//     // }
//     //上拉加载
//     if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
//     	$('.pullup').hide();
//     	$('.loading').show();
//         pindex++;
//         all_coupons(pindex);	
//     }
// });



var edit_gid = 0;
var edit_me = null;
var mask=mui.createMask();
//点击编辑显示
mui('.mui-content').on('tap','.group_penci',function(e){
	mask.show();
	mui('#middlePopover').popover('show');
	$('#middlePopover>p>span').text('编辑分组');
	edit_gid = $(this).parents('.mui-card').data('id');
	var text=$(this).parents('.mui-card').find('.plus_name').text();
	$('#middlePopover ul li:eq(0) input').val(text);
	var des_text=$(this).parents('.mui-card').find('.groups').text();

	$('#middlePopover ul li:eq(1) input').val(des_text);
	edit_me = $(this);
	
});

//点击确定
$('#middlePopover').off('click','a').on('click','a',function(e){
	e.stopPropagation();
	var des=$('#middlePopover ul li:eq(1) input').val();
	var name1=$('.name').val();
	if(name1!=''&&des!=''){
		if(edit_me){
			edit_me.parents('.mui-card').find('.group_comment b span').text(des);
		}
		common.http('Merchantapp&a=add_card_group',{'ticket':ticket,'client':client,'gid':edit_gid,'des':des,'name':name1},function(data){
			mui.toast(data.msg);
			mask.close();
			mui('#middlePopover').popover('hide');
			if(edit_me){
				edit_me.parents('.mui-card').find('.plus_name').text(name1);
			}
			if(edit_gid == 0){
				location.reload();
			}
			return false;
		});
	}else{
		mui.toast('请完善所有信息再进行操作');
	}
});



//新建分组点击
mui('header').on('tap','.open_list',function(e){
	mask.show();
	mui('#middlePopover').popover('show');
	$('#middlePopover>p>span').text('新建分组');
	$('.name').val('');
	$('#middlePopover ul li:eq(1) input').val('');
	edit_gid = 0;
	edit_me = null;
});




//蒙层点击关闭
mui('body').on('tap','.mui-backdrop',function(e){
	mask.close();
	mui('#middlePopover').popover('hide');mui('#middlePopover1').popover('hide');
});

//关闭按钮点击
mui('.plus_penci').on('tap','p i.mui-pull-right',function(e){
	mask.close();
	mui('#middlePopover').popover('hide');
});

mui('body').on('tap','.cancel',function(e){
	console.log(11);
	$('.bg').addClass('hidden');
	$('#des_detial').addClass('hidden');
	//mui('#middlePopover').popover('hide');
})


function all_uesr(pindex,gid){
	common.http('Merchantapp&a=card_group_user_list',{'ticket':ticket,'client':client,'gid':gid,'pindex':pindex},function(data){
		// console.log(data);
		var sum='';
		if(data.data.length!=0){
			for(var i=0;i<data.data.length;i++){
				sum+='<tr data-id='+data.data[i].id+'><th>'+data.data[i].nickname+'</th><th>'+data.data[i].id+'</th><th>'+data.data[i].phone+'</th></tr>';
			}
			$('#middlePopover1 tbody').append(sum);
			$('.loading').hide();
			$('.pullup').show();
			if(data.data.length<10){$('.pullup').html('没有更多数据啦');}
		}else{
			$('.pullup').html('没有更多数据啦');
			$('.loading').hide();
			$('.pullup').show();
		}
	});
}












//查看用户分组点击
mui('.mui-content').on('tap','.see_fen',function(e){
	mask.show();
	mui('#middlePopover1').popover('show');
	var gid=$(this).parents('.mui-card').data('id');
	addid=gid;
	$('#middlePopover1 tbody').html('');
	all_uesr(pindex,gid);
	// 上拉加载
	var flag=false;
	if($('#middlePopover1').height()>$('.sroll_up').height()){
		$('.sroll_up').scroll(function(e){

			 if(flag){
			      //数据加载中
			      return false;
			    }
			// console.log($('.sroll_up').scrollTop());
			// console.log($('#middlePopover1').height() - $('.sroll_up').height());
			if($('.sroll_up').scrollTop()>=  $('#middlePopover1 table').height() - $('.sroll_up').height()){
				$('.pullup').hide();
            	$('.loading').show();
            	flag = true;
                pindex++;
                all_uesr(pindex,gid);
			}
		});
	}
	// 会员卡查看详情
	mui('#middlePopover1').on('tap','tbody tr',function(e){
		var id=$(this).data('id');
		$('.bg').removeClass('hidden');
		$('#des_detial').removeClass('hidden');
		$('#des_detial input').val('');
		$('.time').text('');
		$('.money').text('');
		$('.score').text('');
		$('#balance_text').text('请选择');
		$('#integral_text').text('请选择');
		common.http('Merchantapp&a=card_new_user_detail',{'ticket':ticket,'client':client,'id':id},function(data){
			console.log(data);
			$('.card_num').val(data.card.id);
			$('.time').text(data.card.add_time);
			$('.money').text(parseFloat(data.card.card_money)+parseFloat(data.card.card_money_give));
			$('.score').text(data.card.card_score);
			change_class=[];
			if(data.card.status==0){
				$('#card_status_text').text('禁止');
			}else{
				$('#card_status_text').text('正常');
			}
			setStatus=data.card.status;
			$('#user_class').attr('data-user',data.card.gid);
			$.each(data.card.card_group,function(i,val){
				var group_list={'value':'','text':''};
				group_list.value=val.id;
				group_list.text=val.name;
				change_class.push(group_list);
				if(data.card.gid==val.id){
					$('#class_change li span').text(val.name);
				}
			});
		});
		// 会员卡修改详情
		// mui('#des_detial').on('tap','.determine',function(e){
		$('#des_detial').off('click','.determine').on('click','.determine',function(e){
			 e.stopPropagation();
			var set_money_type=0,set_score_type=0,status=0;
			set_money_type=$('#balance_text').text()=="增加"?1:0;
			set_score_type=$('#integral_text').text()=="增加"?1:0;
			status=$('#card_status_text').text()=="禁止"?0:1;
			var card_money="",card_score="";
			
			if($('#user_class').text()!="请选择分组"){
				card_money=$('#balance_text').text()!="请选择"&&$('#balance+input').val()
				card_score=$('#integral_text').text()!="请选择"&&$('#integral+input').val();
				common.http('Merchantapp&a=card_new_user_edit',{'ticket':ticket,'client':client,'id':id,'set_money_type':set_money_type,'set_score_type':set_score_type,'set_money':card_money,'set_score':card_score,'gid':addid,'status':status},function(data){
					console.log(data);
					mui.toast(data.msg);
				});
				$('.bg').addClass('hidden');
				$('#des_detial').addClass('hidden');
			}else{
				mui.toast('请选择分组');
			}
		});
	});
});




//取消按钮点击
mui('.see_user').on('tap','.cancel',function(e){
	mask.close();
	mui('#middlePopover1').popover('hide');
});
// mui('.see_user').on('tap','.determine',function(e){
// 	mask.close();
// 	mui('#middlePopover1').popover('hide');
// });
mui('#middlePopover1').on('tap','p i.mui-pull-right',function(e){
	mask.close();
	mui('#middlePopover1').popover('hide');
});
mui('#des_detial').on('tap','p i.mui-pull-right',function(e){
	$('.bg').addClass('hidden');
	$('#des_detial').addClass('hidden');
});


//筛选
 (function($, doc) {
	$.init();
	mui('body').on('tap','#integral',function(e){
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
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
		
		
		});
	})(mui, document);
//筛选
(function($, doc) {
	$.init();
	mui('body').on('tap','#balance',function(e){
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
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
		
		});
	})(mui, document);
//用户分组筛选 
 (function($, doc) {
	$.init();
	mui('body').on('tap','#class_change',function(e){
		document.getElementById('bg1').style.display="block";
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
			userPicker.show(function(items) {
				document.getElementById('user_class').innerText = items[0].text;
				document.getElementById('user_class').style.color='#333';
				document.getElementById('user_class').setAttribute('data-user',items[0].value);	
				document.getElementById('bg1').style.display="none";
				addid=items[0].text;
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
			mui('body').on('tap','#bg1',function(e){
				userPicker.hide();
				document.getElementById('bg1').style.display="none";
			});
			mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
				document.getElementById('bg1').style.display="none";
			});
		
	
	});
})(mui, document);
(function($, doc) {
	$.init();
	mui('body').on('tap','#card_status',function(e){
		document.getElementById('bg1').style.display="block";
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
				value: '0',
				text: '禁止'
			},{
				value: '1',
				text: '正常'
			}]);
			userPicker.pickers[0].setSelectedValue(setStatus);
			userPicker.show(function(items) {
				document.getElementById('card_status_text').innerHTML = items[0].text;
				document.getElementById('bg1').style.display="none";
				document.getElementById('card_status_text').style.color="#333";
				setStatus=items[0].value;
				//返回 false 可以阻止选择框的关闭
				//return false;
				
			});
			mui('body').on('tap','#bg',function(e){
				userPicker.hide();
				document.getElementById('bg1').style.display="none";
			});
			mui('body').on('tap','.mui-poppicker-btn-cancel',function(e){
				document.getElementById('bg1').style.display="none";
			});
		
		
		});
})(mui, document);
function pageShowFunc(){
	location.reload(true);
}