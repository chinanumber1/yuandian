<include file="web_head"/>
<div class="app-header bg-white padder w-full p-t8 b-b color-black">
	<div id="cancel" class="pull-left padding-t5">取消</div>
	<div class="text-lg margin-top3 color-black3">{pigcms{$index}</div>
	<div id="comment" class="pull-right" style="margin-top:-20px;">发送</div>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header bg-grey1 text-base wrapper">
	<if condition="isset($aUid)">
		<textarea placeholder="回复{pigcms{$aUid.nickname}：" id="connet" class="recom-texa color-black2 m-t w-full no-border" rows="6"></textarea>
	<else />
		<textarea placeholder="请输入内容" id="connet" class="recom-texa color-black2 m-t w-full no-border" rows="6"></textarea>
	</if>
	<div class="clearfix"></div>
</div>
<script>
	var aricle_id	=	{pigcms{$aricle_id};
	var village_id	=	{pigcms{$village_id};
	var cat_id	=	{pigcms{$cat_id};
	var comment_id	=	{pigcms{$comment_id};
	var	wx_host	=	'{pigcms{$site_url}';
	var status	=	{pigcms{$status};
	var user_session	=	{pigcms{$userSession};
        var is_indep_house ="{pigcms{:defined('IS_INDEP_HOUSE')}";
        if(is_indep_house){
            var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
        }else{
            var domain_host = 'wap.php';
        }        
            
            
	if(user_session	== 0){
		var location_url	=	'/'+domain_host+'?g=Wap&c=Login&a=index';
		layer.open({content:'请先进行登录！',btn: ['确定'],end:function(){location.href=location_url;}});
	}
	$('#cancel').on('click',function(){
		location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_aricele_details&aricle_id="+aricle_id+"&village_id="+village_id+"&cat_id="+cat_id+"&status="+status;
    });
    $('#comment').on('click',function(){
    	if(user_session	== 0){
			var location_url	=	domain_host + '/?g=Wap&c=Login&a=index';
			layer.open({content:'请先进行登录！',btn: ['确定'],end:function(){location.href=location_url;}});
		}else{
			
			var uid = "{pigcms{$aUid['uid']}" ?  "{pigcms{$aUid['uid']}" : 0
			var	connet	=	$('#connet').val();
    		if(connet==''){
				a.msg('请输入评论');
    		}else{
    			a.busy();
				$.ajax({
					type : "post",
					url : wx_host+ domain_host +'?g=Wap&c=Bbs&a=web_bbs_wite_comment_json',
					dataType : "json",
					data:{
						village_id : village_id,
						aricle_id	:	aricle_id,
						cat_id : cat_id,
						comment_content : connet,
						comment_fid	:	comment_id,
						uid : uid
					},
					success : function(result){
						a.busy(0);
						location.href = domain_host + "?g=Wap&c=Bbs&a=web_bbs_aricele_details&aricle_id="+aricle_id+"&village_id="+village_id+"&cat_id="+cat_id+"&status="+status;
					},
					error:function(result){
						a.msg('评论失败');
					}
				});
    		}
		}
    });
</script>
<include file="web_footer"/>