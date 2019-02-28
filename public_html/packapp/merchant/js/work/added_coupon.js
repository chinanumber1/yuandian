mui.init();
mui('.mui-scroll-wrapper').scroll({
    deceleration: 0.0005 //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006 
});
mui('.sroll_style').scroll({
   	scrollY:false,
	scrollX:true,
	deceleration:0.0005
});
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var bgimg_list=[];//
var type_list=[];//
var cate_id="";//使用分类筛选ID
var send_type="";//渠道ID
var status=1;//状态ID
var cate_name="";//使用类别ID
var color="";
var color_lists=[];//卡卷颜色
var pindex =1;
var tongbu=0;
var tops=0;
function selectChange(lists){
	$.each(lists,function(i,val){
		var goods_id_list ={'value':'','text':''};
			goods_id_list.text=val;
			goods_id_list.value=i;
		if(val=="全品类通用"){
				
			bgimg_list.unshift(goods_id_list);
		}else{
			bgimg_list.push(goods_id_list);
		}
		
		
	});
	console.log(bgimg_list);
}
var card_color='';
// 初次加载页面
$('input').val('');
common.http('Merchantapp&a=card_new_coupon_config',{'ticket':ticket,'client':client},function(data){
	console.log(data);
	selectChange(data.category);
	
	card_color=data.color_list;
});


//加载页面渲染指定店铺
common.http('Merchantapp&a=merchant_money_info',{'ticket':ticket,'client':client,'type':'shop'},function(data){
	// console.log(data);
	var str='';
	$.each(data.store_list,function(i,val){
		str+='<li><i class="add_services active" data-id='+val.store_id+'></i>'+val.name+' </li>';
	});
	$('.change_shop').html(str);
});
$('.mui-content').off('click','.change_shop li').on('click','.change_shop li',function(e){
	if($(this).find('i').is('.active')){
		$(this).find('i').removeClass('active');
	}else{
		$(this).find('i').addClass('active');
	}
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

// 保存按钮点击
mui('.mui-bar-tab').on('tap','a',function(e){
	// 基础信息
	var name,img,use_with_card,auto_get,allow_new,platform=[],num,limit,use_limit,discount,order_money,start_time,end_time,des,des_detial,sync_wx;
	var store_id=[];
	
	
	name=$('.name').val();
	img=$('.img').attr('src');
	use_with_card=$('.use_with_card').is('.mui-active')?1:0;
	auto_get=$('.auto_get').is('.mui-active')?1:0;
	
	allow_new=$('.allow_new').is('.mui-active')?1:0;
	num=$('.num').val();
	limit=$('.limit').val();
	use_limit=$('.use_limit').val();
	discount=$('.discount').val();
	order_money=$('.order_money').val();
	start_time=$('#pickTimeBtn').text();
	end_time=$('#pickTimeBtn1').text();
	des=$('.des').val();
	des_detial=$('.des_detial').val();
	$.each($('.change_appsAll li'),function(i,val){
		if($('.change_appsAll li:eq('+i+') i').is('.active')){
			var data_val=$('.change_appsAll li:eq('+i+') i').data('id');
			platform.push(data_val);
		}
	});
	$.each($('.change_shop li'),function(i,val){
			if($('.change_shop li:eq('+i+') i').is('.active')){
				var id=$('.change_shop li:eq('+i+') i').data('id');
				store_id.push(id);
			}
		});
	// 同步微信平台
	var brand_name,notice,center_sub_title,center_url,promotion_url,icon_url_list,custom_url_name ,custom_url,custom_url_sub_title ;
	// 图文信息和提供服务
	var image_url=[],textall=[];
	var business_service=[];
	
	if(tongbu==0){
		sync_wx=0;
	}else{
		sync_wx=1;
		// 同步微信卡包变量值
		brand_name=$('.brand_name').val();
		notice=$('.notice').val();
		center_sub_title=$('.center_sub_title').val();
		center_url=$('.center_url').val();
		promotion_url=$('.promotion_url').val();
		custom_url_name=$('.custom_url_name').val();
		custom_url=$('.custom_url').val();
		custom_url_sub_title=$('.custom_url_sub_title').val();
		icon_url_list=$('.icon_url_list').attr('src');
		color=$('#cardColor li span').attr('background');
		
		console.log(color);
		$.each($('.synchro_weixin .radio_change ul li'),function(i,val){
			if($(this).find('i').is('.active')){
				var data_val=$(this).find('i').data('url');
				business_service.push(data_val);
			}
		});
		$.each($('.add_img_text .mui-card'),function(i,val){
			image_url.push($('.add_img_text .mui-card:eq('+i+') .up_img1 img').attr('src'));
			textall.push($('.add_img_text .mui-card:eq('+i+') textarea').val());
		});
	}
	// console.log(platform,business_service,image_url);
	if(name!=''){
		if(cate_name==''){
			mui.toast('请完善基本信息-优惠券使用类别');
		}else{
			if(num==''){
				mui.toast('请完善基本信息-数量');
			}else{
				if(limit==''){
					mui.toast('请完善基本信息-领取数量限制');
				}else{
					if(use_limit==''){
						mui.toast('请完善基本信息-使用数量限制');
					}else{
						if(discount==''){
							mui.toast('请完善基本信息-优惠金额');
						}else{
							if(order_money==''){
								mui.toast('请完善基本信息-最小订单金额');
							}else{
								if(start_time!="请选择开始时间"){
									if(end_time!="请选择结束时间"){
										common.http('Merchantapp&a=card_new_add_coupon',{'ticket':ticket,'client':client,'cate_id':cate_id,'send_type':send_type,'status':status,'cate_name':cate_name,'name':name,'img':img,'use_with_card':use_with_card,'auto_get':auto_get,'sync_wx':sync_wx,'allow_new':allow_new,'num':num,'limit':limit,'use_limit':use_limit,'discount':discount,'order_money':order_money,'start_time':start_time,'end_time':end_time,'des':des,'des_detial':des_detial,'brand_name':brand_name,'color':color,'notice':notice,'center_sub_title':center_sub_title,'center_url':center_url,'promotion_url':promotion_url,'custom_url_name':custom_url_name,'custom_url':custom_url,'custom_url_sub_title':custom_url_sub_title,'icon_url_list':icon_url_list,'image_url':image_url,'textall':textall,'business_service':business_service,'platform':platform,'store_id':store_id},function(data){
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
									}else{
										mui.toast('请完善基本信息-请选择结束时间');
									}
								}else{
									mui.toast('请完善基本信息-请选择开始时间');
								}
							}

						}
					}
				}
			}	
		}
	}else{
		mui.toast('请完善基本信息-优惠券名称');
	}
});

//渠道选择点击事件
mui('.mui-content').on('tap','.qudao li',function(e){
	$(this).find('i').addClass('active').parent('li').siblings('li').find('i').removeClass('active');
	$.each($('.qudao li'),function(i,val){
		if($(this).find('i').is('.active')){
			send_type=$(this).find('i').data('id');
		}
	});
	//console.log(send_type);
});
//发放渠道提示语
mui('.mui-content').on('tap','.fadang_qu',function(e){
	    mui.alert('店外即商家领券中心，店内即快店店铺内部', '发放渠道', function() {  
        });
});


//头部tab切换 
mui('.mui-content').on('tap','.class_list li',function(e){
	$(this).addClass('active').siblings('li').removeClass('active');
	if($('.class_basic').is('.active')){
		$('.basic_information').removeClass('hidden').siblings('.synchro_weixin').addClass('hidden');
		$('.mui-bar-tab a span').hide();
		tongbu=0;
	}else{
		$('.synchro_weixin').removeClass('hidden').siblings('.basic_information').addClass('hidden');
		$('.mui-bar-tab a span').show();
		tongbu=1;
	}
});


//单选框多选点击
mui('.mui-content').on('tap','.synchro_weixin  .add_service',function(e){
	$(this).is('.active')?$(this).removeClass('active'):$(this).addClass('active');
});
//图文信息删除点击
mui('.mui-content').on('tap','.delate_describle button',function(e){
	$(this).parents('.mui-card').remove();
});
//增加图文信息
mui('.mui-content').on('tap','.add_text_img',function(e){
	var len=$('.add_img_text .mui-card').length+1;
	var  text='<div class="mui-card"><div class="mui-card-header"><span class="left_style">图文消息'+len+'</span></div><div class="mui-card-content text_describe"><ul><li class="up_img1"><img src="images/17-8_03.png" alt="" /><input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage'+len+'" class="fileImage" name="imgFile"></li></ul><textarea name="" rows="3" cols="4" placeholder="请填写店铺描述"></textarea><div class="delate_describle"><button type="button" >删除</button></div></div></div>';
	$('.add_img_text').append(text);
});
//自定义类名点击
mui('.mui-content').on('tap','.mui-scroll div',function(e){
	var index=$(this).index();
	$(this).addClass('active').siblings('div').removeClass('active');
	$('.customLists .item_list:eq('+index+')').removeClass('hidden').siblings('.item_list').addClass('hidden');
});
function scrollDetail(url,page,pid){
	common.http('MerchantLink&a='+url,{'ticket':ticket,'client':client,'pindex':page,'pid':pid},function(data){
		console.log(data);
		var sum='';
		if(data.data.length!=0){
			for(var i=0;i<data.data.length;i++){
				if(data.data[i].sub==0){
					if(data.data[i].linkurl==''){
						sum+='<li><span>'+data.data[i].name+'</span><a class="mui-pull-right active selected" href="javascript:;"  data-url='+data.data[i].linkcode+'>选中</a></li>';
					}else{
						sum+='<li><span>'+data.data[i].name+'</span><a class="mui-pull-right active selected" href="javascript:;"  data-url='+data.data[i].linkurl+'>选中</a></li>';
					}
					
				}else{
					if(data.data[i].linkurl==''){
						sum+='<li><span>'+data.data[i].name+'</span><a class="mui-pull-right detailed" href="javascript:;" data-pid='+data.data[i].pid+' data-val='+data.data[i].module+' data-url='+data.data[i].linkcode+'>详细</a></li>';
					}else{
						sum+='<li><span>'+data.data[i].name+'</span><a class="mui-pull-right detailed" href="javascript:;" data-pid='+data.data[i].pid+' data-val='+data.data[i].module+' data-url='+data.data[i].linkurl+'>详细</a></li>';
					}

					
				}
			}
			$('.details_list').append(sum);
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



 /*点击从功能库选择*/
mui('.mui-content').on('tap','.form_libirly',function(e){
	document.activeElement.blur();
	var me=this;
	//功能库第一次选择	
	common.http('MerchantLink&a=insert',{'ticket':ticket,'client':client},function(data){
		//console.log(data);
		var str='';
		for(var i=0;i<data.modules.length;i++){
			if(data.modules[i].sub==0){
				str+='<li><span>'+data.modules[i].name+'</span><a class="mui-pull-right active selected" href="javascript:;" data-pid='+data.modules[i].pid+'  data-url='+data.modules[i].linkcode+'>选中</a></li>';
			}else{
				str+='<li><span>'+data.modules[i].name+'</span><a class="mui-pull-right detailed" href="javascript:;" data-val='+data.modules[i].module+' data-url='+data.modules[i].linkcode+'>详细</a></li>';
			}
		}
		$('.fun_details').html(str);
	});
	mask.show();
	mui('#middlePopover1').popover('show');

	//点击详细
	$('body').off('click','.detailed').on('click','.detailed',function(e){
		var detail_url=$(this).data('val');
		var pid=$(this).data('pid');
		$('.details_list').html('');
		pindex=1;
		scrollDetail(detail_url,pindex,pid);
		$('.bg').removeClass('hidden');
		$('.last_ceng').removeClass('hidden');


		// 上拉加载
		if($('.details_list').height()>$('.sroll_up').height()){
			$('.sroll_up').scroll(function(e){
				if($('.sroll_up').scrollTop()>=  $('.details_list').height() - $('.sroll_up').height()){
					$('.pullup').hide();
	            	$('.loading').show();
	                pindex++;
	                scrollDetail(detail_url,pindex,pid);
				}
			});
		}
		

	});
	// 点击选中
	$('body').off('click','.selected').on('click','.selected',function(e){
		
		var url_href=$(this).data('url');
		$(me).parents('.mui-card-content').find('input').val(url_href);
		$('.bg').addClass('hidden');
		$('.last_ceng').addClass('hidden');
		mask.close();
		mui('#middlePopover1').popover('hide');
	});

	//bg背景层点击,返回按钮，关闭按钮点击
	mui('body').on('tap','.close_floor',function(e){
		$('.bg').addClass('hidden');
		$('.last_ceng').addClass('hidden');
	});
	//mask 弹层点击  关闭按钮点击
	mui('.mui-popover').on('tap','p i.mui-pull-right',function(e){
		mask.close();
		mui('#middlePopover1').popover('hide');
	});
	mui('body').on('tap','.mui-backdrop',function(e){
		mask.close();
		mui('#middlePopover1').popover('hide');
		mask.close();
			mui('#middlePopover6').popover('hide');
	});
});
$('#middlePopover6 p i').click(function(e){
		mask.close();
			mui('#middlePopover6').popover('hide');
});
//查看微信卡卷
var wh=$(window).height();
mui('.mui-content').on('tap','.see_card',function(e){
	   tops=$(window).scrollTop();
	$("body").css({
		'width':'100%',
		'position':'fixed',
		'top':-tops
	})
	var mt=(wh-500)/2+tops;
	$("#middlePopover").css("margin-top",mt+'px')
	mask.show();
    mui('#middlePopover').popover('show');
});
mui('.mui-popover').on('tap','img',function(e){
	mask.close();
	mui('#middlePopover').popover('hide');
});

//蒙层点击关闭
mui('body').on('tap','.mui-backdrop',function(e){
    $("body").css({
        'position':'static'
    })
    document.documentElement.scrollTop=tops;
    document.body.scrollTop=tops;
    console.log(document.documentElement.scrollTop)
	mask.close();
	mui('#middlePopover').popover('hide');
});
// 使用平台多选点击
mui('.mui-content').on('tap','.change_app li',function(e){
	if($(this).find('i').is('.active')){
		$(this).find('i').removeClass('active');
	}else{
		$(this).find('i').addClass('active');
	}
});



//筛选
(function($, doc) {
	$.init();
	
	mui('.mui-content').on('tap','#class1',function(e) {
		//普通示例
		var type=document.getElementById('class1').getAttribute("data-type");
		console.log(type);
		var userPicker = new $.PopPicker();
		userPicker.setData(bgimg_list);
		var that=this;
		userPicker.show(function(items) {
			that.children[0].children[0].innerHTML = items[0].text;
			that.children[0].children[0].style.color="#333333";
			document.getElementById('class1').setAttribute('data-type', items[0].value);
			cate_name=items[0].value;
			if(items[0].text=="全品类通用"){
				document.getElementById('useClass').style.display="none";
				//document.getElementById('class2').children[0].children[0].innerHTML=''
                cate_id='0'
			
			}else{
				if(items[0].value=="shop"){
                    document.getElementById('prop').style.display="block";
				}else{
                    document.getElementById('prop').style.display="none";
				}
				document.getElementById('useClass').style.display="block";
				type_list=[];
				common.http('Merchantapp&a=ajax_ordertype_cateid',{'ticket':ticket,'client':client,'order_type':cate_name},function(data){
					//console.log(data);
					for(var i=0;i<data.length;i++){
						var type_card={'value':'','text':''};
						type_card.value=data[i].cat_id;
						type_card.text=data[i].cat_name;
						type_list.push(type_card);
					}
					type_list.unshift({
						'value':'0',
						'text':'全选'
					})

				});
				document.getElementById('class2').children[0].children[0].innerHTML='全选';
				cate_id='0'
			}
console.log(cate_id)
		}, false);
	
	});
	mui('.mui-content').on('tap','#class2',function(e) {
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData(type_list);
		var that=this;
		userPicker.show(function(items) {
			that.children[0].children[0].innerHTML = items[0].text;
			that.children[0].children[0].style.color="#333333";
			cate_id=items[0].value;
			console.log(cate_id)
		}, false);
	
	});
	
})(mui, document);


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
// //卡券颜色
// (function($, doc) {
// 	$.init();
// 	mui('.mui-content').on('tap','#colors',function(e) {
// 		//普通示例
// 		var userPicker = new $.PopPicker();
// 		userPicker.setData(color_lists);
// 		var that=this;
// 		userPicker.show(function(items) {
// 			that.children[0].children[0].innerHTML = items[0].text;
// 			that.children[0].children[0].style.color="#333333";
// 			color=items[0].value;
// 		}, false);
// 	});
	
// })(mui, document);
//发放渠道筛选
// (function($, doc) {
// 	$.init();
// 	mui('.mui-content').on('tap','#class3',function(e) {
// 		//普通示例
// 		var userPicker = new $.PopPicker();
// 		userPicker.setData([
// 		{
// 			value: '0',
// 			text: '店内店外'
// 		}, {
// 			value: '1',
// 			text: '仅店外'
// 		},{
// 			value: '2',
// 			text: '仅店内'
// 		}]);
// 		var that=this;
// 		userPicker.show(function(items) {
// 			that.children[0].children[0].innerHTML = items[0].text;
// 			that.children[0].children[0].style.color="#333333";
// 			send_type=items[0].value;
// 		}, false);
// 	});
	
// })(mui, document);



(function($, doc){	
		var btns = $('.begin_date');
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var me=this;
				var optionsJson = '{"type":"date","beginYear":1990,"endYear":2100}';
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
     


