mui.init();
mui('.mui-scroll-wrapper').scroll({
    deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006 
});
mui('.sroll_style').scroll({
   	scrollY:false,
	scrollX:true,
	deceleration:0.0005
});
var coupon_ids=common.getCache('coupon_ids');//
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var bgimg_list=[];//
var type_list=[];//
var cate_id="";//使用分类筛选ID
var send_type="";//渠道ID
var status="";//状态ID
var cate_name="";//使用类别ID
var color="";//卡卷颜色
var color_lists=[];//卡卷颜色数组
var te_color=null;
var add="";//增加或减少数量状态
function selectChange(lists){
	$.each(lists,function(i,val){
		var goods_id_list ={'value':'','text':''};
		goods_id_list.text=val;
		goods_id_list.value=i;
		bgimg_list.push(goods_id_list);
	});
}
var card_color='';
// 初次加载页面
$('input').val('');
//加载页面渲染指定店铺
common.http('Merchantapp&a=merchant_money_info',{'ticket':ticket,'client':client,'type':'shop'},function(data){
	// console.log(data);
	var str='';
	$.each(data.store_list,function(i,val){
		str+='<li><i class="add_service" data-id='+val.store_id+'> <b><sub></sub></b> '+val.name+'</i> </li>';
	});
	$('.change_shop').html(str);
});

common.http('Merchantapp&a=card_new_coupon_config',{'ticket':ticket,'client':client},function(data){
	//console.log(data);
	selectChange(data.category);
	card_color=data.color_list;

	common.http('Merchantapp&a=card_new_coupon_detail',{'ticket':ticket,'client':client,'coupon_id':coupon_ids},function(data){
		console.log(data);
		$('.name').val(data.coupon.name);
		$('.img').attr('src',data.coupon.img);
		data.coupon.use_with_card==1?$('.use_with_card').addClass('mui-active'):$('.use_with_card').removeClass('mui-active');
		data.coupon.auto_get==1?$('.auto_get').addClass('mui-active'):$('.auto_get').removeClass('mui-active');
		// data.coupon.sync_wx==1?$('.sync_wx').addClass('mui-active'):$('.sync_wx').removeClass('mui-active');
		data.coupon.allow_new==1?$('.allow_new').addClass('mui-active'):$('.allow_new').removeClass('mui-active');
		$('.num').val(data.coupon.num);
		$('.limit').val(data.coupon.limit);
		$('.use_limit').val(data.coupon.use_limit);
		$('.discount').val(data.coupon.discount);
		$('.order_money').val(data.coupon.order_money);
		$('#pickTimeBtn').text(data.coupon.start_time).css('color','#333');
		$('#pickTimeBtn1').text(data.coupon.end_time).css('color','#333');
		$('.des').val(data.coupon.des);
		$('.des_detial').val(data.coupon.des_detial);
		$('.app_text').text(data.coupon.platform);
		$('#class1 li span').text(data.coupon.cate_name);
		if(data.coupon.cate_name=='全品类通用'){
			$('.shilei').hide();
		}
		if(data.coupon.cate_id==null){
              $('#class2 li span').text('全选');
		}else{
           $('#class2 li span').text(data.coupon.cate_id);
		}
		
		
		status=data.coupon.status;
		if(data.coupon.send_type==0){
			$('.qudao li:eq(0) i').addClass('active').parent('li').siblings('li').find('i').removeClass('active');
		}else if(data.coupon.send_type==1){
			$('.qudao li:eq(1) i').addClass('active').parent('li').siblings('li').find('i').removeClass('active');
		}else if(data.coupon.send_type==2){$('.qudao li:eq(2) i').addClass('active').parent('li').siblings('li').find('i').removeClass('active');}

		if(data.coupon.status==1){
			$('.state_change span').text("正常").css('color','#333');
		}else if(data.coupon.status==0){$('.state_change span').text("禁止").css('color','#333');}
		// 同步微信卡包变量值
		$('.brand_name').val(data.coupon.wx_param.brand_name);
		$('.notice').val(data.coupon.wx_param.notice);
		$('.center_sub_title').val(data.coupon.wx_param.center_sub_title);
		$('.center_url').val(data.coupon.wx_param.center_url);
		$('.promotion_url').val(data.coupon.wx_param.promotion_url);
		$('.custom_url_name').val(data.coupon.wx_param.custom_url_name);
		$('.custom_url').val(data.coupon.wx_param.custom_url);
		$('.custom_url_sub_title').val(data.coupon.wx_param.custom_url_sub_title);
		$('.icon_url_list').attr('src',data.coupon.wx_param.icon_url_list);
		$.each(card_color,function(i,val){
			if(i==data.coupon.wx_param.color){
				$('#cardColor li span').text(val);
				$('#cardColor li span').attr('background',i);
				$('#cardColor li span').css('background',val);
			}
		});
		// var color_val=data.coupon.wx_param.color;
		// $('#colors li span').text(te_color['"'+color_val+'"']);

		// 图文信息和提供服务
		var text_image_list=[];//图文信息
		if(data.coupon.wx_param.text_image_list!=null){
			var str='';
			$.each(data.coupon.wx_param.text_image_list,function(i,val){
				str+='<div class="mui-card"><div class="mui-card-header"><span class="left_style">图文消息'+i+'</span></div><div class="mui-card-content text_describe"><ul><li class="up_img1"><img src='+val.image_url+' alt="" /><input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage'+i+'" class="fileImage" name="imgFile" disabled></li></ul><textarea name="" rows="3" cols="4" placeholder="请填写店铺描述" disabled>'+val.text+'</textarea><div class="delate_describle"><button type="button" >删除</button></div></div></div>';
			});
			$('.add_img_text').html(str);
		}
		var business_service=[];

		if(data.coupon.store_id!=null){
			$.each($('.change_shop li'),function(i,val){
				var	id=$(this).find('i').data('id');
				//console.log(id);
				if($.inArray(id.toString(),data.coupon.store_id)!==-1){
					$(this).find('i').addClass('active');
				}
			});
		}
		if(data.coupon.wx_param.business_service!=null){
			$.each($('.synchro_weixin .sevice li'),function(i,val){
				var url=$(this).find('i').data('url');
				if($.inArray(url.toString(),data.coupon.wx_param.business_service)!==-1){
					$(this).find('i').addClass('active');
				}
			});
		}
		
	});
});

$('#cardColor span').click(function(e){
			
	var me=this;
	mask.show();
	mui('#middlePopover6').popover('show');
	var strs='';
	$.each(card_color,function(i,val){
		strs+='<dd data-key='+i+' style="background:'+val+'">'+val+'</dd>';
	});
	$('#middlePopover6 dl').html(strs);
	$('#middlePopover6 dd').click(function(e){
		$(me).text($(this).text());
		$(me).attr('background',$(this).attr('data-key'));
		$(me).css('background',$(this).text());
		mask.close();
	mui('#middlePopover6').popover('hide');
	});
	$('#middlePopover6 p i').click(function(e){
		mask.close();
		mui('#middlePopover6').popover('hide');
	});
});



$('.mui-content').off('click','.change_shop li').on('click','.change_shop li',function(e){
	if($(this).find('i').is('.active')){
		$(this).find('i').removeClass('active');
	}else{
		$(this).find('i').addClass('active');
	}
});

// 上传图片
 function imgUpload(id){
    var fullPath=window.document.location.href;  
    var pathName=window.document.location.pathname;  
    var pos=fullPath.indexOf(pathName);  
    var localhostPath=fullPath.substring(0,pos);  
    var post_url = localhostPath+'/appapi.php?c=Merchantapp&a=up_img';
    console.log(post_url);
    $.ajaxFileUpload({
        url:post_url,
        secureuri:false,
        fileElementId:id,
        dataType: 'json', 
        type: 'post',
        data : {'wxcard_img' : 1},
        success: function(data, status){  
        	console.log(data);
        	$('#'+id).prev('img').attr('src',data.result.root_img);
        	$('#'+id).attr('data-url',data.result.url);
        },
        error: function(data, status, e){ 
          //  alert(e);
        }
    }); 
}
$('.up_img').off('change','input').on('change','input',function(e){
	var id=$(this).attr('id');
	imgUpload(id);
});
$('.mui-content').off('change','.up_img1 input').on('change','.up_img1 input',function(e){
	var id=$(this).attr('id');
	imgUpload(id);
});
//渠道选择点击事件
mui('.mui-content').on('tap','.qudao li',function(e){
	$(this).find('i').addClass('active').parent('li').siblings('li').find('i').removeClass('active');
	$.each($('.qudao li'),function(i,val){
		if($(this).find('i').is('.active')){
			send_type=$(this).find('i').data('id');
		}
	});
	console.log(send_type);
});
//发放渠道提示语
mui('.mui-content').on('tap','.fadang_qu',function(e){
	    mui.alert('店外即商家领券中心，店内即快店店铺内部', '发放渠道', function() {  
        });
});


// 保存按钮点击
mui('.mui-bar-tab').on('tap','a',function(e){
	// 基础信息
	var name,img,use_with_card,auto_get,allow_new,num,start_time,end_time,des,des_detial,num_add;
	name=$('.name').val();
	img=$('.img').attr('src');
	use_with_card=$('.use_with_card').is('.mui-active')?1:0;
	auto_get=$('.auto_get').is('.mui-active')?1:0;
	sync_wx=1;
	allow_new=$('.allow_new').is('.mui-active')?1:0;
	num=$('.num').val();
	start_time=$('#pickTimeBtn').text();
	end_time=$('#pickTimeBtn1').text();
	des=$('.des').val();
	des_detial=$('.des_detial').val();
	num_add=$('.num_add').val();
	var store_id=[];
	$.each($('.change_shop li'),function(i,val){
		if($('.change_shop li:eq('+i+') i').is('.active')){
			var id=$('.change_shop li:eq('+i+') i').data('id');
			store_id.push(id);
		}
	});

	common.http('Merchantapp&a=card_new_edit_coupon',{'ticket':ticket,'client':client,'send_type':send_type,'status':status,'name':name,'img':img,'use_with_card':use_with_card,'auto_get':auto_get,'sync_wx':sync_wx,'allow_new':allow_new,'num':num,'start_time':start_time,'end_time':end_time,'des':des,'des_detial':des_detial,'store_id':store_id,'add':add,'num_add':num_add,'coupon_id':coupon_ids},function(data){
		console.log(data);
		mui.toast(data.msg);
		if(common.checkApp()){
			setTimeout(function(){
				if(common.checkAndroidApp()){
					window.pigcmspackapp.closewebview(2);
				}else{
					common.iosFunction('closewebview/2');
				}
			},2000);
		}else{
			setTimeout(function(){
				history.go(-1);
				document.execCommand('Refresh');
			},2000); 
		}	
	});


});


//头部tab切换 
mui('.mui-content').on('tap','.class_list li.class_synchro',function(e){
	//$(this).addClass('active').siblings('li').removeClass('active');
	//if($('.class_basic').is('.active')){
	//	 $('.basic_information').removeClass('hidden').siblings('.synchro_weixin').addClass('hidden');
	// }else{
	//	 $('.synchro_weixin').removeClass('hidden').siblings('.basic_information').addClass('hidden');
		mui.toast('编辑时不支持修改同步微信卡券信息');
	//}
});


//单选框多选点击
// mui('.mui-content').on('tap','.synchro_weixin  .add_service',function(e){
// 	$(this).is('.active')?$(this).removeClass('active'):$(this).addClass('active');
// });
// //图文信息删除点击
// mui('.mui-content').on('tap','.delate_describle button',function(e){
// 	$(this).parents('.mui-card').remove();
// });
//增加图文信息
mui('.mui-content').on('tap','.add_text_img',function(e){
	var len=$('.add_img_text .mui-card').length+1;
	var  text='<div class="mui-card"><div class="mui-card-header"><span class="left_style">图文消息'+len+'</span></div><div class="mui-card-content text_describe"><ul><li class="up_img1"><img src="images/17-8_03.png" alt="" /><input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage'+len+'" class="fileImage" name="imgFile"></li></ul><textarea name="" rows="3" cols="4" placeholder="请填写店铺描述"></textarea></div></div>';
	$('.add_img_text').append(text);
});
//自定义类名点击
mui('.mui-content').on('tap','.mui-scroll div',function(e){
	var index=$(this).index();
	$(this).addClass('active').siblings('div').removeClass('active');
	$('.customLists .item_list:eq('+index+')').removeClass('hidden').siblings('.item_list').addClass('hidden');
});
 /*点击从功能库选择*/
// mui('.mui-content').on('tap','.form_libirly',function(e){
// 	mask.show();
// 	mui('#middlePopover1').popover('show');
// 	//点击详细
// 	mui('.mui-popover').on('tap','.detailed',function(e){
// 		$('.bg').removeClass('hidden');
// 		$('.last_ceng').removeClass('hidden');
// 	});
// 	//bg背景层点击,返回按钮，关闭按钮点击
// 	mui('body').on('tap','.close_floor',function(e){
// 		$('.bg').addClass('hidden');
// 		$('.last_ceng').addClass('hidden');
// 	});
// 	//mask 弹层点击  关闭按钮点击
// 	mui('.mui-popover').on('tap','p i.mui-pull-right',function(e){
// 		mask.close();
// 		mui('#middlePopover1').popover('hide');
// 	});
// 	mui('body').on('tap','.mui-backdrop',function(e){
// 		mask.close();
// 		mui('#middlePopover1').popover('hide');
// 	});
// });
//查看微信卡卷
mui('.mui-content').on('tap','.see_card',function(e){
	mask.show();
    mui('#middlePopover').popover('show');
});
mui('.mui-popover').on('tap','img',function(e){
	mask.close();
	mui('#middlePopover').popover('hide');
});

//蒙层点击关闭
mui('body').on('tap','.mui-backdrop',function(e){
	mask.close();
	mui('#middlePopover').popover('hide');
	mask.close();
			mui('#middlePopover6').popover('hide');
});
// 使用平台多选点击
// mui('.mui-content').on('tap','.change_app li',function(e){
// 	if($(this).find('i').is('.active')){
// 		$(this).find('i').removeClass('active');
// 	}else{
// 		$(this).find('i').addClass('active');
// 	}
// });

//筛选
// (function($, doc) {
// 	$.init();
	//使用类别
// 	mui('.mui-content').on('tap','#class1',function(e) {
// 		//普通示例
// 		var userPicker = new $.PopPicker();
// 		userPicker.setData(bgimg_list);
// 		var that=this;
// 		userPicker.show(function(items) {
// 			that.children[0].children[0].innerHTML = items[0].text;
// 			that.children[0].children[0].style.color="#333333";
// 			cate_name=items[0].value;
// 			common.http('Merchantapp&a=ajax_ordertype_cateid',{'ticket':ticket,'client':client,'order_type':cate_name},function(data){
// 				//console.log(data);
// 				for(var i=0;i<data.length;i++){
// 					var type_card={'value':'','text':''};
// 					type_card.value=data[i].cat_id;
// 					type_card.text=data[i].cat_name;
// 					type_list.push(type_card);
// 				}
// 			})
// 		}, false);
	
// 	});
// 	//
// 	mui('.mui-content').on('tap','#class2',function(e) {
// 		//普通示例
// 		var userPicker = new $.PopPicker();
// 		userPicker.setData(type_list);
// 		var that=this;
// 		userPicker.show(function(items) {
// 			that.children[0].children[0].innerHTML = items[0].text;
// 			that.children[0].children[0].style.color="#333333";
// 			cate_id=items[0].value;
// 		}, false);
	
// 	});
	
// })(mui, document);


//状态
(function($, doc) {
	$.init();
	mui('.mui-content').on('tap','.state_change',function(e) {
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData([
		{
			value: '1',
			text: '正常'
		}, {
			value: '0',
			text: '禁止'
		}]);
		var that=this;
		userPicker.show(function(items) {
			that.children[0].innerHTML = items[0].text;
			that.children[0].style.color="#333333";
			status=items[0].value;
		}, false);
	});
	
})(mui, document);
//增加或减少状态选择
(function($, doc) {
	$.init();
	mui('.mui-content').on('tap','#add_redoce',function(e) {
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData([
		{
			value: '0',
			text: '增加'
		}, {
			value: '1',
			text: '减少'
		}]);
		var that=this;
		userPicker.show(function(items) {
			that.children[0].children[0].innerHTML = items[0].text;
			that.children[0].children[0].style.color="#333333";
			add=items[0].value;
		}, false);
	});
	
})(mui, document);
//卡券颜色
// (function($, doc) {
// 	$.init();
// 	mui('.mui-content').on('tap','#colors',function(e) {
// 		//普通示例
// 		var userPicker = new $.PopPicker();
// 		userPicker.setData(color_lists);
// 		var that=this;
// 		userPicker.show(function(items) {
// 			that.children[0].innerHTML = items[0].text;
// 			that.children[0].style.color="#333333";
// 			color=items[0].value;
// 			document.getElementById('colors').setAttribute('data-user',items[0].value);	
// 		}, false);
// 	});
	
// })(mui, document);
//发放渠道筛选
(function($, doc) {
	$.init();
	mui('.mui-content').on('tap','#class3',function(e) {
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData([
		{
			value: '0',
			text: '店内店外'
		}, {
			value: '1',
			text: '仅店外'
		},{
			value: '2',
			text: '仅店内'
		}]);
		var that=this;
		userPicker.show(function(items) {
			that.children[0].children[0].innerHTML = items[0].text;
			that.children[0].children[0].style.color="#333333";
			send_type=items[0].value;
		}, false);
	});
	
})(mui, document);




(function($, doc){	
		var btns = $('.begin_date');
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var me=this;
				var optionsJson = '{"type":"date","beginYear":2017,"endYear":2100}';
				var options = JSON.parse(optionsJson);
				var id = this.getAttribute('id');
				var picker = new $.DtPicker(options);
				picker.show(function(rs) {
					/*
					 * rs.value 拼合后的 value
					 * rs.text 拼合后的 text
					 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
					 * rs.m 月，用法同年
					 * rs.d 日，用法同年
					 * rs.h 时，用法同年
					 * rs.i 分（minutes 的第二个字母），用法同年
					 */
					$('#pickTimeBtn')[0].innerText=rs.text;
					$('#pickTimeBtn')[0].style.color='#404040';
					
					picker.dispose();
				});
			}, false);
		});
	})(mui, document);
	(function($, doc){	
		var btns = $('.end_date');
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var me=this;
				var optionsJson = '{"type":"date","beginYear":2017,"endYear":2100}';
				var options = JSON.parse(optionsJson);
				var id = this.getAttribute('id');
				var picker = new $.DtPicker(options);
				picker.show(function(rs) {
					/*
					 * rs.value 拼合后的 value
					 * rs.text 拼合后的 text
					 * rs.y 年，可以通过 rs.y.vaue 和 rs.y.text 获取值和文本
					 * rs.m 月，用法同年
					 * rs.d 日，用法同年
					 * rs.h 时，用法同年
					 * rs.i 分（minutes 的第二个字母），用法同年
					 */
					$('#pickTimeBtn1')[0].innerText=rs.text;
					$('#pickTimeBtn1')[0].style.color='#404040';
					
					picker.dispose();
				});
			}, false);
		});
	})(mui, document);
		mui.previewImage();
	var mask = mui.createMask();
     


