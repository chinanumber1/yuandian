<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>{pigcms{$bbs_index}--论坛</title>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" href="{pigcms{$static_path}css/bbs.css" />
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>	
	</head>

	<body onload="loaded()">
		<div id="container" style="overflow-x:hidden;overflow-y:auto;">
			<div id="wrapper" style="overflow-x:hidden;overflow-y:auto;">
				<div id="scroller" class="village_my">
					<div id="scroller-pullDown">
						<span id="down-icon" class="icon-double-angle-down pull-down-icon"></span>
						<span id="pullDown-msg" class="pull-down-msg">下拉刷新</span>
					</div>
					<div class="row bbs-my-top">
						<div class="col-20 bbs-my-avather">
							<img src="{pigcms{$detail['aDetails']['uid']['avatar']}" width="100px" height="100px" />
						</div>
						<div class="col-80 bbs-top-info">
							<p>{pigcms{$detail['aDetails']['uid']['nickname']}</p>
							<p>{pigcms{$detail['aDetails']['address']['city']}-{pigcms{$detail['aDetails']['address']['district']}&nbsp;&nbsp;&nbsp;&nbsp;<span>{pigcms{$detail['aDetails']['update_time']}</span></p>
						</div>
						

						<if condition='$detail["aDetails"]["type"] eq 1'>
							<div class="activity-info activity-info-2">
								<p>已报名：{pigcms{$detail['aDetails']['activity_apply_num']}/{pigcms{$detail['aDetails']['num']}</p>
								<?php if($detail['aDetails']['activity_apply_num'] >= $detail['aDetails']['num']){?>
									<p><span style="color:#fff;background: #b4b4b4">报名已满</span></p>
								<?php }elseif(time() > $detail['aDetails']['close_time']){?>
									<p><span style="color:#fff;background: #b4b4b4">报名已截止</span></p>
								<?php }else{ ?>
									<p><?php if(!$detail["is_bbs_activity_apply"]){?><span id="activity_apply">未报名</span><?php }else{ ?><span style="color:#fff;background: #b4b4b4">已报名</span><?php } ?></p>
								<?php } ?>
							</div>
						</if>
						
						
						<div class="col-100">
							<div class="bbs-list-desc">
								<p>{pigcms{$detail['aDetails']['aricle_title']}</p>
							</div>
						</div>
						
						<if condition='$detail["aDetails"]["aricle_img"]'>
							<div class="col-100 ">
								<div class="bbs-image">
									<ul>
									<volist name='detail["aDetails"]["aricle_img"]' id='img'>
										<li><img src="{pigcms{$img}" /></li>
									</volist>
									</ul>
								</div>
							</div>
						</if>
						<div class="col-100">
							<div class="bbs-foot">
								<p>来自：<span>{pigcms{$index}</span></p>
							</div>
						</div>
						<div class="col-100">
							<div class="bbs-foot2">
								<p><span class="zan" data-article-id="{pigcms{$detail['aDetails']['aricle_id']}" <if condition="$mezan==1">style="background:url('{pigcms{$static_path}/images/zan_hover.png') no-repeat center left 5px; color:#06c1ae"</if>>{pigcms{$detail['aDetails']['aricle_praise_num']}</span><span class="pinlun">{pigcms{$detail['aDetails']['aricle_comment_num']}</span></p>
							</div>
						</div>
						<style>
							.bbs-foot2{padding-bottom: .5rem}
						</style>
						<if condition='$detail["aZanList"]'>
							<div class="col-100 detail-user-logo">
								<div class="bbs-zan-list">
								<volist name='detail["aZanList"]' id='zan'>
									<img src="{pigcms{$zan['uid']['avatar']}" />
								</volist>
								</div>
							</div>
						</if>
						<div style="clear:both;"></div>
						<div class="col-100 detail-user-comment">
							<div class="bbs-foot bbs-detail-pl">
								<p>评论&nbsp;{pigcms{$detail['aDetails']['aricle_comment_num']}</span>
								</p>
							</div>
							<div class="bbs-list-pl">
								<if condition="$detail['aComment']">
									<ul>
									
									
									<volist name="detail['aComment']" id='comment'>
										<li data-user-id="{pigcms{$comment['uid']['uid']}" data-comment-id="{pigcms{$comment['comment_id']}">
											<div class="bbs-detail-tx">
												<img src="{pigcms{$comment['uid']['avatar']}" />
												<div class="bbs-detail-pl-l">
													<p class="nickname top_comment">{pigcms{$comment['uid']['nickname']}</p>
													<p class="bbs-detail-date">{pigcms{$comment['uid']['last_time']|date='Y-m-d H:i:s',###}</p>
												</div>
											</div>
											<p class="bbs-detail-pl-desc">{pigcms{$comment['comment_content']}</p>
											<if condition='$comment["comment_reply_list"]'>
											
												<div class="bbs-detail-hf">
													<ul>
													<volist name='comment["comment_reply_list"]' id='reply'>
														<li>
															<p><span data-uid="{pigcms{$reply['uid']['uid']}" data-comment-id="{pigcms{$comment['comment_id']}">{pigcms{$reply['uid']['nickname']}</span>
															
															<if condition='$comment["uid"] neq $reply["uid"]'>
																<i>&nbsp;回复&nbsp;</i><span>@</span><span data-uid="{pigcms{$reply['comment_fname']['uid']}" data-comment-id="{pigcms{$comment['comment_id']}">{pigcms{$reply['comment_fname']['nickname']}：</span>
															</if>
															{pigcms{$reply['comment_content']}</p>
														</li>
													</volist>
													</ul>
												</div>
											</if>
											
										</li>
									</volist>
									</ul>
								<else />
								<ul>
								<li>
											<p class="bbs-detail-pl-desc" style="margin-left: 0rem;">暂无评论。</p>
										</li></ul>
								</if>
							</div>
						</div>
                        <div class="clear"></div>
					</div>

					<!--div id="scroller-pullUp">
						<span id="up-icon" class="icon-double-angle-up pull-up-icon"></span>
						<span id="pullUp-msg" class="pull-up-msg">上拉刷新</span>
					</div-->

				</div>

				<div id="pullUp" style="bottom:-60px;">
					<img src="/static/logo.png" style="width:130px;height:40px;margin-top:10px" />
				</div>
			</div>
		</div>
		<footer class="footerMenu">
			<input type="text" id="content" placeholder="回复{pigcms{$detail['aDetails']['uid']['nickname']}" />
			<div class="fasong">发送</div>
		</footer>
<script type="text/javascript" src="{pigcms{$static_path}/layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript">
			$('#scroller').height($(window).height()-60);
			// function loaded() {
			// 	var myScroll,
			// 		upIcon = $("#up-icon"),
			// 		downIcon = $("#down-icon");
			// 	myScroll = new IScroll('#wrapper', {
			// 		probeType: 3,
			// 		mouseWheel: true
			// 	});

			// 	myScroll.on("scroll", function() {
			// 		var y = this.y,
			// 			maxY = this.maxScrollY - y,
			// 			downHasClass = downIcon.hasClass("reverse_icon"),
			// 			upHasClass = upIcon.hasClass("reverse_icon");

			// 		if(y >= 40) {
			// 			!downHasClass && downIcon.addClass("reverse_icon");
			// 			return "";
			// 		} else if(y < 40 && y > 0) {
			// 			downHasClass && downIcon.removeClass("reverse_icon");
			// 			return "";
			// 		}

			// 		if(maxY >= 40) {
			// 			!upHasClass && upIcon.addClass("reverse_icon");
			// 			return "";
			// 		} else if(maxY < 40 && maxY >= 0) {
			// 			upHasClass && upIcon.removeClass("reverse_icon");
			// 			return "";
			// 		}
			// 	});

			// 	myScroll.on("slideDown", function() {
			// 		if(this.y > 40) {
			// 			location.reload();
			// 		}
			// 	});

			// 	myScroll.on("slideUp", function() {
			// 		if(this.maxScrollY - this.y > 40) {
			// 			upIcon.removeClass("reverse_icon")
			// 		}
			// 	});
			// }
			
			var uid = 0
			var comment_fid = 0;
			var web_bbs_wite_comment_json_url = "{pigcms{:U('web_bbs_wite_comment_json')}&village_id={pigcms{$_GET['village_id']}";
			$('.fasong').click(function(){
				$.post(web_bbs_wite_comment_json_url,{'aricle_id':"{pigcms{$_GET['aricle_id']}",'comment_fid':comment_fid,'comment_content':$('#content').val(),'uid':uid},function(data){
					if(data['status'] || (data['errorCode'] == '0')){
						layer.open({title:['成功提示','background-color:#06c1ae;color:#fff;'],content:'评论成功',btn: ['确定'],end:function(){location.reload();}});
					}else{
						layer.open({title:['失败提示','background-color:red;color:#fff;'],content:data['info'],btn: ['确定'],end:function(){location.reload();}});
					}
					
				},'json')
			});
			
			$('.bbs-list-pl ul li').click(function(){
				uid = $(this).data('uid');
				comment_fid = $(this).data('comment-id');
				$('#content').attr('placeholder','回复'+$(this).find('.nickname').html())
			});
			
			// $('.top_comment').click(function(){
			// 	$('#content').attr('placeholder','回复'+$(this).find('.nickname').html())
				
			// 	uid = $(this).data('user-id');
			// 	comment_fid = $(this).data('comment-id');
			// 	$.post(web_bbs_wite_comment_json_url,{'aricle_id':"{pigcms{$_GET['aricle_id']}",'comment_fid':comment_fid,'comment_content':$('#content').val(),'uid':uid},function(data){
			// 		if(data.status == 1){
			// 			layer.open({title:['成功提示','background-color:#06c1ae;color:#fff;'],content:data.info,btn: ['确定'],end:function(){location.reload();}});
			// 		}else{
			// 			layer.open({title:['失败提示','background-color:red;color:#fff;'],content:data.info,btn: ['确定'],end:function(){location.reload();}});
			// 		}
					
			// 	},'json')
			// });
			
			
			$('.zan').click(function(){
				var web_bbs_aricele_zan_url = "{pigcms{:U('web_bbs_aricele_zan')}&village_id={pigcms{$_GET['village_id']}";
				var article_id = $(this).data('article-id');
				$.post(web_bbs_aricele_zan_url,{'aricle_id':article_id},function(data){
					if(data.errorCode){
						alert(data.errorMsg)
					}else{
						if(data.result){
							layer.open({title:['成功提示','background-color:#06c1ae;color:#fff;'],content:'点赞成功！',btn: ['确定'],end:function(){location.reload();}});
						}else{
							layer.open({title:['失败提示','background-color:red;color:#fff;'],content:'已点赞',btn: ['确定']});
						}
					}
				},'json')
			});
			
			
			$('#activity_apply').click(function(){
				var ajax_bbs_activity_url = "{pigcms{:U('ajax_bbs_activity')}&village_id={pigcms{$_GET['village_id']}";
				$.post(ajax_bbs_activity_url,{'aricle_id':"{pigcms{$_GET['aricle_id']}"},function(data){
					if(data['status']){
						layer.open({title:['成功提示','background-color:#06c1ae;color:#fff;'],content:data['msg'],btn: ['确定'],end:function(){location.reload();}});
					}else{
						layer.open({title:['失败提示','background-color:red;color:#fff;'],content:data['msg'],btn: ['确定'],end:function(){location.reload();}});
					}
				},'json')
			});
			
			$('.pinlun').click(function(){
				$('#content').trigger('focus')
			});
		</script>
</html>
</body>

</html>