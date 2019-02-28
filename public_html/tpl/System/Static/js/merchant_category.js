//显示分类
function show_category(){
	$.post(choose_cat_fid,function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="cat_fid" name="cat_fid" class="col-sm-1" style="margin-right:10px;">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.cat_id+'" '+(item.cat_id==$('#choose_category').attr('cat_fid') ? 'selected="selected"' : '')+'>'+item.cat_name+'</option>';
			});
			area_dom+= '</select>';
			$('#choose_category').prepend(area_dom);
			show_category_two($('#cat_fid').find('option:selected').attr('value'),$('#cat_fid').find('option:selected').html(),1);
			$('#cat_fid').change(function(){
				show_category_two($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
//		}else if(result.error == 2){
//			var area_dom = '<select id="choose_province_hide" name="province_id" style="display:none;">';
//			area_dom += '<option value="'+result.id+'">'+result.name+'</option>';
//			area_dom += '</select>';
//			$('#choose_cityarea').prepend(area_dom);
//			show_city(result.id,result.name,0);
		}else{
			window.top.msg(0,result.info,true);
			window.top.closeiframe();
		}
	});
}
//显示子分类
function show_category_two(cat_id,cat_name,type){
	$.post(choose_cat_id,{cat_id:cat_id,cat_name:cat_name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="cat_id" name="cat_id" class="col-sm-1" style="margin-right:10px;">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.cat_id+'" '+(item.cat_id==$('#choose_category').attr('cat_id') ? 'selected="selected"' : '')+'>'+item.cat_name+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('cat_id')){
				$('#cat_id').replaceWith(area_dom);
			}else if(document.getElementById('cat_fid')){
				$('#cat_fid').after(area_dom);
			}else{
				$('#choose_category').prepend(area_dom);
			}
			//show_area($('#choose_city').find('option:selected').attr('value'),$('#choose_city').find('option:selected').html(),1);
			//$('#cat_id').change(function(){
//				show_area($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
//			});
//		}else if(result.error == 2){
//			var area_dom = '<select id="choose_city_hide" name="city_id" style="display:none;">';
//			area_dom += '<option value="'+result.id+'">'+result.name+'</option>';
//			area_dom += '</select>';
//			$('#choose_cityarea').prepend(area_dom);
//			show_area(result.id,result.name,0);
		}else{
			window.top.msg(0,result.info,true,5);
			window.top.closeiframe();
		}
	});
}

$(function(){
	//检测是否需要显示城市
	if(document.getElementById('choose_category')){
		show_category();
	}
});