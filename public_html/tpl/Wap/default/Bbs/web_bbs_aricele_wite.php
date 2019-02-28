<include file="web_head"/>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<div class="app-header bg-white padder w-full p-t8 b-b color-black">
	<div class="text-lg margin-top3 color-black3">{pigcms{$index}</div>
<if condition="!$is_app_browser">
	<div id="cancel" class="pull-right" style="margin-top:-20px;">取消</div>
</if>
	<div class="clearfix"></div>
</div>
<div class="app-content with-header bg-grey1 text-base wrapper">
	<div class="edit-list bg-white m-t b-a">
		<input id="title" placeholder="请输入标题" class="recom-texa color-black1 w-full no-border f-30" />
	</div>
	<div class="b-a m-t">
		<textarea id="connet" class="recom-texa color-black1 w-full no-border" rows="6" placeholder="最近都有什么想聊的，快来说说吧"></textarea>
	</div>
	<div class="edit-list bg-white m-t" style="width:50%;">
		<div class="pull-left b-a"><input type="tel" id="exp_time" placeholder="请输入过期天数" class="recom-texa color-black2 w-full no-border f-30" /></div>
	</div>
	<div class="pull-left m-t-xs"><img src="./tpl/Wap/default/static/bbs/img/help.gif" class="tips_img" title="过期时间"></div>
	<div class="clearfix"></div>
	<div style="text-align:center">
		<dl class="list">
			<dd>
				<dl>
					<dd class="item uploadNum" id="uploadNum">还可上传<span class="leftNum orange">8</span>张图片，已上传<span class="loadedNum orange">0</span>张(非必填)</dd>
					<dd class="item">
						<div class="upload_box">
							<ul class="upload_list m-l-n-xl clearfix" id="upload_list">
								<li class="upload_action">
									<img src="./tpl/Wap/default/static/bbs/img/xiangji.png" />
									<input type="file" accept="image/jpg,image/jpeg,image/png,image/gif" id="fileImage" name="" />
								</li>
							</ul>
						</div>
					</dd>
				</dl>
			</dd>
		</dl>
	</div>
	<div class="clearfix"></div>
</div>
<div class="app-footer bg-while b-t">
	<button id="release" class="btn user-list-btn margin-btnBottem5 bg-darkgreen color-while">确定</button>
</div>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script src="{pigcms{$static_path}classify/exif.js"></script>
<script src="{pigcms{$static_path}classify/imgUpload.js"></script>
<script>
	var cat_id	=	{pigcms{$cat_id};
	var village_id	=	{pigcms{$village_id};
	var	wx_host	=	'{pigcms{$site_url}';
	var user_session	=	{pigcms{$userSession};
        var is_indep_house ="{pigcms{:defined('IS_INDEP_HOUSE')}";
        if(is_indep_house){
            var domain_host = "{pigcms{:C('INDEP_HOUSE_URL')}";
        }else{
            var domain_host = 'wap.php';
        }


	if(user_session	== 0){
		if(is_app_browser){
			location.href	=	wx_host+domain_host+'?g=Wap&c=Bbs&a=login&village_id='+village_id;
		}else{
			var location_url	=	'/'+domain_host+'?g=Wap&c=Login&a=index';
			layer.open({content:'请先进行登录！',btn: ['确定'],end:function(){location.href=location_url;}});
		}
	}
	$('#cancel').on('click',function(){
		location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_aricle&village_id="+village_id+"&cat_id="+cat_id;
    });
    $('#release').on('click',function(){
    	if(user_session	== 0){
			var location_url	=	'/'+domain_host+'?g=Wap&c=Login&a=index';
			layer.open({content:'请先进行登录！',btn: ['确定'],end:function(){location.href=location_url;}});
		}else{
    		var inputimg = '';
    		for(var i=1;i<8;i++){
				inputimg	+=	$('#imgShow'+i+' input').val()+';';
    		}
    		var	aricle_title	=	$('#title').val();
    		var	aricle_content	=	$('#connet').val();
    		var	exp_time	=	$('#exp_time').val();
    		if(aricle_title == ''){
				a.msg('标题不能为空');
    		}else if(aricle_content == ''){
				a.msg('内容不能为空');
			}else{
				a.busy();
				$.ajax({
					type : "post",
					url : wx_host+domain_host+'?g=Wap&c=Bbs&a=web_bbs_aricele_wite_json',
					dataType : "json",
					data:{
						village_id : village_id,
						cat_id : cat_id,
						aricle_title : aricle_title,
						aricle_content : aricle_content,
						inputimg	:	inputimg,
						exp_time	:	exp_time,
					},
					success : function(result){
						if(result.errorCode == 0){
							a.busy(0);
							a.msg('发布成功');
							location.href = domain_host+"?g=Wap&c=Bbs&a=web_bbs_aricle&village_id="+village_id+"&cat_id="+cat_id;
						}else{
							a.busy(0);
							a.msg('发布失败');
						}
					},
					error:function(result){
						a.busy(0);
						a.msg('发布失败');
					}
				});
			}
		}
    });
    $(function() {
		if ($(".upload_list").length) {
	        var imgUpload = new ImgUpload({
	            fileInput: "#fileImage",
	            container: "#upload_list",
	            countNum: "#uploadNum",
				url:wx_host + domain_host + "?g=Wap&c=Bbs&a=ajaxImgUpload&ml=luntan&village_id=3",
			})
		}
	});
	$('.tips_img').on('click',function(){
		alert('过期天数适用于发起活动、二手交易等需要限制浏览时间的帖子。\n若过期后，此帖子将会显示“已过期”。');
    });
</script>
<include file="web_footer"/>