$("#showcomment").on('mouseenter mouseleave','.comment_reply>.comment_vote_show', function(event){
	var _self=$(this),_child=_self.find(".comment_vote");
	if(event.type=="mouseenter"){
		_child.addClass( "show" );
	}else{
		_child.removeClass( "show" );
	}
});
$("#showcomment").on('mouseenter mouseleave','.comment_reply>.comment_content', function(event){
	var _self=$(this),_child=_self.siblings('.comment_vote_show').find(".comment_vote");
	if(event.type=="mouseenter"){
		_child.addClass( "show" );
	}else{
		_child.removeClass( "show" );
	}
});
$("#showcomment").on('mouseenter mouseleave','.comment_reply>.comment_user', function(event){
	var _self=$(this),_child=_self.siblings('.comment_vote_show').find(".comment_vote");
	if(event.type=="mouseenter"){
		_child.addClass( "show" );
	}else{
		_child.removeClass( "show" );
	}
});

function insertEmot(e,forid){
	var left = $(e).offset().left,top = $(e).offset().top;
	var fid = 'emot_inner';
	if($('#'+fid)[0]){
		$('#'+fid).remove();
	}else{
		var tipsEmotid = 'tipsEmot';
		var tipsEmotHTML = '<div class="'+tipsEmotid+'" id="'+tipsEmotid+'"><img id="'+tipsEmotid+'_src" src="/template/pc/main/default/images/emotion/24x24tranparent.gif" /><span class="alt" id="'+tipsEmotid+'_alt"></span/></div>';
		if(!$('#'+tipsEmotid)[0]){$('body').append(tipsEmotHTML);}
		$tipsEmotid = $('#'+tipsEmotid);
		$tipsEmotid_src=$('#'+tipsEmotid+'_src');
		$tipsEmotid_alt=$('#'+tipsEmotid+'_alt');
		
		$('body').on('mouseenter mouseleave','.itemEmot', function(event){
			var _self=$(this),alt='',src='';
			alt=_self.attr('data-alt');
			src=_self.attr('data-src');
			
			if(event.type=="mouseenter"){
				if($(event.target).position().left<80 && $(event.target).position().top<140){
					$tipsEmotid.css({'top':($('#'+fid).offset().top+46)+'px','left':($('#'+fid).offset().left+349)+'px'});
				}else{
					$tipsEmotid.css({'top':($('#'+fid).offset().top+46)+'px','left':($('#'+fid).offset().left+6)+'px'});
				}
				$tipsEmotid_src.attr('src',src);
				$tipsEmotid_alt.html(alt);
				$tipsEmotid.show();
			}else{
				$tipsEmotid.hide();
			}
		});
		
		$(document).mousedown(function(event){
			if(!$('#'+fid+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#' + fid).length === 0) && ($target.parents('#myform').length === 0) && ($target.parents('#myformReplay').length === 0)){
				 hideEmot();
			}
		});
		
	}
	loadEmot(fid,forid,left,top);
	return false;
}
function hideEmot(){
	$('#emot_inner').hide();
}
function loadEmot(fid,forid,left,top){
	var iNode = document.createElement('div');
	iNode.id=fid;
	iNode.className = 'emot_inner replay_life';
	iNode.style.display = 'none';
	var strFace = '<div class="hde"><div class="tit">常用表情</div></div><a href="#" class="close">关闭</a><ul class="clearfix ul_0">';
	var i=0,k=0,len=emotData.length,path='/template/pc/main/default/images/emotion/',imgsrc='',sourceImgsrc='';
	for( ;i<len;i++){
		if(emotData[i]['format']==='gif'){imgsrc=path+'e'+emotData[i].id+'.gif';}else{imgsrc=path+'24x24tranparent.gif'}
		sourceImgsrc = path+'e'+emotData[i].id+'.gif';
		strFace += '<li><a href="#" class="itemEmot" onclick="return insertAtCaret(this,\''+forid+'\',\''+sourceImgsrc+'\');" data-src="'+sourceImgsrc+'" data-alt="'+emotData[i].alt+'"><img src="'+imgsrc+'" alt="'+emotData[i].alt+'" /></a></li>';
		if((i+1) % 120 == 0){k++;strFace += '</ul><ul class="clearfix ul_'+k+'" style="display:none;">';}
	}
	strFace += '</ul>';
	strFace += '<div class="emot_page"><a href="#" class="prev" onclick="return pageEmot(this,\'1\',\''+fid+'\');">上一页</a> <span id="'+fid+'_pageNum">1/1</span> <a href="#" class="next" onclick="return pageEmot(this,\'2\',\''+fid+'\');">下一页</div></div>';
	
	
	setTimeout(function(){
		iNode.innerHTML = strFace;
		$('body').append(iNode);
		
		$('#'+fid).css({'top':(top+25)+'px','left':left+'px','display':'block'});
		$('#'+fid).find('.close').on('click',function(e){e.preventDefault();hideEmot();});
		$('#'+fid).find('.prev').trigger('click');
	},100);
	
}
var EmotPageIndex=0;
function pageEmot(o,action,fid){
	var list = $('#'+fid).find('ul');
	var len = list.length;
	var p_node = $("#"+fid+"_pageNum");
	if(len<2){ return false;}
	$(o).parent().find('.next').removeClass('kill');
	
	EmotPageIndex === 0 && $(o).parent().find('.prev').addClass('kill');
	EmotPageIndex === (len-1) && $(o).parent().find('.next').addClass('kill');
	
	p_node.html('1/'+len);
	if(action === '1'){
		if(EmotPageIndex >0){
			EmotPageIndex--;
			if(EmotPageIndex === 0){$(o).addClass('kill');}
			$(o).parent().find('.next').removeClass('kill');
		}else{
			EmotPageIndex=0;
			return false;
		}
	}else{
		if((len-1)>EmotPageIndex){
			EmotPageIndex++;
			if(EmotPageIndex === len-1){$(o).addClass('kill');}
			$(o).parent().find('.prev').removeClass('kill');
		}else{
			EmotPageIndex=len-1;
			p_node.html(len+'/'+len);
			$(o).addClass('kill');
			return false;
		}
	}
	list.hide();
	p_node.html((EmotPageIndex+1)+'/'+len);
	list.eq(EmotPageIndex).show();
	return false;
}
function insertAtCaret(o,sid,src){
	var target_node = $('#'+sid)[0];
	var text = '<img src="'+src+'" />';
	if (document.selection) {
		target_node.focus();
		var cr = document.selection.createRange();
		//cr.text = text;
		cr.pasteHTML(text);
		cr.collapse();
		cr.select();
	}else if (window.getSelection()) {
		target_node.focus();
		window.document.execCommand('InsertHtml','',text);
		//若不是插入图片形式 以下方式性能更好
		/*var selection = window.getSelection();
		var range = selection.getRangeAt(0);	
		var start = range.startOffset,
			end = range.endOffset;
		target_node.innerHTML=target_node.innerHTML.substr(0,start)+text+target_node.innerHTML.substr(start);*/
	}else{
		target_node.innerHTML(target_node.innerHTML + text);
	}
	hideEmot();
	return false;
}

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
	hideEmot();
	return false;
}
function loadRevertReplay(o,parentid){
	$('#parentid').val(parentid);
	$('#isrep').val('1');
	var sid = 'replay';
	if(!$('#'+sid)[0]){
		var strHTML = '<div class="write2014" id="replay" style="display:none;"><form id="myformReplay"><div class="cmt_txt" id="cmt_txtReplay" contenteditable="true"></div><div class="cmt_control clearfix"><div class="left"><div class="emot po_re"><a href="#" onClick="return insertEmot(this,\'cmt_txtReplay\');" class="emot_btn">插入表情</a></div></div><div class="right">　<button type="submit" id="cmt_btnReplay" class="cmt_btn">提交</button></div><div class="right">文明上网 礼貌发帖　<span id="cmt_tipReplay">0/300</span></div></div></form></div>';
		$('body').append(strHTML);
		$("#myformReplay").chackTextarea(600,"cmt_txtReplay","cmt_tipReplay","cmt_btnReplay",postRevertPage);
		$(document).mousedown(function(event){
			if(!$('#'+sid+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#' + sid).length === 0) && !$target.hasClass('replay_life') && ($target.parents('.replay_life').length === 0)){
				 $('#'+sid).hide();
			}
		});
	}
	$(o).parent().parent().append($('#'+sid).detach());
	$('#'+sid+':hidden').show();
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
/*function postNewRevert(o,node,activeid,styleid,shopid,tplid,chrmark,isrep,istg,parentid,score,score1){
	var btn_submit = $('#cmt_btn');
	btn_submit.prop("disabled", true).addClass("disabled");
	var score_val = score || '0';
	var score1_val = score1 || '0';
	
	var url = nowdomain+'request.ashx?action=saverevert&id='+activeid+'&styleid='+styleid+'&shopid='+shopid+'&tplid='+tplid+'&chrmark='+chrmark+'&isrep='+isrep+'&istg='+istg+'&Parentid='+parentid+'&score='+score_val+'&score1='+score1_val+'&jsoncallback=?';
	
	$.getJSON(url,function(data){
		btn_submit.prop("disabled", false).removeClass("disabled");
		if(data[0].islogin === '1'){
			if(data[0].isopen === '1'){
				successPostRevert(o,node,data[0].MSG);
			}else{
				MSGwindowShow('revert','0','恭喜你，评论成功！请耐心等待系统审核！','','');
				$('#isrep').val('');
				$('#parentid').val('');
				$(o).find('.cmt_txt').empty();
				btn_submit.prop("disabled", false).removeClass("disabled");
			}
		}else{
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}*/
function postNewRevert(o,node,activeid,styleid,shopid,tplid,chrmark,isrep,istg,parentid,score,score1){
	var btn_submit = $('#cmt_btn');
	btn_submit.prop("disabled", true).addClass("disabled");
	var score_val = score || '0';
	var score1_val = score1 || '0';
	
	var url = '/request.ashx?action=saverevert&id='+activeid+'&styleid='+styleid+'&shopid='+shopid+'&tplid='+tplid+'&isrep='+isrep+'&istg='+istg+'&Parentid='+parentid+'&score='+score_val+'&score1='+score1_val;
	$('#chrmarkForm').val(chrmark);
	var options = {
		success: function(data){
			btn_submit.prop("disabled", true).addClass("disabled");
			if(data.islogin === '1'){
				if(data.isopen === '1'){
					successPostRevert(o,node,data.MSG);
				}else{
					MSGwindowShow('revert','0','恭喜你，评论成功！请耐心等待系统审核！','','');
					$('#isrep').val('');
					$('#parentid').val('');
					$(o).find('.cmt_txt').empty();
					btn_submit.prop("disabled", false).removeClass("disabled");
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
	$(o).find('.cmt_txt').empty();
	$(o).find('.cmt_btn').addClass("disabled").attr("disabled", "disabled");
}
function edit_replay(o,sid,isadmin){
	window['$id'] = sid;
	window['$isadmin'] = isadmin;
	var f_id = 'replay_tips';
	var replayHTML = '<div class="replay_tips" id="'+f_id+'" style="display:none"><div class="hd"><a href="#" class="close">关闭</a>查看回复</div><div class="bd" id="'+f_id+'_bd">';
	replayHTML += '</div></div>';
	if(!$('#'+f_id)[0]){
		$('body').append(replayHTML);
		$(document).mousedown(function(event){
			if(!$('#'+f_id+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#' + f_id).length === 0)){
				 $('#'+f_id).hide();
			}
		});
		$('#'+f_id).on('click','.close',function(e){
			e.preventDefault();
			$('#'+f_id).hide();
		});
	}
	var f_node = $('#'+f_id);
	var w_h = $(window).height(),d_h = f_node.height(),s_h = $(document).scrollTop(),top_val = (w_h-d_h)/2+s_h;
	f_node.css({'top':top_val+'px','display':'block'});
	$('#'+f_id+'_bd').empty();
	//载入回复内容
	var url= nowdomain+'request.ashx?action=getrevertrep&id='+window['$id']+'&styleid='+window['STYLEID']+'&isadmin='+window['$isadmin']+'&tplid=1&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	jQuery.getJSON(url,function(data){
		$('#'+f_id+'_bd').append(data[0].MSG);
		d_h = f_node.height(),top_val = (w_h-d_h)/2+s_h;
		f_node.css({'top':top_val+'px'});
		if(window['STYLEID'] === '0' || window['STYLEID'] === '1'){
			$('#write_replay')[0]&&$('#write_replay').show();
			$('#write_btn_1')[0]&&$('#write_btn_1').hide();	
		}
	});
	return false;
}
function post_replay(){
	var txt2 = $('#replay_tips_textarea').val();
	var txt1 = $('#replay_tips_input').html();
	if(txt1 === ''){alert('评论内容不能为空！');return false;}
	var url= nowdomain+'request.ashx?action=saverevert_rep&id='+window['$id']+'&styleid='+window['STYLEID']+'&isadmin='+window['$isadmin']+'&Chrmark='+encodeURIComponent(txt1)+'&replaycontent='+encodeURIComponent(txt2)+'&shopid='+window['SHOPID']+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	jQuery.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			alert('提交成功！');
			window.location.reload();
		}else{
			alert(data[0].error);
		}
	});
	return false;
}
function loadDelQuick(o,sid){
	var tgQuickid='tgQuick';
	if(!$('#'+tgQuickid)[0]){
		var tgQuickHTML = '<div class="tgQuick" id="'+tgQuickid+'"><a href="javascript:delRevert(\'0\');">清空该贴内容</a> <a href="javascript:delRevert(\'2\');">仅删除该帖</a> <a href="javascript:delRevert(\'1\');">删除包括后续回复</a><s class="s"></s></div>';
		$('body').append(tgQuickHTML);
		$(document).mousedown(function(event){
			if(!$('#'+tgQuickid+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#' + tgQuickid).length === 0) && !$target.hasClass('event_lift')){
				$('#'+tgQuickid).hide();
			}
		});
	}
	$('#'+tgQuickid).attr('data-id',sid).css({'left':($(o).offset().left-44)+'px','top':($(o).offset().top+22)+'px'}).show();
	return false;
}
function delRevert(action,sid){
	if( confirm("该操作将不可逆！\n您确定要处理所有选中的信息吗？")){
		var nsid = sid||$('#tgQuick').attr('data-id');
		var url = nowdomain+'request.ashx?action=revert_del&id='+nsid+'&styleid='+window['STYLEID']+'&isadmin=1&isdel='+action+'&jsoncallback=?';
		var Digital=new Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		jQuery.getJSON(url,function(data){
			if(data[0].islogin === '1'){
				alert('操作成功！');
				window.location.reload();
			}else{
				alert(data[0].error);
			}
		});
	}
}
function removeHTMLTag(str) {
	str = str.replace(/<(?!img)\/?[^>]*>/g,'');
	str = str.replace(/[ | ]*\n/g,'\n');
	//str = str.replace(/\n[\s| | ]*\r/g,'\n');
	str=str.replace(/&nbsp;/ig,'');
	return str;
}
$.fn.chackTextarea = function(a,c, e, b, callback) {
    
	$("#" + b).attr("disabled", "disabled");
    $("#" + b).addClass("disabled");
	var t = $(this),
	d = $('#'+c),
	btn = $('#'+b),
	isrep = $('#isrep'),
	istg = $('#istg'),
	score = $('#total_score'),
	score1 = $('#score_1'),
	parentid = $('#parentid');
	t.ajaxForm();//jQuery form
    t.submit(function(e){
		if($('#needLogin').val() === '1'){
			if($('#isLogin').val() === '0'){
				var url = nowdomain+'member/login.html?from='+encodeURIComponent(window.location.href);
				MSGwindowShow('revert','1','对不起，请登录后再发表评论！',url,'');
				return false;
			}
		}
		if($('#isuserpinglun').val() === '0'){
			var url = nowdomain+'member/login.html?from='+encodeURIComponent(window.location.href);
			MSGwindowShow('revert','1','对不起，请登录后再发表评论！',url,'');
			return false;
		}
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
		if(e.target.id === 'myform'){
			parentid.val('');
			isrep.val('');
		}
		if(content.length < a){
			callback.call(this,content,isrep.val(),istg.val(),parentid.val(),score.val(),score1.val());
		}else{
			MSGwindowShow('revert','0','对不起，您提交的评论字符数超出限制了','','');
			d.trigger('click');
		}
		return false;
	});
	
	d[0].onpaste = function(){
		setTimeout(function(){
			d.html(removeHTMLTag(d.html()));
		},100);
	}
	d.on('keyup click',function(){
		var f = d.html().replace(/[^\x00-\xff]/g, "**");
		if (f.length > 0) {
			if (f.length > a) {
				$("#" + e).html("已超出<em>" + parseInt((f.length - a) / 2) + "</em>字");
				$("#" + e).css({
					color: "#f30"
				});
				$("#" + b).attr("disabled", "disabled");
				$("#" + b).addClass("disabled")
			} else {
				$("#" + e).html(parseInt(f.length/2) + '/300');
				$("#" + e).css({
					color: "#404040"
				});
			   $("#" + b).prop("disabled", false);
			   $("#" + b).removeClass("disabled")
			}
		} else {
			$("#" + e).html("0/300");
			$("#" + e).css({
				color: "#404040"
			});
			$("#" + b).attr("disabled", "disabled");
			$("#" + b).addClass("disabled")
		}
    });
    
}