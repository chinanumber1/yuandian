mui.init();
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var dyu=common.getCache('dyu');
$('.dyu').text(dyu);
var pindex=1;
var searchtype='';
var seachVal='';//input值
var begin_date='';//开始时间
var end_date='';//结束时间
var pay_type='';////全部支付
var appoint_id=$.getUrlParam('appoint_id');
var payList=[{'value':'','text':"全部支付方式"}];
common.http('Merchantapp&a=pay_list',{'ticket':ticket,'client':client},function(data){
	//console.log(data);
	selectChange(data)
});
function selectChange(lists){
	$.each(lists,function(i,val){
		var goods_id_list ={'value':'','text':''};
		goods_id_list.text=val;
		goods_id_list.value=i;
		payList.push(goods_id_list);
	});
}

var heights=$(window).height();
if(common.checkIosApp()){
	$('#pullrefresh').height(heights-205);
}else{
	$('#pullrefresh').height(heights-185);
}



mui.init({
	pullRefresh: {
		container: '#pullrefresh',
		up: {
			// contentrefresh: '正在加载...',
			callback: pullupRefresh
		}
	}
});
function addAppoint(page,key,type,begin_date,end_date,pay_type){
	common.http('Merchantapp&a=appoint_list',{'ticket':ticket,'client':client,'pindex':page,'stime':begin_date,'etime':end_date,'searchtype':type,'appoint_id':appoint_id,'keyword':key,'pay_type':pay_type},function(data){
		console.log(data);
		if (data.order_list.length!=0) {
			data.order_list.length<=9&&$('.pullup1').html('没有更多数据啦');
			$('.loading1').hide();
			$('.pullup1').show();
			laytpl(document.getElementById('pluscardLists').innerHTML).render(data.order_list, function(html){
				console.log(html);
				$('.kd_entry ul').append(html);
			});
			$('.dyu').text(dyu);
			// if(data.order_list.length>9){
				
			// }
		}else if(data.order_list.length==0){
			$('.pullup1').html('没有更多数据啦');
			$('.loading1').hide();
			$('.pullup1').show();
		}
	});
}


addAppoint(pindex,seachVal,searchtype,begin_date,end_date,pay_type);
function pullupRefresh() {//
	$('.pullup1').hide();
	setTimeout(function() {
		mui('#pullrefresh').pullRefresh().endPullupToRefresh(); //参数为true代表没有更多数据了。
		 	pindex++;
      	addAppoint(pindex,seachVal,searchtype,begin_date,end_date,pay_type);
	}, 1500);
}

// var flag = false;
// 				$(window).scroll(function(e) {
// 				    e.stopPropagation();
// 				    if(flag){
// 				      //数据加载中
// 				      return false;
// 				    }
// 				    //上拉加载
// 				    if ($(document).scrollTop() <= $(document).height() - $(window).height()) {
// 				    	$('.pullup1').hide();
// 				    	$('.loading1').show();
// 				    	flag = true;
// 				        pindex++;
// 				      addAppoint(pindex,seachVal,searchtype,begin_date,end_date,pay_type);
// 				    }
// 				});

mui('body').on('tap','.query',function(e){
	var seachVal=$('#find_value').val();
	pindex=1;
	$('.kd_entry ul').html('');
	addAppoint(pindex,seachVal,searchtype,begin_date,end_date,pay_type);
});







//筛选
(function($, doc) {
	$.init();
	
	mui('.cable_kd').on('tap','.order_number',function(e) {
		//订单编号
		var userPicker = new $.PopPicker();
		userPicker.setData([{
			value: '',
			text: '全部'
		}, {
			value: 'order_id',
			text: '订单编号'
		}, {
			value: 'orderid',
			text: '订单流水号'
		}, {
			value: 'third_id',
			text: '第三方支付流水号'
		}, {
			value: 'name',
			text: '客户名称'
		}, {
			value: 'phone',
			text: '客户电话'
		}]);
		var that=this;
		userPicker.show(function(items) {
			that.children[0].innerHTML = items[0].text;
			that.children[0].style.color="#333333";
			searchtype=items[0].value;
			pindex=1;
		}, false);
	
	});
	
})(mui, document);

//支付方式
(function($, doc) {
	$.init();
	mui('.kd_select').on('tap','.pay',function(e) {
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData(payList);
		var that=this;
		userPicker.show(function(items) {
			that.children[1].innerHTML = items[0].text;
			that.children[1].style.color="#333333";
			pay_type=items[0].value;
			pindex=1;
		}, false);
	});
	
})(mui, document);




(function($, doc){	
		var btns = $('.begin_date');
		btns.each(function(i, btn) {
			btn.addEventListener('tap', function() {
				var me=this;
				var optionsJson = '{"type":"date","beginYear":2014,"endYear":2019}';
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
					$('#pickTimeBtn')[0].children[0].innerHTML=rs.text;
					$('#pickTimeBtn')[0].children[0].style.color='#404040';
					begin_date=rs.text;
					pindex=1;
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
				var optionsJson = '{"type":"date","beginYear":2014,"endYear":2019}';
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
					$('#pickTimeBtn1')[0].children[0].innerText=rs.text;
					$('#pickTimeBtn1')[0].children[0].style.color='#404040';
					end_date=rs.text;
					pindex=1;
					picker.dispose();
				});
			}, false);
		});
	})(mui, document);
	


//验证服务点击
mui('#data').on('tap','.yanzhen',function(e){
	$('.mask').show();
	$('.seek').show();
	var order_id=$(this).parents('li').attr('data-id');
	var me=this;
	//确认消费
	mui('.seek').on('tap','.ensure',function(e){
		
			common.http('Merchantapp&a=appoint_verify',{'ticket':ticket,'client':client,'order_id':order_id},function(data){
				if(data.length==0){
					mui.toast('验证成功');
					// addAppoint(pindex,seachVal,searchtype,begin_date,end_date,pay_type);
					$(me).removeClass('yanzhen').addClass('kd_cons').text('已验证');
				}
			});
		
		$('.mask').hide();
		$('.seek').hide();

	});
});

mui('body').on('tap','.mask',function(e){
	$('.mask').hide();
	$('.seek').hide();
});
//点错了
mui('.seek').on('tap','.close',function(e){
	$('.mask').hide();
	$('.seek').hide();
});

//点击进入订单详情页面
mui('.kd_entry').on('tap','ul li .query_con',function(e){
	var order_id = $(this).parents('li').attr('data-id');
	openWindow({
		url:'order_detail.html?order_id='+order_id+'&appoint_id='+appoint_id,
		id:'order_detail'
	});
});
// //已验证点击
// mui('.kd_entry').on('tap','.kd_cons',function(e){
// 	mui.openWindow({
// 		url:'order_detail.html',
// 		id:'order_detail',
// 	});
	
// });