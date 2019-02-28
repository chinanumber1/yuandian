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
</head>
<body style="min-height:640px;" class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
			<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
			<a href="{pigcms{:U('Wap/My/index')}" class="my <if condition="$user_session['uid']">ico_ok</if>" id="login_ico">我的</a>
			<div class="type" id="nav_ico">导航</div>
			<span id="ipageTitle" style="">贴吧</span>
			<include file="Portal:top_nav"/>
		</div>

		<div class="p_main">
			<div class="posts">
				

				<div id="content2" style="transform: translate3d(0px, 0px, 0px) scale(1);">
					<div class="cell">
						<a href="javascript:void(0);">
							<img src="{pigcms{$static_path}portal/images/201608082202239233052.jpg" alt=""></a>
					</div>
				</div>



				<div id="hideHead2">
					<div class="p_tabs clearfix">
						<ul>
							<li <if condition="$_GET['order'] eq 'last_time' OR $_GET['order'] eq ''">class="cur"</if>>
								<span onclick="listSort('last_time','desc')">最新回复</span>
							</li>
							<li <if condition="$_GET['order'] eq 'add_time'">class="cur"</if>>
								<span onclick="listSort('add_time','desc')">最新发布</span>
							</li>
							<li <if condition="$_GET['order'] eq 'pageviews'">class="cur"</if>>
								<span onclick="listSort('pageviews','desc')">精华热帖</span>
							</li>
						</ul>
					</div>
				</div>
				<input id="pagenum" type="hidden" value="1">
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
			</div>
		</div>

		<div class="nav_index_bottom nav_tb_bottom">
			<ul>
				<li> <a href="{pigcms{:U('Portal/index')}"> <span class="home"></span> 首页 </a> </li>
				<li> <a href="javascript:void(0);" onclick="showCatState()"> <span class="bankuai"></span> 版块 </a> </li>
				<li> <a href="{pigcms{:U('Portal/tieba_add')}" id="seniorSend" class="seniorSend"> <span class="fatie"></span> 发帖 </a> </li>
				<li> <a href="{pigcms{:U('Portal/tieba')}"> <span class="refresh"></span> 刷新 </a> </li>
				<li> <a href="{pigcms{:U('Wap/My/index')}"> <span class="mine"></span> 我的 </a> </li>
			</ul>
		</div>


		<div class="fixed_cat" id="fixed_cat" data-isshow="0" style="display:none; left:-160px;">
			<ul style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
				<li id="s_a_0" <if condition="$_GET['plate_id'] eq ''">class="cur"</if>>
					<a href="{pigcms{:U('Portal/tieba')}"><span>全部版块</span></a>
				</li>
				<volist name="tiebaPlateList" id="vo">
					<li <if condition="$_GET['plate_id'] eq $vo['plate_id']">class="cur"</if>>
						<a href="{pigcms{:U('Portal/tieba',array('plate_id'=>$vo['plate_id']))}"><span>{pigcms{$vo.plate_name} </span></a>
					</li>
				</volist>
			</ul>
		</div>

		<input type="hidden" name="order" id="order" value="{pigcms{$_GET['order']}"/>
		<input type="hidden" name="sort" id="sort" value="{pigcms{$_GET['sort']}"/>
		<input type="hidden" name="plate_id" id="plate_id" value="{pigcms{$_GET['plate_id']}"/>
		<input type="hidden" name="essence" id="essence" value="{pigcms{$_GET['essence']}"/>
		<input type="hidden" name="search" id="search" value="{pigcms{$_GET['search']}"/>
		
	</div>
</body>


<script>

	var page = 1
	$(window).scroll(function () {
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var windowHeight = $(this).height();
		if (scrollTop + windowHeight == scrollHeight) {
			var plate_id = $("#plate_id").val();
	        var order = $("#order").val();
	        var sort = $("#sort").val();
	        var essence = $("#essence").val();
	        var search = $("#search").val();
			var tiebaListUrl = "{pigcms{:U('Portal/ajax_tieba_list')}";
			$.post(tiebaListUrl,{'page':page,'plate_id':plate_id,'order':order,'sort':sort},function(data){
				if(data.error){
					page = page+1;
					$("#pagingList").append(data.html);
				}
			},'json');
		}
	});

	function showCatState(onlyClose){
		var fixed_cat = $('#fixed_cat'),nav_bankuai = $('#nav_bankuai');
		if(!!onlyClose){
			if(fixed_cat.attr('data-isshow') === '1'){
				fixed_cat.attr('data-isshow','0').animate({'left':'-160px'},500,function(){
					fixed_cat.css('display','none');
				});
				nav_bankuai.removeClass('current');
			}
			return false;
		}
		if(fixed_cat.attr('data-isshow') === '0'){
			fixed_cat.css({'display':'block'}).animate({'left':'0'},500,function(){}).attr('data-isshow','1');
			nav_bankuai.addClass('current');
		}else{
			fixed_cat.attr('data-isshow','0').animate({'left':'-160px'},500,function(){
				fixed_cat.css('display','none');
			});
			nav_bankuai.removeClass('current');
		}
		return false;
	}


	function listSort(order,sort){
	    $("#order").val(order);
	    $("#sort").val(sort);
	    getUrl();
	}

	function essence(){
	    $("#essence").val(1);
	    getUrl();
	}

	function getUrl(){
	    var data = '';
	    var plate_id = $("#plate_id").val();
	    var order = $("#order").val();
	    var sort = $("#sort").val();
	    var essence = $("#essence").val();
	    var search = $("#search").val();

	    if(essence){
	        var data = data + "&essence="+essence;
	    }
	    if(plate_id){
	        var data = data + "&plate_id="+plate_id;
	    }
	    if(order){
	        var data = data + "&order="+order;
	    }
	    if(sort){
	        var data = data + "&sort="+sort;
	    }
	    location.href = "{pigcms{:U('Portal/tieba')}"+data;
	}

</script>
{pigcms{$shareScript}
<script type="text/javascript">
	window.shareData = {
		"moduleName":"Home",
		"moduleID":"0",
		"imgUrl": "{pigcms{$config.site_logo}",
		"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Portal/tieba')}",
		"tTitle": "{pigcms{$config.site_name}-门户贴吧",
		"tContent": "{pigcms{$config.site_name}-门户贴吧"
	};
</script>
</html>