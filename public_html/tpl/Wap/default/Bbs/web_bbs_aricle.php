<include file="web_head"/>
<div class="app-header bg-white padder w-full p-t8 b-b color-black clearfix">
<if condition="!$is_app_browser">
	<div id="return_index" class="icon-uniE602 pull-left margin-top3 font-size22"></div>
</if>
	<a href="javascript:scroll(0,0)"><div class="text-lg margin-top3 color-black3 margin-top-7">{pigcms{$index}<span id="xinzeng" class="pull-right" style="margin-top:-7px; font-weight:bold;font-size:28px">+</span></div></a>
</div>
<div class="app-content with-header text-left text-md">
	<div><div id="newBbsAricleList"></div></div>
</div>
<script>
	var	cat_id	=	'{pigcms{$cat_id}';
	var	village_id	=	'{pigcms{$village_id}';
	var	wx_host	=	'{pigcms{$site_url}';
	var page	=	1;
	var user_session	=	'{pigcms{$userSession}';
	var is_app_browser	=	'{pigcms{$is_app_browser}';
	if(cat_id==0){
		a.msg('菜单ID为空');
	}

        var is_indep_house ="{pigcms{:defined('IS_INDEP_HOUSE')}";
        if(is_indep_house){
            var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
        }else{
            var domain_host = 'wap.php';
        }

	list(true);
	$('#xinzeng').on('click',function(){
		location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_aricele_wite&village_id="+{pigcms{$village_id}+"&cat_id="+cat_id;
	});
    $('#return_index').on('click',function(){
		location.href = domain_host+"?g=Wap&c=Bbs&a=web_index&village_id="+{pigcms{$village_id};
    });
$(window).scroll(function(){
	if($(window).scrollTop() == $(document).height() - $(window).height()){
		$('#mais').remove();
		var jia	=	'';
    	jia	+=	'<div id="jia" class="text-center m-t m-b">正在加载</div>';
    	$('#newBbsAricleList').append(jia);
		if($('#is_null').length < 1){
			list(false);
		}else{
			$('#jia').remove();
		}
	}
});
function	list(is){
	a.busy();
	$.ajax({
		type : "post",
		url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricle_json',
		dataType : "json",
		data:{
			village_id : '{pigcms{$village_id}',
			cat_id : cat_id,
			page	:	page
		},
		async:is,
		success : function(result){
			var list 	=	'';
			var newBbsAricleList	=	result.result.newBbsAricleList;
			var img	=	result.result.img;
			var newBbsAricleListLeng	=	newBbsAricleList.length;
			if(newBbsAricleListLeng){
				page++;
				for(var y=0;y<newBbsAricleListLeng;y++){
					list	+=	'<div>';
					list	+=	'<div class="padding-tb5 bg-gray"></div>';
					list	+=	'<a href="'+wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_details&aricle_id='+newBbsAricleList[y].aricle_id+'&village_id='+village_id+'&cat_id='+cat_id+'&status=2">';
					list	+=	'<div class="padding-lr5 padding-tb5 b-b clearfix">';
					list	+=	'	<div class="col-xs-2 m-l-n" style="width:65px;"><img class="avatar" src="'+newBbsAricleList[y].uid.avatar+'" /></div>';
					list	+=	'	<div class="col-xs-6 no-padder m-l-n-sm">';
					list	+=	'		<div class="text-sm">'+newBbsAricleList[y].uid.nickname+'</div>';
					list	+=	'		<div class="text-xs">'+newBbsAricleList[y].update_time+'</div>';
					list	+=	'	</div>';
					list	+=	'	<div class="col-xs-3 pull-right text-right no-padder m-l">';
					list	+=	'		<div class="text-sm">'+newBbsAricleList[y].cat_id.cat_name+'</div>';
					list	+=	'		<div class="text-xs color-green">'+newBbsAricleList[y].exp_time+'</div>';
					list	+=	'	</div>';
					list	+=	'</div>';
					list	+=	'<div class="padding-lr5 clearfix">';
					list	+=	'	<div class="color-black1 margin-top3">'+newBbsAricleList[y].aricle_title+'</div>';
					if(newBbsAricleList[y].aricle_img){
						list	+=	'	<div class="col-xs-9 col-sm-9 text-xs no-padder color-black2" style="height:50px;overflow: hidden;">'+newBbsAricleList[y].aricle_content+'</div>';
						list	+=	'	<div class="col-xs-3 col-sm-3 pull-right m-r-n"><img style="width:60px;height:60px;" src="'+newBbsAricleList[y].aricle_img+'" /></div>';
					}else{
						list	+=	'	<div class="col-xs-11 col-sm-11 text-xs no-padder color-black2" style="height:50px;overflow: hidden;">'+newBbsAricleList[y].aricle_content+'</div>';
					}
					list	+=	'</div></a>';
					list	+=	'<div class="text-sm text-center b-a m-t-sm wrapper-sm">';
					if(newBbsAricleList[y].zan){
						list	+=	'<div class="col-xs-6 b-r color-orange text-base"><img class="w-15" src="'+img.aricle_praise_click+'" />&nbsp;&nbsp;已赞&nbsp;&nbsp;(<span id="zan_'+y+'">'+newBbsAricleList[y].aricle_praise_num+'</span>)</div>';
					}else{
						list	+=	'	<div id="sZan_'+y+'"><div onclick="zan('+newBbsAricleList[y].aricle_id+','+y+',\''+img.aricle_praise_click+'\')" class="col-xs-6 b-r text-base"><img class="w-15" src="'+img.aricle_praise_default+'" />&nbsp;&nbsp;赞&nbsp;&nbsp;(<span id="zan_'+y+'">'+newBbsAricleList[y].aricle_praise_num+'</span>)</div></div>';
					}
					list	+=	'<a href="'+wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_details&aricle_id='+newBbsAricleList[y].aricle_id+'&village_id='+village_id+'&cat_id='+cat_id+'&status=2">';
					list	+=	'	<div class="col-xs-6 b-l text-base"><img class="w-15" src="'+img.aricle_comment_default+'" />&nbsp;&nbsp;评论&nbsp;&nbsp;('+newBbsAricleList[y].aricle_comment_num+')</div></a>';
					list	+=	'	<div class="clearfix"></div>';
					list	+=	'</div>';
					list	+=	'</div>';
				}
				if(newBbsAricleListLeng <= 9){
					list	+=	'<div id="mais" class="text-center m-t m-b text-xs color-black2">没有文章了，点击右上角添加吧<p>点击({pigcms{$index})可以返回顶部</p></div>';
				}else{
					list	+=	'<div id="mais" class="text-center m-t m-b">上拉会有更多文章哦<p class="text-xs color-black2">点击({pigcms{$index})可以返回顶部</p></div>';
				}
			}else{
				list	+=	'<div id="is_null" class="text-center m-t m-b text-xs color-black2">没有文章了，点击右上角添加吧<p>点击({pigcms{$index})可以返回顶部</p></div>';
			}
			$('#jia').remove();
			$('#newBbsAricleList').append(list);
			a.busy(0);
		},
		error:function(){
			a.msg('没有更多内容');
			a.busy(0);
		}
	});
}
function zan(x,y,z){
	if(user_session	== 0){
		if(is_app_browser){
			location.href	=	wx_host+domain_host+'?g=Wap&c=Bbs&a=login&village_id='+village_id;
		}else{
			var location_url	=	'/'+domain_host+'?g=Wap&c=Login&a=index';
			layer.open({content:'请先进行登录！',btn: ['确定'],end:function(){location.href=location_url;}});
		}
	}else{
		var zan_number	=	'';
	    var dianzan	=	$('#zan_'+y).text();
	    dianzan	=	parseInt(dianzan)+1;
	    zan_number	+=	'<div class="col-xs-6 b-r color-orange text-base"><img class="w-15" src="'+z+'" />&nbsp;&nbsp;已赞&nbsp;&nbsp;(<span id="zan_'+y+'">'+dianzan+'</span>)</div>';
		$('#sZan_'+y).html(zan_number);
		$.ajax({
			type : "post",
			url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_zan',
			dataType : "json",
			data:{
				village_id : village_id,
				aricle_id : x
			},
			async:false,
			success : function(result){
				a.msg('点赞成功');
			},
			error:function(){
				a.msg('点赞失败');
			}
		})
	}
}
</script>
<include file="web_footer"/>