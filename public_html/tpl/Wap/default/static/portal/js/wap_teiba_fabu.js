$.fn.replyTabs = function(node){
	var obj = $(this);
	var currentClass = "current";
	var tabs = obj.find(".tab-hd").find(".item");
	var conts = obj.find(".tab-cont");
	var t;
	tabs.each(function(i){
		$(this).bind("click",function(){
			if($(this).attr('data-isopen') === '0'){
				conts.hide().eq(i).show();
				tabs.removeClass(currentClass).attr('data-isopen','0').eq(i).addClass(currentClass).attr('data-isopen','1');
			}else{
				$(this).attr('data-isopen','0');
				conts.hide();
				tabs.removeClass(currentClass);
			}
		});
	});
}
function insertAtCaret(o,sid,src){
	var target_node = $('#'+sid)[0];
	var text = '<img src="'+src+'" width="75" height="75" />';
	if (document.selection) {
		target_node.focus();
		var cr = document.selection.createRange();
		//cr.text = text;
		cr.pasteHTML(text);
		cr.collapse();
		cr.select();
	}else if (window.getSelection()) {
		target_node.innerHTML=target_node.innerHTML+text;
	}else{
		target_node.innerHTML=target_node.innerHTML + text;
	}
	return false;
}
function loadEmot(forid){
	var iNode = document.createElement('div');
	iNode.className = 'clearfix';
	var strFace = '<ul class="clearfix ul_0">';
	var i=0,k=0,len=emotData.length,path='/template/wap/main/default/images/emotion/',sourceImgsrc='',imgsrc=path+'24x24tranparent.gif';
	for( ;i<len;i++){
		sourceImgsrc = path+'e_'+emotData[i].id+'.png';
		strFace += '<li><a href="#" class="itemEmot" onclick="return insertAtCaret(this,\''+forid+'\',\''+sourceImgsrc+'\');" data-src="'+sourceImgsrc+'" data-alt="'+emotData[i].alt+'"><img src="'+imgsrc+'" alt="" /></a></li>';
	}
	strFace += '</ul>';
	setTimeout(function(){
		iNode.innerHTML = strFace;
		$('#emot_inner').append(iNode);
	},100);
	
}
loadEmot('cmt_txt');