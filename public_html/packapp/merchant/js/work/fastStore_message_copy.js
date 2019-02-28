mui.init();
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var shopId= $.getUrlParam('store_id');
var upload1=0;//0上传背景图  1上传商家图片
var byin=common.getCache('byin');
$('title').html(byin+"信息");
$('.byin').text(byin);
// 初次加载页面
common.http('WapMerchant&a=foodshopModify',{'ticket':ticket,'client':client,'store_id':shopId},function(data){
	console.log(data);
	if(data.store!=null){
		if(data.store.background!=""){
			$('.up_img img').attr('src',data.store.background_arr[0].url);
			$('.up_img img').attr('data-urls',data.store.background_arr[0].title);//商城店铺背景图
		}
		
		// 商家图片
		var pic='';
		if(data.store.pic_arr!=﻿undefined){
			var pic_len=data.store.pic_arr.length;
			if(data.store.pic_arr.length>0){
				$.each(data.store.pic_arr,function(i,val){
					pic+='<div class="mui-pull-left delate_photo" data-url='+val.title+' ><img src='+val.url+' data-preview-src=""><i></i></div>';
				});
			}
			$('.showImg').append(pic);
			$('.showImg').width(105*(pic_len+1)+60);
		}
		
		
		
		data.store.is_book==1?$('.is_book').addClass('mui-active'):$('.is_book').removeClass('mui-active');
		if(data.store.is_book==1){
			$('.yushow').show();
		}else{
			$('.yushow').hide();
		}
		$('.book_day').val(data.store.book_day);
		$('.book_start').text(data.store.book_start);
		$('.book_stop').text(data.store.book_stop);
		$('.book_time').val(data.store.book_time);
		$('.cancel_time').val(data.store.cancel_time);//提前取消预约时长
		data.store.is_queue==1?$('.is_queue').addClass('mui-active'):$('.is_queue').removeClass('mui-active');
		data.store.is_takeout==1?$('.is_takeout').addClass('mui-active'):$('.is_takeout').removeClass('mui-active');
		data.store.is_park==1?$('.is_park').addClass('mui-active'):$('.is_park').removeClass('mui-active');
		data.store.is_auto_order==1?$('.is_auto_order').addClass('mui-active'):$('.is_auto_order').removeClass('mui-active');
		data.store.template==0?$('.template1').attr('checked','checked'):$('.template2').attr('checked','checked')
		$('.hot_alias_name').val(data.store.hot_alias_name);
		$('.ranking_num').val(data.store.ranking_num);
	}


	var textClass='';
	var str='',sum='',stj='';//选择分类
	if(data.category_list.length>0){
		$.each(data.category_list,function(i,val){
			str+='<div class="item">'+val.cat_name+'</div>';
			if(data.category_list[i].son_list!=null){
				$.each(data.category_list[i].son_list,function(k,value){
					if(value.is_select==1){
						sum+='<div class="item active" data-fid='+value.cat_fid+' data-id='+value.cat_id+'>'+value.cat_name+'<i class="mui-pull-right"></i></div>';
						textClass+=value.cat_name+'   ';

					}else{
						sum+='<div class="item" data-fid='+value.cat_fid+' data-id='+value.cat_id+'>'+value.cat_name+'<i class="mui-pull-right"></i></div>';
					}
				});
			}
			stj+='<div class="item_box" style="display:none">'+sum+'</div>';
			sum='';
		});
	}
	
	if(textClass==''){
		$('#change2').text('请选择分类');
	}else{
		$('#change2').text(textClass).css('color','#333');

	}
	$('.day').html(str);
	$('.time').html(stj);
	$('.day>div:eq(0)').addClass('active');
	$('.time>div:eq(0)').show();




});
//保存按钮点击
mui('.mui-content').on('tap','.btn_keep',function(e){

	var	background=[];
	$.each($('.up_img img'),function(i,val){
		background.push($(this).data('urls'));
	});
	var pic=[];//商家图片
	$.each($('.showImg .delate_photo'),function(i,val){
		pic.push($(this).attr('data-url'));
	});
	var is_book=$('.is_book').is('.mui-active')?1:0;
	var book_day=$('.book_day').val();
	var book_start=$('.book_start').text();
	var book_stop=$('.book_stop').text();
	var book_time=$('.book_time').val();
	var cancel_time=$('.cancel_time').val();
	var is_queue=$('.is_queue').is('.mui-active')?1:0;
	var is_takeout=$('.is_takeout').is('.mui-active')?1:0;
	var is_park=$('.is_park').is('.mui-active')?1:0;
	var is_auto_order=$('.is_auto_order').is('.mui-active')?1:0;
	var template=0;
	if($('.template1').is(':checked')){
		template=0;
	}else{
		template=1;
	}
	var hot_alias_name=$('.hot_alias_name').val();
	var ranking_num=$('.ranking_num').val();
	
	
	var store_category=[];//选择分类id
	$.each($('.time .item'),function(i,val){
		if($(this).is('.active')){
			var id=$(this).data('fid')+'-'+$(this).data('id');
			store_category.push(id);
		}
		id=null;
	});
	if(store_category!=[]){
		common.http('WapMerchant&a=foodshopSave',{'ticket':ticket,'client':client,'store_id':shopId,'background':background,'pic':pic,'is_book':is_book,'book_day':book_day,'book_start':book_start,'book_stop':book_stop,'book_time':book_time,'cancel_time':cancel_time,'is_queue':is_queue,'is_takeout':is_takeout,'is_park':is_park,'is_auto_order':is_auto_order,'template':template,'store_category':store_category,'hot_alias_name':hot_alias_name,'ranking_num':ranking_num},function(data){
			if(data.length==0){
				mui.toast('保存成功');
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
			}
		});
	}else{
		mui.toast('请选择分类');
	}
	



}); 


mui('.showImg').on('tap','.delate_photo i',function(e){
	$(this).parents('.delate_photo').remove();
});


// 选择分类
mui('.timer').on('tap','.day .item',function(e){
	document.activeElement.blur();
	var index=$(this).index();
	$(this).addClass('active').siblings('.item').removeClass('active');
	$('.time>.item_box:eq('+index+')').show().siblings('.item_box').hide();
});
// 上传图片
function imgUpload(id){
    var fullPath=window.document.location.href;  
    var pathName=window.document.location.pathname;  
    var pos=fullPath.indexOf(pathName);  
    var localhostPath=fullPath.substring(0,pos);  
    var post_url = localhostPath+'/appapi.php?c=WapMerchant&a=uploadPic';
    motify.log('加载中');
    $.ajaxFileUpload({
        url:post_url,
        secureuri:false,
        fileElementId:id,
        dataType: 'json', 
        type: 'post',
        // data : {'store_id' :shopId },
        success: function(data, status){ 
        	console.log(data);
        	setTimeout(function(){
        		$('.motifyShade,.motify').hide();
        	},2000);
        	if(upload1==0){
        		$('#'+id).prev().attr('src',data.result.url);
        	 	$('#'+id).prev().attr('data-urls',data.result.title);
        	}else{
        		var str='';
        		str+='<div class="mui-pull-left delate_photo" data-url='+data.result.title+' ><img src='+data.result.url+' data-preview-src=""><i></i></div>';
        		$('.showImg').append(str);
        		var pic_len=$('.showImg .delate_photo').length;
				$('.showImg').width(105*(pic_len+1)+110);

        	} 	
        },
        error: function(data, status, e){ 
         	mui.alert('上传失败');
        }
       
    }); 
}
$('.up_img').off('change','input').on('change','input',function(e){
	var id=$(this).attr('id');
	imgUpload(id);
	upload1=0;
});
$('.up_img1').off('change','input').on('change','input',function(e){
	var id=$(this).attr('id');
	var length=$('.showImg .delate_photo').length;
	if(length<5){
		upload1=1;
		imgUpload(id);
	}else{
		mui.toast('最多只能上传5个图片');
	}
	
});




//时间设置
(function($, doc){	
	mui('.mui-content').on('tap','.date_btn',function(e){
		 var timeValue=this.innerText;
		 //console.log(timeValue);
			var me=this;
			var optionsJson = '{"type":"time","value":"2012-01-01 '+timeValue+'"}';
			var options = JSON.parse(optionsJson);
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
				$(me)[0].innerText=rs.text;
				
				
				picker.dispose();
			});
	
	});
})(mui, document);

//遮罩蒙版
var mask = mui.createMask();//callback为用户点击蒙版时自动执行的回调；			

//点击小红点弹出遮罩层
mui('.mui-content').on('tap','.open_floor',function(){
	mask.show();
	mui('#middlePopover').popover('show');
});

//swich 开关			commodity_management
mui('.is_book').each(function() {
    var _this = this;
    mui(_this).switch();
    _this.addEventListener("toggle", function(event) {
        var id = _this.getAttribute("title");
        if (event.detail.isActive) {
      //      event.detail.isActive =0;
			$('.yushow').show();
        } else {
          $('.yushow').hide();
        }
    });
});
//点击蒙版
mui('body').on('tap','.mui-backdrop',function(){
	mui('#middlePopover').popover('hide');
	mask.close();
});
//点击关闭按钮
mui('#middlePopover').on('tap','b',function(){
	mui('#middlePopover').popover('hide');
	mask.close();
});
//点击保存按妞
mui('#middlePopover').on('tap','button',function(){
	mui('#middlePopover').popover('hide');
	mask.close();
});
	      	
//复选框点击
mui('.deliver_time_box').on('tap','.time .item',function(e){
	if($(this).is('.active')){
		$(this).removeClass('active')
	}else{
		$(this).addClass('active');
	}
});	      	
//点击选择分类出现弹框
//mask.show();
mui('.mui-content').on('tap','.change_class',function(e){
	mask.show();
	$('.deliver_time_box').removeClass('hidden');
	
	//tab切换
	mui('.deliver_time_box').on('tap','.day .item',function(e){
		$(this).addClass('active').siblings('.item').removeClass('active');
	});
	//完成点击
	mui('.deliver_time_box').on('tap','.header i',function(e){
			var content='请选择分类';
			var len=$('.time .item.active').length;
			if(len>=3){
				
				content=$('.time .item.active:eq(0)').text()+'   '+$('.time .item.active:eq(1)').text()+'   '+$('.time .item.active:eq(2)').text()+'...';
				
			}else if(len==2){
				content=$('.time .item.active:eq(0)').text()+'   '+$('.time .item.active:eq(1)').text();
			}else if(len==1){
				content=$('.time .item.active:eq(0)').text();
			}
			//console.log(content);
		$('.change_class span').html(content).css('color','#333');
		$('.deliver_time_box').addClass('hidden');
		mask.close();
	});
});

//


mui('body').on('tap','.mui-backdrop',function(){
	$('.deliver_time_box').addClass('hidden');
	mask.close();
});
	      	
	      	
mui('.left_srcoll').scroll({
	scrollY:false,
	scrollX:true,
	startX:0,
	startY:0,
	indicators:false,
	deceleration:0.0005,
	bounce:true
});
mui.previewImage();    


mui('.mui-content').on('tap','.cad',function(e){
	 mui.alert('系统默认给一张默认图！图片建议尺寸为1920*1080，大小小于1M。', function() {
        
    });
});

mui('.mui-content').on('tap','.huan',function(e){
	 mui.alert('第一张将作为主图片！最多上传5个图片！图片宽度建议为900px，高度建议为450px。', function() {
        
    });
});
mui('.mui-content').on('tap','.yushi',function(e){
	 mui.alert('如果两个都不填写的话，表示从零点开始，按预订间隔时长进行全天预订', function() {
        
    });
});
mui('.mui-content').on('tap','.yuge',function(e){
	 mui.alert('两个可预订时间之间相隔的时长', function() {
        
    });
});
mui('.mui-content').on('tap','.tiqu',function(e){
	 mui.alert('至少要提前多久才能取消，否则不能取消', function() {
        
    });
});
mui('.mui-content').on('tap','.dianmo',function(e){
	 mui.alert('点餐页模板的界面显示形式', function() {
        
    });
});
mui('.mui-content').on('tap','.quesh',function(e){
	 mui.alert('客户在通知上菜的时候是否要店员确认后再上菜', function() {
        
    });
});

