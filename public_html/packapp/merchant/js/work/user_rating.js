if(common.checkWeixin()){
	$('.mui-bar-nav').remove();
}
mui.init();
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var page=1;//页码
var tab="";//好评
var order_type=3;//快店、团购、餐饮区别参数
var merchant_reply ="";//回复与未回复
var store_id='';//店铺id
var shopList=[];//全部店铺列表
var moren=0;
var first_request=0;
var ashop=common.getCache('ashop');
if(!ashop){
	ashop = '快店';
}
var ctuan=common.getCache('ctuan');
if(!ctuan){
	ctuan = '团购';
}
var byin=common.getCache('byin');
if(!byin){
	byin = '餐饮';
}

var typeList = common.getCache('typeList',true);
//console.log('typeList',typeList);
if(typeList){
	var str = "";
	if(typeList.have_shop){
		str+='<li class="mui-pull-left"><ul><li><span class="ashop">'+ashop+'</span>评论</li></ul></li>';
	}
	if(typeList.have_group){
		str+='<li class="mui-pull-left"><ul><li><span class="ctuan">'+ctuan+'</span>评论</li></ul></li>';
	}
	if(typeList.have_meal){
		str+='<li class="mui-pull-left"><ul><li><span class="byin">'+byin+'</span>评论</li></ul></li>';
	}
	$('.header_style>ul').html(str);
	$('.header_style>ul>li:eq(0)').addClass('active');
}




function addRating(page,tab,order_type,merchant_reply,store_id){
	//console.log(order_type);
	common.http('Merchantapp&a=user_rating_list',{'client':client,'tab':tab,"order_type":order_type,'merchant_reply':merchant_reply,"store_id":store_id,'page':page},function(data){
		first_request++;
		if(page==1){
            if(data.store_deliver_score==-1&&data.store_score==-1){
                $('#usats').hide();
            }else{
                $('#usats').show();
                if(data.store_deliver_score!=-1){
                    $('#replyDeliverScore').text(data.store_deliver_score);
                }else{
                    $('#replyDeliverScore').text('0');
				}
                if(data.store_score!=-1){
                    $('.replyScore').text(data.store_score);
                    $('.fen b').width(25*data.store_score);
                }else{
                    $('.replyScore').text('0');
                    $('.fen b').css("width","0px")
				}



            }
		}

		if(first_request==1){
			common.setCache('typeList',{have_shop:data.have_shop,have_group:data.have_group,have_meal:data.have_meal},true);
			var str = "";
			if(data.have_shop){
				str+='<li class="mui-pull-left"><ul><li><span class="ashop">'+ashop+'</span>评论</li></ul></li>';
			}
			if(data.have_group){
				str+='<li class="mui-pull-left"><ul><li><span class="ctuan">'+ctuan+'</span>评论</li></ul></li>';
			}
			if(data.have_meal){
				str+='<li class="mui-pull-left"><ul><li><span class="byin">'+byin+'</span>评论</li></ul></li>';
			}
			$('.header_style>ul').html(str);
			$('.header_style>ul>li:eq(0)').addClass('active');
			if(order_type==4){
				$('.header_style>ul>li:eq(2)').addClass('active').siblings('li').removeClass('active');
			}else if(order_type==0){
				$('.header_style>ul>li:eq(1)').addClass('active').siblings('li').removeClass('active');
			}else if(order_type==3){
				$('.header_style>ul>li:eq(0)').addClass('active').siblings('li').removeClass('active');
			}
			if($('.header_style>ul>li').length==2){
				$('.header_style>ul>li').width("50%");
			}else if($('.header_style>ul>li').length==1){
				$('.header_style>ul>li').width("100%");
			}
			// 遍历店铺列表
			if(data.store_list!=undefined&&data.store_list.length>0){
				shopList=[];
				for(var i=0;i<data.store_list.length;i++){
					var type_card={'value':'','text':''};
					type_card.value=data.store_list[i].store_id;
					type_card.text=data.store_list[i].name;
					shopList.push(type_card);

				}
				//console.log(shopList);
				shopList.unshift({value: "", text: "全部店铺"});

			}
		}
			
		
		console.log(data);
		if(data.reply_list.length==0){
			$('.pullup1').html('没有更多数据啦');
			$('.loading1').hide();
			$('.pullup1').show();

		}else{
			data.reply_list.length<=9&&$('.pullup1').html('没有更多数据啦');
			// 渲染评价内容
			var str="";
			for(var i=0;i<data.reply_list.length;i++){
				
				var sum='';//好评菜品
				var sums='';
				if(data.reply_list[i].goods.length!=0){
					for(var j=0;j<data.reply_list[i].goods.length;j++){
						sum+=data.reply_list[i].goods[j]+' ';
					}

					sums='<p><b></b><span>'+sum+'</span></p>';
				}
				
				var pic='';//评论照片
				var pics='';
				if(data.reply_list[i].pic!=""&&data.reply_list[i].pics!=[]&&data.reply_list[i].pics!=undefined){
					moren++;
					for(var k=0;k<data.reply_list[i].pics.length;k++){
						pics+='<img src='+data.reply_list[i].pics[k].s_image+' data-preview-src=""  data-preview-group='+moren+'/>';
					}
					pic='<div class="mui-scroll-wrapper" ><div class="mui-scroll ">'+pics+'</div></div>';//评论照片
				}else{
					pic='';
				}
				var btn='';//是否已回复
				var shangHui='';//商家是否已回复
				if(data.reply_list[i].merchant_reply_content==""){
					btn='<a class="mui-pull-right ansowres" data-id='+data.reply_list[i].pigcms_id+' >回复</a>';
					shangHui='';
				}else{
					shangHui='<div class="rating-footer"><p><span>商家回复</span><i class="mui-pull-right">'+data.reply_list[i].merchant_reply_time_hi+'</i></p>	<div>'+data.reply_list[i].merchant_reply_content+'</div></div>';
					btn='<a href="javascript:void(0);" class="mui-pull-right " style="opacity:0;" data-id='+data.reply_list[i].pigcms_id+' >回复</a>';
				}
				//评价等级
				var score='';
				if(data.reply_list[i].score==0){
					score='<i class="one_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i>';
				}else if(data.reply_list[i].score==1){
					score='<i class="five_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i>';
				}else if(data.reply_list[i].score==2){
					score='<i class="five_pointed"></i><i class="five_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i>';
				}else if(data.reply_list[i].score==3){
					score='<i class="five_pointed"></i><i class="five_pointed"></i><i class="five_pointed"></i><i class="one_pointed"></i><i class="one_pointed"></i>';
				}else if(data.reply_list[i].score==4){
					score='<i class="five_pointed"></i><i class="five_pointed"></i><i class="five_pointed"></i><i class="five_pointed"></i><i class="one_pointed"></i>';
				}else if(data.reply_list[i].score==5){
					score='<i class="five_pointed"></i><i class="five_pointed"></i><i class="five_pointed"></i><i class="five_pointed"></i><i class="five_pointed"></i>';
				}
				var nickname='';
				if(data.reply_list[i].nickname==""){
					nickname="匿名用户";
				}else{
					nickname=data.reply_list[i].nickname;
				}
				if(order_type==3){
					str+='<div class="mui-card rating"><div class="mui-card-header"><ul class="mui-table-view"><li><img style="width:42px;" class="mui-media-object mui-pull-left" src='+data.reply_list[i].avatar+'><div class="mui-media-body  mui-clearfix"><div class="mui-pull-left"><span>'+nickname+'</span><p>店铺&nbsp;&nbsp;&nbsp;'+score+'</p></div><div class="mui-pull-right open_ansower"><div class="mui-clearfix">'+btn+'</div><p>'+data.reply_list[i].add_time_hi+'</p></div></div></li></ul></div><div class="mui-card-content"><h5>'+data.reply_list[i].comment+'</h5>' +sums+pic+'</div>'+shangHui+'</div>';
				}else{
					str+='<div class="mui-card rating"><div class="mui-card-header"><ul class="mui-table-view"><li><img style="width:42px;" class="mui-media-object mui-pull-left" src='+data.reply_list[i].avatar+'><div class="mui-media-body  mui-clearfix"><div class="mui-pull-left"><span>'+nickname+'</span><p>'+score+'</p></div><div class="mui-pull-right open_ansower"><div class="mui-clearfix">'+btn+'</div><p>'+data.reply_list[i].add_time_hi+'</p></div></div></li></ul></div><div class="mui-card-content"><h5>'+data.reply_list[i].comment+'</h5>' +sums+pic+'</div>'+shangHui+'</div>';
				}
				
			}
			$('#rats').append(str);
			$('.loading1').hide();
			$('.pullup1').show();
			//图片侧滑
			mui.init();
			mui('.mui-scroll-wrapper').scroll({
				scrollY:false,
				scrollX:true,
				startX:0,
				startY:0,
				indicators:false,
				deceleration:0.0005,
				bounce:true
			});
			mui.previewImage();
			// 上拉加载或下拉刷新
			 var flag = false;
			$(window).scroll(function(e) {
			    e.stopPropagation();
			    if(flag){
			      //数据加载中
			      return false;
			    }
			    //上拉加载
			    if ($(document).scrollTop() == $(document).height() - $(window).height()) {
			    	$('.pullup1').hide();
			    	$('.loading1').show();
			    	flag = true;
			        page++;
			      addRating(page,tab,order_type,merchant_reply,store_id);
			    }
			});
		}
	});
}
//初次加载
addRating(1,'',3,'','');//初次加载页面
tab='all';
// common.http('Merchantapp&a=user_rating_list',{'ticket':ticket,'client':client,'tab':tab,"order_type":order_type,'merchant_reply':merchant_reply,"store_id":store_id},function(data){

// })；


mui('.mui-content').on('tap','.open_ansower .ansowres', function(e) {

	var id=$(this).attr('data-id');
	//window.location.href='reply.html?data='+id;
	openWindow({
		url:'reply.html?data='+id,
		id:'reply'
	});
});

//渲染全部店铺列表
(function($, doc) {
	$.init();
	mui('.mui-content').on('tap','#showUserPicker',function(e){
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
		userPicker.setData(shopList);
		userPicker.pickers[0].setSelectedValue(store_id);
		userPicker.show(function(items) {
			document.getElementById('allShops').innerHTML= items[0].text;
			document.getElementById('allShops').style.color="#333";
			store_id=items[0].value;
			console.log(store_id);
			page=1;
			document.getElementById('rats').innerHTML="";
			addRating(page,tab,order_type,merchant_reply,store_id);
			//返回 false 可以阻止选择框的关闭
			//return false;
		});
		
		
	
	});
})(mui, document);


//全部评价
(function($, doc) {
	$.init();
	$.ready(function() {
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
			value: 'all',
			text: '评价类型'
		}, {
			value: 'high',
			text: '好评'
		}, {
			value: 'mid',
			text: '中评'
		}, {
			value: 'low',
			text: '差评'
		}]);
		userPicker.pickers[0].setSelectedValue(tab);
		var showUserPickerButton1 = doc.getElementById('showUserPicker1');
		showUserPickerButton1.addEventListener('tap', function(event) {
			userPicker.show(function(items) {
				document.getElementById('shopRat').innerHTML = items[0].text;
				document.getElementById('shopRat').style.color="#333";
				tab=items[0].value;
				page=1;
				document.getElementById('rats').innerHTML="";
				addRating(page,tab,order_type,merchant_reply,store_id);
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
		}, false);
	
	});
})(mui, document);

//全部评价
(function($, doc) {
	$.init();
	$.ready(function() {
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
			value: '',
			text: '评价回复'
		}, {
			value: 'yes',
			text: '已回复'
		}, {
			value: 'no',
			text: '未回复'
		}]);
		userPicker.pickers[0].setSelectedValue(merchant_reply);
		var showUserPickerButton1 = doc.getElementById('showUserPicker2');
		showUserPickerButton1.addEventListener('tap', function(event) {
			userPicker.show(function(items) {
				document.getElementById('allUser').innerHTML = items[0].text;
				document.getElementById('allUser').style.color="#333";
				merchant_reply=items[0].value;
				page=1;
				document.getElementById('rats').innerHTML="";
				addRating(page,tab,order_type,merchant_reply,store_id);
				//返回 false 可以阻止选择框的关闭
				//return false;
			});
		}, false);
	
	});
})(mui, document);


//头部点击
mui('.mui-content').on('tap','.header_style>ul>li',function(e){
	//$(this).addClass('active').siblings('li').removeClass('active');
	page=1;
	if($(this).find('li').text()==(ashop+"评论")){
		order_type=3;
		$('#rats').html('');
		$('.pullup1').hide();
		addRating(page,tab,order_type,merchant_reply,store_id);
		$('.header_style>ul>li:eq(0)').addClass('active').siblings('li').removeClass('active');
	}else if($(this).find('li').text()==(ctuan+"评论")){
		order_type=0;
		$('#rats').html('');
		$('.pullup1').hide();
		addRating(page,tab,order_type,merchant_reply,store_id);
		$('.header_style>ul>li:eq(1)').addClass('active').siblings('li').removeClass('active');
	}else if($(this).find('li').text()==(byin+"评论")){
		order_type=4;
		$('#rats').html('');
		$('.pullup1').hide();
		$('.header_style>ul>li:eq(2)').addClass('active').siblings('li').removeClass('active');
		addRating(page,tab,order_type,merchant_reply,store_id);
	}
});
			
//图片侧滑
mui('.mui-scroll-wrapper').scroll({
	scrollY:false,
	scrollX:true,
	startX:0,
	startY:0,
	indicators:false,
	deceleration:0.0005,
	bounce:true
});
mui.previewImage();
function pageShowFunc(){
	location.reload(true);
}