<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>{pigcms{$tieInfo.title}-{pigcms{$config.site_name}</title>
	<!-- UC默认竖屏 ，UC强制全屏 -->
	<meta name="full-screen" content="yes">
	<meta name="browsermode" content="application">
	<!-- QQ强制竖屏 QQ强制全屏 -->
	<meta name="x5-orientation" content="portrait">
	<meta name="x5-fullscreen" content="true">
	<meta name="x5-page-mode" content="app">
	<meta name="keywords" content="百姓生活门户">
	<meta name="description" content="本站是{pigcms{$config.site_name}">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/pageScroll.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/comment-mb.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/tieba-mb.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/news-scroll5.css">

	<style type="text/css">
		.foot_link { display:node!important;}
		#pageNavigation { display:none;}
		.noMore { list-style:none; border-top:1px solid #eaebec; background-color:#eee!important; padding:20px 0; text-align:center; color:#666;}
		#wrapper { position:static!important;}
		#listEmpty { display:none!important;}
	</style>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>

	<!--必须在现有的script外-->
	<script src="{pigcms{$static_path}portal/js/share.js"></script>
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
       	 	<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
       	 	<a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico">我的</a>
	        <div class="type" id="nav_ico">导航</div>
	        <!-- <span id="ipageTitle" style="">{pigcms{$site_title}</span> -->
	        <span id="ipageTitle" style="">贴吧</span>
	        <include file="Portal:top_nav"/>
	    </div>
		<div class="p_main wrapper" id="window_page" style="bottom:40px;">
			<div class="posts" id="resizeIMG">
				<div id="wrapper">
					<div class="title">
						<if condition="$tieInfo.is_top eq 1"><span class="d">置顶</span></if>
						<if condition="$tieInfo.is_essence eq 1"><span class="j">精华</span></if>
						{pigcms{$tieInfo.title}
						<div class="info clearfix">
							<div class="right">
								{pigcms{$tieInfo.pageviews}阅读
								<s class="line"></s>
								{pigcms{$tieInfo.reply_sum}回帖
							</div>
							<if condition="$tieInfo.plate_name neq ''">
								<div class="left">
									<a href="{pigcms{:U('Portal/tieba',array('plate_id'=>$tieInfo['plate_id']))}" class="cat display2258">{pigcms{$tieInfo.plate_name}</a>
								</div>
							</if>
							
						</div>
					</div>
					<script type="text/javascript" src="{pigcms{$static_public}ckplayer/ckplayer.js"></script>
					<div class="Oposter" id="louzhuNode">
						<div class="user_info">
							<div class="user_head">
								<img src="{pigcms{$tieInfo.avatar}" height="110" width="110" style="height: 110px; width: 110px;"></div>
							<ul>
								<li>
									<span class="uName">{pigcms{$tieInfo.nickname}</span>
									<span class="grade i_1" title="初级">1</span>
									<if condition="$tieInfo['plate_admin_status']"><span class="bazhu display1">版主</span></if>
								</li>
								<li>{pigcms{$tieInfo.add_time|date="m-d H:i:s",###}
                                    <if condition="$plateUserUid eq 1">
                                        <a href="javascript:void(0);" onclick="tie_del({pigcms{$tieInfo.tie_id})" style="padding: 3px 10px;border-radius:3px;color: #999;" class="blue">删除</a>
                                    </if>
                                </li>

							</ul>
							<div class="op">楼主</div>
						</div>
						<div class="con" id="louzhuCon">
							<!-- {pigcms{$tieInfo.content|htmlspecialchars_decode} -->

							<if condition="$tieInfo['type'] eq 1">
								<div class="video" style="width: 100%;height: 400px;"></div>
								<script type="text/javascript">
									var videoObject = {
										container: '.video',//“#”代表容器的ID，“.”或“”代表容器的class
										variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
										poster:'pic/wdm.jpg',//封面图片
										video:"{pigcms{$tieInfo.videoUrl}"
									};
									var player=new ckplayer(videoObject);
								</script>
							<else/>
								{pigcms{$tieInfo.content|htmlspecialchars_decode}
							</if>

						</div>
					</div>

					<div class="post_list">
						<div id="pagingList" class="isrevert1">
							<volist name="tieList" id="vo">
								<div class="gentie post_item">
									<div class="user_info">
										<div class="user_head"><img src="{pigcms{$vo.avatar}"></div>
										<ul>
											<li>
												<span class="uName">{pigcms{$vo.nickname}</span>
												<if condition="$vo['plate_admin_status']"><span class="bazhu">版主</span></if>
											</li>
											<li>{pigcms{$vo.add_time|date="m-d H:i:s",###}</li>
										</ul>
										<div class="reply_btn" onclick="return loadRevertReplay('{pigcms{$vo.tie_id}','{pigcms{$vo.nickname}','{pigcms{$vo.sort}');"></div>
									</div>
									<div class="con">
										<div class="replaycontent1">
											{pigcms{$vo.content|htmlspecialchars_decode}
										</div>
									</div>
									<if condition="$plateUserUid eq 1">
										<p class="manage" style="display: block;height: 30px;">
											<a href="javascript:void(0);" onclick="tie_del({pigcms{$vo.tie_id})" style="border:1px solid #f00;padding: 3px 10px;border-radius:3px;color: #f00;" class="blue">删除</a>
											<!-- <a href="#" class="blue" style="padding:0 13px;">更多操作</a> -->
										</p>
									</if>
									
								</div>
							</volist>

						</div>
						<div id="pullUp" style="display:none;">
							<span class="loader">loadding</span>
						</div>
					</div>
				</div>
			</div>
			<div class="banner"></div>
		</div>
	</div>
	<script>
		function tie_del(tie_id){
			


			 //询问框
			layer.open({
				content: '您确定要删除此条信息吗？'
				,btn: ['确定', '取消']
				,yes: function(index){

					var tie_del_url = "{pigcms{:U('tie_del')}";
					$.post(tie_del_url,{tie_id:tie_id},function(data){
						// alert(data);
						if(data.error == 1){
							alert(data.msg);
							location.reload();
						}else{
							alert(data.msg);
						}
					},'json');
				}
			});
		}
	</script>
<!-- 分享 -->
<include file="Portal:fenxiang"/>

<div class="reply_box page_srcoll" id="pageOther">
	<form action="{pigcms{:U('Portal/tieba_reply')}" method="post"  onsubmit="return checkmyfabu();">
		<input type="hidden" name="target_id" value="{pigcms{$tieInfo.tie_id}">
		<input type="hidden" name="reply_tie_id" id="reply_tie_id" value=""/>
		<div class="inner">
			<span class="title">
				回复 <span id="replyName">楼主</span>
			</span>
			<div class="return_close" id="closeReply">返回</div>
			<div class="cmt_txt2" id="cmt_txt" placeholder="想跟他说点什么~" style=" margin-bottom:10px; height:160px; overflow:auto; -webkit-overflow-scrolling:touch; -webkit-user-select:auto;" contenteditable="true"></div>
			<textarea rows="15" cols="80" id="fabu_content" name="content" style="display:none"></textarea>
			
			<div class="reply_tabs" id="reply_tabs">
				<div class="fill_content">
					<div class="reply_tabs" id="reply_tabs">
						<div class="tab-cont" style="display:block;">
							<div class="imgcon">
								<div class="my_prop_imgitem_node clearfix">
									<div class="upimgFileBtnNode">
										<img src="{pigcms{$static_path}portal/images/upimg.png" id="upimgFileBtn" class="upimgFileBtn imgview" alt="" style="height: 78px;">
										<input type="file" id="imgUploadFile" onchange="imgUpload()" style="display: none;" name="imgFile" value="选择文件上传"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="submit" class="rsubmit" value="发表"></div>
	</form>
</div>


<div class="footFixed">
	<div class="reply_hd clearfix display1" id="reply_hd">
		<ul>
			<li> <span class="share" id="share2015">分享</span> </li>
			<li> <span class="num" id="show_total_revert1">{pigcms{$tieInfo.reply_sum}</span> </li>
			<!-- <li id="reply_zan"> <span class="zan">{pigcms{$tieInfo.reply_sum}</span> </li> -->
			<li> <a href="javascript:void(0);" id="openReply" class="btn replay_louzhu">回楼主</a> </li>
		</ul>
	</div>
</div>

<script type='text/javascript'>
										
	$("#upimgFileBtn").click(function(){
		$("#imgUploadFile").click();
	})

	function imgUpload(){
		var len=$('.my_prop_imgitem_node>.my_prop_imgitem').length;
		if(len<=7){
			$.ajaxFileUpload({
				url:"{pigcms{:U('Portal/ajax_upload_file')}",
				secureuri:false,
				fileElementId:'imgUploadFile',
				dataType: 'json',
				success: function (data) {
					if(data.error == 2){
						$(".my_prop_imgitem_node").append('<div class="my_prop_imgitem"> <div class="imgviewNode"><b></b> <img src="'+data.url+'" class="imgview" style="height: 78px;"><input type="hidden" name="imgList[]" value="'+data.url+'" /></div></div>');
					}else{
						alert(data.msg);
					}
				}
			}); 
		}else{
			alert('最多只能上传8张图片');
		}
	}
	// 删除照片按钮点击
	$('.my_prop_imgitem_node.clearfix').off('click','.imgviewNode b').on('click','.imgviewNode b',function(e){
		$(this).parents('.my_prop_imgitem').remove();
	});


	function checkmyfabu(){
		var uid  = "{pigcms{$user_session['uid']}";
		if(!uid){
			layer.open({
				content: '请先登录'
				,skin: 'msg'
				,time: 2  
			});
			return false;
		}

		if(!$("#cmt_txt").html()){
			layer.open({
				content: '内容不可以为空'
				,skin: 'msg'
				,time: 2  
			});
			return false;
		}
		if($("#reply_tie_id").val()){
			$("#fabu_content").html('回复&nbsp;'+$("#replyName").text()+$("#cmt_txt").html());
		}else{
			$("#fabu_content").html($("#cmt_txt").html());
		}
	}
</script>
<script>
	$('#closeReply').click(function(e){
		$("#pageOther").removeClass('page-current');
	});
	$("#openReply").click(function(){
		$("#pageOther").addClass('page-current');
		$("#replyName").html('楼主');
		$("#reply_tie_id").val('');
		$("#fabu_content").html('');
		$("#cmt_txt").html('');
	})

	function loadRevertReplay(tie_id,nickname,sort){
		$("#pageOther").addClass('page-current');

		if(nickname){
			$("#replyName").html(nickname+'&nbsp; '+sort+'楼:');
    	}else{
    		$("#replyName").html(sort+' 楼 :');
    	}
    	$("#fabu_content").html('');
		$("#cmt_txt").html('');
		$("#reply_tie_id").val(tie_id);
	}


	
</script>

<script>
	(function($){
		$('#share2015').share2015();
	})(jQuery);
</script>
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/tieba_detail',array('tie_id'=>$_GET['tie_id']))}",
		"tTitle": "贴吧 - {pigcms{$tieInfo.title}",
		"tContent": "{pigcms{$tieInfo.title}"
	};
</script>
</body>
</html>