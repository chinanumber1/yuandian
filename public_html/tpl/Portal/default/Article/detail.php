<include file="Public/header" />
<div class="content w-1200 clearfix">
	<!-- 主体 -->
	<div class="crumb-bar clearfix">
		<span>当前位置：</span><a href="{pigcms{:U('Article/index')}">首页</a> <a href="{pigcms{:U('Article/lists',array('fcid'=>$article['fcid']))}">{pigcms{$fcid_info.cat_name}</a> <a href="{pigcms{:U('Article/lists',array('cid'=>$article['cid']))}">{pigcms{$cid_info.cat_name}</a><span style="width: 400px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="cur_tit">{pigcms{$article.title}</span>
	</div>
	<div class="clearfix" style="margin-top:12px;">
		<input type="hidden" id="article_id" value="{pigcms{$article.aid}" />
		<div class="zx-content">
		 <div class="detail clearfix">
			<h1>{pigcms{$article.title}</h1>
			<div class="publish">
				<ul>
					<li>{pigcms{$article.dateline|date="Y-m-d H:i:s",###}</li>
					<li>来源：{pigcms{$article.source_name}</li>
					<li class="browse">{pigcms{$article.PV}</li>
					<!--
					<li class="zan">11</li>
					<li class="reply" id="show_total_revert1">0</li>
					-->
				</ul>
			</div>
			
			<div class="con" id="resizeIMG">
			{pigcms{$article.msg|htmlspecialchars_decode}
				<div style="text-align:center" class="pageNav2 pageNav4"></div>
			</div>

			<!--
			<div class="prev-next">
				上一篇：<a href="article_2375.html">南京“最牛公交站台”一站台停靠38趟车 小伙伴们都惊呆了</a><br>
				下一篇：没有了
			</div>
		 -->
		 <!--
			<div class="share">
				<div class="bdsharebuttonbox"><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div><script>
				window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
				</script>
			</div>
			-->
			<div class="zan2" onClick="setarticle({pigcms{$article.aid});">赞(<span id="dingnews">{pigcms{$article.zan}</span>)<i></i></div>
			<script type="text/javascript">
				function setarticle(aid){
					$.post("{pigcms{:U('Article/setarticle')}",{'aid':aid},function(response){
						layer.alert(response.msg);
						if(response.code == 0){
							window.location.reload();
						}
					},'json');
				}
			</script>
		</div>
		 <!--comments-->
		 <div class="zx-comments">
			 <div class="tit clearfix">
				 <h4>网友留言评论</h4>
				 <div class="count"><span class="num" id="show_total_revert">{pigcms{$recomment_list|count}</span>条评论</div>
			 </div>
			 <div class="user_comment">
				<div id="showcomment">
					<if condition="$recomment_list">
					<volist name="recomment_list" key="k" id="vo">
						<div class="comment_item">
							<div class="comment_face">
							<if condition="$vo['avatar'] neq ''">
								<img src="{pigcms{$vo.avatar}">
							<else/>
								<img src="{pigcms{$static_path}images/user_small.gif" />
							</if>
							</div>
							<div class="comment_box">
								<div class="comment_user clearfix"><span class="right">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</span> [{pigcms{$k}楼] <span class="userName">{pigcms{$vo.nickname}</span></div>
								
								<p class="comment_content">{pigcms{$vo.msg}</p>
								<!--
								<div class="comment_vote"><a href="#" onclick="return edit_replay(this,'1159','0');" class="edit_btn display0">回评</a>　<a href="#" onclick="return loadDelQuick(this,'1159');" class="edit_btn display0">更多</a>　<a href="#" onclick="return putRevertPage(this,'1159','xianhua=1');" class="zan_btn">赞(<em class="num">0</em>)</a>　<a href="#" onclick="return loadRevertReplay(this,'1159','0');" class="replay_btn replay_life">回复</a></div>
								<p class="manage_replay display0" data-isrep="0">回复：　<span class="time"></span></p>
								-->
							</div>
						</div>
					</volist>
					</if>
				</div>
				<div class="write2014">
					<ul id="login_info_cm" class="login_info_cm clearfix"></ul>
					<input type="hidden" id="isrep" value="" />
					<input type="hidden" id="istg" value="" />
					<input type="hidden" id="parentid" value="" />
					<div id="myform">
						<input id="chrmarkForm" name="chrmark" type="hidden" />
						<div class="cmt_txt" id="cmt_txt" contenteditable="true"></div>
						<div class="cmt_control clearfix">
							<!--
							<div class="left">
								<div class="emot po_re">
									<a href="#" onClick="return insertEmot(this,'cmt_txt');" class="emot_btn">插入表情</a>
								</div>
							</div>
							-->
							<div class="right">　<button type="submit" class="cmt_btn" onclick="save_recomment()">提交</button></div>
							<!--
							<div class="right">文明上网 礼貌发帖　<span id="cmt_tip">0/300</span></div>
							-->
						</div>
					</div>
				</div>
			</div>
		  <div class="sm">声明：频道所载文章、图片、数据等内容以及相关文章评论纯属个人观点和网友自行上传，并不代表本站立场。如发现有违法信息或侵权行为，请留言或直接与本站管理员联系，我们将在收到您的信息后24小时内作出删除处理。</div>
		 </div>
		</div>
		<div class="zx-list-rside">
		  	<!--
		  	<div class="zx-hotnews">
				<h4>本月热点资讯</h4>
				<ul>
					
				</ul>
			</div>
			-->
			<div class="zx-hotnews">
				<h4>精彩图文</h4>
				<ul>
					<if condition="$hot_img_news">
					<volist name="hot_img_news" id="vo">
					<li>
						<a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank"><img src="{pigcms{$vo.thumb}" /></a>
						<p><a href="{pigcms{:U('Article/detail',array('aid'=>$vo['aid']))}" target="_blank">{pigcms{$vo.title|mb_substr=0,12}...</a></p>
					</li>
					</volist>
					</if>
				</ul>
			  </div>
		</div>
	</div>
	<!-- 主体 结束 -->
	</div>
<include file="Public/footer" />
<script src="{pigcms{$static_path}article/js/select.jQuery.js"></script>
<script src="{pigcms{$static_path}article/js/jquery.form.js"></script>
<script src="{pigcms{$static_path}article/js/live2013.js"></script>
<script src="{pigcms{$static_path}article/js/emotData.js"></script>
<script src="{pigcms{$static_path}article/js/fc2015.js"></script>
<script src="{pigcms{$static_public}layer/layer.js"></script>

<script type="text/javascript">
	// 发表评论
	function save_recomment(){
		var aid = $('#article_id').val();
		var msg = $.trim($('#cmt_txt').text());
		if(msg == ''){
			return;
		}
		$.post("{pigcms{:U('Article/ajax_save_recomment')}",{'msg':msg,'aid':aid},function(response){
			if(response.code == 2){
				// 未登录
				layer.confirm('您还未登录，请先登录', {
					btn: ['登录','放弃']
				}, function(){
					window.location.href = '/index.php?g=Index&c=Login&a=index';
				});
				return;
			}
			if(response.code == 1){
				layer.alert(response.msg);
				return;
			}
			layer.msg(response.msg);
			setTimeout(function(){window.location.reload();},1000);
		},'json');
	}
</script>