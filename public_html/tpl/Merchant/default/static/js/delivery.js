var area_used_id=[],area_selected_id=[],tmp_area_selected_id=[],now_edit_area = null;
$(function(){
	$('.js-assign-cost').live('click',function(){
		now_edit_area = null;
		show_area_layer();
	});
	
	$('.freight-add-form .js-save-btn').live('click',function(){
		var save_btn = $(this);
		var name_dom = $('.freight-add-form input[name="name"]');
		name_dom.val($.trim(name_dom.val()));
		if(name_dom.val().length == 0){
			layer.alert('模板名称不能为空', 1);
		}else if($('.freight-template-table tbody tr').length == 0){
			layer.alert('至少要有一个配送区域');
		}else{
			var post_txt = [];
			var vid = 0, freight = 0, full_money = 0;
			$.each($('.freight-template-table tbody tr'),function(i,item){
				var vid = $(this).data('vid'), aids = [];
				$.each($(item).find('.text-depth'),function(j,jtem){
					aids.push($(jtem).attr('area-id'));
				});
				freight = $(item).find('.cost-input[name="freight"]').val();
				full_money = $(item).find('.cost-input[name="full_money"]').val();
				post_txt.push({'vid':vid,
					'aids':aids,
					'freight':freight,
					'full_money':full_money
				});
			});
			save_btn.html('提交中...').prop('disabled',true);
			var edit_tip_id = $('.freight-add-form').attr('tpl-id');
			if (edit_tip_id) {
				$.post(add_url,{name:name_dom.val(),area:post_txt,tpl_id:edit_tip_id},function(result){
					if (result.err_code == 0) {
						location.href = load_url;
					} else {
						layer.alert(result.err_msg,0);
					}
					save_btn.html('保存').prop('disabled',false);
				}, 'json');
			} else {
				$.post(add_url,{name:name_dom.val(),area:post_txt},function(result){
					if (result.err_code==0) {
						location.href = load_url;
					} else {
						layer.alert(result.err_msg, 0);
					}
				}, 'json');
			}
		}
	});
					
	$('.freight-template-list-wrap .freight-template-title').live('click',function(e){
		e.stopPropagation();
		var siblings_table = $(this).siblings('table');
		if(siblings_table.hasClass('hide')){
			$(this).find('.js-freight-extend-toggle').removeClass('freight-extend-toggle-extend');
			siblings_table.removeClass('hide');
		}else{
			$(this).find('.js-freight-extend-toggle').addClass('freight-extend-toggle-extend');
			siblings_table.addClass('hide');
		}
	});
					
	$('.freight-template-list-wrap .js-freight-edit').live('click',function(e){
		e.stopPropagation();
		location.href = $(this).data('href');
		return false;
	});
	


	$('.area-editor-list-title-content span').live('click',function(){
		if($(this).parent('div').parent('div').hasClass('area-editor-list-select')){
			$(this).parent('div').parent('div').removeClass('area-editor-list-select');
		}else{
			$(this).parent('div').parent('div').addClass('area-editor-list-select');
			// 移除该省下面选中的市
			//$(this).parent('div').parent('div').siblings('div[class="city"]').children('ul').children('li').removeClass('select_city');
		}
	});

//	$('.city ul li').live('click',function(){
//		if($(this).hasClass('select_city')){
//			$(this).removeClass('select_city');
//		}else{
//			$(this).addClass('select_city');
//			// 移除该市所在的省
//			$(this).parent('ul').parent('div').siblings('div[class*="area-editor-list-select"]').removeClass('area-editor-list-select');
//		}
//	});

	$('.js-area-editor-translate').live('click',function(){
		var areas = $('.area-editor-list .area-editor-depth').children('li');
		var selected_num = 0;
		$.each(areas,function(i,v){
			var selected_provence_li = $(v).children('div').hasClass('area-editor-list-select');
			if(selected_provence_li){
				var area_id = $(v).attr('area-id');
				// 检查该省下面的市有没有添加过，如果有，则删除该省下已添加过的市
				var used_li = $('.js-area-editor-used .area-editor-depth li');
				$.each(used_li,function(aa,bb){
					if(parseInt(area_id) == parseInt($(bb).attr('area-id'))){
						area_used_id[$(bb).attr('area-id')] = 0;
					}
				});
				area_used_id[area_id] = 1;
				selected_num++;
				$(v).remove();
				return true;	// 等同于php中的continue
			}
		});
		
		if(selected_num > 0){
			var area_used_html = '';
			for (var key in __alldiv){
				if(area_used_id[key] == 1){
					area_used_html += '<li area-id="'+key+'"><div class="area-editor-list-title"><div class="area-editor-list-title-content js-ladder-select"><div class="area-editor-ladder-toggle extend">&nbsp;</div>'+__alldiv[key][0]+'<div class="area-editor-remove-btn js-ladder-remove">×</div></div></div></li>';
				}
			}
			$('.js-area-editor-used .area-editor-depth').html(area_used_html);
			
		}
	});


	$('.js-ladder-remove').live('click',function(){
		var tmp_li = $(this).closest('li');
		var area_id = tmp_li.attr('area-id');
		area_used_id[area_id] = 0;
		tmp_area_selected_id[area_id] = 0;
		var area_notused_html = '';
		for (var key in __alldiv){
			if(area_used_id[key] != 1){
				area_notused_html += '<li area-id="'+key+'"><div class="area-editor-list-title"><div class="area-editor-list-title-content js-ladder-select"><div class="js-ladder-toggle area-editor-ladder-toggle extend">+</div><span>'+__alldiv[key][0]+'</span></div></div></li>';
			}
		}
		$('.js-area-editor-notused .area-editor-depth').html(area_notused_html);
		tmp_li.remove();
	});
	$('.js-modal-close').live('click',function(){
		$('.area-modal-wrap').remove();
		area_used_id = [];
	});
	
	$('.js-modal-save').live('click',function(){
		var used_li = $('.js-area-editor-used .area-editor-depth li');
		if(used_li.length == 0){
			alert('请先选择省份！');
			return;
		}
		var used_area = [];
		$.each(used_li,function(i,item){
			used_area[i] = $(item).attr('area-id');
		});

		var just_selectes = [];
		$.each($('.freight-template-table tbody tr'),function(ji,item){
			$.each($(item).find('.text-depth'),function(j,jtem){
				var area_id = $(jtem).attr('area-id');
				just_selectes[j] = area_id;
			});
		});

		var selected_area = [];
		selected_area = selected_area.concat(just_selectes);
		var is_exits = false;

		if(is_exits){
			layer_tips(1,'区域重复选择！');
		}else{
			var html = '<tr data-vid="0"><td>';
			var area_html = '';
			$.each(used_area,function(i,item){
				area_html += '、<span area-id="'+item+'" class="text-depth">'+__alldiv[item][0]+'</span>';
			});
			html += area_html.substr(1);
			var freight = 0.00;
			var full_money = 0.00;
			if(now_edit_area){
				freight = now_edit_area.find("input[name='freight']").val();
				full_money = now_edit_area.find("input[name='full_money']").val();
			}
			
			html += '<div class="right"><a href="javascript:;" class="js-edit-cost-item">编辑</a> <a href="javascript:;" class="js-delete-cost-item">删除</a></div></td><td><input type="text" value="' + freight + '" class="cost-input js-input-currency" name="freight" data-default="0" maxlength="5"></td><td><input type="text" value="' + full_money + '" class="cost-input js-input-currency" name="full_money" maxlength="8"></td></tr>';
			if(now_edit_area){
				now_edit_area.replaceWith(html);
			}else{
				$('.freight-template-table tbody').append(html);
			}
			$.each($('.freight-template-table .text-depth'),function(i,item){
				var area_id = $(item).attr('area-id');
				area_selected_id[area_id] = 1;
			});
			$('.area-modal-wrap').remove();
			area_used_id = [];
		}
	});
	
	$('.js-edit-cost-item').live('click',function(){
		now_edit_area = $(this).closest('tr');
		var area_span = $(this).closest('td').find('span');
		$.each(area_span,function(i,item){
			var area_id = $(item).attr('area-id');
			area_used_id[area_id] = 1;
		});
		show_edit_area_layer();
	});
	var cost_item_obj = null;
	$('.js-delete-cost-item').live('click',function(){
		cost_item_obj = $(this).closest('tr');
		layer.tips('<div class="form-inline" style="padding:5px;"><span class="help-inline item-delete" style="display:inline-block;padding-right:20px;font-size:14px;letter-spacing:1px;">确定删除?</span><button type="button" class="tbtn btn-primary js-btn-confirm" id="js-btn-confirm"  style="margin-right:5px;">确定</button><button type="reset" class="tbtn js-btn-cancel">取消</button></div>',$(this),{
			tips: 3,
			time: 0
		});
		$('body').bind('click',function(e){
			e=e||window.event;
			var src=e.target||e.srcElement;
			if(src.id == 'js-btn-confirm'){
				var area_span = cost_item_obj.find('span');
				$.each(area_span,function(i,item){
					var area_id = $(item).attr('area-id');
					area_selected_id[area_id] = 0;
				});
				cost_item_obj.remove();
			}
			layer.closeAll();
			$('body').unbind('click');
		});
	});
	$('.js-btn-cancel').live('click',function(){
		layer.closeAll();
	});
	
	$('.freight-template-table tbody .js-input-number').live('blur',function(){
		$(this).val($.trim($(this).val()));
		if(!/^\d+$/.test($(this).val())){
			$(this).val('0');
		}
	});
	$('.freight-template-table tbody .js-input-currency').live('blur',function(){
		$(this).val($.trim($(this).val()));
		var float_val = parseFloat($(this).val());
		if(float_val > 9999.99){
			$(this).val('9999.00');
		}else if(!/^\d+(\.\d+)?$/.test($(this).val())){
			$(this).val('0.00');
		}else{
			$(this).val(float_val.toFixed(2));
		}
	});
	
	var delete_dom = null;
	$('.freight-template-list-wrap .js-freight-delete').live('click',function(e){
		delete_dom = $(this);
		layer.tips('<div class="form-inline" style="padding:5px;"><span class="help-inline item-delete" style="display:inline-block;padding-right:20px;font-size:14px;letter-spacing:1px;">确定删除?</span><button type="button" class="tbtn btn-primary js-btn-delete" id="js-btn-confirm"  style="margin-right:5px;">确定</button><button type="reset" class="tbtn js-btn-cancel">取消</button></div>',$(this),{
			tips: 3,
			time: 0,
			style: ['background-color:black; color:#fff', 'black']
		});
		e.stopPropagation();
		$('body').bind('click',function(e){
			e=e||window.event;
			var src=e.target||e.srcElement;
			if(src.id == 'js-btn-confirm'){
				$.post(delete_url,{tpl_id:delete_dom.attr('tpl-id')},function(result){
					if(result.err_code == 0){
						layer.alert(result.err_msg,0);
						delete_dom.parents('li').remove();
					}else{
						layer.alert(result.err_msg,0);
					}
				}, 'json');
			}
			layer.closeAll();
			$('body').unbind('click');
		});
	});
});

function analysis_area(){
	$('#nprogress').html('');
	$.each($('td.text-depth-td'),function(i,item){
		var area_arr = $(item).attr('area-ids').split('&');
		var text_depth = '';
		for(var i in area_arr){
			text_depth += '、<span class="text-depth">'+__alldiv[area_arr[i]][0]+'</span>';
		}
		$(item).removeAttr('area-ids').removeClass('text-depth-td').html(text_depth.substr(1));
	});
	if($('.freight-template-list-wrap li').size() == 1){
		$('.freight-template-list-wrap li h4').click();
	}	
}
function show_edit_area_layer(){
	tmp_area_selected_id = area_selected_id;
	var html = '<div class="area-modal-wrap"><div class="modal-mask"><div class="area-modal"><div class="area-modal-head">选择可配送区域</div><div class="area-modal-content"><div class="area-editor-wrap clearfix"><div class="area-editor-column js-area-editor-notused"><div class="area-editor"><h4 class="area-editor-head">可选区域</h4><ul class="area-editor-list"><li><ul class="area-editor-list area-editor-depth">';
	for (var key in __alldiv){
		if (area_used_id[key] == 1) {
			continue;
		}
		html += '<li area-id="'+key+'"><div class="area-editor-list-title"><div class="area-editor-list-title-content js-ladder-select"><div class="js-ladder-toggle area-editor-ladder-toggle extend">+</div><span>'+__alldiv[key][0]+'</span></div></div></li>';
	}
	html += '</ul></li></ul></div></div><button class="tbtn btn-wide area-editor-add-btn js-area-editor-translate">添加</button><div class="area-editor-column area-editor-column-used js-area-editor-used"><div class="area-editor"><h4 class="area-editor-head">已选区域</h4><ul class="area-editor-list"><li><ul class="area-editor-list area-editor-depth">';
	for (var key in __alldiv){
		if(area_used_id[key] == 1){
			html += '<li area-id="'+key+'"><div class="area-editor-list-title"><div class="area-editor-list-title-content js-ladder-select"><div class="js-ladder-toggle area-editor-ladder-toggle extend">+</div>'+__alldiv[key][0]+'<div class="area-editor-remove-btn js-ladder-remove">×</div></div></div></li>';
		}
	}
	html += '</ul></li></ul></div></div></div></div><div class="area-modal-foot"><button class="tbtn btn-primary btn-wide js-modal-save">确定</button>&nbsp;&nbsp;<button class="tbtn btn-wide js-modal-close">取消</button></div></div></div></div>';
	$('body').append(html);
}
function show_area_layer(){
	var html = '<div class="area-modal-wrap"><div class="modal-mask"><div class="area-modal"><div class="area-modal-head">选择可配送区域</div><div class="area-modal-content"><div class="area-editor-wrap clearfix"><div class="area-editor-column js-area-editor-notused"><div class="area-editor"><h4 class="area-editor-head">可选区域</h4><ul class="area-editor-list"><li><ul class="area-editor-list area-editor-depth">';
	for (var key in __alldiv){
		html += '<li area-id="'+key+'"><div class="area-editor-list-title"><div class="area-editor-list-title-content"><div class="js-ladder-toggle area-editor-ladder-toggle extend">+</div><span>'+__alldiv[key][0]+'</span></div></div></li>';
	}
	html += '</ul></li></ul></div></div><button class="tbtn btn-wide area-editor-add-btn js-area-editor-translate">添加</button><div class="area-editor-column area-editor-column-used js-area-editor-used"><div class="area-editor"><h4 class="area-editor-head">已选区域</h4><ul class="area-editor-list"><li><ul class="area-editor-list area-editor-depth"></ul></li></ul></div></div></div></div><div class="area-modal-foot"><button class="tbtn btn-primary btn-wide js-modal-save">确定</button>&nbsp;&nbsp;<button class="tbtn btn-wide js-modal-close">取消</button></div></div></div></div>';
	$('body').append(html);
}