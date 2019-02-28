<include file="web_head"/>
<div class="app-header bg-white padder w-full p-t8 b-b color-black">
<if condition="!$is_app_browser">
	<div id="return" class="icon-uniE602 pull-left margin-top3 font-size22"></div>
</if>
	<a href="javascript:scroll(0,0)"><div class="text-lg margin-top3 color-black3 margin-top-7">{pigcms{$index}</div></a>
	<div id="delete" class="pull-right" style="margin-top:-30px;"></div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header text-left text-md">
	<div class="m-b-sm">
	<div id="aDetails"></div>
	<div id="content" class="wrapper"></div>
	<div class="padding-tb5 bg-gray"></div>
	<div id="number" class="text-base b-b padding-tb5 clearfix"></div>
	<div id="comment" class="text-base m-b-sm"></div>
	<div id="zanList" class="text-base"></div>
	<div class="padding-tb5 bg-gray"></div>
	</div>
</div>
<div id="footer" class="app-footer b-t bgBottom">
	<div class="btn b-r col-xs-6" id="zan_number"></div>
	<div class="btn b-l col-xs-6" id="go_comment"></div>
</div>
<script>
	var	aricle_id	=	{pigcms{$aricle_id};
	var cat_id		=	{pigcms{$cat_id};
	var	status	=	{pigcms{$status};
	var	village_id	=	{pigcms{$village_id};
        var is_indep_house ="{pigcms{:defined('IS_INDEP_HOUSE')}";
        if(is_indep_house){
            var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
        }else{
            var domain_host = 'wap.php';
        }

	var	wx_host	=	'{pigcms{$site_url}';
	var page	=	1;
	var zanPageNumber	=	1;
	var user_session	=	{pigcms{$userSession};
	if(village_id==0){
		a.msg('文章ID不能为空');
	}
	a.busy();
	$.ajax({
		type : "post",
		url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_details_json',
		dataType : "json",
		data:{
			village_id : village_id,
			aricle_id : aricle_id,
			page	:	page,
			zanPageNumber	:	zanPageNumber,
		},
		success : function(result){
			var list 	=	'';
			var img	=	result.result.img;
			var aDetails	=	result.result.aDetails;
			var is_uid	=	result.result.is_uid;
			var aComment	=	result.result.aComment;
			var aCommentLeng	=	aComment.length;
			var exist	=	result.result.exist;
			var aZanList	=	result.result.aZanList;
			var aZanNumberLeng	=	aZanList.length;
			list	+=	'<div class="padding-tb5 bg-gray"></div>';
			list	+=	'<div class="padding-lr5 padding-tb5 b-b clearfix">';
			list	+=	'	<div class="col-xs-2 m-l-n" style="width:65px;"><img class="avatar" src="'+aDetails.uid.avatar+'" /></div>';
			list	+=	'	<div class="col-xs-6 no-padder m-l-n-sm">';
			list	+=	'		<div class="text-sm">'+aDetails.uid.nickname+'</div>';
			list	+=	'		<div class="text-xs">'+aDetails.update_time+'</div>';
			list	+=	'	</div>';
			list	+=	'	<div class="col-xs-3 pull-right text-right no-padder m-l">';
			list	+=	'		<div class="text-sm">'+aDetails.third+'</div>';
			list	+=	'		<div class="text-xs color-green">'+aDetails.exp_time+'</div>';
			list	+=	'	</div>';
			list	+=	'</div>';
			$('#aDetails').html(list);
			var	content	=	'';
			content	+=	'<div class="text-md">'+aDetails.aricle_title+'</div>';
			content	+=	'<div class="wrap m-t-sm text-left color-black2 text-base" style="text-indent:25px">'+aDetails.aricle_content+'</div>';
			for(var q=0;q<aDetails.img.length;q++){
				content	+=	'<img class="m-t-xs" src="'+aDetails.img[q].aricle_img+'" />';
			}
			$('#content').html(content);
			var	number	=	'';
			if(aDetails.aricle_comment_num==0 || aDetails.aricle_comment_num==null){
				number	+=	'<div onclick="countPing()" id="countPing" class="col-xs-6 text-left padding-tb5">全部评论&nbsp;&nbsp;0</div>';
			}else{
				number	+=	'<div onclick="countPing()" id="countPing" class="col-xs-6 text-left padding-tb5">全部评论&nbsp;&nbsp;'+aDetails.aricle_comment_num+'</div>';
			}
			if(aDetails.aricle_praise_num==0 || aDetails.aricle_praise_num==null){
				number	+=	'<div onclick="countZan()" id="countZan" class="col-xs-6 text-right padding-tb5">赞&nbsp;&nbsp;<span id="zanNumber">0</span></div>';
			}else{
				number	+=	'<div onclick="countZan()" id="countZan" class="col-xs-6 text-right padding-tb5">赞&nbsp;&nbsp;<span id="zanNumber">'+aDetails.aricle_praise_num+'</span></div>';
			}
			number	+=	'<div class="arrow"></div>';
			$('#number').html(number);
			var	comment =	'';
			if(aDetails.aricle_comment_num==0 || aDetails.aricle_comment_num==null){
				comment	+=	'<div id="pingConent" class="text-center text-xs color-black2 f-60" style="display:block"><span id="null_ping2">暂无评论</span></div>';
			}else{
				comment	+=	'<div id="pingConent" style="display:block" class="padding-b26">';
				for(var i=0; i<aCommentLeng; i++){
					comment	+=	'<div class="show padding-t10">';
					comment	+=	'<div onclick="pinglunReply('+i+','+aComment[i].uid.status+')" id="pingConent'+i+'" class="b-b clearfix">';
					comment	+=	'	<div class="col-xs-2" style="width:65px;"><img class="avatar" src="'+aComment[i].uid.avatar+'" /></div>';
					comment	+=	'	<div class="col-xs-10 m-l-n padding-b10">';
					comment	+=	'		<div class="m-b-xs">'+aComment[i].uid.nickname+'</div>';
					comment	+=	'		<div class="m-b-xs text-xs">'+aComment[i].create_time+'</div>';
					if(aComment[i].comment_fid == 0){
						comment	+=	'		<div>'+aComment[i].comment_content+'</div>';
					}else{
						comment	+=	'		<div>回复<span class="color-green2">'+aComment[i].comment_fname.nickname+'</span>：&nbsp;&nbsp;'+aComment[i].comment_content+'</div>';
					}
					comment	+=	'	</div>';
					comment	+=	'</div>';
					comment	+=	'</div>';
					if(aComment[i].uid.status == 1){
						comment	+=	'<div id="go_delete'+i+'" class="payment_time_mask" style="display:none;">';
						comment	+=	'	<ul>';
						comment	+=	'		<li onclick="go_delete('+i+','+aComment[i].aricle_id+','+aComment[i].comment_id+')">删除</li>';
						comment	+=	'		<li onclick="cancel_delete('+i+')">取消</li>';
						comment	+=	'	</ul>';
						comment	+=	'</div>';
					}else{
						comment	+=	'<div id="go_reply'+i+'" class="payment_time_mask" style="display:none;">';
						comment	+=	'	<ul>';
						comment	+=	'		<li onclick="go_reply('+i+','+aComment[i].aricle_id+','+aComment[i].comment_id+')">回复</li>';
						comment	+=	'		<li onclick="cancel('+i+')">取消</li>';
						comment	+=	'	</ul>';
						comment	+=	'</div>';
					}
				}
				if(aCommentLeng <= 9){
					comment	+=	'<div id="null_ping" class="text-center text-xs color-black2 m-t">没有评论了</div>';
				}else{
					comment	+=	'<div id="up" class="text-center m-t">上拉会有更多评论哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
				}
				comment	+=	'</div>';
			}
			$('#comment').html(comment);
			var zanList	=	'';
			if(aDetails.aricle_praise_num==0 || aDetails.aricle_praise_num==null){
				zanList	+=	'<div id="zanConent" class="text-center text-xs color-black2 hf-30" style="display:none"><span id="null_zan2">暂无点赞</span></div>';
			}else{
				zanList	+=	'<div id="zanConent" style="display:none" class="padding-b26">';
				for(var i=0; i<aZanNumberLeng; i++){
					if(i==1){
						zanList	+=	'<div class="padding-t10">';
					}else{
						zanList	+=	'<div>';
					}
					zanList	+=	'<div class="b-b padding-b10 clearfix">';
					zanList	+=	'	<div class="col-xs-2" style="width:65px;"><img class="avatar" src="'+aZanList[i].uid.avatar+'" /></div>';
					zanList	+=	'	<div class="col-xs-10 m-l-n">';
					zanList	+=	'		<div class="m-t-xs">'+aZanList[i].uid.nickname+'</div>';
					zanList	+=	'	</div>';
					zanList	+=	'	</div>';
					zanList	+=	'</div>';
				}
				if(aZanNumberLeng <= 9){
					zanList	+=	'<div id="null_zan" class="text-center text-xs color-black2 m-t">没有赞了</div>';
				}else{
					zanList	+=	'<div id="upZan" class="text-center m-t">上拉会有更多赞哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
				}

				zanList	+=	'<div>';
			}
			$('#zanList').html(zanList);
			var	zan_number	=	'';
			if(exist==0 || exist==null){
				zan_number	+=	'<div onclick="zan('+aDetails.aricle_id+',\''+img.aricle_praise_click+'\')"><span class="text-lg m-n"></span>';
				zan_number	+=	'<span class="text-base"><img class="w-15" src="'+img.aricle_praise_default+'" />&nbsp;&nbsp;赞&nbsp;&nbsp;(<span id="dianzan">'+aDetails.aricle_praise_num+'</span>)</span></div>';
			}else{
				zan_number	+=	'<span class="text-lg m-n"></span>';
				zan_number	+=	'<span id="dianzan" class="text-base color-orange"><img class="w-15" src="'+img.aricle_praise_click+'" />&nbsp;&nbsp;已赞&nbsp;&nbsp;('+aDetails.aricle_praise_num+')</span>';
			}
			$('#zan_number').html(zan_number);
			var ping_number	=	'';
			ping_number	+=	'<span class="text-lg m-n"></span>';
			ping_number	+=	'<span id="pinglun" class="text-base"><img class="w-15" src="'+img.aricle_comment_default+'" />&nbsp;&nbsp;评论</span>';
			$('#go_comment').html(ping_number);
			var	is_delect;
			if(is_uid == 1){
				is_delect	=	'<div id="is_delete">删除</div>';
			}
			$('#delete').append(is_delect);
			a.busy(0);
		},
		error:function(){

		}
	});
	$(window).scroll(function(){
		if($(window).scrollTop() == $(document).height() - $(window).height()){
			if($('#pingConent').css('display') == 'block'){
				if($('#null_ping2').length < 1){
					if($('#null_ping').length < 1){
						$('#up').remove();
						var mais = '';
						mais	+=	'<div id="carregar" class="text-center m-t">正在加载</div>';
						$('#pingConent').append(mais);
						page++;
    					commentPage(page);
					}
				}
			}else{
				if($('#null_zan2').length < 1){
					if($('#null_zan').length < 1){
						$('#upZan').remove();
						var maisZan = '';
						maisZan	+=	'<div id="carregarZan" class="text-center m-t">正在加载</div>';
						$('#pingConent').append(maisZan);
						zanPageNumber++;
						zanPage(zanPageNumber);
					}
				}
			}
		}
	});
	//	上拉显示更多评论
	function	commentPage(page){
		a.busy();
		$.ajax({
			type : "post",
			url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_details_json',
			dataType : "json",
			data:{
				village_id : village_id,
				aricle_id : aricle_id,
				page	:	page,
				zanPageNumber	:	zanPageNumber,
			},
			async:false,
			success : function(result){
				var aDetails	=	result.result.aDetails;
				var aComment	=	result.result.aComment;
				var aCommentLeng	=	aComment.length;
				var page	=	result.result.page;
				var	comment =	'';
				if(aComment=='该文章没有评论'){
					comment	+=	'<div id="null_ping" class="text-center text-xs color-black2 m-t">没有评论了</div>';
				}else{
					for(var i=0; i<aCommentLeng; i++){
						comment	+=	'<div class="show padding-t10">';
						comment	+=	'<div onclick="pinglunReply('+page+i+','+aComment[i].uid.status+')" id="pingConent'+i+'" class="b-b clearfix">';
						comment	+=	'	<div class="col-xs-2" style="width:65px;"><img class="avatar" src="'+aComment[i].uid.avatar+'" /></div>';
						comment	+=	'	<div class="col-xs-10 m-l-n padding-b10">';
						comment	+=	'		<div class="m-b-xs">'+aComment[i].uid.nickname+'</div>';
						comment	+=	'		<div class="m-b-xs text-xs">'+aComment[i].create_time+'</div>';
						if(aComment[i].comment_fid == 0){
							comment	+=	'		<div>'+aComment[i].comment_content+'</div>';
						}else{
							comment	+=	'		<div>回复<span class="color-green2">'+aComment[i].comment_fname.nickname+'</span>：&nbsp;&nbsp;'+aComment[i].comment_content+'</div>';
						}
						comment	+=	'	</div>';
						comment	+=	'	</div>';
						comment	+=	'</div>';
						if(aComment[i].uid.status == 1){
							comment	+=	'<div id="go_delete'+page+i+'" class="payment_time_mask" style="display:none;">';
							comment	+=	'	<ul>';
							comment	+=	'		<li onclick="go_delete('+page+i+','+aComment[i].aricle_id+','+aComment[i].comment_id+')">删除</li>';
							comment	+=	'		<li onclick="cancel_delete('+page+i+')">取消</li>';
							comment	+=	'	</ul>';
							comment	+=	'</div>';
						}else{
							comment	+=	'<div id="go_reply'+i+'" class="payment_time_mask" style="display:none;">';
							comment	+=	'	<ul>';
							comment	+=	'		<li onclick="go_reply('+page+i+','+aComment[i].aricle_id+','+aComment[i].comment_id+')">回复</li>';
							comment	+=	'		<li onclick="cancel('+page+i+')">取消</li>';
							comment	+=	'	</ul>';
							comment	+=	'</div>';
						}
					}
					if(aCommentLeng < 9){
						comment	+=	'<div id="null_ping" class="text-center text-xs color-black2 m-t">没有评论了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}else{
						comment	+=	'<div id="up" class="text-center m-t">上拉会有更多评论哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}
				$('#carregar').remove();
				$('#pingConent').append(comment);
				a.busy(0);
			},
			error:function(){
				a.busy(0);
			}
	});}
	// 上拉显示更多赞
	function	zanPage(page){
		a.busy();
		$.ajax({
			type : "post",
			url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_details_json',
			dataType : "json",
			data:{
				village_id : village_id,
				aricle_id : aricle_id,
				page	:	page,
				zanPageNumber	:	zanPageNumber,
			},
			async:false,
			success : function(result){
				var exist	=	result.result.exist;
				var aZanList	=	result.result.aZanList;
				var aZanNumberLeng	=	aZanList.length;
				var zanList	=	'';
				if(aZanList==false){
					zanList	+=	'<div id="null_zan" class="text-center text-xs color-black2 m-t">没有赞了<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}else{
					for(var i=0; i<aZanNumberLeng; i++){
						zanList	+=	'<div class="padding-t10">';
						zanList	+=	'<div class="b-b padding-b10 clearfix">';
						zanList	+=	'	<div class="col-xs-2" style="width:65px;"><img class="avatar" src="'+aZanList[i].uid.avatar+'" /></div>';
						zanList	+=	'	<div class="col-xs-10 m-l-n">';
						zanList	+=	'		<div class="m-t-xs">'+aZanList[i].uid.nickname+'</div>';
						zanList	+=	'	</div>';
						zanList	+=	'	</div>';
						zanList	+=	'</div>';
					}
					if(aZanNumberLeng <= 9){
						zanList	+=	'<div id="null_zan" class="text-center text-xs color-black2 m-t">没有赞了<p>点击({pigcms{$index})可以返回顶部</p></div>';
					}else{
						zanList	+=	'<div id="upZan" class="text-center m-t">上拉会有更多赞哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
					}
				}
				$('#carregarZan').remove();
				$('#zanConent').append(zanList);
				a.busy(0);
			},
			error:function(){
				a.busy(0);
			}
	});}
	//	返回上一页面按钮
    $('#return').on('click',function(){
    	if(status==2){
			location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_aricle&village_id="+village_id+'&aricle_id='+aricle_id+'&cat_id='+cat_id;
    	}else{
			location.href = domain_host+"?g=Wap&c=Bbs&a=web_index&village_id="+village_id;
		}
    });
    //	点击评论，去评论页面
    $('#go_comment').on('click',function(){
		location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_wite_comment&village_id="+village_id+'&aricle_id='+aricle_id+'&cat_id='+cat_id+'&status='+status;
    });
    //	如果是自己发布的文章，可以删除
    $('#delete').on('click',function(){
		$.ajax({
			type : "post",
			url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_delete',
			dataType : "json",
			data:{
				village_id : village_id,
				aricle_id : aricle_id
			},
			success : function(result){
				if(result.result==1){
					a.msg('删除成功');
					if(status==2){
						location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_aricle&village_id="+village_id+'&aricle_id='+aricle_id+'&cat_id='+cat_id;
    				}else{
						location.href = domain_host+"?g=Wap&c=Bbs&a=web_index&village_id="+village_id;
					}
				}else{
					a.msg('删除失败');
				}
			},
			error:function(){
				a.msg('删除失败');
			}
		})
	});
    //	为文章点赞,更改点赞数量和点赞颜色
    function	zan(x,z){
    	if(user_session	== 0){
    		if(is_app_browser){
				location.href	=	wx_host+domain_host+'?g=Wap&c=Bbs&a=login&village_id='+village_id;
			}else{
				var location_url	=	'/'+domain_host+'?g=Wap&c=Login&a=index';
				layer.open({content:'请先进行登录！',btn: ['确定'],end:function(){location.href=location_url;}});
			}
		}else{
    		var zan_number	=	'';
    		var dianzan	=	$('#dianzan').text();
    		var zanNumber	=	$('#zanNumber').text();
    		dianzan	=	parseInt(dianzan)+1;
    		zan_number	+=	'<span class="text-lg m-n"></span>';
			zan_number	+=	'<span id="dianzan" class="text-base color-orange"><img class="w-15" src="'+z+'" />&nbsp;&nbsp;已赞&nbsp;&nbsp;('+dianzan+')</span>';
			$('#zan_number').html(zan_number);
			zanNumber++;
			$('#zanNumber').html(zanNumber);
    		$.ajax({
				type : "post",
				url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_zan',
				dataType : "json",
				data:{
					village_id : village_id,
					aricle_id : x
				},
				success : function(result){
					a.msg('点赞成功');
					$.ajax({
						type : "post",
						url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_zan_list',
						dataType : "json",
						data:{
							village_id : village_id,
							aricle_id : x
						},
						success : function(result){
							var aZanNumberLeng	=	result.result.length;
							var	list 	=	result.result;
							var zanList	=	'';
							zanList	+=	'<div id="zanConent" style="display:none" class="padding-b26">';
							for(var i=0; i<aZanNumberLeng; i++){
								zanList	+=	'<div class="padding-t10">';
								zanList	+=	'<div class="b-b padding-b10 clearfix">';
								zanList	+=	'	<div class="col-xs-2" style="width:65px;"><img class="avatar" src="'+list[i].uid.avatar+'" /></div>';
								zanList	+=	'	<div class="col-xs-10 m-l-n">';
								zanList	+=	'		<div class="m-t-xs">'+list[i].uid.nickname+'</div>';
								zanList	+=	'	</div>';
								zanList	+=	'	</div>';
								zanList	+=	'</div>';
							}
							if(aZanNumberLeng <= 9){
								zanList	+=	'<div id="null_zan" class="text-center text-xs color-black2 m-t">没有赞了</div>';
							}else{
								zanList	+=	'<div id="upZan" class="text-center m-t">上拉会有更多赞哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
							}
							zanList	+=	'<div>';
							$('#zanList').html(zanList);
						},
						error:function(){
							a.msg('显示点赞失败，需要刷新页面');
						}
					})
				},
				error:function(){
					a.msg('点赞失败');
				}
			})
		}
    }
    //	显示评论，隐藏赞
    function	countPing(){
		$('#zanConent').hide();
	    $('#pingConent').show();
	    $('#number .arrow').removeClass('zan');
    }
    //	显示赞，隐藏评论
    function	countZan(){
	    $('#pingConent').hide();
	    $('#zanConent').show();
	    $('#number .arrow').addClass('zan');
    }
    //	赞后，底部赞变颜色，并且数量加1
    function	pinglunReply(i,status){
    	if(status	==	1){
			$('#go_delete'+i).show();
    	}else{
			$('#go_reply'+i).show();
    	}
    }
    //	点击评论，显示的取消按钮
    function	cancel(i){
		$('#go_reply'+i).hide();
    }
    //	点击评论，显示的再评论按钮
    function	go_reply(i,aricle_id,comment_id){
		location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_wite_comment&village_id="+village_id+'&aricle_id='+aricle_id+'&cat_id='+cat_id+'&comment_id='+comment_id;
		$('#go_reply'+i).hide();
    }
    //	点击评论，自己的评论删除按钮
    function	cancel_delete(i){
		$('#go_delete'+i).hide();
    }
    //	删除自己的评论
    function	go_delete(i,aricle_id,comment_id){
		$.ajax({
			type : "post",
			url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_comment_delete',
			dataType : "json",
			data:{
				comment_id : comment_id,
				aricle_id : aricle_id,
				village_id : village_id,
			},
			success : function(result){
				a.msg('删除成功');
			},
			error:function(){
				a.msg('删除失败');
			}
		})
		window.location.reload();
    }
</script>
<style>
#number{
	position:relative;
}
#number .arrow{
	width: 8px;
	height: 8px;
	border: 1px solid #dee5e7;
	border-width: 0 1px 1px 0;
	border-top-width: 0px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 0px;
	-webkit-transform: rotate(45deg);
	position: absolute;
	bottom:-4px;
	background:white;
	left:8%;
}
#number .arrow.zan{
	right:8%;
	left:auto;
}
</style>
<include file="web_footer"/>