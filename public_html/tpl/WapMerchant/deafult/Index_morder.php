<!--头部-->
<include file="Public:top"/>
<body>
<!--头部结束-->
	<header class="pigcms-header mm-slideout">
		<a href="#slide_menu" id="pigcms-header-left">
			<i class="iconfont icon-menu "></i>
		</a>			
		<p id="pigcms-header-title">{pigcms{$config.meal_alias_name}订单管理</p>
		<a href="javascript:window.location.reload();" id="pigcms-header-right">刷新</a>
	</header>
	<!--左侧菜单-->
	<include file="Public:leftMenu"/>
	<!--左侧菜单结束-->
	<link rel="stylesheet" href="{pigcms{$static_path}css/shop_order.css">
	<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js"></script>
<script>
	var url = "{pigcms{:U('Index/morder',array('isajax'=>1))}";
			$(function(){
			$(".pigcms-main").css('height', $(window).height()-130);
		})
</script>
	<div class="order-list-wrap">
		<div class="pigcms-container">
			<div class="search-container">
				<i class="iconfont icon-search"></i>
				<input class="pigcms-search" name="keyword" placeholder=" 客户姓名 / 联系电话" type="text">
			</div>
			<div class="header-fliter-container" id="fliter-active">
				<div class="header-fliter">
					<span>{pigcms{$status_list[$status]}</span><i class="iconfont icon-unfold"></i>
				</div>
			</div>
			<div id="fliter-layer">
				<div>
					<div style="overflow: hidden;" id="fliter-wrapper">
						<div id="fliter-scroller">
							<ul id="fliter-ul">
								<volist name="status_list" id="vo">
								<li class="header-fliter-container">
									<div class="header-fliter">
										<span data-status="{pigcms{$key}">{pigcms{$vo}</span>
									</div>
								</li>
								</volist>
							</ul>
						</div>

					<div><div></div></div>
					
					</div>
				</div>
				<div id="fliter-close"></div>
			</div>
		</div>

		<div id="order-list-wrapper" class="pigcms-main">
			<div id="order-list-scroller">
				<ul id="order-list-ul">
				</ul>
			</div>
		</div>
	</div>
	<script>
		var myScroll_fliter;
		function loaded() {
			myScroll_fliter = new iScroll('fliter-wrapper',{hideScrollbar:false});
			$(".header-fliter-container").eq(0).trigger('click');
		}
		document.addEventListener('touchmove', function(e) {
			e.preventDefault();
		}, false);
		document.addEventListener('DOMContentLoaded', loaded, false);

	</script>
	
	</div>

<div id="mm-blocker" class="mm-slideout"></div>
<script src="{pigcms{$static_path}js/shop_order.js"></script>
</body>
	<include file="Public:footer"/>
</html>