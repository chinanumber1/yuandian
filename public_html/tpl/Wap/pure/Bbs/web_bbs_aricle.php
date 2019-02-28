<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>详情</title>
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
		<header class="pageSliderHide"><div id="backBtn"></div>{pigcms{$index}<div id="plus"><img src="{pigcms{$static_path}images/new_my/recharge.png"></div></header>
		<div id="container" style="top:50px;">
			<div id="scroller" class="village_my">
				<div id="scroller-pullDown">
					<span id="down-icon" class="icon-double-angle-down pull-down-icon"></span>
					<span id="pullDown-msg" class="pull-down-msg">下拉刷新</span>
				</div>
				<ul class="bbs-list">
					<if condition='$bbs_list["newBbsAricleList"]'>
					<volist name='bbs_list["newBbsAricleList"]' id='bbs'>
						<li onclick="location.href='{pigcms{:U('web_bbs_aricele_details',array('aricle_id'=>$bbs['aricle_id'],'village_id'=>$_GET['village_id'],'cat_id'=>$bbs['cat_id']['cat_id'],'status'=>1))}'">
							<div class="row bbs-my-top">
								<div class="col-20 bbs-my-avather">
									<img src="{pigcms{$bbs['uid']['avatar']}" width="100px" height="100px" />
								</div>
								<div class="col-80 bbs-top-info">
									<p>{pigcms{$bbs['uid']['nickname']}</p>
									<p>{pigcms{$bbs['address']['city']}-{pigcms{$bbs['address']['district']}<span>{pigcms{$bbs['update_time']}</span></p>
								</div>
								<div class="col-100">
									<div class="bbs-desc">
										{pigcms{$bbs['aricle_title']}
									</div>
								</div>
								
								<if condition='$bbs["aricle_img"]'>
									<div class="col-100 ">
										<div class="bbs-image">
											<ul>
												<volist name="bbs['aricle_img']" id="img">
													<li><img src="{pigcms{$img}" /></li>
												</volist>
											</ul>
										</div>
									</div>
								</if>
								<div class="col-100">
									<div class="bbs-foot">
										<p>来自：<span>{pigcms{$bbs['cat_id']['cat_name']}</span></p>
									</div>
								</div>
								<div class="col-100">
									<div class="bbs-foot2">
										<if condition="$bbs['mezan']==1">
                                        	<p class="bbs-list-zan" style="color:#06c1ae" data-article-id="{pigcms{$bbs.aricle_id}"><img src="{pigcms{$static_path}images/zan_hover.png" />&nbsp;&nbsp;{pigcms{$bbs['aricle_praise_num']}</p>
                                        <else />
                                        	<p class="bbs-list-zan" data-article-id="{pigcms{$bbs.aricle_id}"><img src="{pigcms{$static_path}images/zan.png" />&nbsp;&nbsp;{pigcms{$bbs['aricle_praise_num']}</p>
                                        </if>
										<p class="bbs-list-pinlun"><img src="{pigcms{$static_path}images/pinlun.png" />&nbsp;&nbsp;{pigcms{$bbs['aricle_comment_num']}</p>
									</div>
								</div>
							</div>
						</li>
					</volist>
				<else />
					<li>
						<div class="row bbs-my-top">
							<div class="col-100">
								<div class="bbs-desc" style="text-align:center">
									暂无信息
								</div>
							</div>
						</div>
					</li>
				</if>	
				</ul>
				<if condition='$bbs_list["newBbsAricleList"]'>
					<div id="scroller-pullUp">
						<span id="up-icon" class="icon-double-angle-up pull-up-icon"></span>
						<span id="pullUp-msg" class="pull-up-msg">上拉刷新</span>
					</div>
				</if>
			</div>
		</div>
		<include file="House:footer"/>
		{pigcms{$shareScript}
		
		<script>
			$('.bbs-list-zan').click(function(e){
					var web_bbs_aricele_zan_url = "{pigcms{:U('web_bbs_aricele_zan')}&village_id={pigcms{$_GET['village_id']}";
					var article_id = $(this).data('article-id');
					$.post(web_bbs_aricele_zan_url,{'aricle_id':article_id},function(data){
						if(data.errorCode){
							alert(data.errorMsg)
						}else{
							
							if(data.result){
								alert('点赞成功！')
								window.location.reload();
							}else{
								alert('已点赞！')
							}
						}
					},'json')
				})
		</script>
		
		<script type="text/javascript">
			function loaded() {
				$('.bbs-list').css('min-height',$('#container').height());
				var myScroll,
					upIcon = $("#up-icon"),
					downIcon = $("#down-icon");

				myScroll = new IScroll('#container', {
					probeType: 3,
					mouseWheel: true,
					click:iScrollClick(),
					scrollbars:false,
					useTransform:false,
					useTransition:false
				});

				myScroll.on("scroll", function() {
					var y = this.y,
						maxY = this.maxScrollY - y,
						downHasClass = downIcon.hasClass("reverse_icon"),
						upHasClass = upIcon.hasClass("reverse_icon");

					if(y >= 40) {
						!downHasClass && downIcon.addClass("reverse_icon");
						return "";
					} else if(y < 40 && y > 0) {
						downHasClass && downIcon.removeClass("reverse_icon");
						return "";
					}

					if(maxY >= 40) {
						!upHasClass && upIcon.addClass("reverse_icon");
						return "";
					} else if(maxY < 40 && maxY >= 0) {
						upHasClass && upIcon.removeClass("reverse_icon");
						return "";
					}
				});

				myScroll.on("slideDown", function() {
					if(this.y > 40) {
						location.reload();
					}
				});
				
				
				var web_index_json_url = "{pigcms{:U('web_bbs_aricle_json')}&village_id={pigcms{$_GET['village_id']}";
				var village_id = "{pigcms{$_GET['village_id']}";
				var static_path = "{pigcms{$static_path}";
				var page=1;

				myScroll.on("slideUp", function() {
					if(this.maxScrollY - this.y > 40) {
						page++;
						$.post(web_index_json_url,{'page':page,'cat_id':"{pigcms{$_GET['cat_id']}"},function(data){
							if(!data.errorCode){
								var shtml = ''; 
								var newBbsAricleList = data['result']['newBbsAricleList'];
								for(var i in newBbsAricleList){
									shtml += '<li onclick="location.href=\'/wap.php?g=Wap&c=Bbs&a=web_bbs_aricele_details&aricle_id='+newBbsAricleList[i]['aricle_id']+'&village_id='+village_id+'&cat_id='+newBbsAricleList[i]['cat_id']['cat_id']+'&status=1\'">';
									shtml += '<div class="row bbs-my-top">';
									shtml += '<div class="col-20 bbs-my-avather">';
									shtml += '<img src="'+newBbsAricleList[i]['uid']['avatar']+'" width="100px" height="100px">';
									shtml += '</div>';
									shtml += '<div class="col-80 bbs-top-info">';
									shtml += '<p>'+newBbsAricleList[i]['uid']['nickname']+'</p>';
									
									if(newBbsAricleList[i]['address']){
										shtml += '<p>'+newBbsAricleList[i]['address']['city']+'-'+newBbsAricleList[i]['address']['district']+'<span>'+newBbsAricleList[i]['update_time']+'</span></p>';
									}
									
									shtml += '</div>';
									shtml += '<div class="col-100">';
									shtml += '<div class="bbs-desc">';
									shtml += '<p>'+newBbsAricleList[i]['aricle_content']+'</p>';
									shtml += '</div>';
									shtml += '</div>';
									
									if(newBbsAricleList[i]['aricle_img']){
										var aricle_img_arr = newBbsAricleList[i]['aricle_img'];
										shtml += '<div class="col-100">';
										shtml += '<div class="bbs-image">';
										shtml += '<ul>';
										for(var j in aricle_img_arr){
											shtml += '<li><img src="'+aricle_img_arr[j]+'"></li>';
										}									
										shtml += '</ul></div>';
										shtml += '</div>';
									}
									shtml += '<div class="col-100">';
									shtml += '<div class="bbs-foot">';
									
									if(newBbsAricleList[i]['cat_name']){
										shtml += '<p>来自：<span>'+newBbsAricleList[i]['cat_name']+'</span></p>';
									}
									
									shtml += '</div>';
									shtml += '</div>';
									shtml += '<div class="col-100">';
									shtml += '<div class="bbs-foot2">';
									shtml += '<p class="bbs-list-zan"><img src="'+static_path+'images/zan.png">&nbsp;&nbsp;'+newBbsAricleList[i]['zan']+'</p>';
									shtml += '<p class="bbs-list-pinlun"><img src="'+static_path+'images/pinlun.png">&nbsp;&nbsp;'+newBbsAricleList[i]['aricle_comment_num']+'</p>';
									shtml += '</div></div></div></li>';
								}
								$('.bbs-list').append(shtml);
								myScroll.refresh();
							}
						},'json')
					}
				});
			}
			$('#backBtn').click(function(){
				if(motify.checkApp() && motify.getLifeAppVersion() >= 200){
					if(motify.checkAndroid()){
						window.lifepasslogin.webViewGoBack();
					}else{
						$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
					}
				}else{
					window.history.go(-1);
				}
			});
			$('#plus').click(function(){
				location.href = "{pigcms{:U('web_bbs_aricele_wite',array('village_id'=>$village_id,'cat_id'=>$cat_id))}"
			});
		</script>
</html>
</body>

</html>