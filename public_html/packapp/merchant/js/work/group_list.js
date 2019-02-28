mui.init();
var ticket = common.getCache('ticket');
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var ctuan=common.getCache('ctuan');
$('.ctuan').text(ctuan);
var pindex=1;
var searchtype='';
var seachVal='';
var group_id=$.getUrlParam('group_id');
mui.init({
	pullRefresh: {
		container: '#pullrefresh',
		
		up: {
			// contentrefresh: '正在加载...',
			callback: pullupRefresh
		}
	}
});

function addTuan(page,key,type){
	console.log(key,type);
	common.http('Merchantapp&a=group_order_list',{'ticket':ticket,'client':client,'pindex':page,'keyword':key,'searchtype':type,'group_id':group_id},function(data){
		console.log(data);
		if (data.order_list.length!=0) {
			data.order_list.length<=9&&$('.pullup1').html('没有更多数据啦');
			
			laytpl(document.getElementById('pluscardLists').innerHTML).render(data.order_list, function(html){
				$('.order_list ul').append(html);
			});
			if(data.order_list.length>9){
				
			}
		}else{
			$('.pullup1').html('没有更多数据啦');
			$('.loading1').hide();
			$('.pullup1').show();
			mui.toast('没有更多订单了');
		}
	});
}
addTuan(1,'','');

function pullupRefresh() {//
	$('.pullup1').hide();
	setTimeout(function() {
		mui('#pullrefresh').pullRefresh().endPullupToRefresh(); //参数为true代表没有更多数据了。
		
		 	pindex++;
      	addTuan(pindex,seachVal,searchtype);
	}, 1500);
}
// 监听input搜索
$('.cable_n input').keyup(function(e){
	seachVal=trim($(this).val());
	$('.order_list ul').html('');
	if(seachVal!=''){
		pindex=1;
	}
	addTuan(pindex,seachVal,searchtype);
});
function trim(str){ //删除左右两端的空格
　　     return str.replace(/(^\s*)|(\s*$)/g, "");
　　 }


(function($, doc) {
	$.init();
	mui('.cable_n').on('tap','.consumption_password',function(e) {
	//消费密码
	var userPicker = new $.PopPicker();
	userPicker.setData([
			{
			value: '',
			text: '全部'
			},{
				value: 'real_orderid',
			text: '订单编号'
			}, {
				value: 'orderid',
			text: '支付流水号'
			}, {
				value: 'third_id',
			text: '第三方支付流水号'
			}, {
				value: 's_name',
			text: ctuan+'名称'
			},{
				value: 'express_id',
			text: '快递单号'
			},{
				value: 'name',
			text: '用户昵称'
			}, {
				value: 'phone',
			text: '用户电话'
	}]);
	var that=this;
	userPicker.show(function(items) {
		that.children[0].innerHTML = items[0].text;
		that.children[0].style.color="#333333";
		searchtype=items[0].value;
		pindex=1;
		console.log(searchtype,seachVal);
		document.getElementById('data').innerHTML=" ";
		addTuan(pindex,seachVal,searchtype);
	}, false);
		
	});

	
})(mui, document);


//点击li进入详情页面
mui('#data').on('tap','li',function(e){
	var order_id=$(this).attr('data-id');
	openWindow({
		url:'group_detail.html?order_id='+order_id+'&group_id='+group_id,
		id:'group_detail'
	});
});














