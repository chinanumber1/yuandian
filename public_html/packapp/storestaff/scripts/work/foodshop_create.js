var selectData = [], order_html = '';
order_html += '<section class="setup">';
order_html += '<div class="setup_n">';
order_html += '{{# if (d.order_id == 0) { }}';
order_html += '<h2>创建订单</h2>';
order_html += '{{# } else { }}';
order_html += '<h2>编辑订单</h2>';
order_html += '{{# } }}';

order_html += '<ul>';
order_html += '<li class="clr classify">';
order_html += '<div class="fl">桌台分类</div>';
order_html += '<div class="fr select">';
order_html += '<select>';
order_html += '{{# var this_table_list = null;}}';
order_html += '{{# for(var i = 0, len = d.list.length; i < len; i++){ }}';
order_html += '{{# if (d.list[i].id == d.table_type) { }}';
order_html += '<option value="{{ d.list[i].id }}" selected>{{ d.list[i].name }}</option>';
order_html += '{{# this_table_list = d.list[i].list;}}';
order_html += '{{# } else { }}';
order_html += '<option value="{{ d.list[i].id }}">{{ d.list[i].name }}</option>';
order_html += '{{# } }}';
order_html += '{{# } }}';
order_html += '</select>';
order_html += '</div>';
order_html += '</li>';
order_html += '<li class="clr number">';
order_html += '<div class="fl">桌台编号</div>';
order_html += '<div class="fr select">';
order_html += '<select>';
order_html += '{{# if (this_table_list == null) { }}';
order_html += '{{# this_table_list = d.list[0].list; }}';
order_html += '{{# } }}';

order_html += '{{# for(var i = 0, len = this_table_list.length; i < len; i++){ }}';
order_html += '{{# if (this_table_list[i].id == d.table_id) { }}';
order_html += '<option value="{{ this_table_list[i].id }}" selected>{{ this_table_list[i].name }}</option>';
order_html += '{{# } else { }}';
order_html += '<option value="{{ this_table_list[i].id }}">{{ this_table_list[i].name }}</option>';
order_html += '{{# } }}';

order_html += '{{# } }}';

order_html += '</select>';
order_html += '</div>';
order_html += '</li>';
order_html += '<li class="clr people">';
order_html += '<div class="clr">';
order_html += '<div class="fl">用餐人数</div>';
order_html += '<div class="fr">';
order_html += '<input type="tel" placeholder="请输入人数" class="book_num"/>';
order_html += '</div>';
order_html += '</div>';
order_html += '<p>不收取客户必点菜费用，可设置为0</p>';
order_html += '</li>';
order_html += '{{# if (d.order_id == 0) { }}';
order_html += '<li class="clr remark"><textarea placeholder="添加备注..."></textarea></li>';
order_html += '{{# } }}';

order_html += '</ul>';
order_html += '<div class="sub">';
order_html += '{{# if (d.order_id == 0) { }}';
order_html += '<input type="submit" value="生成订单" data-order_id="{{d.order_id}}">';
order_html += '{{# } else { }}';
order_html += '<input type="submit" value="保存订单" data-order_id="{{d.order_id}}">';
order_html += '{{# } }}';

order_html += '</div>';
order_html += '<div class="del"></div>';
order_html += '</div>';
order_html += '</section>';


$(document).ready(function(){
	$(".Mask").height($(window).height());
	$(document).on('click', '.Mask, .setup .del', function() {
		$('.setup').remove();
		$(".Mask,.setup").hide();
	});
	
	$(".found").click(function() {
		common.http('Storestaff&a=foodshop_order_before', {'noTip':true}, function(data){
			selectData = data.list;
			laytpl(order_html).render(data, function(html){
				$('.setup').remove();
				$('body').append(html);
				$(".Mask,.setup").show();
			});
		});
	});
	$(document).on('click', '.setup .sub input', function(){
		var note = $('.setup').find('textarea').val(), book_num = $('.setup').find('.book_num').val(), table_id = $('.setup').find('.number select').val(), table_type = $('.setup').find('.classify select').val();
		common.http('Storestaff&a=foodshop_add_order', {'note':note, 'book_num':book_num, 'table_id':table_id, 'table_type':table_type, 'order_id':$(this).data('order_id'), 'noTip':true}, function(data){
			motify.log(data);
			location.reload();
		});
	});
	$(document).on('change', '.classify select', function(){
		var thisID = $(this).val(), childList = [], html = '';
		$.each(selectData, function(i, item){
			if (item.id == thisID) {
				childList = item.list;
			}
		});
		
		html += '{{# for(var i = 0, len = d.length; i < len; i++){ }}';
		html += '<option value="{{ d[i].id }}">{{ d[i].name }}</option>';
		html += '{{# } }}';
		
		laytpl(html).render(childList, function(html){
			$('.number select').html(html);
		});
	});
	$(document).on('click', '.setup .del', function(){
		$(".Mask,.setup").hide();
	});
});