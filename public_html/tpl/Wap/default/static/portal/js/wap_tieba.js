function getUserState(){
	if(!$.isEmptyObject(userDate)){
		$('#footLogin0').hide();
		var hl= '<div class="user_head"><img src="'+userDate.chrpic+'" /></div>'+
				'<span class="uName"><a href="'+nowdomain+'member/modify.aspx">'+userDate.name+'</a></span>'+
				'<span class="grade i_'+userDate.djid+'">'+userDate.djid+'</span>'+
				'<div class="fr">帖子：'+userDate.tiebanum+'&nbsp;&nbsp; 精华：'+userDate.tiebajinghuanum+'</div>';
		$('#userInfo')[0]&&$('#userInfo').html(hl);
		$('#footLogin1').show();
		$('#isfabukill').val(userDate.isfabukill);
		$('#qiandao').addClass('isqiandao'+userDate.qdday);
		if(userDate.qdday === 1){$('#qiandao').html('已签')}
		if(userDate.isadmin === '1'){
			$(document.getElementsByTagName('body')[0]).addClass('showManage');
		}else{
			if(userDate.bankuailist.length>0){
				if($.inArray(parseInt(window['Bigcategory']),userDate.bankuailist) !== -1){
					$(document.getElementsByTagName('body')[0]).addClass('showManage');
				}else{
					$(document.getElementsByTagName('body')[0]).removeClass('showManage');
					if(typeof contrastUser !== 'undefined'){
						contrastUser();
					}
				}
			}else{
				if(typeof contrastUser !== 'undefined'){
					contrastUser();
				}
			}
			
		}
		
	}
}
window['isQiandaoed']=false;
function showQD(node){
	if($.isEmptyObject(userDate)){
		MSGwindowShow('qiandao','1','请登录后再进行签到！',nowdomain+'member/login.html?from='+encodeURIComponent(window.location.href),'');
		return false;
	}
	if(userDate.qdday === 1 || window['isQiandaoed']===true){
		MSGwindowShow('qiandao','0','今天已经签过了，明日再来！','','');
		return false;
	}
	var url = nowdomain+'tieba/tieba_ajax.ashx?action=qiandao&jsoncallback=?';
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			window['isQiandaoed'] = true;
			$('#qiandao').removeClass('isqiandao0').addClass('isqiandao1').html('已签');
			var txt = '+'+data[0].jifennum.toString()+window['jifenneme'];
			animateQD(txt);
		}else{
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}
function animateQD(txt){
	var qdSuccess = $('#qdSuccess');
	qdSuccess.html(txt).show().animate({'top':'-100px','opacity':'0'},2000,function(){qdSuccess.hide();});
}
/////////////////////贴吧页面分页控件//////////////////////
window['pageLoadedSuccess'] = true;
function getPagingGlobal(obj,node,ids,isFirstPage){
	if(!window['pageLoadedSuccess']){ return false;}
	window['pageLoadedSuccess'] = false;
	keyvalues = $.extend({'p':'1'},keyvalues,obj);
	var current_host = window.location;
	var url_obj = $.url(current_host).param();
	var iPage = keyvalues['p'];
	if(url_obj['page'] !== '' && typeof url_obj['page'] !== 'undefined'){
		iPage = parseInt(url_obj['page']);
	}
	
	if(typeof isFirstPage == 'undefined'){
		keyvalues['p'] = iPage; //刷新或返回 当前页
	}else if(isFirstPage == '2'){
		keyvalues['p'] = '1'; //真正的第一页
	}else if(isFirstPage == '0'){
		keyvalues['p'] = parseInt(iPage)+1; //正常至底部加载下一页
	}else{}
	get_data_paging(callback);
	function callback(data){
		if(data[0].islogin !== '1'){MSGwindowShow('revert','0',data[0].error,'','');return false;}
		
		function showPaging(){
			window['pageLoadedSuccess'] = true;
			$('#pagingList').append(data[0].MSG);
			$('#pageNavigation').html(data[0].PageSplit);
			if(typeof window['islazyImg'] !== 'undefined'){setTimeout(function(){lazyImg('#pagingList .n_img',window['isIscroll5']);},50);}
			if(typeof window['showListImg'] !== 'undefined'){setTimeout(function(){showListImg('#pagingList .n_img');},50);}
			$('#resizeIMG').find('iframe').each(function(){
				$(this).attr({'width':'100%','height':parseInt(($(window).width()-30)/4*3)});
			});
		}
		if(!!window['ifTieziDetail']){//新的帖子详情页处理
			window['ifTieziDetailLoadding'] = false;
			$('#pullUp').hide();
			$('#pageLoader').hide();
			showPaging();
			if(keyvalues["p"] == data[0].PageCount || data[0].PageCount == '0'){
				window['ifNoMore'] = true;
				lis = document.createElement('li');
				lis.innerText = '没有更多了';
				lis.className = 'noMore';
				lis.id = 'noMore';
				$('#pagingList').append(lis);
			}
			history.pushState(null, '', '?page='+keyvalues['p']);
			return false;
		}
		if(typeof window['isIscroll5'] === 'undefined'){
			$('#pagingList').empty();
			setTimeout(function(){showPaging();},50);
			return false;
		}
		
		$('#pullUp').hide();
		$('#pageLoader').hide();
		if(keyvalues["p"] === '1'){
			window['ifNoMore'] = false;
			$('#pagingList').empty();
			$('#pullDown').find('.loader').hide();
			$('#pullDown').hide();
			$('#reload').find('.txt').html('下拉可以刷新');
			$('#reload').find('.s').removeClass('s_ok');
			setTimer();	
		}
		
		if(keyvalues['p'] > data[0].PageCount){
			return false;
		}
		showPaging();
		if(keyvalues["p"] == data[0].PageCount || data[0].PageCount == '0'){
			window['ifNoMore'] = true;
			lis = document.createElement('li');
			lis.innerText = '没有更多了';
			lis.className = 'noMore';
			lis.id = 'noMore';
			$('#pagingList').append(lis);
		}
		if($('#resizeIMG img').length >0){//页面中如果有图片，那么待最后一张图片加载完成后再刷新iscroll5的滚动组件
			var imglist = $('#resizeIMG img'),len = imglist.length,k=0;
			$('#resizeIMG img').imagesLoaded(function(){
				k++;
				if(k === (len-1)){
					setTimeout(function () {
						myScroll.refresh();
					}, 50);
				}
			});
		}
		$('.n_img').picConsole($('#container'));
		
		history.pushState(null, '', '?page='+keyvalues['p']);
		setTimeout(function () {
			myScroll&&myScroll.refresh();
			window['pageLoadedSuccess'] = true;
			if($.cookie('myTBsid') !== undefined){
				$('#item'+$.cookie('myTBsid'))[0] && myScroll.scrollToElement( $('#item'+$.cookie('myTBsid'))[0], 500,0,-150,'');
			}
			$.removeCookie('myTBsid',{ path:'/'});
		}, 50);
		
		
		return false;
	}
	
	if(typeof node !== 'undefined'){
		var target = $('#'+ids+$(node).attr('data-catid'));
		target.siblings('.cur').removeClass('cur');
		target.addClass('cur');
	}
	return false;
}
/////////////////贴吧回复操作/////////////////
$("#pagingList").on('mouseenter mouseleave','.comment_reply>.comment_vote_show', function(event){
	var _self=$(this),_child=_self.find(".comment_vote");
	if(event.type=="mouseenter"){
		_child.addClass( "show" );
	}else{
		_child.removeClass( "show" );
	}
});
$("#pagingList").on('mouseenter mouseleave','.comment_reply>.comment_content', function(event){
	var _self=$(this),_child=_self.siblings('.comment_vote_show').find(".comment_vote");
	if(event.type=="mouseenter"){
		_child.addClass( "show" );
	}else{
		_child.removeClass( "show" );
	}
});
$("#pagingList").on('mouseenter mouseleave','.comment_reply>.comment_user', function(event){
	var _self=$(this),_child=_self.siblings('.comment_vote_show').find(".comment_vote");
	if(event.type=="mouseenter"){
		_child.addClass( "show" );
	}else{
		_child.removeClass( "show" );
	}
});
function putRevertPage(o,activeid,action2){
	putNewRevert($(o),activeid,action2);
	return false;
}
function postRevertPage(chrmark,isrep,parentid){
	postNewRevert(this,$('#pagingList'),window['ACTIVEID'],chrmark,isrep,parentid);
	return false;
}
function putNewRevert(node,activeid,action2){
	var url = nowdomain+'tieba/tieba_ajax.ashx?action=setrevert&id='+activeid+'&'+action2+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			node.find('.zan').html(parseInt(node.find('.zan').html())+1);
		}else{
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}
function postNewRevert(o,node,activeid,chrmark,isrep,parentid){
	var url = nowdomain+'tieba/tieba_ajax.ashx?action=saverevert&tplpath='+keyvalues.tplpath+'&tplname='+keyvalues.tplname+'&id='+activeid+'&chrmark='+chrmark+'&isrep='+isrep+'&Parentid='+parentid+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	console.info('000');
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			
			if(data[0].isopen === '0'){
				
				$('#isrep').val('');
				$('#parentid').val('');
				//ue.setContent('');
				$('#closeReply').trigger('click');
				$('#chrcontent').val('');
				MSGwindowShow('revert','0','恭喜你，回复成功！请耐心等待系统审核！','','');
			}else{
				
				successPostRevert(o,node,data[0]);
			}
		}else{
			
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}
function successPostRevert(o,node,str,txt_node,btn_node){
	$('#isrep').val('');
	$('#parentid').val('');
	$('#chrcontent').html('');
	$('#pagingList').append(str.MSG);
	$('#listEmpty')[0]&&$('#listEmpty').remove();
	setTimeout(function(){MSGwindowShow('revert','0','恭喜您！回复成功！','','');},100);
}
function loadRevertReplay(o,parentid,userName){
	$('#parentid').val(parentid);
	$('#isrep').val('1');
	$('#replyName').html(userName);
	goWrite();
	/*var sid = 'replay';
	if($('#'+sid).attr('data-isevent') === '0'){
		$(document).mousedown(function(event){
			if(!$('#'+sid+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#' + sid).length === 0) && !$target.hasClass('replay_life') && ($target.parents('.replay_life').length === 0)){
				$('#'+sid).hide();
			}
		});
	}
	$(o).parent().parent().append($('#'+sid).detach());
	$('#'+sid+':hidden').show();*/
	
	return false;
}
function edit_replay(o,sid){
	window['$id'] = sid;
	var f_id = 'replay_tips';
	var replayHTML = '<div class="replay_tips" id="'+f_id+'"><div class="hpbd" id="'+f_id+'_bd">';
	replayHTML += '<div class="comment_source"><div class="comment_user"><span class="userName">nihao</span> 的原帖</div><div class="input" id="replay_tips_input" contenteditable="true">'+$(o).parents('.post_item').find('.replaycontent1').html()+'</div></div><div class="write_2014 display'+window['STYLEID'] +'" id="write_replay"><div class="timer">回复：</div><textarea id="replay_tips_textarea" class="textarea">'+$(o).parents('.post_item').find('.replaycontent2').html()+'</textarea></div><button class="comn-submit btn_block fabu_btn" onclick="return post_replay();" type="button">确认提交</button>';
	replayHTML += '</div></div>';
	showNewPage('编辑或回评',replayHTML,function(){})
	return false;
}
function post_replay(){
	var txt2 = $('#replay_tips_textarea').val();
	var txt1 = $('#replay_tips_input').html();
	if(txt1 === ''){MSGwindowShow('tieba','0','评论内容不能为空！','','');return false;}
	var url= nowdomain+'tieba/tieba_ajax.ashx?action=saverevert_rep&id='+window['$id']+'&Chrmark='+encodeURIComponent(txt1)+'&replaycontent='+encodeURIComponent(txt2)+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	jQuery.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			MSGwindowShow('tieba','0','提交成功！','','');
			$('#windowIframe').find('.close').trigger('click');
			getPagingGlobal({'p':keyvalues.p});
		}else{
			alert(data[0].error);
		}
	});
	return false;
}
function loadDelQuick(o,sid){
	var tgQuickid='tgQuick';
	if(!$('#'+tgQuickid)[0]){
		var tgQuickHTML = '<div class="tgQuick" id="'+tgQuickid+'"><s class="s"></s><a href="javascript:delRevert(\'0\');">清空该帖内容</a> <a href="javascript:delRevert(\'2\');">仅删除该帖</a> <a href="javascript:delRevert(\'1\');">删除包括后续回复</a></div>';
		$('body').append(tgQuickHTML);
		$(document).mousedown(function(event){
			if(!$('#'+tgQuickid+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#' + tgQuickid).length === 0) && !$target.hasClass('event_lift')){
				$('#'+tgQuickid).hide();
			}
		});
	}
	$('#'+tgQuickid).attr('data-id',sid).css({'left':($(o).offset().left-24)+'px','top':($(o).offset().top+22)+'px'}).show();
	return false;
}
function delRevert(action,sid){
	if( confirm("该操作将不可逆！\n您确定要处理所有选中的信息吗？")){
		var nsid = sid||$('#tgQuick').attr('data-id');
		var url = nowdomain+'tieba/tieba_ajax.ashx?action=revert_del&id='+nsid+'&isdel='+action+'&jsoncallback=?';
		var Digital=new Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		jQuery.getJSON(url,function(data){
			if(data[0].islogin === '1'){
				MSGwindowShow('tieba','0','操作成功！','','');
				getPagingGlobal({'p':keyvalues.p});
			}else{
				alert(data[0].error);
			}
		});
	}
}
function delAllRevert(action){
	var sid = '',sidArr = [];
	
	jQuery('input[name="ID"]:checked').each(function(){
		sidArr.push(jQuery(this).attr('value'));
	});
	sid = sidArr.join(',');
	if(sid===''){MSGwindowShow('tieba','0','您还没有选择任何回帖！','','');return false;}
	delRevert(action,sid);
	return false;
}
function killRevert(sid,action){
	if( confirm("该操作将不可逆！\n您确定要审批所有选中的信息吗？")){
		var url = '../tieba/tieba_ajax.ashx?jsoncallback=?&action=revert_isopen&id='+sid+'&isopen='+action;
		var Digital=new Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		jQuery.getJSON(url,function(data){
			if(data[0].islogin === '1'){
				MSGwindowShow('tieba','0','操作成功！','','');
				getPagingGlobal({'p':keyvalues.p});
			}else{
				alert(data[0].error);
			}
		});
	}
}
function iskillAllRevert(action){
	var sid = '',sidArr = [];
	jQuery('input[name="ID"]:checked').each(function(){
		sidArr.push(jQuery(this).val());
	});
	sid = sidArr.join(',');
	if(sid===''){MSGwindowShow('tieba','0','您还没有选择任何回帖！','','');return false;}
	killRevert(sid,action);
	return false;
}


$.fn.chackTextarea = function(ue,node2,callback) {
	var t = $(this),
	isrep = $('#isrep'),
	parentid = $('#parentid');
    t.submit(function(e){
		var content = ue.html();
		var imgtxt = $('#urlhidden').val();
		var videotxt = $('#cmt_video_txt').val();
		if(imgtxt !== ''){
			imgtxt = '<div id="mobile_content_img">'+imgtxt+'</div>';
			content = imgtxt + content;
		}
		if(!!$('#cmt_video_txt')[0]){
			content = videotxt + content;
		}
		if(content === ''){
			MSGwindowShow('tieba','4','请输入回复内容！','','');
			return false;
		}
		if(e.target.id === 'myform'){
			parentid.val('');
			isrep.val('');
		}
		node2.val(content);
		return true;
	}); 
}
function myReplaySubmit(tform){
	
	isrep = $('#isrep'),
	parentid = $('#parentid');
	var content = $('#chrcontent2').val();
	if(content === ''){
		MSGwindowShow('tieba','4','请输入回复内容！','','');
		return false;
	}
	var imgtxt = $('#urlhidden').val();
	if(imgtxt !== ''){
		imgtxt = '<div id="mobile_content_img">'+imgtxt+'</div>';
		content = imgtxt + content;
	}
	$('#chrmark2').val(content);
	tform.form.submit();
		
}
///////////////////////////////////贴吧帖子操作////////////////////////////////////
$.managerTBiskill = function(sid,val){
	var url = nowdomain+'tieba/tieba_ajax.ashx?jsoncallback=?&action=iskill&id='+sid+'&iskill='+val;
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			MSGwindowShow('tieba','0','操作成功！','','');
			getPagingGlobal({'p':keyvalues.p});
		}
	});
}
$.managerTBisdel = function(sid,isBigCat){
	if(confirm("该操作将不可逆，您确定要删除吗？")){
		var url = nowdomain+'tieba/tieba_ajax.ashx?jsoncallback=?&action=tiebadel&id='+sid;
		$.getJSON(url,function(data){
			if(data[0].islogin === '1'){
				if(typeof isBigCat !== 'undefined'){
					MSGwindowShow('tieba','1','删除成功！','/tieba_a'+isBigCat+'_b0_c0_d0_e0_f0_g0_h0_i0_p1.html','');
				}else{
					MSGwindowShow('tieba','0','删除成功！','','');
					getPagingGlobal({'p':keyvalues.p});
				}
			}
			
		});
    }
}
$.managerTBall = function(action){
	var sid = '',sidArr = [];
	jQuery('input[name="ID"]:checked').each(function(){
		sidArr.push(jQuery(this).val());
	});
	sid = sidArr.join(',');
	if(sid===''){MSGwindowShow('tieba','0','您还没有选择任何帖子！','','');return false;}
	if(typeof action === 'undefined'){
		$.managerTBisdel(sid);
	}else{
		$.managerTBiskill(sid,action);
	}
	return false;
}
window['hasSuperManag'] = false;
window['hasSuperManagHtml'] = '';
function superManage(sid){
	
	if(!window['hasSuperManag']){
		window['hasSuperManag'] = true;
		window['hasSuperManagHtml'] = '<div class="replay_tips" id="super_tips">'+
		'<div class="bd">'+
		'<form method="post" onSubmit="return putSuperManage(this)">'+
		'<input type="hidden" id="super_id" value="" /><input type="hidden" id="super_color" /><input type="hidden" name="isbold" />'+
		'<div class="list">'+
		'<span class="sp_a">标题：</span>'+
		'<span class="sp_b name" id="super_title"></span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a" style="padding-top:7px;">版块及主题：</span>'+
		'<span class="sp_b catNode" id="super_cat"><label id="cat_1"></label><label id="cat_2"></label></span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a">浏览数：</span>'+
		'<span class="sp_b"><input type="text" class="t_ipt" id="super_hits" value="" /></span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a">审核状态：</span>'+
		'<span class="sp_b"><input type="radio" name="iskill" id="super_iskill_0" value="0" /> 否　<input type="radio" name="iskill" id="super_iskill_1" value="1" /> 是</span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a">允许回复：</span>'+
		'<span class="sp_b"><input type="radio" name="isrevert" id="super_isrevert_0" value="0" /> 否　<input type="radio" name="isrevert" id="super_isrevert_1" value="1" /> 是</span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a">是否精华：</span>'+
		'<span class="sp_b"><input type="radio" name="isjinghua" id="super_isjinghua_0" value="0" /> 否　<input type="radio" id="super_isjinghua_1" name="isjinghua" value="1" /> 是</span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a" style="padding-top:7px;">是否置顶：</span>'+
		
		'<span class="sp_b catNode"><label><select name="iszhiding" id="iszhiding"><option value="">不置顶</option><option value="1">置顶权重一</option><option value="2">置顶权重二</option><option value="3">置顶权重三</option><option value="2">置顶权重四</option><option value="5">置顶权重五</option></select></label></span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a">首页推荐：</span>'+
		'<span class="sp_b"><input type="radio" name="isindex" id="super_isindex_0" value="0" /> 否　<input type="radio" id="super_isindex_1" name="isindex" value="1" /> 是</span>'+
		'</div>'+
		'<div class="list">'+
		'<span class="sp_a">推荐排序值：</span>'+
		'<span class="sp_b"><input type="text" class="t_ipt" id="super_intorder" value="" /></span>'+
		'</div>'+
		'<div class="list" style="display:none;">'+
		'<span class="sp_a">标题加粗：</span>'+
		'<span class="sp_b"><input type="radio" name="isbold" id="super_isbold_0" value="0" /> 否　<input type="radio" id="super_isbold_1" name="isbold" value="1" /> 是</span>'+
		'</div>'+
		'<div style="margin:10px;"><input class="comn-submit btn_block fabu_btn" type="submit" value="确认提交"></div>'+
		'</form></div></div>';
	}
	showNewPage('超级管理',window['hasSuperManagHtml'],function(){});
	var f_node = $('#super_tips');
	var url = nowdomain+'tieba/tieba_ajax.ashx?jsoncallback=?&action=tiebaedit&id='+sid;
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			$('#super_title').html(data[0].MSG.title);
			$('#cat_1').html(data[0].MSG.bigcategoryidsel_1);
			$('#cat_2').html(data[0].MSG.categoryidsel_1);
			$('#super_id').val(data[0].MSG.id);
			$('#super_num2').val(data[0].MSG.num2);
			$('#super_revertnum').val(data[0].MSG.revertnum);
			$('#super_hits').val(data[0].MSG.hits);
			$('#super_color').val(data[0].MSG.color);
			$('#super_intorder').val(data[0].MSG.intorder);
			$('#super_iskill_'+data[0].MSG.iskill).prop('checked',true);
			$('#super_isrevert_'+data[0].MSG.isrevert).prop('checked',true);
			$('#super_isjinghua_'+data[0].MSG.isjinghua).prop('checked',true);
			//$('#super_iszhiding_'+data[0].MSG.iszhiding).prop('checked',true);
			$('#iszhiding').val(data[0].MSG.iszhiding)
			$('#super_isindex_'+data[0].MSG.isindex).prop('checked',true);
			$('#super_isbold_'+data[0].MSG.isbold).prop('checked',true);
			$('#bigcategoryid_1').change(function(){categoryData_set("1","bigcategoryid_1",0,"categoryid_1",'');});
		}
	});
}
function putSuperManage(form){
	var url = nowdomain+'tieba/tieba_ajax.ashx?jsoncallback=?&action=tiebaeditsave';
	var iskill = $(form).find('input[name=iskill]:checked').val(),
		isrevert = $(form).find('input[name=isrevert]:checked').val(),
		isjinghua = $(form).find('input[name=isjinghua]:checked').val(),
		iszhiding = $('#iszhiding').val(),
		isindex = $(form).find('input[name=isindex]:checked').val(),
		isbold = $(form).find('input[name=isbold]:checked').val(),
		intorder = $('#super_intorder').val(),
		hits = $('#super_hits').val(),
		color = $('#super_color').val(),
		sid = $('#super_id').val(),
		bigcategoryid = $('#bigcategoryid_1').val(),
		categoryid = $('#categoryid_1').val();
		
	url = url+'&iskill='+iskill+'&isrevert='+isrevert+'&isjinghua='+isjinghua+'&iszhiding='+iszhiding+'&isindex='+isindex+'&hits='+hits+'&id='+sid+'&intorder='+intorder+'&color='+encodeURIComponent(color)+'&bigcategoryid='+bigcategoryid+'&categoryid='+categoryid+'&isbold='+isbold;
	
	$.getJSON(url,function(data){
		if(data[0].islogin==='1'){
			MSGwindowShow('tieba','0','编辑成功！','','');
			$('#windowIframe').find('.close').trigger('click');
			getPagingGlobal({'p':keyvalues.p});
		}
	});
	return false;
}
/////////////////固定边角控件///////////////////
$.rmenuShow = function(){
	var rtop = $("#top");
	$('#myFabu,#myFabu_1').click(function(e){
		e.preventDefault();
		$("html,body").animate({scrollTop:$('#fabuForm').offset().top},300);
	});
	rtop.click(function(e){
		e.preventDefault();
		$("html,body").animate({scrollTop: 0},300);
	});
	$(window).bind("scroll",function(){
		var d = $(document).scrollTop();
		0 < d ? rtop.show() : rtop.hide();
	});
}
//////////////////列表图册控件///////////////////
function showListImg(node){
	var i_height=0,i_width=0;
	i_width = parseInt(($(window).width()-40)/3);
	//console.info($(window).width())
	i_height = parseInt(i_width/4*3);
	$(node).filter('[data-ischeck!="1"]').each(function(){
		$(this).attr('data-ischeck','1');
		if($(this).find('img').length === 1){
			$(this).parent().parent().addClass('onlyone');
		}else{
			
			$(this).find('img').css({'width':i_width+'px','height':i_height+'px'})
		}
	});
}
function lazyImg(selector,isIscroll5){
	var w_h = $(window).height();
	
	$(selector).find('img').each(function(){
		if(!!isIscroll5){
			if($(this).attr("data-ifshow") === '0' && ($(this).offset().top - w_h)<0 ){
				$(this).attr({'src':$(this).attr('data-src'),"data-ifshow":'1'})
			}
		}else{
			if($(this).attr("data-ifshow") === '0' && ($(document).scrollTop()+$(window).height()) > $(this).offset().top){
				$(this).attr({'src':$(this).attr('data-src'),"data-ifshow":'1'})
			}
		}
	});
}
$.fn.myAlbum = function(){
	var albumData = [],t_item=null,len=0,
		media_box=$('#media_box'),
		prev = media_box.find('.bigpic_display_pre'),
		next = media_box.find('.bigpic_display_next'),
		large_pic = media_box.find('.j_large_pic'),
		ypic = media_box.find('.tb_icon_ypic');
	
	$('#pagingList').on('click','#media_box',function(e){
		var $target = $(event.target);
		if($target.hasClass('canvas')){
			hideImg();
		}
	});
	$('#pagingList').on('click','.itemAlbum',function(e){
		e.preventDefault();
		
		albumData = [];
		setImg($(this).attr('original'),$(this).index());
		var list = $(this).parent().find('.itemAlbum');
		$('.n_img').show();
		showImg($(this).parent());
		len = list.length;
		if(len-1>$(this).index()){next.show();}else{next.hide();}
		if(0<$(this).index()){prev.show()}else{prev.hide();}
		list.each(function(){
			albumData.push($(this).attr('original'));
		});
	});
	$('#pagingList').on('click','.j_large_pic,.p_putup',function(e){
		e.preventDefault();
		hideImg();
	});
	$('#pagingList').on('click','.tb_icon_turnleft',function(e){
		e.preventDefault();
		large_pic.rotate('left');
	});
	$('#pagingList').on('click','.tb_icon_turnright',function(e){
		e.preventDefault();
		large_pic.rotate('right');
	});
	$('#pagingList').on('click','.bigpic_display_pre',function(e){
		e.preventDefault();
		var cur = parseInt(large_pic.attr('cur'))-1;
		if(cur === 0){prev.hide();}
		next.show();
		setImg(albumData[cur],cur);
		media_box.find('.canvas').hide();
	});
	$('#pagingList').on('click','.bigpic_display_next',function(e){
		e.preventDefault();
		var cur = parseInt(large_pic.attr('cur'))+1;
		if(cur === len-1){next.hide();}
		prev.show();
		setImg(albumData[cur],cur);
		resetImg();
	});
	
	function setImg(src,index){
		large_pic.attr({'src':src,'cur':index});
		ypic.attr('href',src);
		large_pic.imagesLoaded(function(){
			autoResizeIMG('600','2000',large_pic[0]);
		});
		resetImg();
	}
	function showImg(node){
		node.after(media_box.detach());
		media_box.show();
		t_item = node;
		node.hide();
	}
	function hideImg(){
		media_box.hide();
		t_item.show();
		large_pic.attr({'src':'about:blank','cur':'0'});
	}
	function resetImg(){
		media_box.find('.canvas').hide();
		large_pic.css({'visibility':'visible','position':'static','width':'auto','height':'auto'}).attr({'width':'','height':'','step':'0'});
	}
}
$.fn.rotate = function(p){
	var img = $(this)[0],
		n = img.getAttribute('step');

	// 保存图片大小数据
	if (!this.data('width') && !$(this).data('height')) {
		this.data('width', img.width);
		this.data('height', img.height);
	};
	if(n == null) n = 0;
	if(p == 'left'){
		(n == 3) ? n = 0 : n++;
	}else if(p == 'right'){
		(n == 0)? n = 3 : n--;
	};
	img.setAttribute('step', n);

	// IE浏览器使用滤镜旋转
	if(document.all) {
		img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ n +')';
		// IE8高度设置
		if ($.browser.version == 8) {
			switch(n){
				case 0:
					this.parent().height('');
					//this.height(this.data('height'));
					break;
				case 1:
					this.parent().height(this.data('width') + 10);
					//this.height(this.data('width'));
					break;
				case 2:
					this.parent().height('');
					//this.height(this.data('height'));
					break;
				case 3:
					this.parent().height(this.data('width') + 10);
					//this.height(this.data('width'));
					break;
			};
		};
	// 对现代浏览器写入HTML5的元素进行旋转： canvas
	}else{
		var c = this.next('canvas')[0];
		
		
		if(this.next('canvas').length == 0){
			c = document.createElement('canvas');
			c.setAttribute('class', 'canvas');
			img.parentNode.appendChild(c);
		}
		this.css({'visibility': 'hidden', 'position': 'absolute'});
		c.style.display = 'inline';
		var canvasContext = c.getContext('2d');
		switch(n) {
			default :
			case 0 :
				c.setAttribute('width', img.width);
				c.setAttribute('height', img.height);
				canvasContext.rotate(0 * Math.PI / 180);
				canvasContext.drawImage(img, 0, 0,img.width,img.height);
				break;
			case 1 :
				c.setAttribute('width', img.height);
				c.setAttribute('height', img.width);
				canvasContext.rotate(90 * Math.PI / 180);
				canvasContext.drawImage(img, 0, -img.height,img.width,img.height);
				break;
			case 2 :
				c.setAttribute('width', img.width);
				c.setAttribute('height', img.height);
				canvasContext.rotate(180 * Math.PI / 180);
				canvasContext.drawImage(img, -img.width, -img.height,img.width,img.height);
				break;
			case 3 :
				c.setAttribute('width', img.height);
				c.setAttribute('height', img.width);
				canvasContext.rotate(270 * Math.PI / 180);
				canvasContext.drawImage(img, -img.width, 0,img.width,img.height);
				break;
		};
	};
};
function autoResizeIMG(maxWidth,maxHeight,objImg){
	var img = objImg;
	var hRatio;
	var wRatio;
	var Ratio = 1;
	var w = img.width;
	var h = img.height;
	wRatio = maxWidth / w;
	hRatio = maxHeight / h;
	if (maxWidth ==0 && maxHeight==0){
		Ratio = 1;
	}else if (maxWidth==0){//
		if (hRatio<1) Ratio = hRatio;
	}else if (maxHeight==0){
		if (wRatio<1) Ratio = wRatio;
	}else if (wRatio<1 || hRatio<1){
		Ratio = (wRatio<=hRatio?wRatio:hRatio);
	}
	if (Ratio<1){
		w = parseInt(w * Ratio);
		h = parseInt(h * Ratio);
	}
	
	objImg.height = h;
	objImg.width = w;
	objImg.style.height = h+'px';
	objImg.style.width = w+'px';
}
$.fn.resizeIMG = function(width,height){
	var imgList = $(this).find('img');
	var len = imgList.length,i=0;
	if(len>0){
		imgList.each(function(i,item){
			$(item).imagesLoaded(function(){
				autoResizeIMG(width,height,item);
			});
		});
	}
}
$.fn.imagesLoaded=function(callback){var $this=$(this),$images=$this.find('img').add($this.filter('img')),len=$images.length,blank='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';function triggerCallback(){callback.call($this,$images)}function imgLoaded(event){if(--len<=0&&event.target.src!==blank){setTimeout(triggerCallback);$images.unbind('load error',imgLoaded)}}if(!len){triggerCallback()}$images.bind('load error',imgLoaded).each(function(){if(this.complete||typeof this.complete==="undefined"){var src=this.src;this.src=blank;this.src=src}});return $this};