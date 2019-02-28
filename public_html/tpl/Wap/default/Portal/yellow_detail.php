<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>{pigcms{$detail.title}</title> 
	<meta name="keywords" content="便民黄页栏目关键词">
	<meta name="description" content="便民黄页栏目介绍">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/bm-mb.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/pageScroll.css">
	<link href="{pigcms{$static_path}portal/css/comment-mb.css" rel="stylesheet">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script src="{pigcms{$static_path}portal/js/wap_common_2015.js"></script>
	<!--必须在现有的script外-->
	<script src="{pigcms{$static_path}portal/js/share.js"></script>
	<link rel="stylesheet" href="{pigcms{$static_path}portal/css/share_style0_32.css">
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script type="text/javascript" charset="utf-8" src="{pigcms{$static_path}portal/saved_resource"></script>
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
       	 	<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
	        <a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico">我的</a>
	        <div class="type" id="nav_ico">导航</div>
	        <span id="ipageTitle" style="">黄页</span>
	        <span id="ipageTitle" style="">{pigcms{$site_title}</span>
	        <include file="Portal:top_nav"/>
	    </div>
	
		<div class="bottom_fixed">
			<ul class="clearfix">
				<li>
					<a href="tel:{pigcms{$detail.tel}" class="tel">
						<s class="s"></s>
						打电话
					</a>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="return showWeixin();" class="weixin">
						<s class="s"></s>
						加微信
					</a>
				</li>
			</ul>
		</div>
		<input type="hidden" id="yellow_detail_id" value="{pigcms{$detail.id}">
		<div class="p_main" style="bottom:50px;" id="p_main">
			<div class="topbanner">
				<img class="img" src="{pigcms{$detail.logo}" style="width: 100%; height: 147px;" alt="">

				<div class="title">{pigcms{$detail.title}</div>
				<div class="po">
					<span class="sp">登记：{pigcms{$detail.dateline|date="Y/m/d",###}</span>
					<span class="sp">浏览数：{pigcms{$detail.PV}</span>
				</div>
			</div>
			<div class="company_info2">
				<dl>
					<dt>
						<img src="{pigcms{$detail.logo}" ></dt>
					<dd>
						<h3>
							{pigcms{$detail.title}
						</h3>
					</dd>
				</dl>
				<div class="tel_add">
					<ul>
						<li>
							<a href="tel:{pigcms{$detail.tel}" class="tel">
								<s class="s"></s>
								<p class="t">
									电话 <em>(点击拨打)</em>
								</p>
								<p>{pigcms{$detail.tel}</p>
							</a>
						</li>
						<li>
							<a href="javascript:void(0);" onclick="return showAddIframe();" class="address">
								<s class="s"></s>
								<p class="t">
									地址 :
								</p>
								<p>{pigcms{$detail.address}</p>
							</a>
						</li>
					</ul>
				</div>
				<!-- <a class="jiache" id="jiache" href=""></a>

				<div class="share">
					<a href="javascript:void(0);" id="share2015">分享</a>
				</div> -->
			</div>
			<div class="slide_tabs" id="wrapper2">
				<ul id="scroller2" style="width: 580px; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
					<li class="select">
						<a href="javascript:;">服务内容</a>
					</li>
					<li>
						<a href="javascript:;">{pigcms{$custome_info.title1}</a>
					</li>
					<li>
						<a href="javascript:;">{pigcms{$custome_info.title2}</a>
					</li>
					<li>
						<a href="javascript:;">{pigcms{$custome_info.title3}</a>
					</li>
					<li>
						<a href="javascript:;">{pigcms{$custome_info.title4}</a>
					</li>
					<li>
						<a href="javascript:;">{pigcms{$custome_info.title5}</a>
					</li>
				
				</ul>
				<div class="more" id="iscrollto">
					<span></span>
				</div>
			</div>
			<div class="wen_all tab-cont" style="display: block;">
				{pigcms{$detail.service|htmlspecialchars_decode}
			</div>
			<div class="wen_all tab-cont" style="display: none;">
				{pigcms{$custome_info.msg1|htmlspecialchars_decode}
			</div>
			<div class="wen_all tab-cont" style="display: none;">
				{pigcms{$custome_info.msg2|htmlspecialchars_decode}
			</div>
			<div class="wen_all tab-cont" style="display: none;">
				{pigcms{$custome_info.msg3|htmlspecialchars_decode}
			</div>
			<div class="wen_all tab-cont" style="display: none;">
				{pigcms{$custome_info.msg4|htmlspecialchars_decode}
			</div>
			<div class="wen_all tab-cont" style="display: none;">
				{pigcms{$custome_info.msg5|htmlspecialchars_decode}
			</div>
			<!-- <div class="d_more">
				详细信息
				<em></em>
			</div> -->
		
			<a href="javascript:void(0);"  onclick="return showWeixin();" class="getBMerweima">获取商家页面二维码</a>

			<div class="mod_2017">
				<div class="hd clearfix">
					<a href="#" class="sys_openReply" id="openReply">发表评论</a>
					<span class="tit">网友评论</span>
					<span class="ComentNum" id="show_total_revert">{pigcms{$recomment_list|count}</span>
				</div>

				<div class="bd" style="padding-bottom:10px;">
					<div id="showcomment">
						<div id="total_revert" data-num="4">
							<if condition="$recomment_list">
								<volist name="recomment_list" key="k" id="vo">
									<div class="comment_item">
										<div class="comment_face">
											<if condition="$vo['avatar'] neq ''">
												<img src="{pigcms{$vo.avatar}" alt="">
											<else/>
												<img src="{pigcms{$static_path}portal/images/user_small.gif" />
											</if>
										</div>
										<div class="comment_box">
											<div class="comment_user clearfix">
												<span class="userName" data-pnum="[{pigcms{$k}楼]">{pigcms{$vo.nickname}</span>
												<p class="date">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</p>
											</div>
											<div class="comment_content">{pigcms{$vo.msg}</div>
										</div>
									</div>
								</volist>
							<else/>
								<div style="text-align: center; padding: 15px; font-size: 18px;">暂无评论</div>
							</if>
							
							
						</div>
					</div>
				</div>
			</div>
	</div>

	<script src="{pigcms{$static_path}portal/js/wap_common.js"></script>

</div>


<div class="reply_box page_srcoll" id="pageOther">
	<div class="inner">
		<span class="title">
			<span id="replyName">发表评论</span>
		</span>
		<div class="return_close" id="closeReply">返回</div>
		<div class="cmt_txt2" id="cmt_txt" placeholder="想说点什么~" contenteditable="true"></div>
		<input type="submit" class="rsubmit" onclick="fabiao()" value="发表"/>
	</div>
</div>




<script src="{pigcms{$static_path}portal/js/jquery.form.js"></script>
<script src="{pigcms{$static_path}portal/js/scrollHe.js"></script>
<script src="{pigcms{$static_path}portal/js/bootstrap.min.js"></script>
<script src="{pigcms{$static_path}portal/js/cropper.min.js"></script>
<script src="{pigcms{$static_path}portal/js/wap_upimgOne.js"></script>
<script src="{pigcms{$static_path}portal/js/emotData.js"></script>
<script src="{pigcms{$static_path}portal/js/wap_comments_2017.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/iscroll-probe.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/api"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/getscript"></script>
<script type="text/javascript" src="{pigcms{$static_path}portal/js/convertor.js"></script>
<script type="text/javascript">
	$('#scroller2').css('width',(90*$('#scroller2').find('li').length)+40+'px'); 
	var myScroll = new IScroll('#wrapper2', {
		scrollX: true,
		scrollY: false,
		click:true,
		keyBindings: true
	});

	$('#iscrollto').click(function(){
		myScroll.scrollBy(-100,0,500)
	});

	//tabs切换
	var ind_a = 0;
	var tab_cont = $("#p_main .tab-cont");
	$("#scroller2 li").bind("click",function(){
		ind_a = $(this).index();
		var tabH = $('#p_main .tab-cont').eq(ind_a).height();
		$(this).addClass("select").siblings().removeClass("select");
		tab_cont.eq(ind_a).show().siblings(".tab-cont").hide();
		if(tabH == 300){
			$(".d_more").removeClass("current"); 
		}else{
			$(".d_more").addClass("current");
		}
	});

	function showWeixin(){
		layer.open({
			content: '<img src="{pigcms{$detail.qrcode}"/>'
			,btn: ['关闭']
		});
	}

	function fabiao(){
		var uid = "{pigcms{$user_session['uid']}";
		if(!uid){
			layer.open({
                content: '请先登录'
                ,btn: ['去登录']
                ,yes: function(index){
                    location.href = "{pigcms{:U('Login/index')}";
                }
            });
			return false;
		}
		var msg = $('#cmt_txt').text();
    	if(msg == ''){
    		alert('请输入要评论的内容');
    		return false;
    	}
    	var yellow_detail_id = $('#yellow_detail_id').val();
    	if(!yellow_detail_id){
    		alert('数据异常');
    		return false;
    	}
    	$.post("{pigcms{:U('Portal/release_comment')}",{'yellow_detail_id':yellow_detail_id,'msg':msg},function(response){
    		if(response.code>0){
    			layer.open({
					content: response.msg
					,btn: ['确定']
				});
    		}else{
    			layer.open({
					content: response.msg
					,btn: ['确定']
					,yes: function(index){
				      location.reload();
				      layer.close(index);
				    }
				});
    		}
    	},'json');
	}


</script>
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/yellow_detail',array('id'=>$_GET['id']))}",
		"tTitle": "黄页 - {pigcms{$site_title}",
		"tContent": "{pigcms{$detail.title}"
	};
</script>
</body>
</html>