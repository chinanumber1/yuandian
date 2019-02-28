mui.init();
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var shopId= common.getCache('shopId');
var ashop=common.getCache('ashop');
var discount_controler = 0;
common.http('Merchantapp&a=config',{'client':client,noTip:true}, function(data){
    discount_controler= data.discount_controler;
});

$('.ashop').text(ashop);
$('title').html(ashop+"信息");
var deliver_types=[];//配送方式
var deliverId=0;
$('#deliver_text').attr('data-id',deliverId);
$('#deliver_text').text("请选择配送方式").css('color','#333');
var is_close=0;//开启紧急关店默认不开启 
var send_time_type=0;//默认出单时长分钟 
var send_arr=[{'value':'0','text':'分钟'},{'value':'1','text':'小时'},{'value':'2','text':'天'},{'value':'3','text':'周'},{'value':'4','text':'月'}];
// 初次加载页面
common.http('WapMerchant&a=shopEdit	',{'ticket':ticket,'client':client,'store_id':shopId},function(data){
	console.log(data);
	deliverId=data.deliver_type;
	$('.up_img img').attr('src',data.background_image);
	$('.up_img img').attr('data-urls',data.background);
	data.store_theme==1?$('.store_theme').addClass('mui-active'):$('.store_theme').removeClass('mui-active');//是否开启商城
	data.is_open_pick==1?$('.is_open_pick').addClass('mui-active'):$('.is_open_pick').removeClass('mui-active');//配送自提点
	$('.store_notice').val(data.store_notice);//店铺公告
	data.is_mult_class==1?$('.is_mult_class').addClass('mui-active'):$('.is_mult_class').removeClass('mui-active');//多级分类
	data.is_auto_order==1?$('.is_auto_order').addClass('mui-active'):$('.is_auto_order').removeClass('mui-active');//自动接单
	data.is_invoice==1?$('.is_invoice>div>div:eq(1) input').attr('checked','checked'):$('.is_invoice>div>div:eq(2) input').attr('checked','checked');//开发票
	$('.invoice_price').val(data.invoice_price);
	$('.advance_day').val(data.advance_day);
	$('.mean_money').val(data.mean_money);//人均消费
	if(data.pack_alias==undefined){
		$('.pack_alias').val('包装费');//包装费
	}else{
		$('.pack_alias').val(data.pack_alias);//包装费
	}
	if(data.freight_alias==undefined){
		$('.freight_alias').val('配送费用');
	}else{
		$('.freight_alias').val(data.freight_alias);
	}
	
	$('.work_time').val(data.work_time);//配送时长
	$.each(data.deliver_types,function(i,val){//配送方式
		var type={'value':'','text':''};
		type.value=val.id;
		type.text=val.name;
		deliver_types.push(type);
		if(val.id==data.deliver_type){
			$('#deliver_text').text(val.name);
		}
	});
	is_close=data.is_close;
	data.deliver_type==1?$('#changeType').show():$('#changeType').hide();
	data.is_close==0?$('#openShop_text').text('关闭'):$('#openShop_text').text('开启');
	$('.close_reason').val(data.close_reason);
	send_time_type=data.send_time_type;
	$.each(send_arr,function(i,val){
		if(val.value==data.send_time_type){
			$('#consuming_text').text(val.text);
		}
	});
	$('.work_time').val(data.work_time);
	//$(".virtual_sale_count").val(data.virtual_sale_count)
		
	/*商家配送*/
	$('.extra_price').val(data.extra_price);//加价送
	$('.delivery_radius').val(data.delivery_radius);//服务范围
	$('.delivertime_start').text(data.delivertime_start);//开始时间
	$('.delivertime_stop').text(data.delivertime_stop);//结束时间
	$('.basic_distance').val(data.basic_distance);
	$('.delivery_fee').val(data.delivery_fee);
	$('.per_km_price').val(data.per_km_price);
	if(data.reach_delivery_fee_type==0){
		$('.changeTime>div:eq(0) .stork ul li:eq(0) input').attr('checked','checked');
	}else if(data.reach_delivery_fee_type==1){
		$('.changeTime>div:eq(0) .stork ul li:eq(1) input').attr('checked','checked');
	}else{
		$('.changeTime>div:eq(0) .stork ul li:eq(2) input').attr('checked','checked');
	}
	$('.no_delivery_fee_value').val(data.no_delivery_fee_value);//配送时间段一结束

	$('.extra_price2').val(data.extra_price2);//加价送
	$('.delivery_radius2').val(data.delivery_radius2);//服务范围
	$('.delivertime_start2').text(data.delivertime_start2);//开始时间
	$('.delivertime_stop2').text(data.delivertime_stop2);//结束时间
	$('.basic_distance2').val(data.basic_distance2);
	$('.delivery_fee2').val(data.delivery_fee2);
	$('.per_km_price2').val(data.per_km_price2);
	if(data.reach_delivery_fee_type2==0){
		$('.changeTime>div:eq(1) .stork ul li:eq(0) input').attr('checked','checked');
	}else if(data.reach_delivery_fee_type2==1){
		$('.changeTime>div:eq(1) .stork ul li:eq(1) input').attr('checked','checked');
	}else{
		$('.changeTime>div:eq(1) .stork ul li:eq(2) input').attr('checked','checked');
	}
	$('.no_delivery_fee_value2').val(data.no_delivery_fee_value2);//配送时间段二结束


	var textClass='';
	$('.basic_price').val(data.basic_price);//起送价格
	var str='',sum='',stj='';//选择分类
	if(data.category_list.length>0){
		$.each(data.category_list,function(i,val){
			str+='<div class="item">'+val.cat_name+'</div>';
			if(data.category_list[i].son_list!=null){
				$.each(data.category_list[i].son_list,function(k,value){
					console.log(i)
					console.log(2222)
					if(i==0){
						sum+='<div class="item active" data-fid='+value.cat_fid+' data-id='+value.cat_id+'>'+value.cat_name+'<i class="mui-pull-right"></i></div>';
						textClass+=value.cat_name+'  ';

					}else{
						sum+='<div class="item" data-fid='+value.cat_fid+' data-id='+value.cat_id+'>'+value.cat_name+'<i class="mui-pull-right"></i></div>';
					}
				});
			}
			stj+='<div class="item_box" style="display:none">'+sum+'</div>';
			sum='';
		});
	}
	$('#deliver_text').attr('data-id',data.deliver_type);//配送方式
	if(textClass==''){
		$('#change2').text('请选择分类');
	}else{
		// if(textClass.length<9){
		// 	textClass=textClass.substring(0, textClass.length - 2);  
		// }
		$('#change2').text(textClass).css('color','#333');

	}
	$('.day').html(str);
	$('.time').html(stj);
	$('.day>div:eq(0)').addClass('active');
	$('.time>div:eq(0)').show();
	$('.store_discount').val(data.store_discount);//店铺折扣
	data.discount_type==0?$('.discount_type>div>div:eq(1) input').attr('checked','checked'):$('.discount_type>div>div:eq(2) input').attr('checked','checked');//店铺折扣优惠方式

	data.stock_type==0?$('.st_type>li:eq(0) input').attr('checked','checked'):$(".st_type>li:eq(1) input").attr('checked','checked');//库存类型
	data.reduce_stock_type==0?$('.reduce_stock_type>li:eq(0) input').attr('checked','checked'):$(".reduce_stock_type>li:eq(1) input").attr('checked','checked');//减库存类型
	$('.rollback_time').val(data.rollback_time);//买单时长

	//渲染优惠会员
	var plus='';
	$.each(data.levelarr,function(i,val){
		if(val.type=='0'){
			plus+='<div class="mui-card-content dispatching_class border_dis" data-id='+val.level+'><span class="left_style">'+val.lname+'</span><b data-type="0"><span>无优惠</span> <sub class="mui-pull-right"></sub></b><p><input type="number" placeholder="请填写一个优惠值数字" id="" value='+val.vv+'    /> <span></span></p></div>';
		 }else if(val.type==''){
		 	plus+='<div class="mui-card-content dispatching_class border_dis" data-id='+val.level+'><span class="left_style">'+val.lname+'</span><b data-type=""><span style="color:#404040;">请选择优惠类型</span> <sub class="mui-pull-right"></sub></b><p><input type="number"  placeholder="请填写一个优惠值数字"  id="" value='+val.vv+' /> <span></span></p></div>';
		 }
		else{
			plus+='<div class="mui-card-content dispatching_class border_dis" data-id='+val.level+'><span class="left_style">'+val.lname+'</span><b data-type="1"><span>百分比(%)</span> <sub class="mui-pull-right"></sub></b><p><input type="number" placeholder="请填写一个优惠值数字" id="" value='+val.vv+'   /> <span></span></p></div>';
		}
		
	});
	$('.plus_discont').html(plus);
	if(discount_controler == 1){
		$(".plus_discont").find("input").attr("readonly","readonly");
		$(".store_discount").attr("readonly","readonly");
        $(".discount_type").find("input").attr("disabled","disabled");
	}
});
//保存按钮点击
mui('.mui-content').on('tap','.btn_keep',function(e){
	var store_theme=$('.store_theme').is('.mui-active')?1:0;//是否开启商城
	var background=$('.up_img img').data('urls');//商城店铺背景
	var is_open_pick=$('.is_open_pick').is('.mui-active')?1:0;//配送自提点
	var store_notice=$('.store_notice').val();//店铺公告
	var is_mult_class=$('.is_mult_class').is('.mui-active')?1:0;//多级分类
	var is_auto_order=$('.is_auto_order').is('.mui-active')?1:0;//自动接单
	var is_invoice=$('.is_invoice>div>div:eq(1) input').is(':checked')?1:0;//开发票
	var invoice_price=$('.invoice_price').val();//可开发票
	var advance_day=$('.advance_day').val();//可提前**天
	var mean_money=$('.mean_money').val();//人均消费
	var pack_alias=$('.pack_alias').val();//包装费别名
	var freight_alias=$('.freight_alias').val();//运费别名

	
	var close_reason=$('.close_reason').val();
	var work_time=$('.work_time').val();
	/*配送方式的一系列字段*/
	var deliver_type=$('#deliver_text').data('id');//配送方式id
	//console.log(deliver_type);
	var work_time=$('.work_time').val();//配送时长
	var basic_price=$('.basic_price').val();//起送价格
	var extra_price=$('.extra_price').val();//加价送
	var delivery_radius=$('.delivery_radius').val();//服务距离
	/*两个配送时间段字段*/
	var delivertime_start=$('.delivertime_start').text();//配送时间段一开始
	var delivertime_stop=$('.delivertime_stop').text();//配送时间段一结束
	var basic_distance=$('.basic_distance').val();
	var delivery_fee=$('.delivery_fee').val();
	var per_km_price=$('.per_km_price').val();
	var reach_delivery_fee_type='',no_delivery_fee_value='';
	if($('.changeTime>.allTimes:eq(0) .stock_type li:eq(0) input').is(':checked')){
		reach_delivery_fee_type=0;
	}else if($('.changeTime>.allTimes:eq(0) .stock_type li:eq(1) input').is(':checked')){
		reach_delivery_fee_type=1;
	}else if($('.changeTime>.allTimes:eq(0) .stock_type li:eq(2) input').is(':checked')){
		reach_delivery_fee_type=2;
		no_delivery_fee_value=$('.no_delivery_fee_value').val();
	}

	var delivertime_start2=$('.delivertime_start2').text();//配送时间段二开始
	var delivertime_stop2=$('.delivertime_stop2').text();//配送时间段二结束
	var basic_distance2=$('.basic_distance2').val();
	var delivery_fee2=$('.delivery_fee2').val();
	var per_km_price2=$('.per_km_price2').val();
//	var virtual_sale_count=$(".virtual_sale_count").val();
	var reach_delivery_fee_type2='',no_delivery_fee_value2='';
	if($('.changeTime>.allTimes:eq(1) .stock_type li:eq(0) input').is(':checked')){
		reach_delivery_fee_type2=0;
	}else if($('.changeTime>.allTimes:eq(1) .stock_type li:eq(1) input').is(':checked')){
		reach_delivery_fee_type2=1;
	}else if($('.changeTime>.allTimes:eq(1) .stock_type li:eq(2) input').is(':checked')){
		reach_delivery_fee_type2=2;
		no_delivery_fee_value2=$('.no_delivery_fee_value').val();
	}
	//选择分类
	var store_category=[];
	$.each($('.time .item'),function(i,val){
		if($(this).is('.active')){
			var id=$(this).data('fid')+'-'+$(this).data('id');
			store_category.push(id);
		}
		id=null;
	});
	var store_discount=$('.store_discount').val();//店铺折扣
	var discount_type=$('.discount_type>div>div:eq(1) input').is(':checked')?0:1;//优惠方式
	//console.log(discount_type);
	var stock_type=$('.st_type>li:eq(0) input').is(':checked')?0:1;//库存类型
	var reduce_stock_type=$('.reduce_stock_type>li:eq(0) input').is(':checked')?0:1;//减库存类型
	var rollback_time=$('.rollback_time').val();//买单时长
	var leveloff=[];//各种会员优惠
	$.each($('.plus_discont .border_dis'),function(i,val){
		var temp ={'type':$(this).find('b').data('type'),'vv':$(this).find('input').val(), 'level':$(this).data('id')};
		leveloff.push(temp);
	});
	console.log(leveloff);
	console.log(discount_type);
	common.http('WapMerchant&a=shopSave	',{'ticket':ticket,'client':client,'store_id':shopId,'store_theme':store_theme,'background':background,'is_open_pick':is_open_pick,'store_notice':store_notice,'is_mult_class':is_mult_class,'is_auto_order':is_auto_order,'is_invoice':is_invoice,'invoice_price':invoice_price,'advance_day':advance_day,'mean_money':mean_money,'pack_alias':pack_alias,'freight_alias':freight_alias,'deliver_type':deliver_type,'work_time':work_time,'basic_price':basic_price,'extra_price':extra_price,'delivery_radius':delivery_radius,'delivertime_start':delivertime_start,'delivertime_stop':delivertime_stop,'basic_distance':basic_distance,'delivery_fee':delivery_fee,'per_km_price':per_km_price,'reach_delivery_fee_type':reach_delivery_fee_type,'no_delivery_fee_value':no_delivery_fee_value, 'delivertime_start2':delivertime_start2,'delivertime_stop2':delivertime_stop2,'basic_distance2':basic_distance2,'delivery_fee2':delivery_fee2,'per_km_price2':per_km_price2,'reach_delivery_fee_type2':reach_delivery_fee_type2,'no_delivery_fee_value2':no_delivery_fee_value2, 'store_category':store_category,'store_discount':store_discount,'stock_type':stock_type,'reduce_stock_type':reduce_stock_type,'rollback_time':rollback_time,'leveloff':leveloff,'is_close':is_close,'close_reason':close_reason,'work_time':work_time,'send_time_type':send_time_type,'discount_type':discount_type,'virtual_sale_count':''},function(data){
			
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

	});

}); 
// 配送时间段点击
mui('.set_time').on('tap','ul li',function(e){
	var index=$(this).index();
	$(this).addClass('active').siblings('li').removeClass('active');
	$('.changeTime>div:eq('+index+')').show().siblings('.allTimes').hide();
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
    var post_url = localhostPath+'/appapi.php?c=WapMerchant&a=uploadShopBackground';
    motify.log('加载中');
    $.ajaxFileUpload({
        url:post_url,
        secureuri:false,
        fileElementId:id,
        dataType: 'json', 
        type: 'post',
        // data : {'wxcard_img' : 1},
        success: function(data, status){ 
        	console.log(data);
        	setTimeout(function(){
        		$('.motifyShade,.motify').hide();
        	},2000);
        	$('#'+id).prev().attr('src',data.result.url);
        	$('#'+id).prev().attr('data-urls',data.result.title);
        	
        },
        error: function(data, status, e){ 
          mui.alert('上传失败');
        }
    }); 
}
$('.up_img').off('change','input').on('change','input',function(e){
	var id=$(this).attr('id');
	imgUpload(id);
});

// 出单时长跟开启紧急关闭店
(function($, doc) {
	$.init();
    $('.mui-content').on('tap','#closeShop',function(e){
        document.activeElement.blur();

     setTimeout(function(){
      var _getParam = function(obj, param) {
          return obj[param] || '';
      };
      //普通示例
      var userPicker = new $.PopPicker();
      userPicker.setData([{
          'value':'0',
          'text':'关闭'
      },{
          'value':'1',
          'text':'开启'
      }]);
      userPicker.pickers[0].setSelectedValue(is_close);
      userPicker.show(function(items) {
          document.getElementById('openShop_text').innerHTML = items[0].text;
          document.getElementById('openShop_text').setAttribute('data-id',items[0].value);
          is_close=items[0].value;
      });
  },200)



	});

	mui('.mui-content').on('tap','#consuming',function(e){
        document.activeElement.blur();
        setTimeout(function(){
            var _getParam = function(obj, param) {
                return obj[param] || '';
            };
            //普通示例
            var userPicker = new $.PopPicker();
            userPicker.setData(send_arr);
            userPicker.pickers[0].setSelectedValue(send_time_type);
            userPicker.show(function(items) {
                document.getElementById('consuming_text').innerHTML = items[0].text;
                document.getElementById('consuming_text').setAttribute('data-id',items[0].value);
                send_time_type=items[0].value;
            });
        },200)



	});


})(mui, document);









// 配送方式
(function($, doc) {
	$.init();
	mui('.mui-content').on('tap','#deliver_types',function(e){
        document.activeElement.blur();
        setTimeout(function(){
            var _getParam = function(obj, param) {
                return obj[param] || '';
            };
            //普通示例
            var userPicker = new $.PopPicker();
            userPicker.setData(deliver_types);
            userPicker.pickers[0].setSelectedValue(deliverId);
            userPicker.show(function(items) {
                document.getElementById('deliver_text').innerHTML = items[0].text;
                document.getElementById('deliver_text').setAttribute('data-id',items[0].value);
                deliverId=items[0].value;
                if(items[0].value==1||items[0].value==4){
                    document.getElementById('changeType').style.display="block";
                }else{
                    document.getElementById('changeType').style.display="none";
                }
                //返回 false 可以阻止选择框的关闭
                //return false;
            });
		},200)

	
	});
})(mui, document);



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

//会员优惠功能下拉框选择
(function($, doc) {
	$.init();
	mui('.plus_discont').on('tap','.border_dis b',function(e){
		if(discount_controler==1){return false;}
		var this_text=this.children[0].innerText;
		var me=this;
		var _getParam = function(obj, param) {
			return obj[param] || '';
		};
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData([{
			value: '0',
			text: '无优惠'
		}, {
			value: '1',
			text: '百分比(%)'
		}]);
		if(this_text=="无优惠"||this_text=="请选择优惠类型"){
			userPicker.pickers[0].setSelectedValue(0);
		}else{
			userPicker.pickers[0].setSelectedValue(1);
		}
		userPicker.show(function(items) {
			me.children[0].innerText=items[0].text;
			me.setAttribute('data-type',items[0].value);
			//返回 false 可以阻止选择框的关闭
			//return false;
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
mui('.shop_swich').each(function() {
    var _this = this;
    mui(_this).switch();
    _this.addEventListener("toggle", function(event) {
        var id = _this.getAttribute("title");
        if (event.detail.isActive) {
      //      event.detail.isActive =0;
			
        } else {
          
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
    document.activeElement.blur();
	mask.show();
	$('.deliver_time_box').removeClass('hidden');
	 var h=$(".timer").height()-50;
	 $(".day").css("height",h+"px");
     $(".time").css("height",h+"px");
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
				content=$('.time .item.active:eq(0)').text()+'  '+$('.time .item.active:eq(1)').text();
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
	      	




/*提示语*/


mui('.mui-content').on('tap','.plus_alert',function(e){
  	mui.alert('必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！', function() {
    });
});

mui('.mui-content').on('tap','.youshi',function(e){
  	mui.alert('折上折的意思是如果这个用户是有平台VIP等级，平台VIP等级有折扣优惠。那么这个用户的优惠计算方式是先用店铺的优惠进行打折后，再用VIP折扣进去打折；折扣最优是指：购买产品的总价用店铺优惠打折后的价格与总价跟VIP优惠打折后的价格进行比较，取最小值的优惠方式', function() {
    });
});

mui('.mui-content').on('tap','.peishi',function(e){
  	mui.alert('如果使用自提功能请及时 添加自提点地址; 如果开启了自有支付，那么平台配送是无效的！平台配送时服务距离由平台来设置！快递配送：没有服务距离的限制，按配送时间段一的设置来计算配送费;', function() {
    });
});

mui('.mui-content').on('tap','.jianshi',function(e){
  	mui.alert('1.支付成功后减库存：可能会出现售出的数量大于商品总数；2.下单成功后减库存：可能会出现大量下单但是没有支付时库存就已经没有了，但是如果在下面设置的买单时长的时间内还是没有买单的话系统自动回滚库存', function() {
    });
});



mui('.mui-content').on('tap','.jinguan',function(e){
  	mui.alert('状态是“关闭”时店铺是正常状态，“开启”是店铺已关闭。【紧急关店用于临时有事需要暂停营业的情况下，开启后，用户前台不可下单，请谨慎使用】', function() {
    });
});

mui('.mui-content').on('tap','.chushi',function(e){
  	mui.alert('从店员接单起到店员配好订单所有商品所需要的时间。', function() {
    });
});
	      	