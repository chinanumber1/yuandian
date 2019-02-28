KindEditor.ready(function(K) {
	var content_editor = K.create("#content",{
		width:'702px',
		height:'260px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		autoHeightMode : true,
		afterCreate : function() {
			this.loadPlugin('autoheight');
		},
		items : [
			'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link', 'table'
		],
		emoticonsPath : './static/emoticons/',
		uploadJson : uploadJson,
		cssPath : cssPath
	});
	$('#goods_seckill_open_time').datetimepicker({'timeFormat':'hh:mm', 'dateFormat':'yy-mm-dd'});
	$('#goods_seckill_close_time').datetimepicker({'timeFormat':'hh:mm', 'dateFormat':'yy-mm-dd'});
	/*调整保存按钮的位置*/
	$(".nav-tabs li a").click(function(){
		if($(this).attr("href")=="#imgcontent"){		//店铺图片
			$(".form-submit-btn").css('position','absolute');
			$(".form-submit-btn").css('top','670px');	
		}else{
			$(".form-submit-btn").css('position','static');
		}
	});

	$('form.form-horizontal').submit(function(){
		$(this).find('button[type="submit"]').html('保存中...').prop('disabled',true);
	});
	/*分享图片*/
	$('#image-file').ace_file_input({
		no_file:'gif|png|jpg|jpeg格式',
		btn_choose:'选择',
		btn_change:'重新选择',
		no_icon:'fa fa-upload',
		icon_remove:'',
		droppable:false,
		onchange:null,
		remove:false,
		thumbnail:false
	});
	
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 5){
			alert('最多上传5个图片！');
			return false;
		}
		editor.uploadJson = upload_image;
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
});

function previewimage(input){
	if (input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (e) {$('#image_preview_box').html('<img style="width:120px;height:120px" src="'+e.target.result+'" alt="图片预览" title="图片预览"/>');}
		reader.readAsDataURL(input.files[0]);
	}
}


/*添加选项*/
$(document).ready(function(){
	$(".question_box ul li .list_del").live('click',function(){
		if ($(this).parents("ul").find('li').size() > 1) {
			$(this).parents("li").remove();
		} else {
			$(this).parents(".question_box").remove();
		    if ($('.spec').size() < 1) {
		    	$('.add_table').css('display', 'none');
		    }
		    if ($('.spec').size() < 3) {
		    	$(".add_spec").css('display', 'block');
		    }
		}
	});
	
	$(".properties .bot_add a.btn").live('click',function(){
		var i = $(this).parents('.properties').index();
	    var t = '<li><u>属性的属性值：</u><input type="text" class="txt properties_val" name="properties_val['+i+'][]"/> <a class="list_del" href="javascript:;" title="删除这个选项">×</a></li>';
	    if($(this).parents(".question_box").find("ul li").size() <= 25){
	        $(this).parents(".question_box").find("ul").append(t);
	    }else{
	        alert("您添加的数量够您用一辈子了！");
	    }
	});
	
	$(".add_properties").click(function(){
		var i = $('.properties').size();
	    var t = '<div class="question_box properties"><p class="question_info"><span>属性名称：</span><input type="text" class="txt properties_name" value="" name="properties[]"/><span>可选个数：</span><input type="text" class="txt properties_num" value="1" name="properties_num[]" style="width:50px"/>'
	           +'<input type="hidden" name="properties_id[]" ><a href="javascript:;" class="box_del">删除</a></p>'
	           +'<ul id="1" class="optionul"><li><u>属性的属性值：</u><input type="text" class="txt properties_val" name="properties_val['+i+'][]"/> <a class="list_del" href="javascript:;" title="删除这个选项">×</a></li>'
	           +'</ul><p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">  添加属性的属性值</a></p></div>';
	    $(".add_properties").before(t);
	}); 
	
	$(".spec .bot_add a.btn").live('click',function(){
		var i = $(this).parents('.spec').index();
		var t = '<li><u>规格属性值：</u><input type="hidden" class="hide_txt spec_val_id" name="spec_val_id['+i+'][]"> <input type="text" class="txt spec_val" name="spec_val['+i+'][]"/> <a class="list_del" href="javascript:;" title="删除这个选项">×</a></li>';
	    if($(this).parents(".question_box").find("ul li").size() <= 25){
	        $(this).parents(".question_box").find("ul").append(t);
	    }else{
	        alert("您添加的数量够您用一辈子了！");
	    }
	});
	
	$(".add_spec").click(function(){
		var i = $('.spec').size();
	    var t = '<div class="question_box spec"><p class="question_info"><span>规格名称：</span><input type="text" class="txt spec_name" value="" name="specs[]"/>'
	           +'<input type="hidden" name="spec_id[]" /><a href="javascript:;" class="box_del">删除</a></p>'
	           +'<ul id="1" class="optionul"><li><u>规格属性值：</u><input type="hidden" class="hide_txt spec_val_id" name="spec_val_id['+i+'][]" value=""> <input type="text" class="txt spec_val" name="spec_val['+i+'][]"/> <a class="list_del" href="javascript:;" title="删除这个选项">×</a></li>'
	           +'</ul><p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">  添加规格的属性值</a></p></div>';
	    $(".add_spec").before(t);
	    if ($('.spec').size() > 0) {
	    	$('.add_table').css('display', 'block');
	    }
	    if ($('.spec').size() >= 3) {
	    	$(".add_spec").css('display', 'none');
	    }
	});
	
	$('.add_table').click(function(){
		var header_html = '<tr>';
		formathtml = new Array(), format_value = new Array();
		var flag = false;
		$('.spec_name').each(function(){
			if ($(this).val().length < 1) {
				alert('请输入规格名称');
				flag = true;
				return false;
			}
			header_html += '<th>' + $(this).val() + '</th>';
		});
		header_html += '<th>原价</th><th>现价</th><th>限时价</th><th>库存</th>';
		$('.properties_name').each(function(){
			if ($(this).val().length < 1) {
				alert('请输入属性名称');
				flag = true;
				return false;
			}
			header_html += '<th>' + $(this).val() + '(可选个数)</th>';
		});
		header_html += '</tr>';
	
		var spec_val_arr = new Array();
		var spec_id_arr = new Array();
		$('.spec').each(function(i){
			spec_val_arr[i] = new Array();
			$(this).children('ul').children('li').children('.spec_val').each(function(){
				if ($(this).val().length < 1) {
					alert('请输入规格值的名称');
					flag = true;
					return false;
				}
				spec_val_arr[i].push($(this).val());
			});
			spec_id_arr[i] = new Array();
			$(this).children('ul').children('li').children('.spec_val_id').each(function(l){
				if ($(this).val().length < 1) {
					spec_id_arr[i].push('index_' + l);
				} else {
					spec_id_arr[i].push('id_' + $(this).val());
				}
			});
		});
		if (flag) return false;
		format_id(spec_id_arr, 0, '');
		format_html(spec_val_arr, 0, '');
		for (var e = 0; e < formathtml.length; e++) {
	//			console.log(format_value[e]);
//			console.log(e);
//			console.log(format_value);
			
			header_html += '<tr id="'+format_value[e]+'">' + formathtml[e] + '<td><input type="text" class="txt" name="old_prices[]" value="" style="width:80px;"></td><td><input type="text" class="txt" name="prices[]" value="" style="width:80px;"></td><td><input type="text" class="txt" name="seckill_prices[]" value="" style="width:80px;"></td><td><input type="text" class="txt" name="stock_nums[]" value="" style="width:80px;"></td>';
			$('.properties_name').each(function(t){
				header_html += '<td><input type="text" class="txt" name="num' + t + '[]" value="" style="width:80px;"></td>';
			});
			header_html += '</tr>';
		}
		$('#table_list').html(header_html);
		var values = JSON.parse(window.sessionStorage.getItem(session_index));
//		console.log(values);
		for (var i1 in values) {
			for (var i2 in values[i1]) {
				$('#' + i1 + ' input[name="'+i2+'"]').val(values[i1][i2]);
			}
		}
	});
	$(".box_del").live('click',function(){
	    $(this).parents(".question_box").remove();
	    if ($('.spec').size() < 1) {
	    	$('.add_table').css('display', 'none');
	    }
	    if ($('.spec').size() < 3) {
	    	$(".add_spec").css('display', 'block');
	    }
	});
	
	var values = {};
	$("#table_list input").live('input', function(e){
		if (values[$(this).parents('tr').attr('id')] == undefined) {
			values[$(this).parents('tr').attr('id')] = {};
		}
		values[$(this).parents('tr').attr('id')][$(this).attr('name')] = $(this).val();
		window.sessionStorage.setItem(session_index, JSON.stringify(values));
	});
});

function format_id(a, i, str)
{
	if (i == 0) {
		var ii = i + 1;
		for (var index = 0; index < a[i].length; index++) {
			if (ii == a.length) {
				format_value.push(str + a[i][index]);
			} else {
				format_id(a, ii, a[i][index]);
			}
		}
	} else if (i == a.length - 1) {
		for (var index = 0; index < a[i].length; index++) {
			console.log(str + '_' + a[i][index]);
			format_value.push(str + '_' + a[i][index]);
		}
		return false;
	} else {
		var ii = i + 1;
		for (var index = 0; index < a[i].length; index++) {
			format_id(a, ii, str + '_' + a[i][index]);
		}
	}
}


function format_html(a, i, str)
{
	if (i == 0) {
		var ii = i + 1;
		for (var index = 0; index < a[i].length; index++) {
			if (ii == a.length) {
				formathtml.push(str + '<td>' + a[i][index] + '</td>');
			} else {
				format_html(a, ii, '<td>' + a[i][index] + '</td>');
			}
		}
	} else if (i == a.length - 1) {
		for (var index = 0; index < a[i].length; index++) {
			formathtml.push(str + '<td>' + a[i][index] + '</td>');
		}
		return false;
	} else {
		var ii = i + 1;
		for (var index = 0; index < a[i].length; index++) {
			format_html(a, ii, str + '<td>' + a[i][index] + '</td>');
		}
	}
}
/*添加题目和删除题目结束*/