$(function(e){
var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var store_id=$.getUrlParam('store_id');
var ashop=common.getCache('ashop');
$('.ashop').text(ashop);
$('title').html(ashop+"订单");
var be_date='';
var enddate='';
var mask = mui.createMask();
var selectsText='oid';//订单号
var order_from='-2';//订单来源
var status='-1';//订单状态
var pay_type='-2';//支付方式
var page=1;
var fv='';//输入框搜索值

var order_fromList=[];//订单来源
var statusList=[];//订单状态
var pay_typeList=[];//支付方式
var is_open_pick='';

var is_change='';
var page2=1;
//库存不足查看点击
mui('body').on('tap','.remind .rem_see',function(e){
	mask.show();
	$('.stock').show();
});
//蒙层点击关闭
mui('body').on('tap','.mui-backdrop',function(e){
	mask.close();
	$('.stock').hide();
});
mui('body').on('tap','.stock .del',function(e){
	mask.close();
	$('.stock').hide();
});
mui('body').on('tap','.remind .del',function(e){
	$('.remind').hide();
});

$('body').on('click','.store ul li',function(e){
	var order_id=$(this).attr('data-id');
	openWindow({
		url:'fastList_detail.html?order_id='+order_id+'&store_id='+store_id,
		id:'fastList_detail'
	});	
});

addMages(store_id,1,'','oid','-2','-1','-2','','');

 $('body').off('keypress','#find_value').on('keypress','#find_value',function(event){
 	
 	 if(event.keyCode ==13) {
 	 	fv=$(this).val();
 	 	document.getElementById('masgs').innerHTML='';
 	 	page=1;
 	 	addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate)
 	 }

 });

function xiala(a,b,c){
	order_fromList=[];
	$.each(a,function(i,val){
		var iten={'value':i,'text':val};
		order_fromList.push(iten);
	});
	statusList=[];
	$.each(b,function(i,val){
		var iten={'value':i,'text':val};
		if(i=="-1"){
			statusList.unshift(iten);
		}else{
			statusList.push(iten);
		}
		
	});
	pay_typeList=[];
	$.each(c,function(i,val){
		var iten={'value':i,'text':val};
		pay_typeList.push(iten);
	});
}


var height1=$(window)[0].innerHeight;
function addMages(store_id,page1,fv1,selectsText1,order_from1,status1,pay_type1,be_date1,enddate1){
		common.http('WapMerchant&a=shopOrderList',{'client':client,'page':page1,'store_id':store_id,'fv':fv1,'ft':selectsText1,'order_from':order_from1,'st':status1,'pay_type':pay_type1,'stime':be_date1,'etime':enddate1},function(data){
				console.log(data);
				xiala(data.order_from,data.status_list,data.pay_type);
				if(page2==1){
					is_open_pick=data.is_open_pick;
					is_change=data.is_change;
					page2++;
				}
				console.log(is_change);
				if(data.shop_order.length==0){
					$('.pullup').html('没有更多数据啦');
					$('.loading').hide();
					$('.pullup').show();
					//$('#masgs').html('');
				}else{
					data.shop_order.length<10&&$('.pullup').html('没有更多数据啦');
					$('.loading').hide();
					$('.pullup').show();
					laytpl(document.getElementById('listTpl').innerHTML).render(data.shop_order, function(html){
						//console.log(html);
						$('#masgs').append(html);
						$('.loading').hide();
						$('.pullup').show();
					});	
					if(data.shop_order.length>9){
						var flag = false;
						// 上拉加载下拉刷新数据
						$(window).scroll(function(e) {
				            e.stopPropagation();
				              if(flag){
							      //数据加载中
							      return false;
							    }
							   
				            //上拉加载
				            if ($(document).scrollTop() == $(document).height() - height1) {
				            	$('.pullup').hide();
				            	$('.loading').show();
				            	flag = true;
				                page++;
				              	addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate);
				                $('.pullup').show();
				            	$('.loading').hide();
				            }
				        });
					}
				}
			});
		
}

var handler=	function () {
        event.preventDefault();
        event.stopPropagation();
    };

    function OpenMask ()
    {
        document.body.addEventListener('touchmove',handler,false);
        document.body.addEventListener('wheel',handler,false);
    };
    
 	function CloseMask(){
        document.body.removeEventListener('touchmove',handler,false);
        document.body.removeEventListener('wheel',handler,false);
    };

$("#find_value").focus(function(){
 	 OpenMask();
 	$("#find_value").blur(function(){
	 	CloseMask();
	});
});








//下拉框筛选
(function($, doc) {
	$.init();
	mui('body').on('tap','#selects',function(e){//订单号
		document.activeElement.blur();
		var _getParam = function(obj, param) {
			return obj[param] || '';
		};
		//普通示例
		var userPicker = new $.PopPicker();

		userPicker.setData(
			[{
				'text':'订单号',
				'value':'oid'
			},
			{
				'text':'用户电话',
				'value':'dh'
			},
			{
				'text':'用户姓名',
				'value':'xm'
			}]
		);
		// userPicker.pickers[0].setSelectedValue(prinks_index);
		userPicker.show(function(items) {
			document.getElementById('selectsText').innerHTML = items[0].text;
			selectsText=items[0].value;
			document.getElementById('masgs').innerHTML='';
			page=1;
			addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate);
		});
	});

	mui('body').on('tap','.order_from',function(e){//订单来源
		document.activeElement.blur();
		var _getParam = function(obj, param) {
			return obj[param] || '';
		};
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData(order_fromList);
		// userPicker.pickers[0].setSelectedValue(prinks_index);
		userPicker.show(function(items) {
			document.getElementById('order_fromText').innerHTML = items[0].text;
			order_from=items[0].value;
			document.getElementById('masgs').innerHTML='';
			page=1;
			addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate);
		});
	});
 mui('body').on('tap','#status1',function(e){//订单状态
 	document.activeElement.blur();
		var _getParam = function(obj, param) {
			return obj[param] || '';
		};
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData(statusList);
		// userPicker.pickers[0].setSelectedValue(prinks_index);
		userPicker.show(function(items) {
			document.getElementById('statusText').innerHTML = items[0].text;
			status=items[0].value;
			document.getElementById('masgs').innerHTML='';
			page=1;
			addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate);
		});
	});

    mui('body').on('tap','.pay_type',function(e){//支付方式
    	document.activeElement.blur();
		var _getParam = function(obj, param) {
			return obj[param] || '';
		};
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData(pay_typeList);
		// userPicker.pickers[0].setSelectedValue(prinks_index);
		userPicker.show(function(items) {
			document.getElementById('pay_typeText').innerHTML = items[0].text;
			pay_type=items[0].value;
			document.getElementById('masgs').innerHTML='';
			page=1;
			addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate);
		});
	});
})(mui, document);



(function($, doc){
	$.init();

	$('.begin_date')[0].addEventListener('tap',function(){
		document.activeElement.blur();
		var timeValue=document.getElementById('stime').value;
		// var optionsJson = '{"type":"time","value":"2012-01-01 '+timeValue+'"}';
		var optionsJson = '{"type":"date","beginYear":2014,"beginMonth":5,"beginDay":1,"endYear":2020}';
		var options = JSON.parse(optionsJson);
		var id = this.getAttribute('id');
		var picker = new $.DtPicker(options);
		picker.setSelectedValue(timeValue);
		picker.show(function(rs) {
			$('#stime')[0].value = rs.text;
			be_date=rs.text;
			picker.dispose();
			enddate=document.getElementById('etime').value;
			document.getElementById('masgs').innerHTML='';
			page=1;
			if(enddate!=''){
				addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate);
			}else{
				mui.toast('请选择结束日期');
			}
			
		});
	});
	$('.end_date')[0].addEventListener('tap',function(){
		document.activeElement.blur();
		var timeValue=document.getElementById('etime').value;
		var optionsJson = '{"type":"date","beginYear":2014,"endYear":2018}';
		var options = JSON.parse(optionsJson);
		var id = this.getAttribute('id');
		var picker = new $.DtPicker(options);
		picker.setSelectedValue(timeValue);
		picker.show(function(rs) {
			$('#etime')[0].value = rs.text;
			picker.dispose();
			be_date=document.getElementById('stime').value;
			enddate=rs.text;
			document.getElementById('masgs').innerHTML='';
			page=1;
			if(be_date!=''){
				addMages(store_id,page,fv,selectsText,order_from,status,pay_type,be_date,enddate);
			}else{
				mui.toast('请选择开始日期');
			}
			
		});
	});
})(mui, document);
});