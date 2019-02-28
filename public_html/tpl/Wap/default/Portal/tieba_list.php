<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>贴吧-{pigcms{$config.site_name}</title>
	<!-- UC默认竖屏 ，UC强制全屏 -->
	<meta name="full-screen" content="yes">
	<meta name="browsermode" content="application">
	<!-- QQ强制竖屏 QQ强制全屏 -->
	<meta name="x5-orientation" content="portrait">
	<meta name="x5-fullscreen" content="true">
	<meta name="x5-page-mode" content="app">
	<meta name="keywords" content="贴吧">
	<meta name="description" content="贴吧">
	<link href="{pigcms{$static_path}portal/css/tieba-mb.css" rel="stylesheet">
	<style type="text/css">
		.foot_link { margin-top:0!important;}
		#pageNavigation { display:none;}
		#noMore { padding:10px 0 20px;}
		#hideHead,#hideHead2 { background-color:#eee; padding-bottom:10px;}
		#listEmpty { display:none!important;}
		.headerblack { background-color:#000!important;}
	
	</style>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>

<section class="search searcht">
	<div class="searcht_n">
		<div class="cond">
			<span class="on">贴吧</span>
			<div class="cond_list">
				<span class="sp">资讯</span>
				<span class="dp">贴吧</span>
			</div>
		</div>
		<input type="text" placeholder="输入关键字搜索" class="se_input" value="{pigcms{$_GET['keyword']}" style="padding-left: 55px;" />
		<input type="hidden" id="store_id" value="{pigcms{$store_id}" />
		<a href="javascript:void(0)" id="search">搜索</a>
	</div>
</section>

<body style="min-height: 640px;" class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">

		<div class="p_main">
			<div class="posts">
				<input id="pagenum" type="hidden" value="1">
				<if condition="$tiebaList">
				<div id="wrapper" style="top: 271px; transform: translate3d(0px, 0px, 0px); transition: transform 0.6s; height: auto;">
					<div id="scroller" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">

						<div class="post_list">
							<ul id="pagingList">
								<volist name="tiebaList" id="vo">
									<div class="item iszhiding0" id="item39">
										<h2>
											<if condition="$vo.is_top eq 1"><span class="d">顶</span></if>
											<if condition="$vo.is_essence eq 1"><span class="j">精</span></if>
											<a href="{pigcms{:U('Portal/tieba_detail',array('tie_id'=>$vo['tie_id']))}">{pigcms{$vo.title}</a>
										</h2>
										<div class="con">
											<div class="n_img" id="n_img_39" data-ischeck="1">
												<volist name="vo['pic']" id="pic_vo" offset="0" length='3'>
													<a href="{pigcms{:U('Portal/tieba_detail',array('tie_id'=>$vo['tie_id']))}"  class="itemAlbum">
														<img src="{pigcms{$pic_vo}" style="width: 106px; height: 79px;">
													</a>
												</volist>
											</div>
										</div>
										<a href="{pigcms{:U('Portal/tieba_detail',array('tie_id'=>$vo['tie_id']))}">
											<dl>
												<dt> <span class="chrname">{pigcms{$vo.last_nickname}</span> <span class="revertnum">{pigcms{$vo.pageviews}</span> 阅读 </dt>
												<dd> <span class="stime">{pigcms{$vo.last_time|date="m月d日 H:i",###}</span> </dd>
											</dl>
										</a>
										
									</div>
								</volist>
							</ul>
						</div>
						<div style="height:50px; background-color:#eee;"></div>
					</div>
				</div>
				<else/>
					<p style="text-align: center;margin-top: 20px;font-size: 12px;  color: #aaa; line-height: 18px; max-height: 18px;  white-space: nowrap;    overflow: hidden;  text-overflow: ellipsis;">
						暂未搜索到相关内容
					</p>
				</if>
			</div>
		</div>		
	</div>
</body>


<script>

	var page = 1,search_type =1;
	$(window).scroll(function () {
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var windowHeight = $(this).height();
		if (scrollTop + windowHeight == scrollHeight) {
			var keyword = '{pigcms{$_GET['keyword']}';
			var tiebaListUrl = "{pigcms{:U('Portal/ajax_tieba_list')}" + '&keyword='+keyword;
			$.post(tiebaListUrl,{'page':page},function(data){
				if(data.error){
					page = page+1;
					$("#pagingList").append(data.html);
				}
			},'json');
		}
	});


	//搜索
	$(".cond span.on").click(function(){
		if($(".cond_list").is(":hidden")){
			$(".cond_list").show();
		}else{
			$(".cond_list").hide();
		}
	});
	$(".cond_list span").click(function(){
		if ($(this).attr('class') == 'sp') {
			search_type = 0;
		} else {
			search_type = 1;
		}
		$(".cond span.on").text($(this).text());
		$(".cond_list").hide();
	});

	$('#search').click(function(){
		var keyword = $('.se_input').val();
		if (!keyword) {
			layer.open({
				content: '请输入关键字'
				,skin: 'msg'
				,time: 2  
			});
			return false;
		}
		if (search_type==0) {
			window.location.href="{pigcms{:U('Portal/article_list')}" + '&keyword='+keyword;
		}else if(search_type==1){
			window.location.href="{pigcms{:U('Portal/tieba_list')}" + '&keyword='+keyword;
		}
		
	});

</script>
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "{pigcms{$config.site_logo}",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/tieba_list')}",
		"tTitle": "{pigcms{$config.site_name}-门户贴吧",
		"tContent": "{pigcms{$config.site_name}-门户贴吧"
	};
</script>
</html>