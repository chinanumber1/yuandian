function getUserState(){
	if(!$.isEmptyObject(userDate)){
		var hl = '<div class="head_img"><a href="'+nowdomain+'member/modify.aspx?action=tou" target="_blank"><img src="'+userDate.chrpic+'" /></a></div><div class="user_name"><a href="'+nowdomain+'member/modify.aspx" target="_blank">'+userDate.name+'</a></div><div class="member_type">'+userDate.chrlevel+' <em>'+userDate.djid+'</em></div><div class="bazhu display'+userDate.isbazhu+'">版主</div><ul><li class="s_jifen">'+window['jifenneme']+'：<em>'+userDate.jifennum+'</em><a href="'+nowdomain+'member/myrevert.aspx?action=tieba" target="_blank">[管理]</a></li><li class="s_tiezi">帖子：<em>'+userDate.tiebanum+'</em></li><li>精华：<em>'+userDate.tiebajinghuanum+'</em></li></ul>';
		$('#userInfo').html(hl);
		$('#isfabukill').val(userDate.isfabukill);
		$('#qiandao')[0]&&$('#qiandao').qiandao();
		$('#wrap_kalendar')[0]&&$('#wrap_kalendar').showkalendar();
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
	$('#userInfo').show();
}
//////////////////签到/////////////////////
window['isQiandaoed']=false;
$.fn.qiandao = function(){
	var that = $(this);
	$('#qdlou').html(userDate.qdlou);
	$('#qdmonths').html(userDate.qdmonths);
	$('#qdallnum').html(userDate.qdallnum);
	$('#qdlian').html(userDate.qdlian);
	$('#qiandao').addClass('isqiandao'+userDate.qdday);
	that.attr('onClick','').click(function(e){
		e.preventDefault();
		if(userDate.qdday === 1 || window['isQiandaoed']===true){
			MSGwindowShow('qiandao','0','今天已经签过了，明日再来！','','');
			return false;
		}
		$('#'+f_node+',#'+m_node).show();
	});
	var f_node = 'consoleQD';
	var m_node = 'masklayer';
	if(!$('#consoleQD')[0]){
		var pop_sign = "";
			pop_sign += '<h4>每日签到</h4>';
			pop_sign += '<div class="close">关闭</div>';
			pop_sign += '<a href="javascript:void(0);" onClick="return showQD(this);">开始签到</a>';
			pop_sign += '<p>每天可签到一次，签到有惊喜哦！</p>';
			pop_sign += '<div class="time">总签到次数：'+userDate.qdallnum+'　本月签到次数：'+userDate.qdmonths+'</div>';

		var divs = document.createElement('div');
		divs.className = 'pop_sign';
		divs.id = f_node;
		divs.style.display = 'none';
		divs.innerHTML = pop_sign;
		var mask = document.createElement('div');
		mask.id = m_node;
		mask.className = 'masklayer';
		mask.style.height = $(document).height()+'px';
		$('body').append(divs);
		$('body').append(mask);
		$('#'+f_node).find('.close').click(function(e){
			e.preventDefault();
			$('#'+f_node+',#'+m_node).hide();
		});
	}
	$('#qiandaoWrap').hover(function(){
		if(that.hasClass('isqiandao1')){$('#wrap_kalendar_node').show();}
	},function(){
		$('#wrap_kalendar_node:visible').fadeOut();
	});
}
function showQD(node){
	var url = nowdomain+'tieba/tieba_ajax.ashx?action=qiandao&jsoncallback=?';
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			window['isQiandaoed'] = true;
			$('#qiandao').removeClass('isqiandao0').addClass('isqiandao1');
			$('#qdallnum').html(parseInt($('#qdallnum').html())+1);
			$('#consoleQD,#masklayer').hide();
			//MSGwindowShow('revert','0','签到成功！'+data[0].jifennum,'','');
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
function getQD(dateObj){
	var dateObj = dateObj || '';
	var url = nowdomain+'request_paging.ashx?jsoncallback=?&table_id=255&tplpath=tieba&tplname=tieba_qiandao_list_json.html&isjson=1&g='+userDate.userid+'&startdate='+dateObj;
	var Digital=new Date().getTime();
	url=url+"&k="+encodeURIComponent(Digital);
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			var arr = data[0].MSG;
			if(arr.length === 0){return false;}
			for(var i=0;i<arr.length;i++){
				$('#y'+data[0].MSG[i].dtappenddate.Y+'_m'+data[0].MSG[i].dtappenddate.M+'_d'+data[0].MSG[i].dtappenddate.D).addClass('yiqian');
			}
		}else{
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}
function checkQD(){
	if($.isEmptyObject(userDate)){
		MSGwindowShow('qiandao','1','请登录后再进行签到！',nowdomain+'member/login.html?from='+encodeURIComponent(window.location.href),'');
		return false;
	}
}
/////////////////////贴吧页面分页控件//////////////////////
function getPagingGlobal(obj,node){
	keyvalues["p"]='1';
	keyvalues = $.extend({},keyvalues,obj);
	get_data_paging(callback);
	function callback(data){
		if(data[0].islogin !== '1'){MSGwindowShow('revert','0',data[0].error,'','');return;}
		$('#pagingList').html(data[0].MSG);
		$('#pageNavigation').html(data[0].PageSplit);
		setTimeout(function(){$('#resizeIMG')[0]&&$('#resizeIMG .post_con').resizeIMG('800','1000');},50);
		if(typeof window['islazyImg'] !== 'undefined'){setTimeout(function(){lazyImg('#pagingList .n_img',false);},50);}
		$("html,body").animate({scrollTop: 0},300);
	}
	if(!!$('#louzhuNode')[0]){
		if(keyvalues.p !== '1'){
			$('#louzhuNode').hide();
		}else{
			$('#louzhuNode').show();
		}
	}
	if(typeof node !== 'undefined'){
		$('#tiebaCatChr')[0]&&$('#tiebaCatChr').html($(node).html());
		$(node).parent().siblings('.cur').removeClass('cur');
		$(node).parent().addClass('cur');
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
			node.find('.num').html(parseInt(node.find('.num').html())+1);
		}else{
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}
function postNewRevert(o,node,activeid,chrmark,isrep,parentid){
	var url = nowdomain+'tieba/tieba_ajax.ashx?action=saverevert&id='+activeid+'&chrmark='+chrmark+'&isrep='+isrep+'&Parentid='+parentid+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			if(data[0].isopen === '0'){
				MSGwindowShow('revert','0','恭喜你，回复成功！请耐心等待系统审核！','','');
				$('#isrep').val('');
				$('#parentid').val('');
				$('#replay').hide();
				ue.setContent('');
			}else{
				successPostRevert(o,node,data[0]);
			}
		}else{
			MSGwindowShow('revert','0',data[0].error,'','');
		}
	});
}
function successPostRevert(o,node,str){
	$('#isrep').val('');
	$('#parentid').val('');
	ue.setContent('');
	$('#replay').hide();
	$('#pagingList').append(str.MSG);
	$('#listEmpty')[0]&&$('#listEmpty').remove();
	setTimeout(function(){
		MSGwindowShow('revert','0','恭喜您！回复成功！','','');
		$("html,body").animate({scrollTop: $("#pagingList .post_item:last-child").offset().top},300);
	},100);
}
function loadRevertReplay(o,parentid){
	$('#parentid').val(parentid);
	$('#isrep').val('1');
	var sid = 'replay';
	/*if($('#'+sid).attr('data-isevent') === '0'){
		$(document).mousedown(function(event){
			if(!$('#'+sid+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#' + sid).length === 0) && !$target.hasClass('replay_life') && ($target.parents('.replay_life').length === 0)){
				 $('#'+sid).hide();
			}
		});
	}*/
	$(o).parent().parent().append($('#'+sid).detach());
	$('#'+sid+':hidden').show();
	return false;
}
function edit_replay(o,sid){
	window['$id'] = sid;
	var f_id = 'replay_tips';
	
	if(!$('#'+f_id)[0]){
		var replayHTML = '<div class="replay_tips" id="'+f_id+'" style="display:none"><div class="hd"><a href="#" class="close">关闭</a>编辑和回评</div><div class="bd" id="'+f_id+'_bd">';
		replayHTML += '</div></div>';
		$('body').append(replayHTML);
		$(document).mousedown(function(event){
			if(!$('#'+f_id+':visible')[0]) return;
			var $target = $(event.target);
			if(($target.parents('#' + f_id).length === 0)){
				 $('#'+f_id).hide();
				 $('#ue_editor').append(ue.container.parentNode);
				ue.reset();
			}
		});
		$('#'+f_id).on('click','.close',function(e){
			e.preventDefault();
			$('#'+f_id).hide();
			$('#ue_editor').append(ue.container.parentNode);
			ue.reset();
		});
	}
	var f_node = $('#'+f_id);
	var w_h = $(window).height(),d_h = f_node.height(),s_h = $(document).scrollTop(),top_val = (w_h-d_h)/2+s_h;
	f_node.css({'top':top_val+'px','display':'block'});
	$('#'+f_id+'_bd').empty();
	//载入回复内容
	var html = '<div class="comment_source"><div class="comment_user">原帖内容：</div><div id="replay_tips_input" style="height:300px;"></div></div><div class="write_2014 display'+window['STYLEID'] +'" id="write_replay"><div class="timer">回复：(你的回评代表官方发言,请慎重!)</div><textarea id="replay_tips_textarea" class="textarea">'+$(o).parents('.post_item').find('.replaycontent2').html()+'</textarea></div><button class="global_btn_blue_big" onclick="return post_replay();" type="button">确认提交</button>';
	$('#'+f_id+'_bd').append(html);
	d_h = f_node.height(),top_val = (w_h-d_h)/2+s_h;
	f_node.css({'top':top_val+'px'});
	
	var yuantieneirong = $(o).parents('.post_item').find('.replaycontent1').html();
	$('#replay_tips_input').append(ue.container.parentNode);
	ue.reset();
	setTimeout(function(){
		ue.setContent(yuantieneirong);
	},200);
	return false;
}
function post_replay(){
	var txt2 = $('#replay_tips_textarea').val();
	var txt1 = ue.getContent();
	if(txt1 === ''){MSGwindowShow('tieba','0','评论内容不能为空！','','');return false;}
	var url= nowdomain+'tieba/tieba_ajax.ashx?action=saverevert_rep&id='+window['$id']+'&Chrmark='+encodeURIComponent(txt1)+'&replaycontent='+encodeURIComponent(txt2)+'&jsoncallback=?';
	var Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	jQuery.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			MSGwindowShow('tieba','0','提交成功！','','');
			$('#replay_tips').find('.close').trigger('click');
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
		var tgQuickHTML = '<div class="tgQuick" id="'+tgQuickid+'"><a href="javascript:void(0);" onClick="return delRevert(\'0\');">清空该帖内容</a><a href="javascript:void(0);" onClick="return delRevert(\'2\');">仅删除该帖</a><a href="javascript:void(0);" onClick="return delRevert(\'1\');">删除包括后续回复</a><s class="s"></s></div>';
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



$.fn.chackTextarea = function(ue,callback) {
	var t = $(this),
	isrep = $('#isrep'),
	parentid = $('#parentid');
    t.submit(function(e){
		var content = encodeURIComponent(ue.getContent());
		if(!ue.hasContents()){
			MSGwindowShow('tieba','4','请输入回复内容！','','');
			return false;
		}
		if(e.target.id === 'myform'){
			parentid.val('');
			isrep.val('');
		}
		//callback.call(this,content,isrep.val(),parentid.val());
		return true;
	}); 
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
	var f_node = 'super_tips';
	if(!window['hasSuperManag']){
		window['hasSuperManag'] = true;
		window['hasSuperManagHtml'] = '<div class="hd clearfix"><a href="#" class="close">关闭</a>超级管理</div>'+
		'<div class="bd">'+
		'<form method="post" onSubmit="return putSuperManage(this)">'+
		'<input type="hidden" id="super_id" value="" />'+
		'<div class="list">'+
			'<span class="sp_a">标题：</span>'+
			'<span class="sp_b name" id="super_title"></span>'+
		'</div>'+
		'<div class="list">'+
			'<span class="sp_a">标题颜色：</span>'+
			'<span class="sp_b"><input type="text" class="t_ipt" id="super_color" value="" /> <img src="'+window['Default_tplPath']+'images/colorpicker.png" id="cp" style="cursor:pointer"/></span>'+
			'<span class="sp_b">　标题加粗：</span>'+
			'<span class="sp_b"><input type="radio" name="isbold" value="0" />否　<input type="radio" name="isbold" value="1" />是</span>'+
		'</div>'+
		'<div class="list">'+
			'<span class="sp_a">版块及主题：</span>'+
			'<span class="sp_b" id="super_cat"></span>'+
		'</div>'+
		'<div class="list">'+
			'<span class="sp_b">　浏览数：</span>'+
			'<span class="sp_b"><input type="text" class="t_ipt" id="super_hits" value="" /></span>'+
		'</div>'+
		'<div class="list">'+
			'<span class="sp_a">审核状态：</span>'+
			'<span class="sp_b"><input type="radio" name="iskill" value="0" />否　<input type="radio" name="iskill" value="1" />是</span>'+
			'<span class="sp_a">允许回复：</span>'+
			'<span class="sp_b"><input type="radio" name="isrevert" value="0" />否　<input type="radio" name="isrevert" value="1" />是</span>'+
		'</div>'+
		'<div class="list">'+
			'<span class="sp_a">是否精华：</span>'+
			'<span class="sp_b"><input type="radio" name="isjinghua" value="0" />否　<input type="radio" name="isjinghua" value="1" />是</span>'+
			'<span class="sp_a">是否置顶：</span>'+
			'<span class="sp_b"><select name="iszhiding" id="iszhiding"><option value="">不置顶</option><option value="1">置顶权重一</option><option value="2">置顶权重二</option><option value="3">置顶权重三</option><option value="2">置顶权重四</option><option value="5">置顶权重五</option></select></span>'+
			
		'</div>'+
		'<div class="list">'+
			'<span class="sp_a">首页推荐：</span>'+
			'<span class="sp_b"><input type="radio" name="isindex" value="0" />否　<input type="radio" name="isindex" value="1" />是</span>'+
			'<span class="sp_a">推荐排序值：</span>'+
			'<span class="sp_b"><input type="text" class="t_ipt" id="super_intorder" value="" /></span>'+
		'</div>'+
		'<div class="list">'+
			'<span class="sp_a">&nbsp;</span>'+
			'<span class="sp_b"><button type="submit" class="t_btn">确认提交</button></span>'+
		'</div>'+
		'</form></div>';
		var divs = document.createElement('div');
		divs.className = 'replay_tips';
		divs.id = f_node;
		divs.style.display = 'none';
		divs.innerHTML = window['hasSuperManagHtml'];
		$('body').append(divs);
		$(document).mousedown(function(event){
			if(!$('#'+f_node+':visible')) return;
			var $target = $(event.target);
			if(($target.parents('#super_tips').length === 0) && ($target.parents('#colorpanel').length === 0)){
				 $('#'+f_node).hide();
			}
		});
		$('#'+f_node).on('click','.close',function(e){
			e.preventDefault();
			$('#'+f_node).hide();
		});
		$("#cp").colorpicker({fillcolor:true,target:$("#super_color")});
	}
	
	var url = nowdomain+'tieba/tieba_ajax.ashx?jsoncallback=?&action=tiebaedit&id='+sid;
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			$('#super_title').html(data[0].MSG.title);
			$('#super_cat').html(data[0].MSG.bigcategoryidsel_1 + ' ' + data[0].MSG.categoryidsel_1);
			$('#super_id').val(data[0].MSG.id);
			$('#super_num2').val(data[0].MSG.num2);
			$('#super_revertnum').val(data[0].MSG.revertnum);
			$('#super_hits').val(data[0].MSG.hits);
			$('#super_color').val(data[0].MSG.color);
			$('#super_intorder').val(data[0].MSG.intorder);
			$('#'+f_node).find("input[name='iskill'][value='"+data[0].MSG.iskill+"']").prop('checked',true);
			$('#'+f_node).find("input[name='isrevert'][value='"+data[0].MSG.isrevert+"']").prop('checked',true);
			$('#'+f_node).find("input[name='isjinghua'][value='"+data[0].MSG.isjinghua+"']").prop('checked',true);
			//$('#'+f_node).find("input[name='iszhiding'][value='"+data[0].MSG.iszhiding+"']").prop('checked',true);
			$('#iszhiding').val(data[0].MSG.iszhiding);
			$('#'+f_node).find("input[name='isbold'][value='"+data[0].MSG.isbold+"']").prop('checked',true);
			$('#'+f_node).find("input[name='isindex'][value='"+data[0].MSG.isindex+"']").prop('checked',true);
			
			$('#bigcategoryid_1').change(function(){categoryData_set("1","bigcategoryid_1",0,"categoryid_1",'');});
		}
	});
	var w_h = $(window).height(),d_h = $('#'+f_node).height(),s_h = $(document).scrollTop(),top_val = (w_h-d_h)/2+s_h;
	$('#'+f_node).css({'top':top_val+'px','display':'block'});
}
function putSuperManage(form){
	var url = nowdomain+'tieba/tieba_ajax.ashx?jsoncallback=?&action=tiebaeditsave';
	var iskill = $(form).find('input[name=iskill]:checked').val(),
		isrevert = $(form).find('input[name=isrevert]:checked').val(),
		isjinghua = $(form).find('input[name=isjinghua]:checked').val(),
		//iszhiding = $(form).find('input[name=iszhiding]:checked').val(),
		iszhiding = $('#iszhiding').val(),
		isbold = $(form).find('input[name=isbold]:checked').val(),
		isindex = $(form).find('input[name=isindex]:checked').val(),
		intorder = $('#super_intorder').val(),
		hits = $('#super_hits').val(),
		color = $('#super_color').val(),
		sid = $('#super_id').val(),
		bigcategoryid = $('#bigcategoryid_1').val(),
		categoryid = $('#categoryid_1').val();
		
	url = url+'&iskill='+iskill+'&isrevert='+isrevert+'&isjinghua='+isjinghua+'&iszhiding='+iszhiding+'&hits='+hits+'&id='+sid+'&intorder='+intorder+'&color='+encodeURIComponent(color)+'&bigcategoryid='+bigcategoryid+'&categoryid='+categoryid+'&isbold='+isbold+'&isindex='+isindex;
	
	$.getJSON(url,function(data){
		if(data[0].islogin==='1'){
			$('#super_tips').find('.close').trigger('click');
			getPagingGlobal({'p':keyvalues.p});
			//setTimeout(function(){MSGwindowShow('tieba','0','编辑成功！','','');},600);
		}
	});
	return false;
}
/////////////////固定边角控件///////////////////
$.fn.rmenuShow = function(){
	var rtop = $("#top");
	var $t = $(this),w_w = $(window).width(),r_css = 0;
	r_css = parseInt((w_w-1200)/2 - $t.width() - 12);
	r_css = r_css<0?0:r_css;
	$t.css({'right':r_css+'px'})
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
function lazyImg(selector,isIscroll5){
	var w_h = $(window).height();
	$(selector).find('img').each(function(){
		if($(this).attr("data-ifshow") === '0' && ($(document).scrollTop()+$(window).height()) > $(this).offset().top){
			$(this).attr({'src':$(this).attr('data-src'),"data-ifshow":'1'})
		}
	});
}
$.fn.dialogImg = function(selector){
	var that = $(this);
	var f_node = 'dialogImg';
	var div = document.createElement('div');
	div.id = f_node;
	div.className = f_node;
	div.style.display = 'none';
	$('body').append(div);
	
	var node = $('#'+f_node);
	$(document).mousedown(function(event){
		if(!$('#'+f_node+':visible')) return;
		var $target = $(event.target);
		if($target.parents('#'+f_node).length === 0){
			 node.hide();
		}
	});
	node.append('<a href="#" class="close">关闭</a>');
	node.find('.close').click(function(e){
		node.hide();
		e.preventDefault();
	});
	that.on('click',selector,function(e){
		e.preventDefault();
		node.find('img').remove();
		var src = $(this).attr('src');
		var img = new Image();
		img.style.display = 'none';
		$(img).attr({'src':src}).imagesLoaded(function(){
			autoResizeIMG('800','600',$(this)[0]);
			$(this).show();
			setTimeout(function(){
				var csTop = $(document).scrollTop()+($(window).height() - node.height())/2;
				var csLeft = ($(window).width() - node.width())/2
				node.css({'top':csTop +'px','left':csLeft+'px'}).show();
			},20);
		}).appendTo(node);
		
	});
}
$.fn.myAlbum = function(){
	function setImg(src,index){
		if($('#rotateCanvas')[0]){resetImg();}
		resetImg();
		large_pic.attr({'src':src,'cur':index}).imagesLoaded(function(){
			autoResizeIMG('600','600',large_pic[0]);
		});
		ypic.attr('href',src);
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
		$('#rotateCanvas').remove();
		large_pic.css({'visibility':'visible','position':'static','width':'auto','height':'auto'}).attr({'width':'','height':'','step':'0'});
	}
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
		if($('#rotateCanvas')[0]){resetImg();}
	});
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
		//jQuery.browser.msie + ' '+jQuery.browser.version
		if (jQuery.browser.version == 8) {
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
			c.setAttribute('class','canvas');
			c.id = 'rotateCanvas';
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
	var img = new Image();
	img.src = objImg.src;
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
(function(){
jQuery.browser = {};
jQuery.browser.mozilla = false; 
jQuery.browser.webkit = false; 
jQuery.browser.opera = false; 
jQuery.browser.msie = false; 

var nAgt = navigator.userAgent; 
jQuery.browser.name = navigator.appName; 
jQuery.browser.fullVersion = ''+parseFloat(navigator.appVersion); 
jQuery.browser.majorVersion = parseInt(navigator.appVersion,10); 
var nameOffset,verOffset,ix; 

// In Opera, the true version is after "Opera" or after "Version" 
if ((verOffset=nAgt.indexOf("Opera"))!=-1) { 
jQuery.browser.opera = true; 
jQuery.browser.name = "Opera"; 
jQuery.browser.fullVersion = nAgt.substring(verOffset+6); 
if ((verOffset=nAgt.indexOf("Version"))!=-1) 
jQuery.browser.fullVersion = nAgt.substring(verOffset+8); 
} 
// In MSIE, the true version is after "MSIE" in userAgent 
else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) { 
jQuery.browser.msie = true; 
jQuery.browser.name = "Microsoft Internet Explorer"; 
jQuery.browser.fullVersion = nAgt.substring(verOffset+5); 
} 
// In Chrome, the true version is after "Chrome" 
else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) { 
jQuery.browser.webkit = true; 
jQuery.browser.name = "Chrome"; 
jQuery.browser.fullVersion = nAgt.substring(verOffset+7); 
} 
// In Safari, the true version is after "Safari" or after "Version" 
else if ((verOffset=nAgt.indexOf("Safari"))!=-1) { 
jQuery.browser.webkit = true; 
jQuery.browser.name = "Safari"; 
jQuery.browser.fullVersion = nAgt.substring(verOffset+7); 
if ((verOffset=nAgt.indexOf("Version"))!=-1) 
jQuery.browser.fullVersion = nAgt.substring(verOffset+8); 
} 
// In Firefox, the true version is after "Firefox" 
else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) { 
jQuery.browser.mozilla = true; 
jQuery.browser.name = "Firefox"; 
jQuery.browser.fullVersion = nAgt.substring(verOffset+8); 
} 
// In most other browsers, "name/version" is at the end of userAgent 
else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) < 
(verOffset=nAgt.lastIndexOf('/')) ) 
{ 
jQuery.browser.name = nAgt.substring(nameOffset,verOffset); 
jQuery.browser.fullVersion = nAgt.substring(verOffset+1); 
if (jQuery.browser.name.toLowerCase()==jQuery.browser.name.toUpperCase()) { 
jQuery.browser.name = navigator.appName; 
} 
} 
// trim the fullVersion string at semicolon/space if present 
if ((ix=jQuery.browser.fullVersion.indexOf(";"))!=-1) 
jQuery.browser.fullVersion=jQuery.browser.fullVersion.substring(0,ix); 
if ((ix=jQuery.browser.fullVersion.indexOf(" "))!=-1) 
jQuery.browser.fullVersion=jQuery.browser.fullVersion.substring(0,ix); 

jQuery.browser.majorVersion = parseInt(''+jQuery.browser.fullVersion,10); 
if (isNaN(jQuery.browser.majorVersion)) { 
jQuery.browser.fullVersion = ''+parseFloat(navigator.appVersion); 
jQuery.browser.majorVersion = parseInt(navigator.appVersion,10); 
} 
jQuery.browser.version = jQuery.browser.majorVersion; 
})();