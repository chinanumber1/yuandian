var client = common.checkAndroidApp()  ?  2 : (common.checkIosApp() ? 1 : 0);
var store_id=$.getUrlParam('store_id');
console.log(store_id);
var byin=common.getCache('byin');
var page=1;
var searchType='dh';
var keyword='';
var tops=0;
$('.byin').text(byin);
$('title').html(byin+"信息");
var mask = mui.createMask();
var height1=$(window)[0].innerHeight;
$('#masgs').html('');
function addList(searchType1,keyword1){
	common.http('WapMerchant&a=foodshopOrderList',{'client':client,'page':page,'store_id':store_id,'status':0,'searchType':searchType1,'keyword':keyword1},function(data){
		console.log(data);
		if(data.order_list.length==0){
					$('.pullup').html('没有更多数据啦');
					$('.loading').hide();
					$('.pullup').show();
					$('#masgs').html('');
				}else{
					data.order_list.length<10&&$('.pullup').html('没有更多数据啦');
					$('.loading').hide();
					$('.pullup').show();
					laytpl(document.getElementById('listTpl').innerHTML).render(data.order_list, function(html){
						//console.log(html);
						$('#masgs').append(html);
						$('.loading').hide();
						$('.pullup').show();
					});	
					console.log(page);
					if(data.order_list.length>9&&page<Number(data.totalPage)){
						var flag = false;
						// 上拉加载下拉刷新数据
						$(window).scroll(function(e) {
				            e.stopPropagation();
							e.preventDefault(); 
				              if(flag){
							      //数据加载中
							      return false;
							    }
				            //上拉加载
				            if ($(document).scrollTop() == $(document).height() - height1) {
				            	$('.pullup').hide();
				            	$('.loading').show();
				                page++;
				              	addList(searchType,keyword);
				              	flag = true;
				                $('.pullup').show();
				            	$('.loading').hide();
				            }
				        });
					}
				}
	});
}
addList(searchType,keyword);

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

 $('body').off('keypress','#find_value').on('keypress','#find_value',function(event){

 	 if(event.keyCode ==13) {
 	 	keyword=$(this).val();
 	 	document.getElementById('masgs').innerHTML='';
 	 	page=1;
 	 	addList(searchType,keyword);
 	 }

 });
 function OpenMasks(event){
 	var scrollTop=$("body").scrollTop();
 		tops=scrollTop;
 	$("body").css({
 		'overfloww':'hidden',
 		'position':'fixed',
 		'top':-scrollTop  
 	})
 }

//菜品详情点击 
mui('body').on('tap','.more',function(e){
	console.log(22)
	//$('body').css('overflow','hidden');
	mask.show();
	$('.cease').show();

	 OpenMasks();
	var order_id=$(this).attr('data-order_id');
	common.http('WapMerchant&a=foodshopOrderDetail',{'client':client,'store_id':store_id,'order_id':order_id},function(data){
		console.log(data);
		laytpl(document.getElementById('orderDetailTpl').innerHTML).render(data, function(html){
			$('.surface ul').html(html);
		});
		$('.tol_price .fl').text('共'+data.total_num+'份');
		$('.tol_price .fr span').text('￥'+data.total_price);
	});
});
//蒙层点击关闭
mui('body').on('tap','.mui-backdrop',function(e){
	mask.close();
	
	$('.cease').hide();
	$('body').css('overflow-y','auto');
	//CloseMask();
	$("body").css({
 		'overfloww':'auto',
 		'position':'static'
 		
 	})
 	document.body.scrollTop=tops
	$('.surface ul').empty();
	//CloseMask();
});

mui('body').on('tap','.dels',function(e){
	mask.close();
	$('.cease').hide();
	$('body').css('overflow-y','auto');
	//CloseMask();
	$("body").css({
 		'overfloww':'auto',
 		'position':'static'
 		
 	})
 	document.body.scrollTop=tops
	$('.surface ul').empty();
	//CloseMask();
});



(function($, doc) {
	$.init();
	mui('body').on('tap','#searchType',function(e){//订单号
		document.activeElement.blur();
		var _getParam = function(obj, param) {
			return obj[param] || '';
		};
		//普通示例
		var userPicker = new $.PopPicker();
		userPicker.setData(
			[{
				'text':'用户电话',
				'value':'dh'
			},
			{
				'text':'用户姓名',
				'value':'xm'
			},
			{
				'text':'桌台名称',
				'value':'zt'
			}]
		);
		// userPicker.pickers[0].setSelectedValue(prinks_index);
		userPicker.show(function(items) {
			document.getElementById('shaiText').innerText = items[0].text;
			searchType=items[0].value;
			document.getElementById('masgs').innerHTML='';
			page=1;
			addList(searchType,keyword);
		});
	});

	
})(mui, document);






























