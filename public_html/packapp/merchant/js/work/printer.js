mui.init();
var store_idAll=common.getCache('store_idAll');
mui('.mui-bar-nav').on('tap','.mui-pull-right',function(e){
	openWindow({
		url:"printer_add.html",
		id:'printer_add'
	});
});

mui('.mui-content').on('tap','.delate',function(e){
	var print_id = this.getAttribute('print_id');
	var print_box = this.parentNode.parentNode.parentNode;
	var btnArray=['否','是']
	mui.confirm('您确认要删除此打印机吗？', '删除提醒', btnArray, function(e){
		if(e.index == 1){
			common.http('Merchantapp&a=hardware_del',{pigcms_id:print_id}, function(data){
				print_box.remove();
				mui.alert('删除成功');
			});
		}
	});
});

mui('.mui-content').on('tap','.edit',function(e){
	openWindow({
		url:"printer_edit.html?print_id="+this.getAttribute('print_id'),
		id:'printer_edit'
	});
});

$(function(){
	common.http('Merchantapp&a=hardware',{'store_id':store_idAll}, function(data){
		console.log(data);
		laytpl($('#printListTpl').html()).render(data, function(html){
			$('.mui-content').html(html);
		});
	});
});


function pageShowFunc(){
	location.reload(true);
}