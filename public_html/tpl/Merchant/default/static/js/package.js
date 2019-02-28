/*添加选项*/


function addImgMessage(){
	art.dialog.data('titledom', 'titledom');
	art.dialog.data('imgids', 'imgids');
	art.dialog.data('multinews', 'multinews');
	art.dialog.data('singlenews', 'singlenews');
	
	art.dialog.data('js_appmsg_preview', 'js_appmsg_preview');
	art.dialog.data('multione', 'multione');
	art.dialog.open('?g=Merchant&c=Article&a=select_img',{lock:true,title:'选择图文消息',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}


$(document).ready(function(){
	$(".question_box table .red").live('click',function(){
		if ($(this).parents("table").find('tr').size() > 2) {
			$(this).parents("tr").remove();
		} else {
			$(this).parents(".table").remove();
		}
	});

	
	$(".spec .bot_add a.btn").live('click',function(){
		
		var i = $(this).parents('.spec').index();
		
		
		if ($(this).parents(".question_box").find("table").hasClass("table")) {
			art.dialog.data('html_tr', $(this).parents(".question_box").find("table"));
			art.dialog.removeData('html_table')
		} else {
			art.dialog.removeData('html_tr')
			art.dialog.data('html_table', $(this).parents(".question_box").find(".optionul_r"));
		}
		console.log(parseInt($(this).parents('.spec').index()));
		art.dialog.data('index_i', parseInt($(this).parents('.spec').index()));
		
		art.dialog.open(menu_url,{lock:true,title:'菜单列表',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45}, false);
		
		
//		var i = $(this).parents('.spec').index();
//		
//		var t = '<tr>';
//		t += '<td>名称</td>';
//		t += '<td>价格</td>';
//		t += '<td></td>';
//		t += '<td><a title="删除" class="red" style="padding-right:8px;" href="javascript:;">';
//		t += '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
//		t += '</a></td>';
//		t += '</tr>';
//		if ($(this).parents(".question_box").find("table").hasClass("table")) {
//			$(this).parents(".question_box").find("table").append(t);
//		} else {
//			$(this).parents(".question_box").find(".optionul_r").prepend('<table class="table table-striped table-bordered table-hover"><tr><td>菜品名称</td><td>菜品价格</td><td>规格</td><td>操作</td></tr>' + t + '</table>');
//		}
	});
	
	$(".add_spec").click(function(){
		var i = $('.spec').size();
		
		var t = '<div class="question_box spec">';
			t += '<p class="question_info"><span>可选数：</span>';
			t += '<input type="text" class="txt" value="1" name="nums[]"/>';
			t += '<input type="hidden" class="txt" name="dids[]"/>';
			t += '<a href="javascript:;" class="box_del">删除</a>';
			t += '</p><div class="optionul_r">';
//			t += '<table class="table table-striped table-bordered table-hover">';
//			t += '<tr>';
//			t += '<td>菜品名称</td>';
//			t += '<td>菜品价格</td>';
//			t += '<td>规格</td>';
//			t += '<td>操作</td>';
//			t += '</tr>';
//			t += '<tr>';
//			t += '<td>名称</td>';
//			t += '<td>价格</td>';
//			t += '<td></td>';
//			t += '<td class="button-column">';
//			t += '<a title="删除" class="red" style="padding-right:8px;" href="javascript:;">';
//			t += '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
//			t += '</a>';
//			t += '</td>';
//			t += '</tr>';
//			t += '</table>';
			t += '<p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">添加菜品</a></p>';
			t += '</div>';
			$(".add_spec").before(t);

	});
	
	$(".box_del").live('click',function(){
	    $(this).parents(".question_box").remove();
	});
	
});
