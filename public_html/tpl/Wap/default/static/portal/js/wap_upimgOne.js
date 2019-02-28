//使用的频道：贴吧 文章
function handler(event){
	var originPhoto = event.target.files[0];
	window.originFileType = originPhoto.type;
	window.originFileName = originPhoto.name;
	var URL = window.URL || window.webkitURL,originPhotoURL;
	originPhotoURL = URL.createObjectURL(originPhoto);
	$('#image').cropper({autoCropArea:1,built:function(){
		cropAndUpload();
	}}).cropper('replace', originPhotoURL);
}	
function cropAndUpload(){
	var size = {
		width:'900',
		height:''
	}
	var croppedCanvas = $('#image').cropper("getCroppedCanvas",size);  // 生成 canvas 对象
	var croppedCanvasUrl = croppedCanvas.toDataURL(window.originFileType); // Base64
	$('#Base64Filename').val(window.originFileName);
	$('#imgFile').val(croppedCanvasUrl);
	setTimeout(function(){$("#fileForm").trigger('submit');},100);
}
$.fn.uploadImgWap = function(){
	var t = $(this),
		btn = $('#upimgFileBtn');
	btn.click(function(e){
		e.preventDefault();
		t.trigger('click');
	});
}
function upLoad_init(){
	showMyImageHeight();
	reset_moveBtn();
	var options = {
		beforeSubmit: function(){
			$('#pageLoaderNode').show();
		},
		success: function(data){
			$('#pageLoaderNode').hide();
			if(data.error == 1){alert(data.message);return false;}
			uploadsuccess(data.id,data.url)
		},
		url: '/kindeditor/upload_json.ashx',
		type: 'post',
		dataType: 'json',
		clearForm: true,
		resetForm: true,
		timeout: 60000
	}
	$('#fileForm').ajaxForm();
	$("#fileForm").bind('submit',function(){
	   $(this).ajaxSubmit(options);
	   return false;
	});
	$('#Base64File').uploadImgWap();
}
function showMyImageHeight(){
	var list = $('#xiangce').find('.imgview'),heightNum = 0;
	var $divWidth = $(document).width()/4 -12;
	list.css({'height':$divWidth});
}
var pic_obj_arr = {};
function uploadsuccess(sid,surl){
	pic_obj_arr[sid]=surl;
	showMyImage(sid,surl);
}
function showMyImage(sid,surl){
	var txt='',node = jQuery('#xiangce');
	txt='<div class="my_prop_imgitem">'+
		'<div class="imgviewNode"><img src="'+surl+'" data-id="'+sid+'" class="imgview" /></div>'+
		'<a href="javascript:;" onclick="delfile(this,\''+sid+'\')" class="del">删除</a>'+
		'<a href="javascript:;" onclick="return move_PrevNext(this,0);" class="move_prev">前移</a>'+
		'<a href="javascript:;" onclick="return move_PrevNext(this,1);" class="move_next">后移</a>'+
		'</div>';
	node.append(jQuery(txt));
	reset_urlhidden();
	showMyImageHeight();
	reset_moveBtn();
}
function reset_urlhidden(){
	var urlhidden = jQuery("#urlhidden");
	var txt = '';
	var list = $('#xiangce').find('.imgview').not('.upimgFileBtn');
	list.each(function(i,item){
		txt += '<p style="text-align:center;"><img src="'+$(item).attr('src')+'" alt="" /></p>';
	});
	urlhidden.val(txt);
}
function delfile(that,sid){
	delete pic_obj_arr[sid];
	$(that).parent().remove();
	reset_urlhidden();
}
function resetChrcontent(){
	var html_urlhidden = $('#cmt_txt');
	var txt = '';
	var html = html_urlhidden.html();
	
	html_urlhidden.html(html);
	var html_urlhidden_img = $('.mobile_content_img');
	
	html_urlhidden_img.find('img').each(function(i,item){
		pic_obj_arr[$(item).attr('data-id')] = $(item).attr('src');
		showMyImage($(item).attr('data-id'),$(item).attr('src'))
	});
	html_urlhidden_img.remove();
	txt = html_urlhidden[0].innerHTML;
	
	html_urlhidden.html(txt);
}
function reset_moveBtn(){
	var node = jQuery('#xiangce');
	if(node.length<1){return;}
	node.find('.move_next,.move_prev').show();
	node.find('.move_next').css({'left':'41px'});
	node.find('.my_prop_imgitem:last .move_next').hide();
	node.find('.my_prop_imgitem:first .move_prev').hide();
	node.find('.my_prop_imgitem:first .move_next').css({'left':'10px'});
}
function move_PrevNext(o,index){
	var ht = jQuery(o).parent(),ht2 = '';
	if(index === 0){
		ht2 = ht.prev();
		ht.detach().insertBefore(ht2);
	}else{
		ht2 = ht.next();
		ht.detach().insertAfter(ht2);
	}
	reset_moveBtn();
	reset_urlhidden()
	return false;
}