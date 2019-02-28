var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var power=common.getCache('power');
console.log(power);
var be_date='';
var enddate='';
var stor_list=[];
var type_list=[];
var type='';//全局
var store_id=''; //全局
var store_index='';
var type_index='';
var mySwiper;
var tickets=urlParam.ticket;
var forms=urlParam.from;
var store_device=urlParam.store_device;
function logIn(){
	if(forms&&tickets&&store_device){
		common.http('Merchantapp&a=login', {'from':forms,'store_ticket':tickets,'store_device':store_device}, function(data){
			console.log(data);
			//alert(data.ticket);
			common.setCache('ticket',data.ticket,true);
			common.setCache('merchant_info',data.user,true);
			common.setCache('ticket',data.ticket);
			common.setCache('mer_id',data.user.mer_id);
			common.setCache('store_idAll',data.store_id);
			common.setCache('power',data.auth);
			location.href = (urlParam.back ? urlParam.back : 'index1')+'.html';
		});
	}
}
if(urlParam.from){
	// alert(forms);
	common.setCache('forms',forms);
	common.setCache('tickets',tickets);
	common.setCache('store_device',store_device);
	logIn();
}else{
	var forms1=common.getCache('forms');
	if(forms1){
		tickets=common.getCache('tickets')
		forms=common.getCache('forms')
		store_device=common.getCache('store_device');
		logIn();
	}else{




function sleep(numberMillis) {  
    var now = new Date();  
    var exitTime = now.getTime() + numberMillis;  
    while (true) {  
        now = new Date();  
        if (now.getTime() > exitTime)  
            return;  
    }  
} 






function selectChange(lists){
	$.each(lists,function(i,val){
		var goods_id_list ={'value':'','text':''};
		goods_id_list.text=val.name;
		goods_id_list.value=val.store_id;
		stor_list.push(goods_id_list);
	});
}











function replaceAll(str){
	if(str!=null)
	str = str.replace(/-/g,"/")
	return str;
}

function setData(data){
	$('.ashop').text(data.shop_name);
	$('.byin').text(data.meal_name);
	$('.ctuan').text(data.group_name);
	$('.dyu').text(data.appoint_name);
	common.setCache('ashop',data.shop_name);
	common.setCache('byin',data.meal_name);
	common.setCache('ctuan',data.group_name);
	common.setCache('dyu',data.appoint_name);
	common.setCache('jif',data.score_name);
	common.setCache('androidUrl',data.staff_android_url);
	common.setCache('iosUrl',data.storestaff_ios_download_url);
	common.setCache('androidBao',data.storestaff_android_package_name);
	common.setCache('iosBao',data.storestaff_ios_package_name);
}

/*先初始化判断有没有缓存，有的话先展示缓存内容*/
var app_config = common.getCache('config',true);
if(app_config){
	setData(app_config);
}
common.http('Merchantapp&a=config', {'client':client}, function(data){
	common.setCache('app_config',data,true);
	setData(data);
},function(data){
	
});

	
if(common.getCache('isStarted',true)){
	$('#mainPage').css('opacity','1');
	$('#startBg').show();
	$('#startBg1').show();
	$('#startBg2').show();
	initialize();
}else{
	common.setCache('isStarted','true',true);
	$('#startBg').hide();
	$('#startBg1').hide();
	$('#startBg2').hide();
	$('#startBg img').css({height:$(window).height(),width:$(window).width()});
	$('#startBg1 img').css({height:$(window).height(),width:$(window).width()});
	$('#startBg2 img').css({height:$(window).height(),width:$(window).width()});
	$('#mainPage').css('opacity','0');
	common.http('Merchantapp&a=config',{'client':client,noTip:true}, function(data){
		common.setCache('config',data,true);
		initialize();
	});
}
	
//选择店铺
common.http('Merchantapp&a=merchant_money_info', {'client':client,'type':'group'}, function(data){
	//console.log(data);
	stor_list=[];
	selectChange(data.store_list);
	$('.open_income div h3').text('￥'+data.today_money);
	$('#todayordercount').text(data.today_count);
	$('.my_balance div h3').text('￥'+data.merchant_money);
	
},function(data){
	
});

function set_index_data(data,type){

	common.setCache('qrcodeinfo',data.qrcodeinfo,true);
	type_list=[];
	$.each(data.type_name,function(i,val){
		var types_lists ={'value':'','text':''};
		types_lists.text=val.name+"订单";
		types_lists.value=val.type;
		type_list.push(types_lists);
	});
	var length=data.wap_merchantAd.length;
	if(length>0){
		 /*if(!type){
		mySwiper.destroy(false);
	   }*/
		var str = '';
		$.each(data.wap_merchantAd,function(i,val){
			str+='<div class="swiper-slide swiper-slide-duplicate" ><div ><img data-url='+data.wap_merchantAd[i].url+' src='+data.wap_merchantAd[i].pic+'></div></div>';
		});
		$('#add_imgpic .swiper-wrapper').html(str);
          $('#add_imgpic').show();
		mySwiper = new Swiper('.swiper-container',{
			direction:"horizontal",/*横向滑动*/  
			loop:true,/*形成环路（即：可以从最后一张图跳转到第一张图*/  
			pagination:".swiper-pagination",/*分页器*/   
			autoplay:3000/*每隔3秒自动播放*/  ,
			pagination : '.swiper-pagination',
			paginationClickable :true
		}); 	
		
	}else{
		$('#add_imgpic').hide();
	}
}
//网址打开广告图
 $('.swiper-wrapper').off('click','.swiper-slide img').on('click','.swiper-slide img',function(e){
	e.stopPropagation();
	e.preventDefault(); 
	var url=$(this).attr('data-url');
	if(common.checkApp()){
		if(common.checkAndroidApp()){
			window.pigcmspackapp.openBrowser(url);
		}else{
			var iosHref = window.btoa(url);
			iosHref = iosHref.replace('/','&');
			common.iosFunction('openBrowser/'+iosHref);
		}
	}else{
		location.href=url;
	}
	
});

if(common.getCache('ticket',true)){
	//首页主体数据
	/*先初始化判断有没有缓存，有的话先展示缓存内容*/

	var index_data = common.getCache('index_data',true);
	if(index_data){
		// set_index_data(index_data,true);
	}
	common.http('Merchantapp&a=index', {'client':client}, function(data){
		common.setCache('index_data',data,true);
		set_index_data(data,false);
	});

	//订单报表
	common.http('Merchantapp&a=merchant_money_date',{'client':client,'type':type,'store_id':store_id},function(data){
		echartJs(data.xAxis_arr,data.order_count,data.income);
	});
}

// 轮播图切换点击
mui('.mui-content').on('tap','.mui-indicator',function(e){
	if($(this).is('.mui-active')){}else{

		var transform_width=-(index-this_index)*index_width+'px';
		console.log(transform_width);	
		$('.mui-slider-group.mui-slider-loop').css({
		    'transform': 'translate3d('+transform_width+', 0px, 0px) translateZ(0px)',
		    'transition-duration': '0ms',
		   'transition-timing-function':'cubic-bezier(0.165, 0.84, 0.44, 1)'
		});
	}
});



function initialize(){
	if(common.checkLogin() == false){
		return false;
	}
	
	if(common.checkIosApp()){
		common.iosFunction('changecolor/#2ECC71');
	}else if(common.checkAndroidApp()){
		window.pigcmspackapp.changecolor('#2ECC71');
	}

	var merchantArr = common.getCache('merchant_info',true);
	if(merchantArr){
		indexDataObj(merchantArr);
	}else{
		common.http('Merchantapp&a=login',{noTip:true}, function(data){
			common.setCache('ticket',data.ticket,true);
			common.setCache('merchant_info',data.user,true);
			indexDataObj(data.user);

		},function(data){
			location.href = 'login.html';
		});
	}
}

function indexDataObj(merchantArr){
	if(merchantArr){
		$('#staff_name,#footer_staff_name').html(merchantArr.name);
		$('#footer_store_name').html(merchantArr.store_name);
	}
	var indexData = common.getCache('indexData',true);
	if(indexData){
		editData(indexData);
	}else{
		common.http('Merchantapp&a=index',{noTip:true}, function(data){
			common.setCache('indexData',data,true);
			editData(data);
		});
	}
}

function editData(data){
	common.setData(data.count_number);
	//console.log(data);
	$('#mainPage').css('opacity','1');
	$('#startBg').hide();
	$('#startBg1').hide();
	$('#startBg2').hide();
}

(function($, doc){
	$.init();
	mui('.mui-content').on('tap','#showUserPicker1',function(){
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
		userPicker.setData(stor_list);
		userPicker.pickers[0].setSelectedValue(store_index);
		var userResult = doc.getElementById('userResult');
			userPicker.show(function(items) {
				if(items[0].text!=undefined){
					$('#change_shop')[0].innerText= items[0].text;
					//返回 false 可以阻止选择框的关闭
					//return false;
					store_id=items[0].value;
					store_index=items[0].value;
					common.http('Merchantapp&a=merchant_money_date', {'client':client,'type':type,'store_id':store_id,'period':be_date+'-'+enddate}, function(data){
						//console.log(data);
						echartJs(data.xAxis_arr,data.order_count,data.income);
					});
				}
				
			});
	
	});
	//团购、快店类型选择
	mui('.mui-content').on('tap','#showUserPicker',function(){
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
		userPicker.setData(type_list);
		userPicker.pickers[0].setSelectedValue(type_index);
		var userResult = doc.getElementById('userResult');
			userPicker.show(function(items) {
				if(items[0].text!=undefined){
					$('#tuan_ding')[0].innerText= items[0].text;
					//返回 false 可以阻止选择框的关闭
					//return false;
					type=items[0].value;
					type_index=items[0].value;
					//console.log(type);
					common.http('Merchantapp&a=merchant_money_info', {'client':client,'type':type}, function(data){
						//console.log(data);
						stor_list=[];
						selectChange(data.store_list);
					
						
					});
					common.http('Merchantapp&a=merchant_money_date', {'client':client,'type':type,'store_id':store_id,'period':be_date+'-'+enddate}, function(data){
						//console.log(data);
						echartJs(data.xAxis_arr,data.order_count,data.income);
					});
				}
				
			});
	
	});
	

	$('.begin_date')[0].addEventListener('tap',function(){
		var timeValue=document.getElementById('begin_date').innerText;
		// var optionsJson = '{"type":"time","value":"2012-01-01 '+timeValue+'"}';
		var optionsJson = '{"type":"date","beginYear":2014,"beginMonth":5,"beginDay":1,"endYear":2020}';
		var options = JSON.parse(optionsJson);
		var id = this.getAttribute('id');
		var picker = new $.DtPicker(options);
		picker.setSelectedValue(timeValue);
		picker.show(function(rs) {
			$('#begin_date')[0].innerText = rs.text;
			be_date=replaceAll(rs.text);
			picker.dispose();
			//enddate=document.getElementById('end_date').innerText;
			if(enddate!=''){
				common.http('Merchantapp&a=merchant_money_date',{'client':client,'period':be_date+'-'+enddate,'type':type,'store_id':store_id},function(data){
					console.log(data);
					echartJs(data.xAxis_arr,data.order_count,data.income);
				});
			}else{
				mui.toast('请选择结束日期');
			}
			
		});
	});
	$('.end_date')[0].addEventListener('tap',function(){
		var timeValue=document.getElementById('end_date').innerText;
		var optionsJson = '{"type":"date","beginYear":2014,"endYear":2020}';
		var options = JSON.parse(optionsJson);
		var id = this.getAttribute('id');
		var picker = new $.DtPicker(options);
		picker.setSelectedValue(timeValue);
		picker.show(function(rs) {
			$('#end_date')[0].innerText = rs.text;
			picker.dispose();
			//var start_date=replaceAll($('#begin_date').text());
			enddate=replaceAll(rs.text);
			if(be_date!=''){
				common.http('Merchantapp&a=merchant_money_date',{'client':client,'period':be_date+'-'+enddate,'type':type,'store_id':store_id},function(data){
					console.log(data);
					echartJs(data.xAxis_arr,data.order_count,data.income);
				});
			}else{
				mui.toast('请选择开始日期');
			}
			
		});
	});
})(mui, document);




function echartJs(xAxis_arr,order_count,income){
	//曲线图表
	var getOption = function(chartType) {
	var chartOption = {
	//					option = {
			title : {
	//					        text: '某楼盘销售情况',/
			},
			tooltip : {
				trigger: 'axis'
			},
		grid: {  
			left: '3%',  
			right: '4%',  
			bottom: '3%',  
			containLabel: true  
		},  
			calculable : false,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data :xAxis_arr,
					axisLine:{
						lineStyle:{
							color:'#D2DDDE',
							width:1,//这里是为了突出显示加上的，可以去掉
						}
				   },
					 splitLine: {           // 分隔线
						show: true,        // 默认显示，属性show控制显示与否
						// onGap: null,
						lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
							color: ['#D2DDDE'],
							width: 1,
							type: 'solid'
						}
					}
				}
			],
			yAxis : [
				{
					type : 'value',
					axisLine:{
						lineStyle:{
							color:'#D2DDDE',
							width:1,//这里是为了突出显示加上的，可以去掉
						}
					},
					splitLine: {           // 分隔线
						show: true,        // 默认显示，属性show控制显示与否
						// onGap: null,
						lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
							color: ['#D2DDDE'],
							width: 1,
							type: 'solid'
						}
					}
				}
			],
			series : [
			  
				{
					name:'订单总数',
					type:'line',
					smooth:true,
					symbol:'none',
					itemStyle: {
						normal: {
							areaStyle: {
								type: 'default'
							},
						}
					},
					data:order_count
				},
				{
					name:'订单收入',
					type:'line',
					smooth:true,
					symbol:'none',
					itemStyle: {
						normal: {
							areaStyle: {
								type: 'default'
							},
						}
					},
					data:income
				}
				
			]
		};
		
	return chartOption;
	};
	var byId = function(id) {
	return document.getElementById(id);
	};
	
	var lineChart = echarts.init(byId('lineChart'));
	lineChart.setOption(getOption('line'));
}

mui.init({ 
	swipeBack: true //启用右滑关闭功能 
}); 

	
//点击二维码进入二维码页面
mui('.mui-bar-nav').on('tap', 'div.mui-pull-right', function(e) {
	openWindow({
		url:'qr_code.html',
		id:'qr_code'
	});
});

//点击进入收入记录
mui('.mui-content').on('tap','.open_income',function(e){
	openWindow({
			url:'record.html',
			id:'record'
		});
});

//快店管理点击
mui('.mui-content').on('tap','.shop_list',function(e){
	openWindow({
		url:'shop_list.html',
		id:'shop_list'
	});
});
//餐饮管理点击
mui('.mui-content').on('tap','.eat_list',function(e){
	openWindow({
			url:'shop_list_col.html',
			id:'shop_list_col'
		});
});
//点击店铺管理
mui('.mui-content').on('tap','.shop_mange',function(e){
	openWindow({
		url:'shop_management.html',
		id:'shop_management'
	});
});
//团购管理点击
mui('.mui-content').on('tap','.grounp_buy',function(e){
	openWindow({
		url:'group_buy.html',
		id:'group_buy'
	});
});
//预约管理点击
mui('.mui-content').on('tap','.booking',function(e){
	openWindow({
		url:'booking.html',
		id:'booking'
	});
});

//点击进入我的余额
mui('.mui-content').on('tap','.my_balance',function(e){
	openWindow({
		url:'my_balance.html',
		id:'my_balance'
	});
});
	}
}