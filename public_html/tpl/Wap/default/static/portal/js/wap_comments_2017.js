$('#openReply').click(function(e){
	e.preventDefault();
	$('#isrep').val('0');
	$('#parentid').val('0');
	$('#replyName').html('发表评论');
	scrollHe.pageOther = $('#pageOther');
	scrollHe.showLayer();
});
$('#closeReply').click(function(e){
	e.preventDefault();
	scrollHe.hideLayer();
});
function getNewRevertPage(pageNo){
	var tpl = window['TPLNUM'] || '1';
	var shop = window['SHOPID'] || '';
	var score = $('#fscore').val() || '';
	getNewRevert($('#showcomment'),window['ACTIVEID'],window['STYLEID'],shop,tpl,pageNo,score);
}
function putRevertPage(o,activeid,action2){
	var tpl = window['TPLNUM'] ||  '1';
	putNewRevert($(o),activeid,window['STYLEID'],'',tpl,action2);
	return false;
}
function postRevertPage(chrmark,isrep,istg,parentid,score,score1){
	var tpl = window['TPLNUM'] ||  '1';
	var shop = window['SHOPID'] || '';
	postNewRevert(this,$('#showcomment'),window['ACTIVEID'],window['STYLEID'],shop,tpl,chrmark,isrep,istg,parentid,score,score1);
	return false;
}
function loadRevertReplay(o,parentid,p_id,userName){
	$('#parentid').val(parentid);
	$('#isrep').val('1');
	$('#replyName').html('回复'+userName);
	scrollHe.showLayer();
	return false;
}

function getNewRevert(node,activeid,styleid,shopid,tplid,pageNo,score){
	var url = nowdomain+'request.ashx?action=getrevert&id='+activeid+'&styleid='+styleid+'&shopid='+shopid+'&tplid='+tplid+'&PageNo='+pageNo+'&score='+score+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	$.getJSON(url,function(data){
		if(data[0].islogin !== '1'){MSGwindowShow('revert','0',data[0].error,'','');return;}
		setTimeout(function(){
			node[0].innerHTML = data[0].MSG;
			var num = $('#total_revert').attr('data-num');
			$('#show_total_revert')[0]&&$('#show_total_revert').html(num);
			$('#show_total_revert1')[0]&&$('#show_total_revert1').html(num);
			$('#show_total_revert2')[0]&&$('#show_total_revert2').html(num);
			if($('#listEmpty')[0]){
				$('#listEmpty').html('抱歉，暂时还没有人发表评论哦~！');
			}
		},10);
	});
}
function putNewRevert(node,activeid,styleid,shopid,tplid,action2){
	var url = nowdomain+'request.ashx?action=setrevert&id='+activeid+'&styleid='+styleid+'&shopid='+shopid+'&tplid='+tplid+'&'+action2+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			node.find('.num').html(parseInt(node.find('.num').html())+1);
		}else{
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}
function postNewRevert(o,node,activeid,styleid,shopid,tplid,chrmark,isrep,istg,parentid,score,score1){
	var score_val = score || '0';
	var score1_val = score1 || '0';
	
	var url = '/request.ashx?action=saverevert&id='+activeid+'&styleid='+styleid+'&shopid='+shopid+'&tplid='+tplid+'&isrep='+isrep+'&istg='+istg+'&Parentid='+parentid+'&score='+score_val+'&score1='+score1_val;
	var options = {
		success: function(data){
			if(data.islogin === '1'){
				if(data.isopen === '1'){
					successPostRevert(o,node,data.MSG);
				}else{
					MSGwindowShow('revert','0','恭喜你，评论成功！请耐心等待系统审核！','','');
					$('#isrep').val('');
					$('#parentid').val('');
					$('#urlhidden').val('');
					$('#cmt_txt').html('');
					$('#xiangce').find('.my_prop_imgitem').remove();
					scrollHe.hideLayer();
				}
			}else{
				MSGwindowShow('revert','0',data.error,'','');
			}
		},
		url: url,
		type: 'post',
		clearForm: true,
		resetForm: true,
		timeout: 60000
	}
	$("#myform").ajaxSubmit(options);
}
function successPostRevert(o,node,str,txt_node,btn_node){
	$('#isrep').val('');
	$('#parentid').val('');
	getNewRevertPage('1');
	$("html,body").animate({scrollTop: node.offset().top},300);
	setTimeout(function(){MSGwindowShow('revert','0','恭喜您！评论发布成功了！','','');},500);
	$('#cmt_txt').html('');
	$('#xiangce').find('.my_prop_imgitem').remove();
	scrollHe.hideLayer();
}
$.fn.chackTextarea = function(a,c, e, b, callback) {
	var t = $(this),
	d = $('#'+c),
	btn = $('#'+b),
	isrep = $('#isrep'),
	istg = $('#istg'),
	score = $('#total_score'),
	score1 = $('#score_1'),
	parentid = $('#parentid'),
	chrmarkForm = $('#chrmarkForm');
    t.submit(function(e){
		if($('#needScore').val() === '1'){
			if(score.val() === ''){
				MSGwindowShow('revert','0','请选择整体评价！','','');
				return false;
			}
			if(score1.val() === ''){
				MSGwindowShow('revert','0','请选择星级评价！','','');
				return false;
			}
		}
		var content = d.html();
		var imgtxt = $('#urlhidden').val();
		if(imgtxt !== ''){
			imgtxt = '<div id="mobile_content_img">'+imgtxt+'</div>';
			content = imgtxt + content;
		}
		if(content.length > 0){
			chrmarkForm.val(content);
			callback.call(this,content,isrep.val(),istg.val(),parentid.val(),score.val(),score1.val());
		}else{
			MSGwindowShow('revert','0','请输入评论内容！','','');
		}
		return false;
	});	
}

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
