<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>新闻详情</title>
        </if>
		<meta name="description" content="{pigcms{$config.seo_description}">
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
        <link rel="stylesheet" href="{pigcms{$static_path}css/bbs.css" />
		<link href="{pigcms{$static_path}css/mp_news.css" rel="stylesheet"/>
        <script src="{pigcms{$static_path}js/iscroll_village_news.js"></script>
	</head>
		<body id="activity-detail" onload="checkReply()">
		<div class="rich_media container" style="margin-bottom:50px;background-color: #eee;" id="wrapper">
			<div class="rich_media_inner content">
				<h2 class="rich_media_title" id="activity-name">{pigcms{$now_news['title']}</h2>
				<div class="rich_media_meta_list">
					<em id="post-date" class="rich_media_meta text">{pigcms{$now_news['add_time']|date='Y-m-d H:i',###}</em> 
					<em class="rich_media_meta text"></em> 
					<a class="rich_media_meta link nickname js-no-follow js-open-follow" href="{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}" id="post-user">{pigcms{$now_village.village_name}</a>
				</div>
				<div id="page-content" class="content">
					<div id="img-content">
						<div class="rich_media_content" id="js_content">{pigcms{$now_news['content']|htmlspecialchars_decode=ENT_QUOTES}</div>
					</div>
				</div>
                <!-- start -->
                <div class="col-100 detail-user-comment">
                    <div class="bbs-foot bbs-detail-pl">
                        <p>评论&nbsp;<span id="replynum">
                                <if condition="$reply['count_reply'] eq n">
                                    <else />
                                    {pigcms{$reply['count_reply']}
                                </if>

                            </span></p>
                    </div>
                    <div class="bbs-list-pll">
                            <ul id="reply_list_ull">
                            </ul>
                    </div>
                    <div class="bbs-list-pl">
                        <if condition="$reply['reply_list']">
                            <ul id="reply_list_ul">
                                <volist name="reply['reply_list']" id='comment'>

                                    <li data-user-id="{pigcms{$comment['uid']}" data-comment-id="{pigcms{$comment['pigcms_id']}" class="replies">
                                        <div class="bbs-detail-tx">
                                            <if condition="$comment['avatar'] eq ''">
                                                <img src="{pigcms{$static_path}images/user_avatar.jpg" />
                                                <else />
                                                <img src="{pigcms{$comment['avatar']}" />
                                            </if>
                                            <div class="bbs-detail-pl-l">
                                                <p class="nickname top_comment">{pigcms{$comment['nickname']}</p>
                                                <p class="bbs-detail-date">{pigcms{$comment['add_time']}</p>
                                            </div>
                                        </div>
                                        <p class="bbs-detail-pl-desc">{pigcms{$comment['content']}</p>

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
<!--                <div class="clear"></div>-->
                <if condition="$reply['reply_list']">
                    <div class="get_more" style="text-align: center;font-size: 12px;margin-bottom: 10px;color: #eeee">上拉查看更多记录</div>
            </if>
		</div>

		<section class="foot_comment">
			<aside class="foot_commentcont">
				<div class="foot_cmt_input j_cmt_btn"><p>说说你的看法</p></div>
			</aside>
			<aside class="cmnt_wrap" style="display:none;">
				<div class="cmnt_tp">
					<span class="fl"><a href="javascript:void(0);" class="cmnt_cancel" id="j_cmnt_cancel">取消</a></span>
					<span class="fr"><a href="javascript:void(0);" class="cmnt_smt" id="j_cmnt_smt">发送</a></span>
				</div>
				<div class="cmntarea">
					<textarea id="j_cmnt_input" class="newarea" name="" placeholder="说说你的看法"></textarea>
				</div>
			</aside>
		</section>
		<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script>
			var is_login = <if condition="$user_session">1<else/>0</if>;
		</script>
		<script>
			$(function(){
				$('.rich_media_inner').css('min-height',$(window).height()-30+'px');
				$('.foot_commentcont').click(function(){
					if(is_login){
						$(this).hide();
						$('.cmnt_wrap').show();
						$('#j_cmnt_input').val('').focus();
					}else{
						if(confirm('您需要先登录才能进行评论。是否前往登录？')){
							window.location.href = "{pigcms{:U('Login/index')}";
						}
					}
				});
				$('#j_cmnt_cancel').click(function(){
					$('.cmnt_wrap').hide();
					$('.foot_commentcont').show();
				});
				var is_sending = false;
				$('#j_cmnt_smt').click(function(){
					if(is_sending){
						alert('正在发送中，请稍候');
					}
					$('#j_cmnt_input').val($.trim($('#j_cmnt_input').val()));
					if($('#j_cmnt_input').val() == ''){
						return false;
					}else{
						is_sending = true;
						$.post("{pigcms{:U('House/village_news_reply',array('news_id'=>$now_news['news_id']))}",{content:$('#j_cmnt_input').val()},function(result){
							if(result.errcode != 1){
								alert(result.errmsg);
                                $('#j_cmnt_cancel').trigger('click');
								window.location.reload();

                            }else{
								alert(result.errmsg);
                                window.location.reload();
							}
						});
					}
				});
			});
		</script>
        <script>
            function checkReply(){
                var list_num = $('.replies').length;
                if(list_num<5){
                    $('.get_more').hide();
                }
            }
            function loadMore(){
                var get_more = $('.get_more');
                if(get_more.text()=='没有更多了'){return false;} //停止加载
                var list_num = $('.replies').length;  //获取当前总条数
                var amount = 5 ; //每次点击加载条数
                var reply_num = parseInt($('#replynum').text());
                if(list_num >= reply_num){
                    get_more.text('没有更多了');
                    return false;
                }
                $.post("{pigcms{:U('House/village_news',array('village_id'=>$now_village['village_id'],'news_id'=>$now_news['news_id']))}",
                    {list_num:list_num,amount:amount},
                    function (result) {
                        if(result=='no_more') {
                            $('.get_more').text('没有更多了');
                        }else{
                            var news = '';
                            $('.get_more').text('查看更多记录');
                            $('#replynum').text(result.count_reply);
                            $.each(result.reply_list, function(i,val){
                                if(val.avatar = ' '){
                                    val.avatar = "{pigcms{$static_path}images/user_avatar.jpg";
                                }
                                news = "<li data-user-id=" + val.uid + " data-comment-id=" + val.pigcms_id + " class=\"replies\">" +
                                    "<div class=\"bbs-detail-tx\">"+ "<img src=\"" + val.avatar +" \"\/>"+"<div class=\"bbs-detail-pl-l\">"+
                                    "<p class=\"nickname top_comment\">"+ val.nickname+"</p>" +
                                    "<p class=\"bbs-detail-date\">" + val.add_time +"<\p>" +
                                    "</div></div><p class=\"bbs-detail-pl-desc\">"+val.content+"</p></li>";
                                $('#reply_list_ul').append(news);
                            });
                            reply_num = parseInt($('#replynum').text());
                            list_num = $('.replies').length;
                            if(list_num >= reply_num){
                                get_more.text('没有更多了');
                            }
                            wrapper.refresh();
                        }
                    },'json')

            }
        </script>
        <script type="text/javascript">
            var refresher = {
                init: function(parameter) {
                    var wrapper = document.getElementById(parameter.id);
                    var div = document.createElement("div");
                    div.className = "scroller";
                    wrapper.appendChild(div);
                    var scroller = wrapper.querySelector(".scroller");
                    var list = wrapper.querySelector("#" + parameter.id + " ul");
                    scroller.insertBefore(list, scroller.childNodes[0]);
                    var pullDown = document.createElement("div");
                    pullDown.className = "pullDown";
                    var loader = document.createElement("div");
                    loader.className = "loader";
                    for (var i = 0; i < 4; i++) {
                        var span = document.createElement("span");
                        loader.appendChild(span);
                    }
                    pullDown.appendChild(loader);
                    var pullDownLabel = document.createElement("div");
                    pullDownLabel.className = "pullDownLabel";
                    pullDown.appendChild(pullDownLabel);
                    scroller.insertBefore(pullDown, scroller.childNodes[0]);
                    var pullUp = document.createElement("div");
                    pullUp.className = "pullUp";
                    var loader = document.createElement("div");
                    loader.className = "loader";
                    for (var i = 0; i < 4; i++) {
                        var span = document.createElement("span");
                        loader.appendChild(span);
                    }
                    pullUp.appendChild(loader);
                    var pullUpLabel = document.createElement("div");
                    pullUpLabel.className = "pullUpLabel";
                    pullUp.appendChild(pullUpLabel);
                    scroller.appendChild(pullUp);
                    var pullDownEl = wrapper.querySelector(".pullDown");
                    var pullDownOffset = pullDownEl.offsetHeight;
                    var pullUpEl = wrapper.querySelector(".pullUp");
                    var pullUpOffset = pullUpEl.offsetHeight;
                    this.scrollIt(parameter, pullDownEl, pullDownOffset, pullUpEl, pullUpOffset);
                },
                scrollIt: function(parameter, pullDownEl, pullDownOffset, pullUpEl, pullUpOffset) {
                    wrapper = new iScroll('wrapper', {useTransition: true,vScrollbar: true,topOffset: pullDownOffset,onRefresh: function () {refresher.onRelease(pullDownEl,pullUpEl);},onScrollMove: function () {refresher.onScrolling(this,pullDownEl,pullUpEl,pullUpOffset);},onScrollEnd: function () {refresher.onPulling(pullDownEl,parameter.pullDownAction,pullUpEl,parameter.pullUpAction);},});
                    document.addEventListener('touchmove', function(e) {
                        e.preventDefault();
                    }, false);
					$('img').load(function(){
						wrapper.refresh();
					});
                },
                onScrolling: function(e, pullDownEl, pullUpEl, pullUpOffset) {
                    if (e.y > -(pullUpOffset)) {
                        $('.get_more').text('loading...');
                        e.minScrollY = -pullUpOffset;
                    }
                    if (e.y > 0) {
                        pullDownEl.classList.add("flip");
                        $('.get_more').text('loading...');
                        e.minScrollY = 0;
                    }
                    if (e.scrollerH < e.wrapperH && e.y < (e.minScrollY - pullUpOffset) || e.scrollerH > e.wrapperH && e.y < (e.maxScrollY - pullUpOffset)) {
                        pullUpEl.style.display = "block";
                        pullUpEl.classList.add("flip");
                        $('.get_more').text('loading...');
                    }
                    if (e.scrollerH < e.wrapperH && e.y > (e.minScrollY - pullUpOffset) && pullUpEl.id.match('flip') || e.scrollerH > e.wrapperH && e.y > (e.maxScrollY - pullUpOffset) && pullUpEl.id.match('flip')) {
                        pullDownEl.classList.remove("flip");
                        $('.get_more').text('loading...');
                    }
                },
                onRelease: function(pullDownEl, pullUpEl) {
                    if (pullDownEl.className.match('loading')) {
                        pullDownEl.classList.toggle("loading");
                        pullDownEl.querySelector('.loader').style.display = "none"
                        pullDownEl.style.lineHeight = pullDownEl.offsetHeight + "px";
                    }
                    if (pullUpEl.className.match('loading')) {
                        pullUpEl.classList.toggle("loading");
                        pullUpEl.querySelector('.loader').style.display = "none"
                        pullUpEl.style.lineHeight = pullUpEl.offsetHeight + "px";
                    }
                },
                onPulling: function(pullDownEl, pullDownAction, pullUpEl, pullUpAction) {
                    if (pullDownEl.className.match('flip') /*&&!pullUpEl.className.match('loading')*/ ) {
                        pullDownEl.classList.add("loading");
                        pullDownEl.classList.remove("flip");
                        pullDownEl.querySelector('.loader').style.display = "block"
                        pullDownEl.style.lineHeight = "20px";
                        if (pullDownAction) pullDownAction();
                    }
                    if (pullUpEl.className.match('flip') /*&&!pullDownEl.className.match('loading')*/ ) {
                        pullUpEl.classList.add("loading");
                        pullUpEl.classList.remove("flip");
                        pullUpEl.querySelector('.loader').style.display = "block"
                        pullUpEl.style.lineHeight = "20px";
                        if (pullUpAction) pullUpAction();
                    }
                }
            }
        </script>
        <script>

            refresher.init({
                id:"wrapper",
                pullDownAction:Refresh,
                pullUpAction:Load
            });
            function Refresh() {}
            function Load() {
                setTimeout(function () {
                    loadMore();
                }, 1000);
            }
        </script>
		<script type="text/javascript">
		window.shareData = {  
		            "moduleName":"Article",
		            "moduleID":"0",
		            "imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
		            "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('House/village_news', array('village_id' => $now_village['village_id'],'news_id' => $now_news['news_id']))}",
		            "tTitle": "{pigcms{$now_news['title']}",
		            "tContent": "{pigcms{$now_village['village_name']}"
		};
		</script>
		{pigcms{$shareScript}
	</body>
</html>